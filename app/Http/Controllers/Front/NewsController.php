<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsViewer;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
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

        $search = $request->q;
        $news = News::where('title', 'like', "%$search%")
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
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
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
            'news_trending' => News::withCount('viewers')->orderByDesc('viewers_count')->take(5)->get(),
            'categories' => NewsCategory::with('news')->get(),

        ];
        return view('front.pages.news.index', $data);
    }

    public function detail($slug)
    {
        $setting_web = SettingWebsite::first();
        $news = News::where('slug', $slug)->firstOrFail();
        $data = [
            'title' => $news->title,
            'meta' => [
                'title' => $news->title . ' | ' . $setting_web->name,
                'description' => Str::limit(strip_tags($news->content), 155),
                'keywords' => $setting_web->name . ', ' . $news->title . ', ' . ($news->category->name ?? 'berita') . ', artikel',
                'favicon' => $news->thumbnail ?? $setting_web->favicon,
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
                    'name' => $news->title,
                    'link' => route('news.detail', $news->slug)
                ]
            ],
            'news' => $news,
            'prev_news' => News::where('id', '<', $news->id)->latest()->first(),
            'next_news' => News::where('id', '>', $news->id)->latest()->first(),
            'news_trending' => News::withCount('viewers')->orderByDesc('viewers_count')->take(3)->get(),
            'categories' => NewsCategory::with('news')->get(),
        ];
        return view('front.pages.news.detail', $data);
    }

    public function category($slug)
    {
        $setting_web = SettingWebsite::first();
        $category = NewsCategory::where('slug', $slug)->firstOrFail();
        $news = $category->news()->latest()->paginate(6);
        $data = [
            'title' => $category->name,
            'meta' => [
                'title' => $category->name . ' | ' . $setting_web->name,
                'description' => Str::limit('Berita kategori ' . $category->name . ' - ' . strip_tags($setting_web->about), 155),
                'keywords' => $setting_web->name . ', ' . $category->name . ', berita, kategori, artikel',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
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
            'news_trending' => News::withCount('viewers')->orderByDesc('viewers_count')->take(5)->get(),
            'categories' => NewsCategory::with('news')->get(),
        ];
        return view('front.pages.news.category', $data);
    }

    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|exists:news,id',
            'name' => 'required',
            'email' => 'required|email',
            'comment' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ],
        [
            'news_id.required' => 'News ID is required.',
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'comment.required' => 'Comment cannot be empty.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha verification failed, please try again.'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Please fill all the form');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $news = News::find($request->news_id);
        $news->comments()->create($request->all());
        Alert::success('Success', 'Comment has been added');
        return redirect()->back();
    }

    public function visit(Request $request)
    {
        $news_id = $request->news_id;
        // dd($news_id);
        try {
            $currentUserInfo = Location::get(request()->ip());
            $news_visitor = new NewsViewer();
            $news_visitor->news_id = $news_id;
            $news_visitor->ip = request()->ip();
            if ($currentUserInfo) {
                $news_visitor->country = $currentUserInfo->countryName;
                $news_visitor->city = $currentUserInfo->cityName;
                $news_visitor->region = $currentUserInfo->regionName;
                $news_visitor->postal_code = $currentUserInfo->postalCode;
                $news_visitor->latitude = $currentUserInfo->latitude;
                $news_visitor->longitude = $currentUserInfo->longitude;
                $news_visitor->timezone = $currentUserInfo->timezone;
            }
            $news_visitor->user_agent = Agent::getUserAgent();
            $news_visitor->platform = Agent::platform();
            $news_visitor->browser = Agent::browser();
            $news_visitor->device = Agent::device();
            $news_visitor->save();

            return response()->json(['status' => 'success', 'message' => 'Visitor has been saved'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
