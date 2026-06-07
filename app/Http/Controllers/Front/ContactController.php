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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Please fill all the form');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $message = new Message();
        $message->name = $request->name;
        $message->email = $request->email;
        $message->phone = $request->phone;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->save();

        Alert::success('Success', 'Message has been sent');
        return redirect()->back();


    }
}
