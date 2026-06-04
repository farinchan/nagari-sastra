<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class FaqController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'FAQ',
            'breadcrumbs' => [
                ['name' => 'FAQ', 'link' => route('back.faq.index')]
            ],
            'faqs' => Faq::ordered()->get(),
        ];

        return view('back.pages.faq.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
        ], [
            'question.required' => 'Pertanyaan harus diisi',
            'answer.required' => 'Jawaban harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        Alert::success('Berhasil', 'FAQ berhasil ditambahkan');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $faq = Faq::findOrFail($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->is_active = $request->has('is_active');
        $faq->order = $request->order ?? 0;
        $faq->save();

        Alert::success('Berhasil', 'FAQ berhasil diperbarui');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        Alert::success('Berhasil', 'FAQ berhasil dihapus');
        return redirect()->back();
    }
}
