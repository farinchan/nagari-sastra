<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class TestimonialController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Testimonial',
            'breadcrumbs' => [
                ['name' => 'Testimonial', 'link' => route('back.testimonial.index')]
            ],
            'testimonials' => Testimonial::ordered()->get(),
        ];

        return view('back.pages.testimonial.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'nullable|integer',
        ], [
            'name.required' => 'Nama harus diisi',
            'content.required' => 'Isi testimonial harus diisi',
            'rating.required' => 'Rating harus diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create([
            'name' => $request->name,
            'position' => $request->position,
            'company' => $request->company,
            'content' => $request->content,
            'rating' => $request->rating,
            'avatar' => $avatarPath,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
        ]);

        Alert::success('Berhasil', 'Testimonial berhasil ditambahkan');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testimonial = Testimonial::findOrFail($id);

        if ($request->hasFile('avatar')) {
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $testimonial->avatar = $request->file('avatar')->store('testimonials', 'public');
        }

        $testimonial->name = $request->name;
        $testimonial->position = $request->position;
        $testimonial->company = $request->company;
        $testimonial->content = $request->content;
        $testimonial->rating = $request->rating;
        $testimonial->is_active = $request->has('is_active');
        $testimonial->order = $request->order ?? 0;
        $testimonial->save();

        Alert::success('Berhasil', 'Testimonial berhasil diperbarui');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        Alert::success('Berhasil', 'Testimonial berhasil dihapus');
        return redirect()->back();
    }
}
