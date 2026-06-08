<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SettingWebsite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $setting_web = SettingWebsite::first();
        $data = [
            'title' =>  'Daftar | ' . $setting_web->name,
            'meta' => [
                'title' => 'Daftar | ' . $setting_web->name,
                'description' => strip_tags($setting_web->about),
                'keywords' => $setting_web->name . ', Register, Daftar',
                'favicon' => $setting_web->favicon
            ],
            'breadcrumbs' =>  [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Daftar',
                    'link' => route('register')
                ]
            ],
            'setting_web' => $setting_web
        ];
        return view('front.pages.auth.register', $data);
    }

    public function register(Request $request)
    {
        // Rate limit: max 3 registrations per 10 minutes per IP
        $rateLimitKey = 'register:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            Alert::error('Terlalu Banyak Percobaan', "Silakan tunggu {$seconds} detik sebelum mencoba lagi.");
            return redirect()->back()->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:50|alpha_dash|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'sinta_id' => 'nullable|string|max:50|alpha_num',
            'scopus_id' => 'nullable|string|max:50|alpha_num',
            'google_scholar' => 'nullable|url|max:500',
            'agree_terms' => 'required|accepted',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'name.required' => 'Nama lengkap tidak boleh kosong',
            'username.unique' => 'Username sudah digunakan',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash, dan underscore',
            'username.max' => 'Username maksimal 50 karakter',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'sinta_id.alpha_num' => 'SINTA ID hanya boleh huruf dan angka',
            'scopus_id.alpha_num' => 'Scopus ID hanya boleh huruf dan angka',
            'google_scholar.url' => 'Format URL Google Scholar tidak valid',
            'agree_terms.required' => 'Anda harus menyetujui Syarat dan Ketentuan serta Kebijakan Privasi',
            'agree_terms.accepted' => 'Anda harus menyetujui Syarat dan Ketentuan serta Kebijakan Privasi',
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
            'g-recaptcha-response.captcha' => 'Verifikasi captcha gagal, silakan coba lagi.',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Honeypot check
        if ($request->filled('website_url')) {
            Alert::success('Success', 'Registrasi berhasil! Silakan login.');
            return redirect()->route('login');
        }

        try {
            $user = User::create([
                'name' => strip_tags($request->name),
                'username' => strip_tags($request->username),
                'email' => $request->email,
                'phone' => strip_tags($request->phone),
                'password' => Hash::make($request->password),
                'sinta_id' => strip_tags($request->sinta_id),
                'scopus_id' => strip_tags($request->scopus_id),
                'google_scholar' => $request->google_scholar ? filter_var($request->google_scholar, FILTER_SANITIZE_URL) : null,
            ]);

            RateLimiter::hit($rateLimitKey, 600); // 10 minutes

            Alert::success('Success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
            return redirect()->route('login');

        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }
}
