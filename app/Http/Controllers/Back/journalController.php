<?php

namespace App\Http\Controllers\Back;

use App\Exports\articleIssueExport;
use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Mail\LoaMail;
use App\Models\Editor;
use App\Models\Issue;
use App\Models\Journal;
use App\Models\PaymentAccount;
use App\Models\PaymentInvoice;
use App\Models\Reviewer;
use App\Models\SettingWebsite;
use App\Models\Submission;
use App\Models\SubmissionEditor;
use App\Models\SubmissionReviewer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use ZipArchive;

class journalController extends Controller
{
    public function index($journal_path)
    {
        $journal = Journal::where('url_path', $journal_path)->with('issues.submissions')->first();
        if (! $journal) {
            return abort(404);
        }
        $data = [
            'title' => $journal->title,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard'),
                ],
                [
                    'name' => 'Journal',
                    'link' => route('back.journal.index', $journal_path),
                ],
            ],
            'journal_path' => $journal_path,
            'journal' => $journal,
        ];

        // return response()->json($data);
        return view('back.pages.journal.index', $data);
    }

    public function issueStore(Request $request, $journal_path)
    {
        $validator = Validator::make($request->all(), [
            'volume' => 'required',
            'number' => 'required',
            'year' => 'required',
            'title' => 'required',
            'description' => 'nullable',
        ], [
            'volume.required' => 'Volume harus diisi',
            'number.required' => 'Number harus diisi',
            'year.required' => 'Year harus diisi',
            'title.required' => 'Title harus diisi',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $journal->issues()->create($request->all());
        Alert::success('Success', 'Issue has been created');

        return redirect()->back();
    }

    public function issueUpdate(Request $request, $journal_path, $issue_id)
    {
        $validator = Validator::make($request->all(), [
            'volume' => 'required',
            'number' => 'required',
            'year' => 'required',
            'title' => 'required',
            'description' => 'nullable',
            'loa_template' => 'nullable|mimes:pptx,docx,doc,pdf|max:10240',
        ], [
            'volume.required' => 'Volume harus diisi',
            'number.required' => 'Number harus diisi',
            'year.required' => 'Year harus diisi',
            'title.required' => 'Title harus diisi',
            'loa_template.mimes' => 'File harus berupa pptx, docx, doc, pdf',
            'loa_template.max' => 'File tidak boleh lebih dari 10 MB',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = $journal->issues()->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $issue->update(
            $request->except('loa_template')
        );
        if ($request->hasFile('loa_template')) {
            $file = $request->file('loa_template');
            $filename = Str::random(10).'.'.$file->getClientOriginalExtension();
            $issue->loa_template = $file->storeAs('loa_template', $filename, 'public');
            $issue->save();
        }
        Alert::success('Success', 'Issue has been updated');

        return redirect()->back();
    }

    public function issueDestroy($journal_path, $issue_id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = $journal->issues()->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $issue->delete();
        Alert::success('Success', 'Issue has been deleted');

        return redirect()->route('back.journal.index', $journal_path);
    }

    public function dashboardIndex($journal_path, $issue_id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = Issue::with('submissions')->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $data = [
            'title' => 'Vol. '.$issue->volume.' No. '.$issue->number.' ('.$issue->year.'): '.$issue->title,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard'),
                ],
                [
                    'name' => $journal->title,
                    'link' => route('back.journal.index', $journal_path),
                ],
                [
                    'name' => $issue->title,
                    'link' => route('back.journal.index', $journal_path),
                ],
            ],
            'journal_path' => $journal_path,
            'journal' => $journal,
            'issue' => $issue,
            // 'submissions' => $issue->submissions->pluck('submission_id'),
        ];

        // return response()->json($data);
        return view('back.pages.journal.detail-dashboard', $data);
    }

    // TODO: ARTCILE SECTION

    public function articleIndex($journal_path, $issue_id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = Issue::with(['submissions'])->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $data = [
            'title' => 'Vol. '.$issue->volume.' No. '.$issue->number.' ('.$issue->year.'): '.$issue->title,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard'),
                ],
                [
                    'name' => $journal->title,
                    'link' => route('back.journal.index', $journal_path),
                ],
                [
                    'name' => $issue->title,
                    'link' => route('back.journal.index', $journal_path),
                ],
            ],
            'journal_path' => $journal_path,
            'journal' => $journal,
            'issue' => $issue,
            'editors' => Editor::where('issue_id', $issue_id)->get(),
            'reviewers' => Reviewer::where('issue_id', $issue_id)->get(),
            'submissions' => $issue->submissions,
        ];

        // return response()->json($data);
        return view('back.pages.journal.detail-article', $data);
    }

    public function articleUpdate(Request $request, $journal_path, $issue_id, $id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = Issue::with('submissions')->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $submission = $issue->submissions()->find($id);
        if (! $submission) {
            return abort(404);
        }

        $validator = Validator::make($request->all(), [
            'reviewer' => 'nullable|array',
            'editor' => 'nullable|array',
        ], [
            'reviewer.required' => 'Reviewer harus dipilih',
            'reviewer.array' => 'Reviewer harus dipilih',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $submission->update([
            'free_charge' => $request->free_charge ? 1 : 0,
        ]);

        SubmissionReviewer::where('submission_id', $submission->id)->delete();

        if ($request->reviewer) {

            foreach ($request->reviewer as $reviewer) {
                SubmissionReviewer::create([
                    'submission_id' => $submission->id,
                    'reviewer_id' => $reviewer,
                ]);
            }
        }

        SubmissionEditor::where('submission_id', $submission->id)->delete();

        if ($request->editor) {
            foreach ($request->editor as $editor) {
                SubmissionEditor::create([
                    'submission_id' => $submission->id,
                    'editor_id' => $editor,
                ]);
            }
        }

        Alert::success('Success', 'Artcle has been updated');

        return redirect()->back();
    }

    public function articleDestroy($journal_path, $issue_id, $id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = Issue::with('submissions')->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $submission = $issue->submissions()->find($id);
        if (! $submission) {
            return abort(404);
        }

        $submission->delete();
        Alert::success('Success', 'Article has been deleted');

        return redirect()->back();
    }

    public function articleExport($journal_path, $issue_id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = Issue::with('submissions')->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        return Excel::download(new articleIssueExport($issue_id), 'Article-'.$issue->volume.'-'.$issue->number.'-'.$issue->year.'.xlsx');

    }

    public function loaGenerate($submission)
    {
        $submission = Submission::find($submission);
        if (! $submission) {
            Alert::error('Error', 'Submission not found');

            return redirect()->back()->with('error', 'Submission not found');
        }

        $issue = Issue::find($submission->issue_id);
        if (! $issue) {
            Alert::error('Error', 'Issue not found');

            return redirect()->back()->with('error', 'Issue not found');
        }

        // Cek kategori LOA di persuratan
        $loaCategory = \App\Models\OutgoingMailCategory::where('kode', 'LOA')->first();
        if (! $loaCategory) {
            Alert::error('Error', 'Kategori surat dengan kode "LOA" belum dibuat. Silakan buat terlebih dahulu di menu Kategori Surat.');

            return redirect()->back();
        }

        // Ambil penulis pertama saja
        $authors = $submission->authors;
        if (empty($authors)) {
            Alert::error('Error', 'Tidak ada penulis pada submission ini');

            return redirect()->back();
        }
        $author = $authors[0];
        $displayName = count($authors) > 1 ? $author['name'] . ', et al.' : $author['name'];

        $path = 'arsip/loa/'.'LoA-'.$submission->ojs_submission_id.'-'.$submission->id.'.pdf';

        // Cek apakah surat keluar sudah pernah dibuat untuk submission ini
        $outgoingMail = \App\Models\OutgoingMail::where('file_surat', $path)
            ->where('outgoing_mail_category_id', $loaCategory->id)
            ->first();

        if ($outgoingMail) {
            // Pakai nomor surat yang sudah ada
            $nomorSurat = $outgoingMail->nomor_surat;
        } else {
            // Generate nomor surat baru
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;

            $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
            $romanMonth = $romans[$month] ?? '';

            $count = \App\Models\OutgoingMail::whereYear('tanggal_surat', $year)->count();
            $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            $nomorSurat = "{$sequence}/{$loaCategory->kode}/NSG/{$romanMonth}/{$year}";

            // Simpan ke tabel surat keluar (persuratan)
            $outgoingMail = new \App\Models\OutgoingMail();
            $outgoingMail->nomor_surat = $nomorSurat;
            $outgoingMail->outgoing_mail_category_id = $loaCategory->id;
            $outgoingMail->tujuan = $displayName . ($author['affiliation'] ? ' - ' . $author['affiliation'] : '');
            $outgoingMail->tanggal_surat = $now->toDateString();
            $outgoingMail->perihal = 'Letter of Acceptance (LoA) - ' . $submission->fullTitle;
            $outgoingMail->klasifikasi = 'biasa';
            $outgoingMail->keterangan = 'LoA untuk submission #' . $submission->ojs_submission_id . ' pada jurnal ' . $issue->journal->title . ' (' . 'Vol. ' . $issue->volume . ' No. ' . $issue->number . ' Tahun ' . $issue->year . ')';
            $outgoingMail->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        }

        $data = [
            'number' => $nomorSurat,
            'year' => Carbon::parse($outgoingMail->tanggal_surat)->format('Y'),
            'authors_string' => $submission->authorsString,
            'name' => $displayName,
            'affiliation' => $author['affiliation'],
            'title' => $submission->fullTitle,
            'journal' => $issue->journal->title,
            'edition' => 'Vol. '.$issue->volume.' No. '.$issue->number.' Tahun '.$issue->year,
            'date' => Carbon::parse($outgoingMail->tanggal_surat)->translatedFormat('d F Y'),
            'article_url' => $issue->journal->url . '/article/view/' . $submission->ojs_submission_id,
            'journal_thumbnail' => 'data:image/png;base64,'.base64_encode(file_get_contents($issue->journal->getJournalThumbnail())),
            'chief_editor' => $issue->journal->editor_chief_name,
            'chief_editor_signature' => $issue->journal->editor_chief_signature ? 'data:image/png;base64,'.base64_encode(file_get_contents(storage_path('app/public/'.$issue->journal->editor_chief_signature))) : null,
        ];

        $pdf = Pdf::loadView('back.pages.journal.pdf.loa', $data)->setPaper('A4', 'portrait');

        // Hapus file lama jika ada
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        Storage::disk('public')->put($path, $pdf->output());

        // Simpan path file ke surat keluar
        $outgoingMail->file_surat = $path;
        $outgoingMail->save();

        return response()->download(storage_path('app/public/'.$path), 'LoA-'.$submission->ojs_submission_id.'.pdf');
    }

    public function loaMailSend($submission)
    {
        $submission = Submission::find($submission);
        if (! $submission) {
            Alert::error('Error', 'Submission not found');

            return redirect()->back()->with('error', 'Submission not found');
        }

        $issue = Issue::find($submission->issue_id);
        if (! $issue) {
            Alert::error('Error', 'Issue not found');

            return redirect()->back()->with('error', 'Issue not found');
        }

        // Cek kategori LOA di persuratan
        $loaCategory = \App\Models\OutgoingMailCategory::where('kode', 'LOA')->first();
        if (! $loaCategory) {
            Alert::error('Error', 'Kategori surat dengan kode "LOA" belum dibuat. Silakan buat terlebih dahulu di menu Kategori Surat.');

            return redirect()->back();
        }

        // Ambil penulis pertama saja
        $authors = $submission->authors;
        if (empty($authors)) {
            Alert::error('Error', 'Tidak ada penulis pada submission ini');

            return redirect()->back();
        }
        $author = $authors[0];
        $displayName = count($authors) > 1 ? $author['name'] . ', et al.' : $author['name'];

        $path = 'arsip/loa/'.'LoA-'.$submission->ojs_submission_id.'-'.$submission->id.'.pdf';

        // Cek apakah surat keluar sudah pernah dibuat untuk submission ini
        $outgoingMail = \App\Models\OutgoingMail::where('file_surat', $path)
            ->where('outgoing_mail_category_id', $loaCategory->id)
            ->first();

        if ($outgoingMail) {
            // Pakai nomor surat yang sudah ada
            $nomorSurat = $outgoingMail->nomor_surat;
        } else {
            // Generate nomor surat baru
            $now = Carbon::now();
            $year = $now->year;
            $month = $now->month;

            $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
            $romanMonth = $romans[$month] ?? '';

            $count = \App\Models\OutgoingMail::whereYear('tanggal_surat', $year)->count();
            $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            $nomorSurat = "{$sequence}/{$loaCategory->kode}/NSG/{$romanMonth}/{$year}";

            // Simpan ke tabel surat keluar (persuratan)
            $outgoingMail = new \App\Models\OutgoingMail();
            $outgoingMail->nomor_surat = $nomorSurat;
            $outgoingMail->outgoing_mail_category_id = $loaCategory->id;
            $outgoingMail->tujuan = $displayName . ($author['affiliation'] ? ' - ' . $author['affiliation'] : '');
            $outgoingMail->tanggal_surat = $now->toDateString();
            $outgoingMail->perihal = 'Letter of Acceptance (LoA) - ' . $submission->fullTitle;
            $outgoingMail->klasifikasi = 'biasa';
            $outgoingMail->keterangan = 'LoA untuk submission #' . $submission->ojs_submission_id . ' pada jurnal ' . $issue->journal->title . ' (' . 'Vol. ' . $issue->volume . ' No. ' . $issue->number . ' Tahun ' . $issue->year . ')';
            $outgoingMail->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        }

        $data = [
            'subject' => 'Letter of Acceptance (LoA) for '.$displayName,
            'number' => $nomorSurat,
            'year' => Carbon::parse($outgoingMail->tanggal_surat)->format('Y'),
            'authors_string' => $submission->authorsString,
            'name' => $displayName,
            'email' => $author['email'],
            'affiliation' => $author['affiliation'],
            'title' => $submission->fullTitle,
            'journal' => $issue->journal->title,
            'edition' => 'Vol. '.$issue->volume.' No. '.$issue->number.' Tahun '.$issue->year,
            'date' => Carbon::parse($outgoingMail->tanggal_surat)->translatedFormat('d F Y'),
            'article_url' => $issue->journal->url . '/article/view/' . $submission->ojs_submission_id,
            'journal_thumbnail' => 'data:image/png;base64,'.base64_encode(file_get_contents($issue->journal->getJournalThumbnail())),
            'chief_editor' => $issue->journal->editor_chief_name,
            'chief_editor_signature' => $issue->journal->editor_chief_signature ? 'data:image/png;base64,'.base64_encode(file_get_contents(storage_path('app/public/'.$issue->journal->editor_chief_signature))) : null,
            'setting_web' => SettingWebsite::first(),
        ];

        $pdf = Pdf::loadView('back.pages.journal.pdf.loa', $data)->setPaper('A4', 'portrait');

        // Hapus file lama jika ada
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        Storage::disk('public')->put($path, $pdf->output());

        // Simpan path file ke surat keluar
        $outgoingMail->file_surat = $path;
        $outgoingMail->save();

        $data['attachments'] = storage_path('app/public/'.$path);

        $mailEnvirontment = env('MAIL_ENVIRONMENT', 'local');
        foreach ($authors as $a) {
            if (!empty($a['email'])) {
                if ($mailEnvirontment == 'production') {
                    Mail::to($a['email'])->send(new LoaMail($data));
                } else {
                    Mail::to(env('MAIL_LOCAL_ADDRESS'))->send(new LoaMail($data));
                }
            }
        }

        $this->sendLoaWhatsappNotification($submission->id);

        Alert::success('Success', 'Email has been sent');

        return redirect()->back();
    }

    public function invoiceGenerate($submission)
    {
        $submission = Submission::find($submission);
        if (! $submission) {
            Alert::error('Error', 'Submission not found');

            return redirect()->back()->with('error', 'Submission not found');
        }

        $issue = Issue::find($submission->issue_id);
        if (! $issue) {
            Alert::error('Error', 'Issue not found');

            return redirect()->back()->with('error', 'Issue not found');
        }

        $invoice = $submission->paymentInvoice;
        if (! $invoice) {
            $year = Carbon::now()->year;
            $last = PaymentInvoice::whereYear('created_at', $year)
                ->orderBy('invoice_number', 'desc')
                ->first();
            $newNumber = $last ? $last->invoice_number + 1 : 1;

            // Format jadi 4 digit
            $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            $invoice = PaymentInvoice::create([
                'invoice_number' => $formattedNumber,
                'invoice' => format_nomor($formattedNumber, 'INV', 'NSG', Carbon::now()->month, Carbon::now()->year),
                'payment_percent' => 100,
                'payment_amount' => $issue->journal->author_fee,
                'payment_due_date' => Carbon::now()->addDays(3),
                'items' => [
                    [
                        'id' => $submission->ojs_submission_id,
                        'name' => 'Biaya Publikasi Artikel Jurnal ID: '.$submission->ojs_submission_id.' - '.$submission->fullTitle,
                        'qty' => 1,
                        'detail' => 'Pembayaran Biaya Publikasi jurnal '.$issue->journal->title.' Pada Vol. '.$issue->volume.' No. '.$issue->number.' ('.$issue->year.')',

                        'amount' => $issue->journal->author_fee,
                    ],
                ],
            ]);

            $submission->update(['payment_invoice_id' => $invoice->id]);
        }

        // Get first author only
        $author = $submission->authors[0] ?? null;
        if (! $author) {
            Alert::error('Error', 'No authors found');

            return redirect()->back()->with('error', 'No authors found');
        }

        $data = [
            'number' => $invoice->invoice ?? '0000',
            'year' => $invoice->created_at->format('Y') ?? Carbon::now()->format('Y'),
            'name' => $author['name'],
            'affiliation' => $author['affiliation'],
            'authorship' => collect($submission->authors)->map(function ($author) {
                return $author['name'];
            })->implode(', '),
            'title' => $submission->fullTitle,
            'authorString' => $submission->authorsString,
            'journal' => $issue->journal->title,
            'payment_percent' => $invoice->payment_percent,
            'payment_amount' => $invoice->payment_amount,
            'payment_due_date' => \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y'),
            'edition' => 'Vol. '.$issue->volume.' No. '.$issue->number.' Tahun '.$issue->year,
            'date' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
            'id' => $submission->ojs_submission_id,
        ];

        // Check if file exists
        $storagePath = 'arsip/invoice/jurnal/'.$invoice->created_at->format('Y').'/';
        $fileName = 'invoice-'.$invoice->invoice_number.'-'.$submission->ojs_submission_id.'.pdf';

        if (Storage::exists($storagePath.$fileName)) {
            $invoice->update(['invoice_file' => $storagePath.$fileName]);

            return response()->download(storage_path('app/public/'.$storagePath.$fileName));
        } else {
            // Create directory if not exists
            if (! Storage::exists($storagePath)) {
                Storage::makeDirectory($storagePath, 0777, true, true);
            }

            $pdf = Pdf::loadView('back.pages.journal.pdf.invoice', $data)->setPaper('A4', 'portrait');
            Storage::disk('public')->put($storagePath.$fileName, $pdf->output());
            $invoice->update(['invoice_file' => $storagePath.$fileName]);

            return response()->download(storage_path('app/public/'.$storagePath.$fileName));
        }
    }

    public function invoiceMailSend($submission)
    {
        $submission = Submission::find($submission);
        if (! $submission) {
            Alert::error('Error', 'Submission not found');

            return redirect()->back()->with('error', 'Submission not found');
        }

        $issue = Issue::find($submission->issue_id);
        if (! $issue) {
            Alert::error('Error', 'Issue not found');

            return redirect()->back()->with('error', 'Issue not found');
        }

        $invoice = $submission->paymentInvoice;
        if (! $invoice) {
            $year = Carbon::now()->year;
            $last = PaymentInvoice::whereYear('created_at', $year)
                ->orderBy('invoice_number', 'desc')
                ->first();
            $newNumber = $last ? $last->invoice_number + 1 : 1;

            // Format jadi 4 digit
            $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            $invoice = PaymentInvoice::create([
                'invoice_number' => $formattedNumber,
                'invoice' => format_nomor($formattedNumber, 'INV', 'NSG', Carbon::now()->month, Carbon::now()->year),
                'payment_percent' => 100,
                'payment_amount' => $issue->journal->author_fee,
                'payment_due_date' => Carbon::now()->addDays(3),
                'items' => [
                    [
                        'id' => $submission->ojs_submission_id,
                        'name' => 'Biaya Publikasi Artikel Jurnal ID: '.$submission->ojs_submission_id.' - '.$submission->fullTitle,
                        'qty' => 1,
                        'detail' => 'Pembayaran Biaya Publikasi jurnal '.$issue->journal->title.' Pada Vol. '.$issue->volume.' No. '.$issue->number.' ('.$issue->year.')',
                        'amount' => $issue->journal->author_fee,
                    ],
                ],
            ]);

            $submission->update(['payment_invoice_id' => $invoice->id]);
        }

        // Get first author only
        $author = $submission->authors[0] ?? null;
        if (! $author) {
            Alert::error('Error', 'No authors found');

            return redirect()->back()->with('error', 'No authors found');
        }

        try {
            if ($author['email']) {
                $data = [
                    'number' => $invoice->invoice ?? '0000',
                    'year' => $invoice->created_at->format('Y') ?? Carbon::now()->format('Y'),
                    'name' => $author['name'],
                    'affiliation' => $author['affiliation'],
                    'authorship' => collect($submission->authors)->map(function ($author) {
                        return $author['name'];
                    })->implode(', '),
                    'title' => $submission->fullTitle,
                    'authorString' => $submission->authorsString,
                    'journal' => $issue->journal->title,
                    'payment_percent' => $invoice->payment_percent,
                    'payment_amount' => $invoice->payment_amount,
                    'payment_due_date' => \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y'),
                    'edition' => 'Vol. '.$issue->volume.' No. '.$issue->number.' Tahun '.$issue->year,
                    'date' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
                    'id' => $submission->ojs_submission_id,
                ];

                // Check if file exists
                $storagePath = 'arsip/invoice/jurnal/'.$invoice->created_at->format('Y').'/';
                $fileName = 'invoice-'.$invoice->invoice_number.'-'.$submission->ojs_submission_id.'.pdf';

                if (Storage::exists($storagePath.$fileName)) {
                    $data['attachments'] = storage_path('app/public/'.$storagePath.$fileName);
                } else {
                    // Create directory if not exists
                    if (! Storage::exists($storagePath)) {
                        Storage::makeDirectory($storagePath, 0777, true, true);
                    }

                    $pdf = Pdf::loadView('back.pages.journal.pdf.invoice', $data)->setPaper('A4', 'portrait');
                    Storage::disk('public')->put($storagePath.$fileName, $pdf->output());
                    $data['attachments'] = storage_path('app/public/'.$storagePath.$fileName);
                }

                $invoice->update(['invoice_file' => $storagePath.$fileName]);

                $mailEnvirontment = env('MAIL_ENVIRONMENT', 'local');
                if ($mailEnvirontment == 'production') {
                    Mail::to($author['email'])->send(new InvoiceMail($data));
                } else {
                    // For testing purpose
                    Mail::to(env('MAIL_LOCAL_ADDRESS'))->send(new InvoiceMail($data));
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
        }

        $this->sendInvoiceWhatsappNotification($invoice->id);

        Alert::success('Success', 'Email has been sent');

        return redirect()->back();
    }

    public function invoiceCustomStore(Request $request, $submission)
    {
        $validator = Validator::make($request->all(), [
            'custom_amount' => 'required|numeric|min:1',
        ], [
            'custom_amount.required' => 'Jumlah tagihan harus diisi',
            'custom_amount.numeric' => 'Jumlah tagihan harus berupa angka',
            'custom_amount.min' => 'Jumlah tagihan minimal 1',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->all());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $submission = Submission::find($submission);
        if (! $submission) {
            Alert::error('Error', 'Submission not found');

            return redirect()->back()->with('error', 'Submission not found');
        }

        $issue = Issue::find($submission->issue_id);
        if (! $issue) {
            Alert::error('Error', 'Issue not found');

            return redirect()->back()->with('error', 'Issue not found');
        }

        $invoice = $submission->paymentInvoice;
        if ($invoice && $invoice->is_custom && $invoice->is_paid) {
            Alert::error('Error', 'Tagihan custom sudah lunas');

            return redirect()->back();
        }

        if (! $invoice || ! $invoice->is_custom) {
            $year = Carbon::now()->year;
            $last = PaymentInvoice::whereYear('created_at', $year)
                ->orderBy('invoice_number', 'desc')
                ->first();
            $newNumber = $last ? $last->invoice_number + 1 : 1;

            // Format jadi 4 digit
            $formattedNumber = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            $invoice = PaymentInvoice::create([
                'invoice_number' => $formattedNumber,
                'invoice' => format_nomor($formattedNumber, 'INV', 'NSG', Carbon::now()->month, Carbon::now()->year),
                'payment_percent' => 100,
                'payment_amount' => (int) $request->custom_amount,
                'payment_due_date' => Carbon::now()->addDays(3),
                'is_custom' => true,
                'items' => [
                    [
                        'id' => $submission->ojs_submission_id,
                        'name' => 'Biaya Publikasi Artikel Jurnal ID: '.$submission->ojs_submission_id.' - '.$submission->fullTitle,
                        'qty' => 1,
                        'detail' => 'Pembayaran Biaya Publikasi jurnal '.$issue->journal->title.' Pada Vol. '.$issue->volume.' No. '.$issue->number.' ('.$issue->year.')',
                        'amount' => (int) $request->custom_amount,
                    ],
                ],
            ]);

            $submission->update(['payment_invoice_id' => $invoice->id]);
        } else {
            $invoice->update([
                'invoice' => $invoice->invoice ?? format_nomor($invoice->invoice_number, 'INV', 'NSG', Carbon::now()->month, Carbon::now()->year),
                'payment_percent' => 100,
                'payment_amount' => (int) $request->custom_amount,
                'payment_due_date' => Carbon::now()->addDays(3),
                'is_custom' => true,
            ]);
        }

        // Generate PDF invoice and save to storage
        $author = $submission->authors[0] ?? null;
        $data = [
            'number' => $invoice->invoice ?? '0000',
            'year' => $invoice->created_at->format('Y') ?? Carbon::now()->format('Y'),
            'name' => $author['name'] ?? '-',
            'authorship' => collect($submission->authors)->map(function ($a) {
                return $a['name'];
            })->implode(', '),
            'affiliation' => $author['affiliation'] ?? '-',
            'title' => $submission->fullTitle,
            'authorString' => $submission->authorsString,
            'journal' => $issue->journal->title,
            'payment_percent' => $invoice->payment_percent,
            'payment_amount' => $invoice->payment_amount,
            'payment_due_date' => \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y'),
            'edition' => 'Vol. '.$issue->volume.' No. '.$issue->number.' Tahun '.$issue->year,
            'date' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
            'id' => $submission->ojs_submission_id,
        ];

        $storagePath = 'arsip/invoice/jurnal/'.$invoice->created_at->format('Y').'/';
        $fileName = 'invoice-'.$invoice->invoice_number.'-'.$submission->ojs_submission_id.'.pdf';

        if (! Storage::exists($storagePath)) {
            Storage::makeDirectory($storagePath, 0777, true, true);
        }

        $pdf = Pdf::loadView('back.pages.journal.pdf.invoice', $data)->setPaper('A4', 'portrait');
        Storage::disk('public')->put($storagePath.$fileName, $pdf->output());
        $invoice->update(['invoice_file' => $storagePath.$fileName]);

        Alert::success('Success', 'Tagihan custom berhasil disimpan');

        return redirect()->back();
    }

    public function invoiceGenerateCustom($invoiceId)
    {
        $invoice = PaymentInvoice::find($invoiceId);
        if (! $invoice || ! $invoice->is_custom) {
            Alert::error('Error', 'Custom invoice not found');

            return redirect()->back()->with('error', 'Custom invoice not found');
        }

        if (! $invoice->invoice_file || ! Storage::disk('public')->exists($invoice->invoice_file)) {
            Alert::error('Error', 'File invoice belum tersedia');

            return redirect()->back()->with('error', 'File invoice belum tersedia');
        }

        return response()->download(storage_path('app/public/'.$invoice->invoice_file));
    }

    public function invoiceMailSendCustom($invoiceId)
    {
        $invoice = PaymentInvoice::find($invoiceId);
        if (! $invoice || ! $invoice->is_custom) {
            Alert::error('Error', 'Custom invoice not found');

            return redirect()->back()->with('error', 'Custom invoice not found');
        }

        $submission = $invoice->submissions()->first();
        if (! $submission) {
            Alert::error('Error', 'Submission not found');

            return redirect()->back()->with('error', 'Submission not found');
        }

        $issue = Issue::find($submission->issue_id);
        if (! $issue) {
            Alert::error('Error', 'Issue not found');

            return redirect()->back()->with('error', 'Issue not found');
        }

        // Get first author only
        $author = $submission->authors[0] ?? null;
        if (! $author) {
            Alert::error('Error', 'No authors found');

            return redirect()->back()->with('error', 'No authors found');
        }

        try {
            if ($author['email']) {
                $data = [
                    'number' => $invoice->invoice ?? '0000',
                    'year' => $invoice->created_at->format('Y') ?? Carbon::now()->format('Y'),
                    'name' => $author['name'],
                    'affiliation' => $author['affiliation'],
                    'authorship' => collect($submission->authors)->map(function ($a) {
                        return $a['name'];
                    })->implode(', '),
                    'title' => $submission->fullTitle,
                    'authorString' => $submission->authorsString,
                    'journal' => $issue->journal->title,
                    'payment_percent' => $invoice->payment_percent,
                    'payment_amount' => $invoice->payment_amount,
                    'payment_due_date' => \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y'),
                    'edition' => 'Vol. '.$issue->volume.' No. '.$issue->number.' Tahun '.$issue->year,
                    'date' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
                    'id' => $submission->ojs_submission_id,
                ];

                if ($invoice->invoice_file && Storage::disk('public')->exists($invoice->invoice_file)) {
                    $data['attachments'] = storage_path('app/public/'.$invoice->invoice_file);
                }

                $mailEnvirontment = env('MAIL_ENVIRONMENT', 'local');
                if ($mailEnvirontment == 'production') {
                    Mail::to($author['email'])->send(new InvoiceMail($data));
                } else {
                    // For testing purpose
                    Mail::to(env('MAIL_LOCAL_ADDRESS'))->send(new InvoiceMail($data));
                }
            }
        } catch (\Throwable $th) {
            // throw $th;
        }

        $this->sendInvoiceWhatsappNotification($invoice->id);

        Alert::success('Success', 'Email has been sent');

        return redirect()->back();
    }

    // Setting

    public function settingIndex($journal_path, $issue_id)
    {
        $journal = Journal::where('url_path', $journal_path)->first();
        if (! $journal) {
            return abort(404);
        }

        $issue = Issue::with('submissions')->find($issue_id);
        if (! $issue) {
            return abort(404);
        }

        $data = [
            'title' => 'Vol. '.$issue->volume.' No. '.$issue->number.' ('.$issue->year.'): '.$issue->title,
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard'),
                ],
                [
                    'name' => $journal->title,
                    'link' => route('back.journal.index', $journal_path),
                ],
                [
                    'name' => $issue->title,
                    'link' => route('back.journal.index', $journal_path),
                ],
            ],
            'journal_path' => $journal_path,
            'journal' => $journal,
            'issue' => $issue,
            // 'submissions' => $issue->submissions->pluck('submission_id'),
        ];

        // return response()->json($data);
        return view('back.pages.journal.detail-setting', $data);
    }

    private function sendInvoiceWhatsappNotification($paymentInvoiceId): void
    {
        $paymentInvoice = PaymentInvoice::find($paymentInvoiceId);
        if (! $paymentInvoice) {
            Log::error('PaymentInvoice not found with ID: '.$paymentInvoiceId);

            return;
        }

        $submission = $paymentInvoice->submissions()->first();
        $jurnal = $submission ? Journal::where('url_path', $submission->issue->journal->url_path)->first() : null;
        $paymentAccount = PaymentAccount::first();

        if (! $jurnal) {
            Log::error('Journal not found for PaymentInvoice ID: '.$paymentInvoice->id);

            return;
        }

        try {
            $response1 = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$jurnal->api_key,
            ])->get($jurnal->url.'/api/v1/submissions/'.$submission->ojs_submission_id.'/participants', [
                'apiToken' => $jurnal->api_key,
            ]);

            if ($response1->status() === 200) {
                $data1 = $response1->json();
                $response2 = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$jurnal->api_key,
                ])->get($data1[0]['_href'], [
                    'apiToken' => $jurnal->api_key,
                ]);
                if ($response2->status() === 200) {
                    $data2 = $response2->json();

                    $response_wa = Http::post(env('WHATSAPP_API_URL').'/send-message', [
                        'session' => env('WHATSAPP_API_SESSION'),
                        'to' => whatsappNumber($data2['phone']),
                        'text' => 'Halo Bapak/Ibu '.($data2['fullName'] ?? '-')."\n\n".
                            'Invoice untuk untuk pembayaran artikel Anda dengan *SUBMISSION ID: '.$submission->ojs_submission_id."* telah terbit. Berikut adalah detail invoice Anda:\n\n".
                            'INVOICE: '.($paymentInvoice->invoice_number ?? '0000').'/JRNL/UINSMDD/'.($paymentInvoice->created_at->format('Y') ?? Carbon::now()->format('Y'))."\n".
                            'Jumlah: Rp '.number_format($paymentInvoice->payment_amount, 0, ',', '.')."\n".
                            'Persentase Pembayaran: '.($paymentInvoice->payment_percent ?? '-')."%\n".

                            "Silakan lakukan pembayaran sesuai dengan jumlah yang tertera pada invoice. pembayaran dapat dilakukan melalui transfer ke rekening berikut:\n".
                            'Bank: '.($paymentAccount->bank ?? '-')."\n".
                            'Nomor Rekening: '.($paymentAccount->account_number ?? '-')."\n".
                            'Atas Nama: '.($paymentAccount->account_name ?? '-')."\n\n".

                            "berikut kami lampirkan file invoice kepada anda, jika file tidak terkirim anda dapat mengunduhnya melalui tautan berikut:\n".
                            asset('storage/arsip/invoice/'.$paymentInvoice->created_at->format('Y').'/'.$paymentInvoice->invoice_number.'/invoice-'.$submission->ojs_submission_id.'-'.$submission->authors[0]['id'].'.pdf')."\n\n".

                            'batas waktu pembayaran anda adalah '.\Carbon\Carbon::parse($paymentInvoice->payment_due_date)->translatedFormat('d F Y').". Setelah melakukan pembayaran, silakan unggah bukti pembayaran melalui tautan berikut:\n".
                            route('payment.pay', [$submission->issue->journal->url_path, $submission->ojs_submission_id])."\n\n".
                            'Terima kasih atas perhatian dan kerjasama Anda '.

                            "Salam,\n".
                            "Editorial Rumah Jurnal\n\n".

                            "_generate by system_\n".
                            url('/'),

                    ]);
                    if ($response_wa->status() === 200) {
                        Log::info('WhatsApp message sent successfully to '.$data2['phone']);
                    } else {
                        Log::error('Error sending WhatsApp message: '.$response_wa->body());
                    }
                } else {
                    Log::error('Error PaymentInvoiceObserver Response 2: '.$response2->body());
                }
            } else {
                Log::error('Error PaymentInvoiceObserver Response 1: '.$response1->body());
            }
        } catch (\Throwable $th) {
            Log::error('Error PaymentInvoiceObserver TryCatch: '.$th->getMessage());
        }
    }

    private function sendLoaWhatsappNotification($submissionId): void
    {
        $submission = Submission::find($submissionId);
        if (! $submission) {
            Log::error('Submission not found with ID: '.$submissionId);

            return;
        }

        $jurnal = Journal::where('url_path', $submission->issue->journal->url_path)->first();

        if (! $jurnal) {
            Log::error('Journal not found for Submission ID: '.$submission->id);

            return;
        }

        try {
            $response1 = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$jurnal->api_key,
            ])->get($jurnal->url.'/api/v1/submissions/'.$submission->ojs_submission_id.'/participants', [
                'apiToken' => $jurnal->api_key,
            ]);

            if ($response1->status() === 200) {
                $data1 = $response1->json();
                $response2 = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$jurnal->api_key,
                ])->get($data1[0]['_href'], [
                    'apiToken' => $jurnal->api_key,
                ]);
                if ($response2->status() === 200) {
                    $path = 'arsip/loa/'.'LoA-'.$submission->ojs_submission_id.'-'.$submission->id.'-'.$submission->authors[0]['id'].'.pdf';
                    $data2 = $response2->json();
                    $response_wa = Http::post(env('WHATSAPP_API_URL').'/send-message', [
                        'session' => env('WHATSAPP_API_SESSION'),
                        'to' => whatsappNumber($data2['phone']),
                        'text' => 'Halo Bapak/Ibu '.($data2['fullName'] ?? '-')."\n\n".
                            'Selamat! Kami dengan senang hati memberitahukan bahwa artikel Anda dengan *SUBMISSION ID: '.$submission->ojs_submission_id."* telah diterima untuk publikasi di jurnal kami. Berikut adalah detailnya:\n\n".
                            'Judul Artikel: '.($submission->fullTitle ?? '-')."\n".
                            'Penulis: '.($submission->authorsString ?? '-')."\n".
                            'Jurnal: '.($submission->issue->journal->title ?? '-')."\n".
                            'Edisi: Vol. '.($submission->issue->volume ?? '-').' No. '.($submission->issue->number ?? '-').' Tahun '.($submission->issue->year ?? '-')."\n\n".
                            "Kami lampirkan file surat penerimaan (Letter of Acceptance) untuk artikel Anda. Jika file tidak terkirim, Anda dapat mengunduhnya melalui tautan berikut:\n".
                            asset('storage/'.$path)."\n\n".
                            "Terimakasih atas kontribusi Anda terhadap kemajuan ilmu pengetahuan melalui publikasi di jurnal kami.\n\n".
                            "Salam,\n".
                            "Editorial Rumah Jurnal\n\n".
                            "_generate by system_\n".
                            url('/'),
                    ]);
                    if ($response_wa->status() === 200) {
                        Log::info('WhatsApp message sent successfully to '.$data2['phone']);
                    } else {
                        Log::error('Error sending WhatsApp message: '.$response_wa->body());
                    }
                } else {
                    Log::error('Error LoaObserver Response 2: '.$response2->body());
                }
            } else {
                Log::error('Error LoaObserver Response 1: '.$response1->body());
            }
        } catch (\Throwable $th) {
            Log::error('Error LoaObserver TryCatch: '.$th->getMessage());
        }
    }
}
