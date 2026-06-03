<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\SettingWebsite;

class PageController extends Controller
{
    public function terms()
    {
        $setting_web = SettingWebsite::first();

        $data = [
            'title' => 'Syarat & Ketentuan | ' . $setting_web->name,
            'meta' => [
                'title' => 'Syarat & Ketentuan | ' . $setting_web->name,
                'description' => 'Syarat dan ketentuan penggunaan layanan ' . $setting_web->name,
                'keywords' => $setting_web->name . ', Syarat, Ketentuan, Terms, Conditions',
                'favicon' => $setting_web->favicon,
            ],
            'breadcrumbs' => [
                ['name' => 'Beranda', 'link' => route('home')],
                ['name' => 'Syarat & Ketentuan', 'link' => route('page.terms')],
            ],
            'setting_web' => $setting_web,
        ];

        return view('front.pages.page.terms', $data);
    }

    public function privacy()
    {
        $setting_web = SettingWebsite::first();

        $data = [
            'title' => 'Kebijakan Privasi | ' . $setting_web->name,
            'meta' => [
                'title' => 'Kebijakan Privasi | ' . $setting_web->name,
                'description' => 'Kebijakan privasi dan perlindungan data pengguna ' . $setting_web->name,
                'keywords' => $setting_web->name . ', Kebijakan, Privasi, Privacy, Policy',
                'favicon' => $setting_web->favicon,
            ],
            'breadcrumbs' => [
                ['name' => 'Beranda', 'link' => route('home')],
                ['name' => 'Kebijakan Privasi', 'link' => route('page.privacy')],
            ],
            'setting_web' => $setting_web,
        ];

        return view('front.pages.page.privacy', $data);
    }
}
