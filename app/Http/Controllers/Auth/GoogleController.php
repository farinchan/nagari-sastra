<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use RealRashid\SweetAlert\Facades\Alert;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id ATAU email
            $existingUser = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($existingUser) {
                // User sudah ada — update google_id jika belum ada (link akun)
                if (!$existingUser->google_id) {
                    $existingUser->google_id = $googleUser->id;
                }

                // Update avatar dari Google jika belum punya foto lokal
                if (!$existingUser->photo || Str::startsWith($existingUser->photo, 'http')) {
                    $existingUser->photo = $googleUser->getAvatar();
                }

                $existingUser->save();
                $user = $existingUser;
            } else {
                // User baru — register via Google
                $user = User::create([
                    'name' => strip_tags($googleUser->getName()),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'photo' => $googleUser->getAvatar(),
                    'password' => Hash::make(Str::random(32)),
                ]);
            }

            Auth::login($user);

            // Regenerate session for security
            request()->session()->regenerate();

            // Redirect based on role
            if ($user->hasRole('super-admin|keuangan|editor|humas')) {
                Alert::success('Berhasil', 'Login dengan Google berhasil!');
                return redirect()->intended(route('back.dashboard'));
            }

            Alert::success('Berhasil', 'Login dengan Google berhasil!');
            return redirect()->intended('/');

        } catch (\Throwable $th) {
            Alert::error('Gagal', 'Login dengan Google gagal. Silakan coba lagi.');
            return redirect()->route('login');
        }
    }
}
