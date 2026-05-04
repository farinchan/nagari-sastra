<?php

namespace App\Exports;

use App\Models\PaymentInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceManagementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $status;
    protected $is_custom;
    protected $date_start;
    protected $date_end;
    protected $search;

    public function __construct($status = null, $is_custom = null, $date_start = null, $date_end = null, $search = null)
    {
        $this->status = $status;
        $this->is_custom = $is_custom;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->search = $search;
    }

    public function collection()
    {
        return PaymentInvoice::query()
            ->when($this->status !== null && $this->status !== '', function ($query) {
                if ($this->status === 'paid') {
                    $query->where('is_paid', true);
                } elseif ($this->status === 'unpaid') {
                    $query->where('is_paid', false);
                }
            })
            ->when($this->date_start, function ($query) {
                $query->whereDate('created_at', '>=', $this->date_start);
            })
            ->when($this->date_end, function ($query) {
                $query->whereDate('created_at', '<=', $this->date_end);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
                      ->orWhere('invoice', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Invoice',
            'Invoice',
            'Item ID',
            'Item Nama',
            'Qty',
            'Item Detail',
            'Persentase',
            'Jumlah Tagihan',
            'Jatuh Tempo',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($invoice): array
    {
        static $no = 0;
        $no++;

        $items = $invoice->items ?? [];
        $firstItem = $items[0] ?? [];

        return [
            $no,
            $invoice->invoice_number,
            $invoice->invoice,
            $firstItem['id'] ?? '-',
            $firstItem['name'] ?? '-',
            $firstItem['qty'] ?? '-',
            $firstItem['detail'] ?? '-',
            $invoice->payment_percent . '%',
            $invoice->payment_amount,
            $invoice->payment_due_date ? \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y') : '-',
            $invoice->is_paid ? 'Lunas' : 'Belum Lunas',
            $invoice->created_at?->translatedFormat('d F Y H:i') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
