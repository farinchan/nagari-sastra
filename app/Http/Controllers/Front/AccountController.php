<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\EventUser;
use App\Models\SettingWebsite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{
    public function profile(Request $request)
    {
        $setting_web = SettingWebsite::first();
        $me = Auth::user();

        $data = [
            'title' => $me->name . ' | ' . $setting_web->name,
            'meta' => [
                'title' => 'Profil',
                'description' => 'Halaman profil pengguna ' . $setting_web->name,
                'keywords' => $setting_web->name . ', profil, akun',
                'favicon' => $setting_web->favicon,
                'og_type' => 'profile',
                'robots' => 'noindex, nofollow',
                'canonical' => route('account.profile'),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Profil',
                    'link' => route('account.profile')
                ]
            ],
            'setting_web' => $setting_web,
            'events' => EventUser::with(['event'])
                ->where('user_id', $me->id)
                ->latest()
                ->paginate(10),
            'me' => $me,
        ];

        return view('front.pages.account.profile', $data);
    }

    /**
     * Update profile photo via AJAX
     */
    public function updatePhoto(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'photo.required' => 'Foto wajib dipilih',
            'photo.image' => 'File harus berupa gambar',
            'photo.mimes' => 'Format: jpeg, png, jpg, gif',
            'photo.max' => 'Ukuran maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // Delete old photo
            if ($user->photo && !Str::startsWith($user->photo, ['http://', 'https://']) && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Store new photo
            $photoPath = $request->file('photo')->store('users', 'public');
            $user->photo = $photoPath;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'photo_url' => $user->getPhoto(),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal upload foto'], 500);
        }
    }

    /**
     * Update profile data (name, email, phone, gender, academic links)
     */
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:laki-laki,perempuan',
            'sinta_id' => 'nullable|string|max:50|alpha_num',
            'scopus_id' => 'nullable|string|max:50|alpha_num',
            'google_scholar' => 'nullable|url|max:500',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'gender.required' => 'Jenis kelamin wajib dipilih',
            'gender.in' => 'Jenis kelamin tidak valid',
            'sinta_id.alpha_num' => 'SINTA ID hanya boleh huruf dan angka',
            'scopus_id.alpha_num' => 'Scopus ID hanya boleh huruf dan angka',
            'google_scholar.url' => 'Format URL Google Scholar tidak valid',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user->name = strip_tags($request->name);
            $user->email = $request->email;
            $user->phone = strip_tags($request->phone);
            $user->gender = $request->gender;

            // Academic links — sanitize
            $user->sinta_id = strip_tags($request->sinta_id);
            $user->scopus_id = strip_tags($request->scopus_id);
            $user->google_scholar = filter_var($request->google_scholar, FILTER_SANITIZE_URL) ?: null;

            $user->save();

            Alert::success('Success', 'Profil berhasil diperbarui');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat memperbarui profil');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update password (separate from profile)
     */
    public function passwordUpdate(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back()->withErrors($validator);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            Alert::error('Error', 'Password saat ini tidak benar');
            return redirect()->back();
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            Alert::success('Success', 'Password berhasil diubah');
            return redirect()->back();

        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengubah password');
            return redirect()->back();
        }
    }
}
