<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\OutgoingMail;
use App\Models\OutgoingMailCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class OutgoingMailController extends Controller
{
    /**
     * Convert month number to Roman numeral
     */
    private function monthToRoman($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $romans[(int)$month] ?? '';
    }

    /**
     * Generate auto nomor surat: 0001/KODE/NSG/V/2026
     */
    private function generateNomorSurat($categoryId, $tanggalSurat)
    {
        $date = Carbon::parse($tanggalSurat);
        $year = $date->year;
        $month = $date->month;

        // Count existing outgoing mails in the same year
        $count = OutgoingMail::whereYear('tanggal_surat', $year)->count();
        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        // Get category code
        $category = OutgoingMailCategory::find($categoryId);
        $kode = $category ? $category->kode : 'UMUM';

        // Roman month
        $romanMonth = $this->monthToRoman($month);

        return "{$sequence}/{$kode}/NSG/{$romanMonth}/{$year}";
    }

    public function index()
    {
        $data = [
            'title' => 'Surat Keluar',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Keluar',
                    'link' => route('back.outgoing-mail.index')
                ]
            ],
            'list_mail' => OutgoingMail::with('category')->latest()->get()
        ];

        return view('back.pages.outgoing_mail.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Surat Keluar',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Keluar',
                    'link' => route('back.outgoing-mail.index')
                ],
                [
                    'name' => 'Tambah Surat Keluar',
                    'link' => route('back.outgoing-mail.create')
                ]
            ],
            'categories' => OutgoingMailCategory::all()
        ];

        return view('back.pages.outgoing_mail.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outgoing_mail_category_id' => 'required|exists:outgoing_mail_categories,id',
            'tujuan' => 'required|max:255',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|max:255',
            'klasifikasi' => 'required|in:biasa,penting,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|mimes:pdf,jpg,jpeg,png|max:16384',
            'keterangan' => 'nullable',
        ], [
            'outgoing_mail_category_id.required' => 'Kategori surat harus dipilih',
            'outgoing_mail_category_id.exists' => 'Kategori surat tidak valid',
            'tujuan.required' => 'Tujuan harus diisi',
            'tujuan.max' => 'Tujuan maksimal 255 karakter',
            'tanggal_surat.required' => 'Tanggal surat harus diisi',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid',
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

        $mail = new OutgoingMail();
        $mail->nomor_surat = $this->generateNomorSurat($request->outgoing_mail_category_id, $request->tanggal_surat);
        $mail->outgoing_mail_category_id = $request->outgoing_mail_category_id;
        $mail->tujuan = $request->tujuan;
        $mail->tanggal_surat = $request->tanggal_surat;
        $mail->perihal = $request->perihal;
        $mail->klasifikasi = $request->klasifikasi;
        $mail->keterangan = $request->keterangan;
        $mail->user_id = Auth::user()->id;

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $mail->file_surat = $file->storeAs('outgoing_mail', $file_name, 'public');
        }

        $mail->save();

        Alert::success('Berhasil', 'Surat keluar berhasil ditambahkan');
        return redirect()->route('back.outgoing-mail.index');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Surat Keluar',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Keluar',
                    'link' => route('back.outgoing-mail.index')
                ],
                [
                    'name' => 'Edit Surat Keluar',
                    'link' => route('back.outgoing-mail.edit', $id)
                ]
            ],
            'mail' => OutgoingMail::findOrFail($id),
            'categories' => OutgoingMailCategory::all()
        ];

        return view('back.pages.outgoing_mail.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tujuan' => 'required|max:255',
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|max:255',
            'klasifikasi' => 'required|in:biasa,penting,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|mimes:pdf,jpg,jpeg,png|max:16384',
            'keterangan' => 'nullable',
        ], [
            'tujuan.required' => 'Tujuan harus diisi',
            'tujuan.max' => 'Tujuan maksimal 255 karakter',
            'tanggal_surat.required' => 'Tanggal surat harus diisi',
            'tanggal_surat.date' => 'Format tanggal surat tidak valid',
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

        $mail = OutgoingMail::findOrFail($id);
        $mail->tujuan = $request->tujuan;
        $mail->tanggal_surat = $request->tanggal_surat;
        $mail->perihal = $request->perihal;
        $mail->klasifikasi = $request->klasifikasi;
        $mail->keterangan = $request->keterangan;

        if ($request->hasFile('file_surat')) {
            if ($mail->file_surat) {
                Storage::disk('public')->delete($mail->file_surat);
            }
            $file = $request->file('file_surat');
            $file_name = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $mail->file_surat = $file->storeAs('outgoing_mail', $file_name, 'public');
        }

        $mail->save();

        Alert::success('Berhasil', 'Surat keluar berhasil diperbarui');
        return redirect()->route('back.outgoing-mail.index');
    }

    public function destroy($id)
    {
        $mail = OutgoingMail::findOrFail($id);
        if ($mail->file_surat) {
            Storage::disk('public')->delete($mail->file_surat);
        }
        $mail->delete();

        Alert::success('Berhasil', 'Surat keluar berhasil dihapus');
        return redirect()->back();
    }

    public function show($id)
    {
        $data = [
            'title' => 'Detail Surat Keluar',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Keluar',
                    'link' => route('back.outgoing-mail.index')
                ],
                [
                    'name' => 'Detail Surat Keluar',
                    'link' => route('back.outgoing-mail.show', $id)
                ]
            ],
            'mail' => OutgoingMail::with('category')->findOrFail($id)
        ];

        return view('back.pages.outgoing_mail.show', $data);
    }

    // ============================
    // Kategori Surat Keluar (CRUD)
    // ============================

    public function category()
    {
        $data = [
            'title' => 'Kategori Surat Keluar',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Surat Keluar',
                    'link' => route('back.outgoing-mail.index')
                ],
                [
                    'name' => 'Kategori',
                    'link' => route('back.outgoing-mail.category')
                ]
            ],
            'categories' => OutgoingMailCategory::latest()->get()
        ];

        return view('back.pages.outgoing_mail.category', $data);
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'kode' => 'required|max:20|unique:outgoing_mail_categories,kode',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'kode.required' => 'Kode harus diisi',
            'kode.max' => 'Kode maksimal 20 karakter',
            'kode.unique' => 'Kode sudah digunakan',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = new OutgoingMailCategory();
        $category->name = $request->name;
        $category->kode = strtoupper($request->kode);
        $category->description = $request->description;
        $category->save();

        Alert::success('Berhasil', 'Kategori surat berhasil ditambahkan');
        return redirect()->route('back.outgoing-mail.category');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'kode' => 'required|max:20|unique:outgoing_mail_categories,kode,' . $id,
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'kode.required' => 'Kode harus diisi',
            'kode.max' => 'Kode maksimal 20 karakter',
            'kode.unique' => 'Kode sudah digunakan',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = OutgoingMailCategory::findOrFail($id);
        $category->name = $request->name;
        $category->kode = strtoupper($request->kode);
        $category->description = $request->description;
        $category->save();

        Alert::success('Berhasil', 'Kategori surat berhasil diperbarui');
        return redirect()->route('back.outgoing-mail.category');
    }

    public function categoryDestroy($id)
    {
        $category = OutgoingMailCategory::findOrFail($id);
        $category->delete();

        Alert::success('Berhasil', 'Kategori surat berhasil dihapus');
        return redirect()->back();
    }
}
