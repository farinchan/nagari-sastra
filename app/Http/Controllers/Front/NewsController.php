<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsViewer;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Jenssegers\Agent\Facades\Agent;
use Stevebauman\Location\Facades\Location;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $setting_web = SettingWebsite::first();

        // Sanitize search input
        $search = strip_tags($request->q ?? '');
        $news = News::where('status', 'published')
            ->where('title', 'like', '%' . $search . '%')
            ->with(['category', 'comments', 'user', 'viewers'])
            ->latest()
            ->paginate(6);
        $news->appends(['q' => $search]);
        $data = [
            'title' => 'Berita | ' . $setting_web->name,
            'meta' => [
                'title' => 'Berita | ' . $setting_web->name,
                'description' => Str::limit('Berita terbaru dari ' . strip_tags($setting_web->about), 155),
                'keywords' => $setting_web->name . ', berita, informasi, artikel, kabar terbaru',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->getLogo(),
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('news.index'),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Berita',
                    'link' => route('news.index')
                ]
            ],
            'news' => $news,
            'news_trending' => News::where('status', 'published')->withCount('viewers')->orderByDesc('viewers_count')->take(5)->get(),
            'categories' => NewsCategory::with('news')->get(),

        ];
        return view('front.pages.news.index', $data);
    }

    public function detail($slug)
    {
        $setting_web = SettingWebsite::first();
        $news = News::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $data = [
            'title' => $news->title,
            'meta' => [
                'title' => $news->title,
                'description' => Str::limit(strip_tags($news->content), 155),
                'keywords' => $setting_web->name . ', ' . $news->title . ', ' . ($news->category->name ?? 'berita') . ', artikel',
                'favicon' => $news->thumbnail ?? $setting_web->favicon,
                'author' => $news->user?->name ?? $setting_web->name,
                'og_image' => $news->getThumbnail(),
                'og_type' => 'article',
                'robots' => 'index, follow',
                'canonical' => route('news.detail', $news->slug),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Berita',
                    'link' => route('news.index')
                ],
                [
                    'name' => $news->id,
                    'link' => route('news.detail', $news->slug)
                ]
            ],
            'news' => $news,
            'prev_news' => News::where('status', 'published')->where('id', '<', $news->id)->latest()->first(),
            'next_news' => News::where('status', 'published')->where('id', '>', $news->id)->oldest()->first(),
            'news_trending' => News::where('status', 'published')->withCount('viewers')->orderByDesc('viewers_count')->take(3)->get(),
            'categories' => NewsCategory::with('news')->get(),
        ];
        return view('front.pages.news.detail', $data);
    }

    public function category($slug)
    {
        $setting_web = SettingWebsite::first();
        $category = NewsCategory::where('slug', $slug)->firstOrFail();
        $news = $category->news()->where('status', 'published')->latest()->paginate(6);
        $data = [
            'title' => $category->name . ' | ' . $setting_web->name,
            'meta' => [
                'title' => $category->name . ' | ' . $setting_web->name,
                'description' => Str::limit('Berita kategori ' . $category->name . ' - ' . strip_tags($setting_web->about), 155),
                'keywords' => $setting_web->name . ', ' . $category->name . ', berita, kategori, artikel',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->getLogo(),
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('news.category', $category->slug),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Berita',
                    'link' => route('news.index')
                ],
                [
                    'name' => $category->name,
                    'link' => route('news.category', $category->slug)
                ]
            ],
            'category' => $category,
            'news' => $news,
            'news_trending' => News::where('status', 'published')->withCount('viewers')->orderByDesc('viewers_count')->take(5)->get(),
            'categories' => NewsCategory::with('news')->get(),
        ];
        return view('front.pages.news.category', $data);
    }

    public function comment(Request $request)
    {
        // ---- Rate Limiting: max 5 comments per minute per IP ----
        $rateLimitKey = 'comment:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            Alert::error('Terlalu Cepat', "Anda terlalu sering mengirim komentar. Coba lagi dalam {$seconds} detik.");
            return redirect()->back()->withInput();
        }

        // ---- Validate all fields strictly ----
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|integer|exists:news,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'news_id.required' => 'ID berita wajib diisi.',
            'news_id.integer' => 'ID berita tidak valid.',
            'news_id.exists' => 'Berita tidak ditemukan.',
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'comment.required' => 'Komentar tidak boleh kosong.',
            'comment.max' => 'Komentar maksimal 2000 karakter.',
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, silakan coba lagi.',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ---- Verify the news exists and is published ----
        $news = News::where('id', $request->news_id)
            ->where('status', 'published')
            ->first();

        if (!$news) {
            Alert::error('Error', 'Berita tidak ditemukan.');
            return redirect()->back();
        }

        // ---- Honeypot check (if present) ----
        if ($request->filled('website_url')) {
            // Bot likely filled the hidden honeypot field — silently reject
            Alert::success('Success', 'Komentar berhasil ditambahkan');
            return redirect()->back();
        }

        // ---- Create comment with sanitized data only ----
        $news->comments()->create([
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'comment' => strip_tags($request->comment),
        ]);

        // ---- Record the rate limit hit ----
        RateLimiter::hit($rateLimitKey, 60);

        Alert::success('Berhasil', 'Komentar berhasil ditambahkan');
        return redirect()->back();
    }

    public function visit(Request $request)
    {
        // ---- Rate Limiting: max 30 visits per minute per IP ----
        $rateLimitKey = 'news-visit:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 30)) {
            return response()->json(['status' => 'error', 'message' => 'Too many requests'], 429);
        }

        $validator = Validator::make($request->all(), [
            'news_id' => 'required|integer|exists:news,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request'], 422);
        }

        try {
            $currentUserInfo = Location::get($request->ip());
            $news_visitor = new NewsViewer();
            $news_visitor->news_id = $request->news_id;
            $news_visitor->ip = $request->ip();
            if ($currentUserInfo) {
                $news_visitor->country = $currentUserInfo->countryName;
                $news_visitor->city = $currentUserInfo->cityName;
                $news_visitor->region = $currentUserInfo->regionName;
                $news_visitor->postal_code = $currentUserInfo->postalCode;
                $news_visitor->latitude = $currentUserInfo->latitude;
                $news_visitor->longitude = $currentUserInfo->longitude;
                $news_visitor->timezone = $currentUserInfo->timezone;
            }
            $news_visitor->user_agent = Str::limit(Agent::getUserAgent(), 500);
            $news_visitor->platform = Agent::platform();
            $news_visitor->browser = Agent::browser();
            $news_visitor->device = Agent::device();
            $news_visitor->save();

            RateLimiter::hit($rateLimitKey, 60);

            return response()->json(['status' => 'success', 'message' => 'OK'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Failed to record visit'], 500);
        }
    }
}
