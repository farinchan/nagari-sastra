<?php

namespace App\Http\Controllers\Back;

use App\Exports\CashflowExport;
use App\Exports\FinanceReportExport;
use App\Http\Controllers\Controller;
use App\Mail\ConfirmPaymentMail;
use App\Models\Finance;
use App\Models\FinanceYear;
use App\Models\Issue;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\PaymentInvoice;
use App\Models\SettingWebsite;
use App\Models\Submission;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use illuminate\Support\Str;


class FinanceController extends Controller
{

    public function reportIndex()
    {
        $data = [
            'title' => 'Laporan Jurnal',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Jurnal',
                    'link' => route('back.finance.report.index')
                ]
            ],
            'journals' => Journal::all()
        ];
        return view('back.pages.finance.report', $data);
    }

    public function reportDatatable(Request $request)
    {
        $journal_id = $request->journal_id;
        $date_end = $request->date_end ?? now()->toDateString();
        $date_start = $request->date_start ?? now()->subMonth()->toDateString();
        $issue_id = $request->issue_id;


        $submission = Submission::with(['paymentInvoice.submissions.issue.journal'])
            ->when($journal_id, function ($query) use ($journal_id) {
                return $query->whereHas('issue.journal', function ($q) use ($journal_id) {
                    $q->where('id', $journal_id);
                });
            })
            ->when($issue_id, function ($query) use ($issue_id) {
                return $query->where('issue_id', $issue_id);
            })
            ->when($date_start, function ($query) use ($date_start) {
                return $query->whereHas('paymentInvoice', function ($q) use ($date_start) {
                    $q->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime($date_start)));
                });
            })
            ->when($date_end, function ($query) use ($date_end) {
                return $query->whereHas('paymentInvoice', function ($q) use ($date_end) {
                    $q->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime($date_end)));
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();




        $total_income = $submission->sum(function ($item) {
            return $item->paymentInvoice && $item->paymentInvoice->is_paid ? $item->paymentInvoice->payment_amount : 0;
        });
        // dd($total_income);
        $total_expense = 0;
        $total_balance = $total_income - $total_expense;

        return datatables()
            ->of($submission)
            ->addColumn('journal', function ($submission) {
                return '
                        <div class="d-flex flex-column">
                            <a href="#"
                                class="text-gray-800 text-hover-primary mb-1">' . $submission->issue->journal->title . '</a>
                        </div>
                ';
            })
            ->addColumn('author', function ($submission) {
                $author = "";
                $authorList = '';
                foreach ($submission->authors as $key => $authorData) {
                    $authorList .= '<li> <b>' . $authorData['name'] . ' </b> <br>' . $authorData['affiliation'] . '</li>';
                }
                $author = '<ul>' . $authorList . '</ul>';

                return $author;
            })
            ->addColumn('submission', function ($submission) {
                return '
                        <div class="d-flex flex-column">
                            <a href="#"
                                class="text-gray-800 text-hover-primary"> Submission ID: ' . $submission->submission_id . '</a>
                                <span class="text-gray-800 ">' . $submission->fullTitle . '</span>
                        </div>
                ';
            })
            ->addColumn('edition', function ($submission) {
                return '
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 mb-1">Vol. ' . $submission->issue->volume . ' No. ' . $submission->issue->number . ' (' . $submission->issue->year . '): ' . $submission->issue->title . '</span>
                        </div>
                ';
            })
            ->addColumn('payment_info', function ($submission) {
                $paymentInfo = '';
                $paymentInvoice = $submission->paymentInvoice;
                if ($paymentInvoice) {
                    $paymentInfo .= '
                        <div class="d-flex flex-column">
                            <span class="text-gray-800 mb-1">INVOICE ' . $paymentInvoice->invoice_number . '/JRNL/UINSMDD/' . $paymentInvoice->created_at->format('Y') . '</span>
                            <span>pembayaran: ' . $paymentInvoice->payment_percent . '% - Rp ' . number_format($paymentInvoice->payment_amount, 0, ',', '.') .  ($paymentInvoice->is_paid ? ' <span class="badge badge-light-success">Sudah Dibayar</span>' : ' <span class="badge badge-light-warning">Belum Dibayar</span>') . '</span>
                        </div>
                    ';
                }
                if ($paymentInfo == '') {
                    $paymentInfo = '<span class="text-muted">Tidak ada informasi pembayaran</span>';
                }
                return $paymentInfo;
            })
            ->addColumn('loa', function ($submission) {
                $authorId = $submission->authors[0]['id'] ?? null;
                if (Storage::exists('arsip/loa/' . 'LoA-'  . $submission->submission_id  . '-' . $submission->id . '-' . $authorId . '.pdf')) {
                    return '
                        <span class="text-success">LoA Sudah Dikirim</span>
                        ';
                } else {
                    return '<span class="text-muted">LoA Belum Terbit</span>';
                }
            })
            ->with([
                'total_income' => $total_income,
                'total_expense' => $total_expense,
                'total_balance' => $total_balance,
            ])
            ->rawColumns([
                'journal',
                'author',
                'submission',
                'edition',
                'payment_info',
                'loa'
            ])
            ->make(true);
    }

    public function reportExport(Request $request)
    {
        $journal_id = $request->journal_id;
        $issue_id = $request->issue_id;
        $date_end = $request->date_end ?? now()->toDateString();
        $date_start = $request->date_start ?? now()->subMonth()->toDateString();

        return Excel::download(new FinanceReportExport($journal_id, $issue_id, $date_start, $date_end), 'laporan-journal-' . date('Y-m-d') . '.xlsx');
    }

    public function cashflowYearStore(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:finance_years,name',
                'start_date' => 'required|date',
            ],
            [
                'name.required' => 'Nama Tahun Keuangan harus diisi',
                'name.string' => 'Nama Tahun Keuangan harus berupa teks',
                'name.max' => 'Nama Tahun Keuangan maksimal 255 karakter',
                'name.unique' => 'Nama Tahun Keuangan sudah ada',
                'start_date.required' => 'Tanggal Mulai harus diisi',
                'start_date.date' => 'Tanggal Mulai tidak valid',
            ]
        );
        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }
        FinanceYear::latest()->first()?->update([
            'end_date' => Carbon::parse($request->start_date)->subDay()->toDateString(),
            'is_active' => 0,
        ]);
        FinanceYear::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::user()->name,
        ]);

        Alert::success('Berhasil', 'Tahun Keuangan berhasil ditambahkan');
        return redirect()->back()->with('success', 'Tahun Keuangan berhasil ditambahkan');
    }

    public function cashflowYearEdit(Request $request)
    {
        $finance_year = FinanceYear::latest()->first();
        if (!$finance_year) {
            Alert::error('Gagal', 'Tahun Keuangan tidak ditemukan');
            return redirect()->back()->with('error', 'Tahun Keuangan tidak ditemukan');
        }
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255|unique:finance_years,name,' . $finance_year->id,
                'start_date' => 'required|date',
            ],
            [
                'name.required' => 'Nama Tahun Keuangan harus diisi',
                'name.string' => 'Nama Tahun Keuangan harus berupa teks',
                'name.max' => 'Nama Tahun Keuangan maksimal 255 karakter',
                'name.unique' => 'Nama Tahun Keuangan sudah ada',
                'start_date.required' => 'Tanggal Mulai harus diisi',
                'start_date.date' => 'Tanggal Mulai tidak valid',
            ]
        );
        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $finance_year->update([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'updated_by' => Auth::user()->name,
            ]);
            Alert::success('Berhasil', 'Tahun Keuangan berhasil diperbarui');
            return redirect()->back()->with('success', 'Tahun Keuangan berhasil diperbarui');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Tahun Keuangan gagal diperbarui');
            return redirect()->back()->with('error', 'Tahun Keuangan gagal diperbarui');
        }
    }

    public function cashflowIndex(Request $request)
    {
        $id = $request->id;
        if ($id) {
            $finance_year_now = FinanceYear::findOrFail($id);
            $start_date = $finance_year_now->start_date;
            $end_date = $finance_year_now->end_date ?? now()->addDay()->toDateString();
        } else {
            $finance_year_now = FinanceYear::latest()->first();
            $start_date = $finance_year_now ? $finance_year_now->start_date : now()->startOfYear()->toDateString();
            $end_date = $finance_year_now && $finance_year_now->end_date ? $finance_year_now->end_date : now()->addDay()->toDateString();
        }

        $finance_now = Finance::where('date', '>=', $start_date)
            ->where('date', '<=', $end_date);

        $outcome = (clone $finance_now)->where('type', 'expense')->sum('amount');
        $income_temp = (clone $finance_now)->where('type', 'income')->sum('amount');

        $payment = Payment::with(['paymentInvoice'])
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->where('payment_status', 'accepted')
            ->get()
            ->map(function ($item) {
            return $item->paymentInvoice->payment_amount ?? 0;
            })->sum();

        $income = $income_temp + $payment;
        $balance = $income - $outcome;
        $data = [
            'title' => 'Laporan Keuangan',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Keuangan',
                    'link' => route('back.finance.cashflow.index')
                ]
            ],
            'finance_year' => $finance_year_now,
            'list_finance_year' => FinanceYear::latest()->get(),
            'total_outcome_now' => $outcome,
            'total_income_now' => $income,
            'total_balance_now' => $balance,

        ];
        // return response()->json($data);
        return view('back.pages.finance.cashflow', $data);
    }

    public function cashflowDatatables(Request $request)
    {
        $type = $request->type ?? "all";
        $date_end = $request->date_end ?? now()->toDateString();
        $date_start = $request->date_start ?? now()->subMonth()->toDateString();

        $finance = Finance::where('date', '>=', $date_start)
            ->where('date', '<=', $date_end)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'type' => $item->type,
                    'amount' => $item->amount,
                    'date' => $item->date,
                    'payment_method' => $item->payment_method,
                    'payment_reference' => $item->payment_reference,
                    'payment_note' => $item->payment_note,
                    'attachment' => $item->attachment,
                    'editable' => true,
                    'created_at' => $item->created_at,
                    'created_by' => $item->created_by,
                    'updated_at' => $item->updated_at,
                    'updated_by' => $item->updated_by,
                ];
            })->collect();

        $billing = Payment::with(['paymentInvoice'])
            ->whereBetween('created_at', [$date_start, $date_end])
            ->where('payment_status', 'accepted')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'id' => null,
                    'name' => 'Pembayaran Invoice ' . ($item->paymentInvoice->invoice_number ?? 'Unknown Invoice')  . "/JRNL/UINSMDD/" . ($item->paymentInvoice->created_at ? $item->paymentInvoice->created_at->format('Y') : '-'),
                    'description' => 'Pembayaran Invoice ' . ($item->paymentInvoice->invoice_number ?? 'Unknown Invoice') . "/JRNL/UINSMDD/" . ($item->paymentInvoice->created_at ? $item->paymentInvoice->created_at->format('Y') : '-') . ' Yang Telah Dibayarkan Oleh ' . ($item->name ?? 'Unknown Payer'),
                    'type' => 'income',
                    'amount' => $item->paymentInvoice->payment_amount ?? 0,
                    'date' => $item->payment_timestamp,
                    'payment_method' => ($item->payment_method ?? "-") . ' a/n ' . ($item->payment_account_name ?? "-"),
                    'payment_reference' => "-",
                    'payment_note' => $item->payment_note,
                    'attachment' => $item->payment_file,
                    'editable' => false,
                    'created_at' => $item->created_at,
                    'created_by' => $item->created_by ?? '-',
                    'updated_at' => $item->updated_at,
                    'updated_by' => $item->updated_by ?? '-',
                ];
            })->collect();


        $data = $finance->merge($billing)->when($type != 'all', function ($query) use ($type) {
            return $query->where('type', $type);
        })->sortByDesc('date')->values();

        $total_income = $data->where('type', 'income')->sum('amount');
        $total_expense = $data->where('type', 'expense')->sum('amount');
        $total_balance = $total_income - $total_expense;

        return datatables()->of($data)
            ->addColumn('transaction', function ($row) {
                return '<div class="d-flex flex-column">
                            <a href="#"
                            class="text-gray-800 text-hover-primary mb-1">' . $row->name . '</a>
                            <span class="text-muted">' . $row->description . '</span>
                        </div>';
            })
            ->addColumn('date', function ($row) {
                return '<span class="fw-bold">' . Carbon::parse($row->date)->format('d M Y') . '</span>';
            })
            ->addColumn('amount', function ($row) {
                if ($row->type == 'income') {
                    return '<span class="text-success">+' . number_format($row->amount, 0, ',', '.') . '</span>';
                } else {
                    return '<span class="text-danger">-' . number_format($row->amount, 0, ',', '.') . '</span>';
                }
            })
            ->addColumn('type', function ($row) {
                return '<span class="badge badge-' . ($row->type == 'income' ? 'success' : 'danger') . '">' . $row->type . '</span>';
            })
            ->addColumn('payment_info', function ($row) {
                return '<ul>
                            <li>
                                <span class="fw-bold">Metode Pembayaran:</span>
                                <span>' . ($row->payment_method ?? '-') . '</span>
                            </li>
                            <li>
                                <span class="fw-bold">No Ref:</span>
                                <span>' . ($row->payment_reference ?? '-') . '</span>
                            </li>
                            <li>
                                <span class="fw-bold">Note:</span>
                                <span>' . ($row->payment_note ?? '-') . '</span>
                            </li>
                        </ul>';
            })
            ->addColumn('attachment', function ($row) {
                if ($row->attachment) {
                    return '<a href="' . asset('storage/' . $row->attachment) . '" target="_blank">
                        <i class="ki-duotone ki-file-added text-primary fs-3x" data-bs-toggle="tooltip" data-bs-placement="right" title="Lihat File">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>';
                } else {
                    return '<i class="ki-duotone ki-file-deleted text-danger fs-3x" data-bs-toggle="tooltip" data-bs-placement="right" title="File Tidak Ada">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>';
                }
            })
            ->addColumn('log', function ($row) {
                return '<ul>
                    <li>
                        <span class="fw-bold">Created At:</span>
                        <span>' . Carbon::parse($row->created_at)->format('d M Y H:i') . '</span>
                    </li>
                    <li>
                        <span class="fw-bold">Created By:</span>
                        <span>' . ($row->created_by ? (User::find($row->created_by)->name ?? "-") : '-') . '</span>
                    </li>

                    <br>

                    <li>
                        <span class="fw-bold">Update At:</span>
                        <span>' . Carbon::parse($row->updated_at)->format('d M Y H:i')  . '</span>
                    </li>
                    <li>
                        <span class="fw-bold">Update By:</span>
                        <span>' . ($row->updated_by ? (User::find($row->updated_by)->name ?? "-") : '-') . '</span>
                    </li>
                </ul>';
            })
            ->addColumn('action', function ($row) {
                if ($row->editable) {
                    return ' <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-icon btn-light-warning me-3" data-bs-toggle="modal" data-bs-target="#edit_' . $row->id . '"><i class="fa-solid fa-pen-to-square fs-4"></i></a>
                        <a href="#" class="btn btn-icon btn-light-danger" data-bs-toggle="modal" data-bs-target="#delete_' . $row->id . '"><i class="fa-solid fa-trash fs-4"></i></a>
                    </div>
                    <!-- Modal Edit -->
                    <div class="modal fade" tabindex="-1" id="edit_' . $row->id . '" aria-labelledby="editLabel_' . $row->id . '" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLabel_' . $row->id . '">Edit Transaksi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="' . route('back.finance.cashflow.update', $row->id) . '" method="POST" enctype="multipart/form-data">
                                    ' . csrf_field() . '
                                    ' . method_field('PUT') . '
                                    <div class="modal-body">
                                        <div class="mb-5">
                                            <label for="name_' . $row->id . '" class="form-label required">Nama Transaksi</label>
                                            <input type="text" class="form-control" id="name_' . $row->id . '" name="name" value="' . $row->name . '" required>
                                        </div>
                                        <div class="mb-5">
                                            <label for="description_' . $row->id . '" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="description_' . $row->id . '" name="description">' . $row->description . '</textarea>
                                        </div>
                                        <div class="mb-5">
                                            <label for="amount_' . $row->id . '" class="form-label required">Jumlah</label>
                                            <div class="input-group mb-5">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control" id="amount_' . $row->id . '" name="amount" value="' . $row->amount . '" required>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label for="date_' . $row->id . '" class="form-label required">Tanggal</label>
                                            <input type="date" class="form-control" id="date_' . $row->id . '" name="date" value="' . Carbon::parse($row->date)->format('Y-m-d') . '" required>
                                        </div>
                                        <div class="mb-5">
                                            <label for="type_' . $row->id . '" class="form-label required">Type</label>
                                            <select class="form-select" id="type_' . $row->id . '" name="type" required>
                                                <option value="income" ' . ($row->type == 'income' ? 'selected' : '') . '>Income</option>
                                                <option value="expense" ' . ($row->type == 'expense' ? 'selected' : '') . '>Expense</option>
                                            </select>
                                        </div>
                                        <div class="mb-5">
                                            <div class="row mb-5">
                                                <div class="col">
                                                    <label for="payment_method_' . $row->id . '" class="form-label">Metode Pembayaran</label>
                                                    <input type="text" class="form-control" id="payment_method_' . $row->id . '" name="payment_method" value="' . $row->payment_method . '">
                                                </div>
                                                <div class="col">
                                                    <label for="payment_reference_' . $row->id . '" class="form-label">No Referensi</label>
                                                    <input type="text" class="form-control" id="payment_reference_' . $row->id . '" name="payment_reference" value="' . $row->payment_reference . '">
                                                </div>
                                            </div>
                                            <div class="mb-5">
                                                <label for="payment_note_' . $row->id . '" class="form-label">Note</label>
                                                <textarea class="form-control" id="payment_note_' . $row->id . '" name="payment_note">' . $row->payment_note . '</textarea>
                                            </div>
                                            <div class="mb-5">
                                                <label for="attachment_' . $row->id . '" class="form-label">Lampiran</label>
                                                <input type="file" class="form-control" id="attachment_' . $row->id . '" name="attachment" accept=".jpg,.jpeg,.png,.pdf">
                                                <div class="mt-2">
                                                    File saat ini:
                                                    <a href="' . ($row->attachment ? asset('storage/' . $row->attachment) : '#') . '" target="_blank">
                                                        ' . ($row->attachment ? basename($row->attachment) : 'Tidak ada file yang diunggah') . '
                                                    </a>
                                                </div>
                                                <small class="form-text text-muted">Format: jpg, jpeg, png, pdf. Maksimal 10MB.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Delete -->
                    <div class="modal fade" tabindex="-1" id="delete_' . $row->id . '" aria-labelledby="deleteLabel_' . $row->id . '" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="deleteLabel_' . $row->id . '">Hapus Transaksi</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body mb-5">
                                    <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                                    <p class="text-danger">
                                        <strong>Peringatan: </strong> Seluruh data yang terkait dengan transaksi ini
                                        akan dihapus dan tidak dapat dikembalikan.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <form action="' . route('back.finance.cashflow.destroy', $row->id) . '" method="POST" style="display:inline;">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                } else {
                    return '<div class="d-flex justify-content-end">
                        <span class="badge badge-secondary">Tidak Dapat Diedit</span>
                    </div>';
                }
            })
            ->with([
                'total_income' => $total_income,
                'total_expense' => $total_expense,
                'total_balance' => $total_balance,
            ])
            ->rawColumns(['transaction', 'date', 'amount', 'type', 'payment_info', 'attachment', 'log', 'action'])
            ->make(true);
    }

    public function cashFlowExport(Request $request)
    {
        $type = $request->type;
        $date_end = $request->date_end ?? now()->toDateString();
        $date_start = $request->date_start ?? now()->subMonth()->toDateString();

        return Excel::download(new CashflowExport($date_start, $date_end, $type), 'cashflow_' . now()->format('Y_m_d') . '.xlsx');
    }

    public function CashflowStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
            'payment_note' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Validation failed: ' . implode(', ', $validator->errors()->all()));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $finance = new Finance();
        $finance->name = $request->name;
        $finance->description = $request->description;
        $finance->type = $request->type;
        $finance->amount = $request->amount;
        $finance->date = Carbon::parse($request->date);
        $finance->payment_method = $request->payment_method;
        $finance->payment_reference = $request->payment_reference;
        $finance->payment_note = $request->payment_note;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = Str::slug($request->name) . '_' . time() . '.' . $attachment->getClientOriginalExtension();
            $path = $attachment->storeAs('attachments/finances', $filename, 'public');
            $finance->attachment = $path;
        }
        $finance->created_by = Auth::user()->id;
        $finance->save();

        Alert::success('Success', 'Finance record created successfully.');
        return redirect()->back();
    }

    public function cashflowDestroy(Request $request)
    {
        $finance = Finance::find($request->id);
        if (!$finance) {
            Alert::error('Error', 'Finance record not found.');
            return redirect()->back();
        }

        // Hapus file lampiran jika ada
        if ($finance->attachment) {
            Storage::disk('public')->delete($finance->attachment);
        }
        $finance->delete();

        Alert::success('Success', 'Finance record deleted successfully.');
        return redirect()->back();
    }

    public function cashflowUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:1000',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'payment_reference' => 'nullable|string|max:255',
            'payment_note' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($validator->fails()) {
            Alert::error('Error', 'Validation failed: ' . implode(', ', $validator->errors()->all()));
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $finance = Finance::findOrFail($request->id);
        if (!$finance) {
            Alert::error('Error', 'Finance record not found.');
            return redirect()->back();
        }

        $finance->name = $request->name;
        $finance->description = $request->description;
        $finance->type = $request->type;
        $finance->amount = $request->amount;
        $finance->date = Carbon::parse($request->date);
        $finance->payment_method = $request->payment_method;
        $finance->payment_reference = $request->payment_reference;
        $finance->payment_note = $request->payment_note;

        if ($request->hasFile('attachment')) {
            if ($finance->attachment) {
                Storage::disk('public')->delete($finance->attachment);
            }
            $attachment = $request->file('attachment');
            $filename = Str::slug($request->name) . '_' . time() . '.' . $attachment->getClientOriginalExtension();
            $path = $attachment->storeAs('attachments/finances', $filename, 'public');
            $finance->attachment = $path;
        }
        $finance->updated_by = Auth::user()->id;
        $finance->save();

        Alert::success('Success', 'Finance record updated successfully.');
        return redirect()->back();
    }
    public function invoiceIndex()
    {
        $data = [
            'title' => 'Management Invoice',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Finance',
                    'link' => '#'
                ],
                [
                    'name' => 'Management Invoice',
                    'link' => route('back.finance.invoice.index')
                ]
            ],
        ];

        return view('back.pages.finance.invoice-management', $data);
    }

    public function invoiceDatatable(Request $request)
    {
        $status = $request->status;
        $date_start = $request->date_start;
        $date_end = $request->date_end;

        $invoices = PaymentInvoice::query()
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ($status === 'paid') {
                    $query->where('is_paid', true);
                } elseif ($status === 'unpaid') {
                    $query->where('is_paid', false);
                }
            })
            ->when($date_start, function ($query) use ($date_start) {
                $query->whereDate('created_at', '>=', $date_start);
            })
            ->when($date_end, function ($query) use ($date_end) {
                $query->whereDate('created_at', '<=', $date_end);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $total_invoices = $invoices->count();
        $total_amount = $invoices->sum('payment_amount');
        $total_paid = $invoices->where('is_paid', true)->sum('payment_amount');
        $total_unpaid = $invoices->where('is_paid', false)->sum('payment_amount');

        return datatables()
            ->of($invoices)
            ->addIndexColumn()
            ->addColumn('invoice_info', function ($invoice) {
                return '
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-gray-800">' . ($invoice->invoice ?? '-') . '</span>
                        <span class="text-muted fs-7">No: ' . ($invoice->invoice_number ?? '-') . '</span>
                        <span class="text-muted fs-7">' . ($invoice->created_at?->translatedFormat('d M Y') ?? '-') . '</span>
                    </div>
                ';
            })
            ->addColumn('items_info', function ($invoice) {
                $items = $invoice->items ?? [];
                if (empty($items)) {
                    return '<span class="text-muted">-</span>';
                }
                $html = '<div class="d-flex flex-column">';
                foreach ($items as $item) {
                    $html .= '<div class="mb-2">';
                    $html .= '<span class="text-gray-800 fw-semibold">' . ($item['id'] ?? '-') . '</span>';
                    $html .= ' <span class="text-muted">x' . ($item['qty'] ?? 1) . '</span>';
                    $html .= '<br><span class="text-gray-600 fs-7">' . ($item['name'] ?? '-') . '</span>';
                    $html .= '<br><span class="text-muted fs-7">' . ($item['detail'] ?? '-') . '</span>';
                    $html .= '</div>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('amount', function ($invoice) {
                return '
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-gray-800">Rp ' . number_format($invoice->payment_amount ?? 0, 0, ',', '.') . '</span>
                        <span class="text-muted fs-7">' . ($invoice->payment_percent ?? 0) . '%</span>
                    </div>
                ';
            })
            ->addColumn('status', function ($invoice) {
                if ($invoice->is_paid) {
                    return '<span class="badge badge-light-success">Lunas</span>';
                }
                return '<span class="badge badge-light-warning">Belum Lunas</span>';
            })
            ->addColumn('due_date', function ($invoice) {
                if (!$invoice->payment_due_date) {
                    return '<span class="text-muted">-</span>';
                }
                $dueDate = \Carbon\Carbon::parse($invoice->payment_due_date);
                $isOverdue = !$invoice->is_paid && $dueDate->isPast();
                $class = $isOverdue ? 'text-danger fw-bold' : 'text-gray-800';
                return '<span class="' . $class . '">' . $dueDate->translatedFormat('d M Y') . '</span>'
                    . ($isOverdue ? '<br><span class="badge badge-light-danger fs-8">Jatuh Tempo</span>' : '');
            })
            ->addColumn('file', function ($invoice) {
                if ($invoice->invoice_file) {
                    return '<a href="' . asset('storage/' . $invoice->invoice_file) . '" target="_blank" class="btn btn-sm btn-light-primary">
                        <i class="ki-duotone ki-document fs-5"><span class="path1"></span><span class="path2"></span></i>
                        PDF
                    </a>';
                }
                return '<span class="text-muted fs-7">Belum ada</span>';
            })
            ->addColumn('action', function ($invoice) {
                $viewUrl = route('back.finance.invoice.show', $invoice->id);
                $deleteUrl = route('back.finance.invoice.destroy', $invoice->id);

                return '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="' . $viewUrl . '">
                                    <i class="ki-duotone ki-eye fs-5 me-2"><span class="path1"></span><span class="path2"></span></i>
                                    Lihat
                                </a>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger" type="button" onclick="deleteInvoice(' . $invoice->id . ')">
                                    <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span></i>
                                    Hapus
                                </button>
                            </li>
                        </ul>
                    </div>
                ';
            })
            ->with([
                'total_invoices' => $total_invoices,
                'total_amount' => $total_amount,
                'total_paid' => $total_paid,
                'total_unpaid' => $total_unpaid,
            ])
            ->rawColumns(['invoice_info', 'items_info', 'amount', 'status', 'due_date', 'file', 'action'])
            ->make(true);
    }

    public function invoiceExport(Request $request)
    {
        $status = $request->status;
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        $search = $request->search;

        return Excel::download(
            new \App\Exports\InvoiceManagementExport($status, null, $date_start, $date_end, $search),
            'management-invoice-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function invoiceCreate()
    {
        $currentYear = now()->year;

        // Get the last invoice number for this year
        $lastInvoice = PaymentInvoice::whereYear('created_at', $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        // Generate next invoice number
        if ($lastInvoice && $lastInvoice->invoice_number) {
            // Extract number from last invoice (e.g., INV/2026/001 -> 001)
            $lastNumber = $lastInvoice->invoice_number;
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        $nextInvoiceNumber = $nextNumber;
        $nextInvoice = format_nomor($nextNumber, 'INV', 'NSG', Carbon::now()->month, $currentYear);

        $data = [
            'title' => 'Buat Invoice',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Finance',
                    'link' => '#'
                ],
                [
                    'name' => 'Management Invoice',
                    'link' => route('back.finance.invoice.index')
                ],
                [
                    'name' => 'Buat Invoice',
                    'link' => route('back.finance.invoice.create')
                ]
            ],
            'next_invoice_number' => $nextInvoiceNumber,
            'next_invoice' => $nextInvoice,
        ];

        return view('back.pages.finance.invoice-form', $data);
    }

    public function invoiceStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|string|max:255|unique:payment_invoices,invoice_number',
            'kepada' => 'required|string|max:255',
            'kepada_detail' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:2000',
            'items' => 'nullable|json',
            'payment_percent' => 'nullable|numeric|min:0|max:100',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_due_date' => 'nullable|date',
            'invoice_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->all());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $invoice = new PaymentInvoice();
            // Build formatted unique invoice from sequence
            $sequence = $request->invoice_number;
            $formattedInvoice = format_nomor($sequence, 'INV', 'NSG', Carbon::now()->month, Carbon::now()->year);
            $invoice->invoice = $formattedInvoice;
            $invoice->invoice_number = $sequence;

            // Decode items and calculate total
            $items = $request->items ? json_decode($request->items, true) : null;
            $invoice->items = $items;

            // Calculate payment_amount from items (qty x amount) if items exist
            $paymentAmount = 0;
            if ($items && is_array($items)) {
                foreach ($items as $item) {
                    $qty = floatval($item['qty'] ?? 0);
                    $amount = floatval($item['amount'] ?? 0);
                    $paymentAmount += ($qty * $amount);
                }
            }

            // Use calculated amount if items provided, otherwise use the submitted amount
            $invoice->payment_amount = $paymentAmount > 0 ? $paymentAmount : ($request->payment_amount ?? 0);
            $invoice->payment_percent = $request->payment_percent ?? 0;
            $invoice->payment_due_date = $request->payment_due_date;
            $invoice->is_custom = true;

            // Simpan field baru
            $invoice->kepada = $request->kepada;
            $invoice->kepada_detail = $request->kepada_detail;
            $invoice->keterangan = $request->keterangan;
            $invoice->created_by = Auth::user()->id;

            if ($request->hasFile('invoice_file')) {
                // Upload file manual
                $file = $request->file('invoice_file');
                $filename = Str::slug($request->invoice_number) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('invoices', $filename, 'public');
                $invoice->invoice_file = $path;
            }

            $invoice->save();

            // Jika tidak upload file manual, generate PDF otomatis
            if (!$request->hasFile('invoice_file')) {
                $pdfData = [
                    'number' => $formattedInvoice,
                    'kepada' => $invoice->kepada,
                    'kepada_detail' => $invoice->kepada_detail ?? '',
                    'items' => $items,
                    'payment_amount' => $invoice->payment_amount,
                    'payment_percent' => $invoice->payment_percent,
                    'payment_due_date' => $invoice->payment_due_date ? Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y') : null,
                    'keterangan' => $invoice->keterangan,
                    'payment_link' => route('payment.show', ['invoice_number' => str_replace('/', '-', $formattedInvoice)]),
                    'date' => Carbon::now()->translatedFormat('d F Y'),
                ];

                $pdf = Pdf::loadView('back.pages.finance.pdf.invoice', $pdfData)->setPaper('A4', 'portrait');
                $pdfPath = 'arsip/invoice/' . Carbon::now()->format('Y') . '/invoice-' . $sequence . '.pdf';

                // Hapus file lama jika ada
                if (Storage::disk('public')->exists($pdfPath)) {
                    Storage::disk('public')->delete($pdfPath);
                }
                Storage::disk('public')->put($pdfPath, $pdf->output());

                $invoice->invoice_file = $pdfPath;
                $invoice->save();
            }

            Alert::success('Berhasil', 'Invoice berhasil dibuat');
            return redirect()->route('back.finance.invoice.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Invoice gagal dibuat: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function invoiceShow($id)
    {
        $invoice = PaymentInvoice::findOrFail($id);

        $data = [
            'title' => 'Detail Invoice',
            'breadcrumbs' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('back.dashboard')
                ],
                [
                    'name' => 'Finance',
                    'link' => '#'
                ],
                [
                    'name' => 'Management Invoice',
                    'link' => route('back.finance.invoice.index')
                ],
                [
                    'name' => 'Detail Invoice',
                    'link' => route('back.finance.invoice.show', $id)
                ]
            ],
            'invoice' => $invoice,
        ];

        return view('back.pages.finance.invoice-detail', $data);
    }

    public function invoiceConfirm(Request $request, $id)
    {
        try {
            $invoice = PaymentInvoice::findOrFail($id);

            if ($request->action === 'confirm') {
                $validator = Validator::make($request->all(), [
                    'note' => 'nullable|string|max:2000',
                    'confirmation_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
                ]);

                if ($validator->fails()) {
                    Alert::error('Gagal', $validator->errors()->all());
                    return redirect()->back()->withErrors($validator);
                }

                $invoice->is_paid = true;
                $invoice->confirmed_by = Auth::user()->id;
                $invoice->confirmed_at = now();
                $invoice->confirmation_note = $request->note;
                $invoice->midtrans_payment_method = 'Manual Confirmation';
                $invoice->midtrans_paid_at = now();
                $invoice->midtrans_gross_amount_paid = $invoice->payment_amount;

                if ($request->hasFile('confirmation_file')) {
                    $file = $request->file('confirmation_file');
                    $filename = 'bukti-' . $invoice->invoice_number . '-' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('arsip/invoice/bukti/' . now()->format('Y'), $filename, 'public');
                    $invoice->confirmation_file = $path;
                }

                $invoice->save();

                Alert::success('Berhasil', 'Pembayaran invoice berhasil dikonfirmasi');
            } elseif ($request->action === 'cancel') {
                // Hapus file bukti jika ada
                if ($invoice->confirmation_file && Storage::disk('public')->exists($invoice->confirmation_file)) {
                    Storage::disk('public')->delete($invoice->confirmation_file);
                }

                $invoice->is_paid = false;
                $invoice->confirmed_by = null;
                $invoice->confirmed_at = null;
                $invoice->confirmation_note = null;
                $invoice->confirmation_file = null;
                $invoice->midtrans_payment_method = null;
                $invoice->midtrans_paid_at = null;
                $invoice->midtrans_gross_amount_paid = null;
                $invoice->midtrans_response = null;
                $invoice->midtrans_transaction_id = null;
                $invoice->save();

                Alert::success('Berhasil', 'Konfirmasi pembayaran invoice berhasil dibatalkan');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Gagal mengubah status pembayaran: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function invoiceDestroy($id)
    {
        try {
            $invoice = PaymentInvoice::findOrFail($id);

            // Hapus file invoice jika ada
            if ($invoice->invoice_file && Storage::disk('public')->exists($invoice->invoice_file)) {
                Storage::disk('public')->delete($invoice->invoice_file);
            }

            $invoice->delete();

            Alert::success('Berhasil', 'Invoice berhasil dihapus');
            return redirect()->route('back.finance.invoice.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Invoice gagal dihapus: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
