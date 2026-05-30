<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\IncomingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class IncomingMailController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Surat Masuk',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Masuk',
                    'link' => route('back.incoming-mail.index')
                ]
            ],
            'list_mail' => IncomingMail::latest()->get()
        ];

        return view('back.pages.incoming_mail.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Surat Masuk',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Masuk',
                    'link' => route('back.incoming-mail.index')
                ],
                [
                    'name' => 'Tambah Surat Masuk',
                    'link' => route('back.incoming-mail.create')
                ]
            ]
        ];

        return view('back.pages.incoming_mail.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|max:255',
            'pengirim' => 'required|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'perihal' => 'required|max:255',
            'klasifikasi' => 'required|in:biasa,penting,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|mimes:pdf,jpg,jpeg,png|max:16384',
            'keterangan' => 'nullable',
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.max' => 'Nomor surat maksimal 255 karakter',
            'pengirim.required' => 'Pengirim harus diisi',
            'pengirim.max' => 'Pengirim maksimal 255 karakter',
            'tanggal_surat.required' => 'Tanggal surat harus diisi',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid',
            'tanggal_diterima.required' => 'Tanggal diterima harus diisi',
            'tanggal_diterima.date' => 'Format tanggal diterima tidak valid',
            'perihal.required' => 'Perihal harus diisi',
            'perihal.max' => 'Perihal maksimal 255 karakter',
            'klasifikasi.required' => 'Klasifikasi harus dipilih',
            'klasifikasi.in' => 'Klasifikasi tidak valid',
            'file_surat.mimes' => 'File harus berupa PDF, JPG, JPEG, atau PNG',
            'file_surat.max' => 'File maksimal 16MB',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mail = new IncomingMail();
        $mail->nomor_surat = $request->nomor_surat;
        $mail->pengirim = $request->pengirim;
        $mail->tanggal_surat = $request->tanggal_surat;
        $mail->tanggal_diterima = $request->tanggal_diterima;
        $mail->perihal = $request->perihal;
        $mail->klasifikasi = $request->klasifikasi;
        $mail->keterangan = $request->keterangan;
        $mail->user_id = Auth::user()->id;

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $mail->file_surat = $file->storeAs('incoming_mail', $file_name, 'public');
        }

        $mail->save();

        Alert::success('Berhasil', 'Surat masuk berhasil ditambahkan');
        return redirect()->route('back.incoming-mail.index');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Surat Masuk',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Masuk',
                    'link' => route('back.incoming-mail.index')
                ],
                [
                    'name' => 'Edit Surat Masuk',
                    'link' => route('back.incoming-mail.edit', $id)
                ]
            ],
            'mail' => IncomingMail::findOrFail($id)
        ];

        return view('back.pages.incoming_mail.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|max:255',
            'pengirim' => 'required|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'perihal' => 'required|max:255',
            'klasifikasi' => 'required|in:biasa,penting,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|mimes:pdf,jpg,jpeg,png|max:16384',
            'keterangan' => 'nullable',
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.max' => 'Nomor surat maksimal 255 karakter',
            'pengirim.required' => 'Pengirim harus diisi',
            'pengirim.max' => 'Pengirim maksimal 255 karakter',
            'tanggal_surat.required' => 'Tanggal surat harus diisi',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid',
            'tanggal_diterima.required' => 'Tanggal diterima harus diisi',
            'tanggal_diterima.date' => 'Format tanggal diterima tidak valid',
            'perihal.required' => 'Perihal harus diisi',
            'perihal.max' => 'Perihal maksimal 255 karakter',
            'klasifikasi.required' => 'Klasifikasi harus dipilih',
            'klasifikasi.in' => 'Klasifikasi tidak valid',
            'file_surat.mimes' => 'File harus berupa PDF, JPG, JPEG, atau PNG',
            'file_surat.max' => 'File maksimal 16MB',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mail = IncomingMail::findOrFail($id);
        $mail->nomor_surat = $request->nomor_surat;
        $mail->pengirim = $request->pengirim;
        $mail->tanggal_surat = $request->tanggal_surat;
        $mail->tanggal_diterima = $request->tanggal_diterima;
        $mail->perihal = $request->perihal;
        $mail->klasifikasi = $request->klasifikasi;
        $mail->keterangan = $request->keterangan;

        if ($request->hasFile('file_surat')) {
            if ($mail->file_surat) {
                Storage::disk('public')->delete($mail->file_surat);
            }
            $file = $request->file('file_surat');
            $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $mail->file_surat = $file->storeAs('incoming_mail', $file_name, 'public');
        }

        $mail->save();

        Alert::success('Berhasil', 'Surat masuk berhasil diperbarui');
        return redirect()->route('back.incoming-mail.index');
    }

    public function destroy($id)
    {
        $mail = IncomingMail::findOrFail($id);
        if ($mail->file_surat) {
            Storage::disk('public')->delete($mail->file_surat);
        }
        $mail->delete();

        Alert::success('Berhasil', 'Surat masuk berhasil dihapus');
        return redirect()->back();
    }

    public function show($id)
    {
        $data = [
            'title' => 'Detail Surat Masuk',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Masuk',
                    'link' => route('back.incoming-mail.index')
                ],
                [
                    'name' => 'Detail Surat Masuk',
                    'link' => route('back.incoming-mail.show', $id)
                ]
            ],
            'mail' => IncomingMail::findOrFail($id)
        ];

        return view('back.pages.incoming_mail.show', $data);
    }
}
