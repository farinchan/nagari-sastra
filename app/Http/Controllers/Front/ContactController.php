<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ContactController extends Controller
{
    public function index()
    {
        $setting_web = SettingWebsite::first();
        $data = [
            'title' => 'Hubungi Kami | ' . $setting_web->name,
            'meta' => [
                'title' => 'Hubungi Kami | ' . $setting_web->name,
                'description' => 'Hubungi Nagari Sastra Group untuk informasi lebih lanjut mengenai layanan publikasi, jurnal, dan penerbitan buku.',
                'keywords' => 'kontak, hubungi kami, ' . $setting_web->name,
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('contact.index'),
            ],
            'breadcrumbs' =>  [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Hubungi Kami',
                    'link' => route('contact.index')
                ]
                ],
            'setting_web' => SettingWebsite::first()
        ];
        return view('front.pages.home.contact', $data);
    }

    public function send(Request $request)
    {
        // Rate limit: max 3 messages per minute per IP
        $rateLimitKey = 'contact:' . $request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($rateLimitKey);
            Alert::error('Terlalu Cepat', "Silakan tunggu {$seconds} detik sebelum mengirim pesan lagi.");
            return redirect()->back()->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:5000',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'subject.required' => 'Subjek wajib diisi.',
            'subject.max' => 'Subjek maksimal 200 karakter.',
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max' => 'Pesan maksimal 5000 karakter.',
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, silakan coba lagi.',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Honeypot check
        if ($request->filled('website_url')) {
            Alert::success('Berhasil', 'Pesan berhasil dikirim');
            return redirect()->back();
        }

        $message = new Message();
        $message->name = strip_tags($request->name);
        $message->email = $request->email;
        $message->phone = strip_tags($request->phone);
        $message->subject = strip_tags($request->subject);
        $message->message = strip_tags($request->message);
        $message->save();

        \Illuminate\Support\Facades\RateLimiter::hit($rateLimitKey, 60);

        Alert::success('Berhasil', 'Pesan berhasil dikirim');
        return redirect()->back();
    }
}
