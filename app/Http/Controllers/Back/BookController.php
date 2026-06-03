<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\BookCategory;
use App\Models\BookEditor;
use App\Models\OutgoingMail;
use App\Models\OutgoingMailCategory;
use App\Models\PaymentInvoice;
use App\Models\SettingWebsite;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class BookController extends Controller
{
    private function normalizeTagifyInput($value): array
    {
        if (is_array($value)) {
            return collect($value)->filter()->values()->all();
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return collect($decoded)->filter()->values()->all();
        }

        return collect(preg_split('/\s*(?:,|;|\n)\s*/', $value, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->map(fn ($item) => ['value' => $item])
            ->all();
    }

    private function normalizeAuthorString(?string $authorString, array $authors): ?string
    {
        return filled($authorString) ? trim($authorString) : null;
    }

    public function category()
    {
        $data = [
            'title' => 'Kategori Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Kategori Buku',
                    'link' => route('back.book.category')
                ]
            ],
            'categories' => BookCategory::all()
        ];

        return view('back.pages.book.category', $data);
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:book_categories,name',
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        BookCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'meta_title' => $request->name,
            'meta_description' => $request->description,
            'meta_keywords' => implode(", ", explode(" ", $request->name)),
        ]);

        return redirect()->route('back.book.category')->with('success', 'Kategori Buku berhasil ditambahkan');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:book_categories,name,' . $id,
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $category = BookCategory::find($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'meta_title' => $request->name,
            'meta_description' => $request->description,
            'meta_keywords' => implode(", ", explode(" ", $request->name)),
        ]);

        return redirect()->route('back.book.category')->with('success', 'Kategori Buku berhasil diubah');
    }

    public function categoryDestroy($id)
    {
        $category = BookCategory::find($id);
        $category->delete();

        return redirect()->route('back.book.category')->with('success', 'Kategori Buku berhasil dihapus');
    }

    public function index()
    {
        $data = [
            'title' => 'Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Buku',
                    'link' => route('back.book.index')
                ]
            ],
            'list_books' => Book::with('category')->get()
        ];

        return view('back.pages.book.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Buku',
                    'link' => route('back.book.index')
                ],
                [
                    'name' => 'Tambah Buku',
                    'link' => route('back.book.create')
                ]
            ],
            'categories' => BookCategory::all()
        ];

        return view('back.pages.book.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:book_categories,id',
            ],
            [
                'title.required' => 'Judul buku harus diisi',
                'category_id.required' => 'Kategori harus dipilih',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $slug = Book::where('slug', Str::slug($request->title))->count() > 0
            ? Str::slug($request->title) . '-' . rand(1000, 9999)
            : Str::slug($request->title);

        $book = Book::create([
            'title' => $request->title,
            'slug' => $slug,
            'book_category_id' => $request->category_id,
            'status' => 'draft',
            'price' => 0,
            'stock' => 0,
            'language' => 'id',
        ]);

        Alert::success('Success', 'Buku berhasil dibuat. Silakan lengkapi informasi detail buku.');

        return redirect()->route('back.book.show', $book->id);
    }


    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
                'preview_file' => 'nullable|mimes:pdf|max:30720',
                'attachment' => 'nullable|mimes:pdf|max:30720',
                'title' => 'required',
                'category_id' => 'required',
                'status' => 'required',
                'publisher' => 'nullable',
                'isbn' => 'nullable|unique:books,isbn,' . $id,
                'edition' => 'nullable',
                'publish_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'pages' => 'nullable|integer',
                'size' => 'nullable',
                'weight' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer',
                'language' => 'nullable|in:en,id,jp',
                'description' => 'nullable',
                'keywords' => 'nullable',
            ],
            [
                'required' => 'Kolom :attribute harus diisi',
                'image' => 'File harus berupa gambar',
                'mimes' => 'Format file harus :values',
                'max' => 'Ukuran file maksimal :max KB',
                'unique' => ':attribute sudah ada',
                'integer' => ':attribute harus berupa angka',
                'numeric' => ':attribute harus berupa angka',
                'min' => ':attribute minimal :min',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $slug = "";
        if (Book::where('slug', Str::slug($request->title))->where('id', '!=', $id)->count() > 0) {
            $slug = Str::slug($request->title) . '-' . rand(1000, 9999);
        } else {
            $slug = Str::slug($request->title);
        }

        $book->title = $request->title;
        $book->slug = $slug;
        $book->publisher = $request->publisher;
        $book->isbn = $request->isbn;
        $book->edition = $request->edition;
        $book->publish_year = $request->publish_year;
        $book->pages = $request->pages;
        $book->size = $request->size;
        $book->weight = $request->weight;
        $book->price = $request->price ?? 0;
        $book->stock = $request->stock ?? 0;
        $book->language = $request->language ?? 'id';
        $book->description = $request->description;
        $book->book_category_id = $request->category_id;
        $book->status = $request->status;
        $book->keywords = $request->keywords ? json_decode($request->keywords) : null;
        if ($request->hasFile('thumbnail')) {
            if ($book->thumbnail && Storage::disk('public')->exists($book->thumbnail)) {
                Storage::disk('public')->delete($book->thumbnail);
            }
            $thumbnail = $request->file('thumbnail');
            $book->thumbnail = $thumbnail->storeAs('books', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('preview_file')) {
            if ($book->preview_file && Storage::disk('public')->exists($book->preview_file)) {
                Storage::disk('public')->delete($book->preview_file);
            }
            $preview = $request->file('preview_file');
            $book->preview_file = $preview->storeAs('books/preview', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $preview->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($book->attachment && Storage::disk('public')->exists($book->attachment)) {
                Storage::disk('public')->delete($book->attachment);
            }
            $attachment = $request->file('attachment');
            $book->attachment = $attachment->storeAs('books/attachment', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $attachment->getClientOriginalExtension(), 'public');
        }

        $book->save();

        Alert::success('Success', 'Buku berhasil diperbarui');

        return redirect()->route('back.book.show', $id);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if ($book->thumbnail && Storage::disk('public')->exists($book->thumbnail)) {
            Storage::disk('public')->delete($book->thumbnail);
        }
        if ($book->preview_file && Storage::disk('public')->exists($book->preview_file)) {
            Storage::disk('public')->delete($book->preview_file);
        }
        if ($book->attachment && Storage::disk('public')->exists($book->attachment)) {
            Storage::disk('public')->delete($book->attachment);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus');
    }

    // ==========================================
    // DETAIL TABS
    // ==========================================

    public function show($id)
    {
        $book = Book::with(['category', 'editors', 'bookAuthors', 'invoices'])->findOrFail($id);

        $data = [
            'title' => $book->title,
            'breadcrumbs' => [
                ['name' => 'Buku', 'link' => route('back.book.index')],
                ['name' => $book->title, 'link' => route('back.book.show', $id)],
            ],
            'book' => $book,
            'editors' => User::role('editor')->get(),
            'categories' => BookCategory::all(),
        ];

        return view('back.pages.book.show', $data);
    }

    public function authorTab($id)
    {
        $book = Book::with(['category', 'bookAuthors', 'editors', 'invoices'])->findOrFail($id);

        $data = [
            'title' => $book->title . ' - Penulis',
            'breadcrumbs' => [
                ['name' => 'Buku', 'link' => route('back.book.index')],
                ['name' => $book->title, 'link' => route('back.book.show', $id)],
                ['name' => 'Penulis', 'link' => route('back.book.authors', $id)],
            ],
            'book' => $book,
        ];

        return view('back.pages.book.show-author', $data);
    }

    public function paymentTab($id)
    {
        $book = Book::with(['category', 'bookAuthors', 'editors', 'invoices'])->findOrFail($id);

        $data = [
            'title' => $book->title . ' - Pembayaran',
            'breadcrumbs' => [
                ['name' => 'Buku', 'link' => route('back.book.index')],
                ['name' => $book->title, 'link' => route('back.book.show', $id)],
                ['name' => 'Pembayaran', 'link' => route('back.book.payment', $id)],
            ],
            'book' => $book,
        ];

        return view('back.pages.book.show-payment', $data);
    }

    // ==========================================
    // EDITOR ASSIGNMENT
    // ==========================================

    public function editorUpdate(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        BookEditor::where('book_id', $book->id)->delete();

        if ($request->editor_ids) {
            foreach ($request->editor_ids as $editorId) {
                BookEditor::create([
                    'book_id' => $book->id,
                    'user_id' => $editorId,
                ]);
            }
        }

        Alert::success('Success', 'Editor berhasil diperbarui');

        return redirect()->back();
    }

    // ==========================================
    // AUTHORS CRUD
    // ==========================================

    public function authorStore(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_with_title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'affiliation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ], [
            'name.required' => 'Nama penulis harus diisi',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $maxOrder = $book->bookAuthors()->max('order') ?? 0;

        BookAuthor::create([
            'book_id' => $book->id,
            'name' => $request->name,
            'name_with_title' => $request->name_with_title,
            'email' => $request->email,
            'affiliation' => $request->affiliation,
            'phone' => $request->phone,
            'order' => $maxOrder + 1,
        ]);

        Alert::success('Success', 'Penulis berhasil ditambahkan');

        return redirect()->back();
    }

    public function authorUpdate(Request $request, $id, $authorId)
    {
        $book = Book::findOrFail($id);
        $author = BookAuthor::where('book_id', $book->id)->findOrFail($authorId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'name_with_title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'affiliation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ], [
            'name.required' => 'Nama penulis harus diisi',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $author->update([
            'name' => $request->name,
            'name_with_title' => $request->name_with_title,
            'email' => $request->email,
            'affiliation' => $request->affiliation,
            'phone' => $request->phone,
        ]);

        Alert::success('Success', 'Penulis berhasil diperbarui');

        return redirect()->back();
    }

    public function authorDestroy($id, $authorId)
    {
        $book = Book::findOrFail($id);
        $author = BookAuthor::where('book_id', $book->id)->findOrFail($authorId);

        $author->delete();

        Alert::success('Success', 'Penulis berhasil dihapus');

        return redirect()->back();
    }

    public function authorCertificate($id, $authorId)
    {
        $book = Book::findOrFail($id);
        $author = BookAuthor::where('book_id', $book->id)->findOrFail($authorId);
        $setting_web = SettingWebsite::first();

        // Find or create category SRT-PB
        $category = OutgoingMailCategory::firstOrCreate(
            ['kode' => 'SRT-PB'],
            ['name' => 'Sertifikat Penulis Buku']
        );

        // Generate nomor surat
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;
        $count = OutgoingMail::whereYear('tanggal_surat', $year)->count();
        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $romanMonth = $romans[$month] ?? '';
        $nomorSurat = "{$sequence}/{$category->kode}/NSG/{$romanMonth}/{$year}";

        // Generate PDF
        $data = [
            'book' => $book,
            'author' => $author,
            'setting_web' => $setting_web,
            'date' => $now->translatedFormat('d F Y'),
            'nomor_surat' => $nomorSurat,
        ];

        $pdf = Pdf::loadView('back.pages.book.pdf.certificate', $data)->setPaper('A4', 'landscape');

        // Save PDF file
        $pdfPath = 'arsip/sertifikat-buku/' . $now->format('Y') . '/sertifikat-' . Str::slug($author->name) . '-' . Str::slug($book->title) . '-' . $now->format('YmdHis') . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Create OutgoingMail record
        OutgoingMail::create([
            'nomor_surat' => $nomorSurat,
            'outgoing_mail_category_id' => $category->id,
            'tujuan' => $author->name_with_title ?? $author->name,
            'tanggal_surat' => $now->toDateString(),
            'perihal' => 'Sertifikat Penulis Buku: ' . $book->title,
            'klasifikasi' => 'biasa',
            'keterangan' => 'Sertifikat penulis buku "' . $book->title . '" atas nama ' . ($author->name_with_title ?? $author->name),
            'file_surat' => $pdfPath,
            'user_id' => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Sertifikat berhasil diterbitkan dan tercatat sebagai surat keluar');

        return $pdf->download('Sertifikat-' . Str::slug($author->name) . '-' . Str::slug($book->title) . '.pdf');
    }

    // ==========================================
    // INVOICE
    // ==========================================

    public function invoiceStore(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kepada' => 'required|string|max:255',
            'kepada_detail' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:2000',
            'payment_due_date' => 'nullable|date',
            'item_name' => 'required|array|min:1',
            'item_name.*' => 'required|string|max:255',
            'item_detail' => 'nullable|array',
            'item_detail.*' => 'nullable|string|max:255',
            'item_qty' => 'required|array',
            'item_qty.*' => 'required|numeric|min:1',
            'item_amount' => 'required|array',
            'item_amount.*' => 'required|numeric|min:0',
        ], [
            'kepada.required' => 'Kepada harus diisi',
            'item_name.required' => 'Minimal harus ada 1 item',
            'item_name.*.required' => 'Nama item harus diisi',
            'item_qty.*.required' => 'Qty harus diisi',
            'item_amount.*.required' => 'Harga harus diisi',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Build items array
        $items = [];
        $totalAmount = 0;
        foreach ($request->item_name as $i => $name) {
            $qty = floatval($request->item_qty[$i] ?? 1);
            $amount = floatval($request->item_amount[$i] ?? 0);
            $items[] = [
                'id' => 'BOOK-' . $book->id . '-' . ($i + 1),
                'name' => $name,
                'detail' => $request->item_detail[$i] ?? '',
                'qty' => $qty,
                'amount' => $amount,
            ];
            $totalAmount += ($qty * $amount);
        }

        // Check if book already has an invoice
        $invoice = PaymentInvoice::where('book_id', $book->id)->first();

        if ($invoice) {
            // Update existing invoice
            $invoice->update([
                'kepada' => $request->kepada,
                'kepada_detail' => $request->kepada_detail,
                'keterangan' => $request->keterangan,
                'items' => $items,
                'payment_amount' => $totalAmount,
                'payment_due_date' => $request->payment_due_date,
            ]);
        } else {
            // Create new invoice
            $currentYear = Carbon::now()->year;
            $lastInvoice = PaymentInvoice::whereYear('created_at', $currentYear)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastInvoice && $lastInvoice->invoice_number
                ? str_pad(intval($lastInvoice->invoice_number) + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $formattedInvoice = format_nomor($nextNumber, 'INV', 'NSG', Carbon::now()->month, $currentYear);

            $invoice = PaymentInvoice::create([
                'book_id' => $book->id,
                'invoice' => $formattedInvoice,
                'invoice_number' => $nextNumber,
                'items' => $items,
                'kepada' => $request->kepada,
                'kepada_detail' => $request->kepada_detail,
                'keterangan' => $request->keterangan,
                'payment_amount' => $totalAmount,
                'payment_percent' => 100,
                'payment_due_date' => $request->payment_due_date,
                'is_custom' => true,
                'created_by' => Auth::id(),
            ]);
        }

        // Generate / Regenerate PDF
        $pdfData = [
            'number' => $invoice->invoice,
            'kepada' => $invoice->kepada,
            'kepada_detail' => $invoice->kepada_detail ?? '',
            'items' => $items,
            'payment_amount' => $totalAmount,
            'payment_percent' => $invoice->payment_percent,
            'payment_due_date' => $invoice->payment_due_date ? Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y') : null,
            'keterangan' => $invoice->keterangan,
            'payment_link' => route('payment.show', ['invoice_number' => str_replace('/', '-', $invoice->invoice)]),
            'date' => Carbon::now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('back.pages.finance.pdf.invoice', $pdfData)->setPaper('A4', 'portrait');
        $pdfPath = 'arsip/invoice/' . Carbon::now()->format('Y') . '/invoice-' . $invoice->invoice_number . '.pdf';

        if (Storage::disk('public')->exists($pdfPath)) {
            Storage::disk('public')->delete($pdfPath);
        }
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $invoice->update(['invoice_file' => $pdfPath]);

        Alert::success('Success', 'Invoice berhasil disimpan');

        return redirect()->back();
    }

    public function invoiceGenerate($invoiceId)
    {
        $invoice = PaymentInvoice::findOrFail($invoiceId);

        if ($invoice->invoice_file && Storage::disk('public')->exists($invoice->invoice_file)) {
            return response()->download(Storage::disk('public')->path($invoice->invoice_file));
        }

        // Regenerate if file doesn't exist
        $pdfData = [
            'number' => $invoice->invoice,
            'kepada' => $invoice->kepada,
            'kepada_detail' => $invoice->kepada_detail ?? '',
            'items' => $invoice->items ?? [],
            'payment_amount' => $invoice->payment_amount,
            'payment_percent' => $invoice->payment_percent,
            'payment_due_date' => $invoice->payment_due_date ? Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y') : null,
            'keterangan' => $invoice->keterangan,
            'payment_link' => route('payment.show', ['invoice_number' => str_replace('/', '-', $invoice->invoice)]),
            'date' => $invoice->created_at->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('back.pages.finance.pdf.invoice', $pdfData)->setPaper('A4', 'portrait');

        return $pdf->download('Invoice-' . $invoice->invoice . '.pdf');
    }
}

