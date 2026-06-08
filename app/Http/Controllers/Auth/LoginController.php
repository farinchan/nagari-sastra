<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        $setting_web = SettingWebsite::first();
        $data = [
            'title' =>  'Masuk | ' . $setting_web->name,
            'meta' => [
                'title' => 'Masuk' . ' | ' . $setting_web->name,
                'description' => strip_tags($setting_web->about),
                'keywords' => $setting_web->name . ', Login, Masuk',
                'favicon' => $setting_web->favicon
            ],
            'breadcrumbs' =>  [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Masuk',
                    'link' => route('login')
                ]
            ],
            'setting_web' => $setting_web
        ];
        return view('front.pages.auth.login', $data);
    }

    public function login(Request $request)
    {
        // Rate limit: max 5 attempts per minute per IP
        $rateLimitKey = 'login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            Alert::error('Terlalu Banyak Percobaan', "Akun dikunci sementara. Coba lagi dalam {$seconds} detik.");
            return redirect()->back()->withInput(['login' => $request->login]);
        }

        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:255',
        ], [
            'login.required' => 'Email atau username tidak boleh kosong',
            'login.max' => 'Email atau username terlalu panjang',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput(['login' => $request->login]);
        }

        $loginInput = strip_tags(trim($request->input('login')));
        $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $loginInput, 'password' => $request->input('password')], $request->boolean('remember'))) {
            // Reset rate limiter on success
            RateLimiter::clear($rateLimitKey);

            $request->session()->regenerate();

            if (Auth::user()->hasRole('super-admin|keuangan|editor|humas')) {
                Alert::success('Success', 'Login berhasil');
                return redirect()->intended(route('back.dashboard'));
            }
            return redirect()->intended('/');
        }

        // Record failed attempt
        RateLimiter::hit($rateLimitKey, 60);

        Alert::error('Error', 'Email/username atau password salah');
        return redirect()->back()->withInput(['login' => $request->login]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
