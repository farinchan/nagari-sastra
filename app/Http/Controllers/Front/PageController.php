<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Faq;
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
                'keywords' => $setting_web->name . ', Syarat, Ketentuan, Terms, Conditions, Penggunaan, Layanan, kota padang, sumatera barat',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('page.terms'),
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
                'keywords' => $setting_web->name . ', Kebijakan, Privasi, Privacy, Policy, kota padang, sumatera barat',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('page.privacy'),
            ],
            'breadcrumbs' => [
                ['name' => 'Beranda', 'link' => route('home')],
                ['name' => 'Kebijakan Privasi', 'link' => route('page.privacy')],
            ],
            'setting_web' => $setting_web,
        ];

        return view('front.pages.page.privacy', $data);
    }

    public function faq()
    {
        $setting_web = SettingWebsite::first();

        $data = [
            'title' => 'FAQ',
            'meta' => [
                'title' => 'FAQ | ' . $setting_web->name,
                'description' => 'Pertanyaan yang sering diajukan seputar layanan ' . $setting_web->name,
                'keywords' => $setting_web->name . ', FAQ, Pertanyaan, Bantuan, Help, kota padang, sumatera barat',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('page.faq'),
            ],
            'breadcrumbs' => [
                ['name' => 'Beranda', 'link' => route('home')],
                ['name' => 'FAQ', 'link' => route('page.faq')],
            ],
            'setting_web' => $setting_web,
            'list_faq' => Faq::active()->ordered()->get(),
        ];

        return view('front.pages.page.faq', $data);
    }
}
