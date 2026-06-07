<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JournalController extends Controller
{
    public function index()
    {
        $setting_web = SettingWebsite::first();
        $data = [
            'title' => 'Jurnal | ' . $setting_web->name,
            'meta' => [
                'title' => 'Jurnal | ' . $setting_web->name,
                'description' => Str::limit(strip_tags($setting_web->about), 155),
                'keywords' => 'jurnal ilmiah, publikasi, penelitian, akademik, kota padang, sumatera barat, ' . $setting_web->name,
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('journal.index'),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Jurnal',
                    'link' => route('journal.index')
                ]
                ],
            'journals' => Journal::latest()->get(),
        ];
        return view('front.pages.journal.index', $data);
    }

    public function detail($journal_path)
    {
        $setting_web = SettingWebsite::first();
        $journal = Journal::where('url_path', $journal_path)->first();
        if (!$journal) {
            abort(404);
        }
        $data = [
            'title' => $journal->title,
            'meta' => [
                'title' => $journal->title . ' | ' . $setting_web->name,
                'description' => Str::limit(strip_tags($journal->description), 155),
                'keywords' => $setting_web->name . ', ' . $journal->title . ', jurnal ilmiah, publikasi, penelitian, kota padang, sumatera barat',
                'favicon' => $journal->getJournalThumbnail() ?? $setting_web->favicon,
                'og_image' => $journal->getJournalThumbnail(),
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('journal.detail', $journal->url_path),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Jurnal',
                    'link' => route('journal.index')
                ],
                [
                    'name' => $journal->url_path,
                    'link' => route('journal.detail', $journal->url_path)
                ]
            ],
            'journal' => $journal,
            'issues' => $journal->issues()->latest()->paginate(6),
        ];
        return view('front.pages.journal.detail', $data);
    }
}
