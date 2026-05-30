<?php

namespace App\Exports;

use App\Models\Submission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinanceReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $journal_id;
    protected $issue_id;
    protected $date_start;
    protected $date_end;
    protected $counter = 0;

    public function __construct($journal_id = null, $issue_id = null, $date_start = null, $date_end = null)
    {
        $this->journal_id = $journal_id;
        $this->issue_id = $issue_id;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
    }

    public function title(): string
    {
        return 'Laporan Keuangan Jurnal';
    }

    public function collection()
    {
        return Submission::with(['paymentInvoice', 'issue.journal'])
            ->when($this->journal_id, function ($query) {
                return $query->whereHas('issue.journal', function ($q) {
                    $q->where('id', $this->journal_id);
                });
            })
            ->when($this->issue_id, function ($query) {
                return $query->where('issue_id', $this->issue_id);
            })
            ->when($this->date_start, function ($query) {
                return $query->where('created_at', '>=', $this->date_start);
            })
            ->when($this->date_end, function ($query) {
                return $query->where('created_at', '<=', $this->date_end);
            })
            ->whereHas('paymentInvoice')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jurnal',
            'Edisi',
            'Submission ID',
            'Judul Artikel',
            'Penulis',
            'Nomor Invoice',
            'Jumlah Tagihan',
            'Status Pembayaran',
            'Tanggal Bayar',
            'Metode Pembayaran',
            'Tanggal Dibuat',
        ];
    }

    public function map($submission): array
    {
        $this->counter++;
        $invoice = $submission->paymentInvoice;
        $issue = $submission->issue;

        // Format authors
        $authors = collect($submission->authors ?? [])
            ->pluck('name')
            ->filter()
            ->implode(', ');

        // Format edition
        $edition = $issue
            ? 'Vol. ' . $issue->volume . ' No. ' . $issue->number . ' (' . $issue->year . ')'
            : '-';

        return [
            $this->counter,
            $issue?->journal?->title ?? '-',
            $edition,
            $submission->submission_id ?? $submission->ojs_submission_id ?? '-',
            $submission->fullTitle ?? '-',
            $authors ?: '-',
            $invoice?->invoice ?? '-',
            $invoice?->payment_amount ?? 0,
            $invoice?->is_paid ? 'Lunas' : 'Belum Lunas',
            $invoice?->confirmed_at?->format('d/m/Y H:i') ?? ($invoice?->midtrans_paid_at?->format('d/m/Y H:i') ?? '-'),
            $invoice?->midtrans_payment_method ?? ($invoice?->confirmed_by ? 'Manual' : '-'),
            $submission->created_at?->format('d/m/Y H:i') ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 25,
            'C' => 22,
            'D' => 15,
            'E' => 40,
            'F' => 30,
            'G' => 30,
            'H' => 18,
            'I' => 18,
            'J' => 18,
            'K' => 20,
            'L' => 18,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE2E8F0'],
                ],
            ],
        ];
    }
}
