<?php

namespace App\Exports;

use App\Models\Finance;
use App\Models\PaymentInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashflowExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $date_start;
    protected $date_end;
    protected $type;
    protected $counter = 0;

    public function __construct($date_start = null, $date_end = null, $type = 'all')
    {
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->type = $type;
    }

    public function title(): string
    {
        return 'Laporan Cashflow';
    }

    public function collection()
    {
        // Data finance manual
        $finance = Finance::query()
            ->when($this->date_start, fn($q) => $q->where('date', '>=', $this->date_start))
            ->when($this->date_end, fn($q) => $q->where('date', '<=', $this->date_end))
            ->when($this->type && $this->type !== 'all', fn($q) => $q->where('type', $this->type))
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'date' => $item->date?->format('d/m/Y H:i'),
                    'name' => $item->name,
                    'description' => $item->description,
                    'type' => $item->type,
                    'amount' => $item->amount,
                    'payment_method' => $item->payment_method,
                    'payment_reference' => $item->payment_reference,
                    'payment_note' => $item->payment_note,
                    'source' => 'Manual',
                ];
            });

        // Data invoice lunas (hanya jika tipe all atau income)
        $invoices = collect();
        if (!$this->type || $this->type === 'all' || $this->type === 'income') {
            $invoices = PaymentInvoice::where('is_paid', true)
                ->when($this->date_start, fn($q) => $q->whereDate('confirmed_at', '>=', $this->date_start))
                ->when($this->date_end, fn($q) => $q->whereDate('confirmed_at', '<=', $this->date_end))
                ->orderBy('confirmed_at', 'asc')
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'date' => $item->confirmed_at?->format('d/m/Y H:i') ?? $item->created_at?->format('d/m/Y H:i'),
                        'name' => 'Pembayaran Invoice ' . ($item->invoice ?? '-'),
                        'description' => 'Invoice ' . ($item->invoice ?? '-') . ' — ' . ($item->kepada ?? '-'),
                        'type' => 'income',
                        'amount' => $item->payment_amount ?? 0,
                        'payment_method' => $item->midtrans_payment_method ?? 'Manual',
                        'payment_reference' => $item->midtrans_transaction_id ?? '-',
                        'payment_note' => $item->confirmation_note ?? '-',
                        'source' => 'Invoice',
                    ];
                });
        }

        return $finance->merge($invoices)->sortBy('date')->values();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Transaksi',
            'Deskripsi',
            'Tipe',
            'Debit (Masuk)',
            'Kredit (Keluar)',
            'Metode Pembayaran',
            'No Referensi',
            'Catatan',
            'Sumber',
        ];
    }

    public function map($row): array
    {
        $this->counter++;

        return [
            $this->counter,
            $row->date,
            $row->name,
            $row->description,
            $row->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
            $row->type === 'income' ? $row->amount : 0,
            $row->type === 'expense' ? $row->amount : 0,
            $row->payment_method,
            $row->payment_reference,
            $row->payment_note,
            $row->source,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 18,
            'C' => 35,
            'D' => 40,
            'E' => 14,
            'F' => 18,
            'G' => 18,
            'H' => 20,
            'I' => 20,
            'J' => 25,
            'K' => 12,
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
