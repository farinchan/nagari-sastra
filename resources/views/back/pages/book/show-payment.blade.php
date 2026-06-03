@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        @include('back.pages.book.show-header')

        @php
            $invoice = $book->invoices->first();
            $items = $invoice ? ($invoice->items ?? []) : [];
        @endphp

        <form action="{{ route('back.book.invoice.store', $book->id) }}" method="POST">
            @csrf
            <div class="row">
                {{-- Left: Invoice Header --}}
                <div class="col-lg-4">
                    <div class="card mb-5 mb-lg-10">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>
                                    @if($invoice)
                                        <i class="ki-duotone ki-document fs-2 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                        {{ $invoice->invoice }}
                                    @else
                                        Buat Invoice
                                    @endif
                                </h3>
                            </div>
                            @if($invoice)
                                <div class="card-toolbar">
                                    @if($invoice->is_paid)
                                        <span class="badge badge-light-success fs-7">Lunas</span>
                                    @else
                                        <span class="badge badge-light-warning fs-7">Belum Lunas</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="mb-5">
                                <label class="form-label required">Kepada</label>
                                <input type="text" name="kepada" class="form-control" required
                                    placeholder="Nama penerima invoice"
                                    value="{{ old('kepada', $invoice->kepada ?? '') }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Detail Penerima</label>
                                <input type="text" name="kepada_detail" class="form-control"
                                    placeholder="Alamat / Instansi"
                                    value="{{ old('kepada_detail', $invoice->kepada_detail ?? '') }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $invoice->keterangan ?? '') }}</textarea>
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Jatuh Tempo</label>
                                <input type="date" name="payment_due_date" class="form-control"
                                    value="{{ old('payment_due_date', $invoice && $invoice->payment_due_date ? $invoice->payment_due_date->format('Y-m-d') : '') }}" />
                            </div>

                            {{-- Total --}}
                            <div class="border border-dashed border-gray-300 rounded p-4 mb-5">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5 text-gray-700">Total</span>
                                    <span class="fw-bolder fs-3 text-primary" id="invoiceTotal">
                                        Rp {{ $invoice ? number_format($invoice->payment_amount ?? 0, 0, ',', '.') : '0' }}
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="ki-duotone ki-check fs-2"></i>
                                {{ $invoice ? 'Simpan Perubahan' : 'Buat Invoice' }}
                            </button>

                            @if($invoice)
                                <div class="d-flex gap-2">
                                    <a href="{{ route('back.book.invoice.download', $invoice->id) }}"
                                        class="btn btn-light-primary flex-grow-1">
                                        <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> Download PDF
                                    </a>
                                    <a href="{{ route('back.finance.invoice.show', $invoice->id) }}"
                                        class="btn btn-light-info flex-grow-1">
                                        <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span></i> Detail
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Items --}}
                <div class="col-lg-8">
                    <div class="card mb-5 mb-lg-10">
                        <div class="card-header">
                            <div class="card-title"><h3>Item Invoice</h3></div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-primary" id="btnAddItem">
                                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Item
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-bordered gs-0 gy-4" id="itemsTable">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="ps-4 rounded-start" style="width: 30%">Nama Item</th>
                                            <th style="width: 25%">Detail</th>
                                            <th style="width: 10%" class="text-center">Qty</th>
                                            <th style="width: 20%">Harga Satuan</th>
                                            <th style="width: 10%" class="text-end">Subtotal</th>
                                            <th class="pe-4 rounded-end text-center" style="width: 5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        {{-- Items will be rendered by JS --}}
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td colspan="4" class="text-end fw-bolder fs-5 ps-4">Total</td>
                                            <td class="text-end fw-bolder fs-5 text-primary" id="footerTotal">Rp 0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            @if(count($items) === 0)
                                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mt-4" id="emptyNotice">
                                    <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <div class="fw-semibold">
                                            <div class="fs-6 text-gray-700">
                                                Belum ada item. Klik <strong>"Tambah Item"</strong> untuk menambahkan biaya penerbitan, HAKI, ongkir, dll.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    const existingItems = @json($items);

    let itemIndex = 0;

    function formatRupiah(num) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const amount = parseFloat(row.querySelector('.item-amount').value) || 0;
            const subtotal = qty * amount;
            row.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
            total += subtotal;
        });
        document.getElementById('invoiceTotal').textContent = formatRupiah(total);
        document.getElementById('footerTotal').textContent = formatRupiah(total);
    }

    function addItemRow(item = null) {
        const notice = document.getElementById('emptyNotice');
        if (notice) notice.style.display = 'none';

        const tbody = document.getElementById('itemsBody');
        const idx = itemIndex++;

        const row = document.createElement('tr');
        row.className = 'item-row';
        row.innerHTML = `
            <td class="ps-4">
                <input type="text" name="item_name[${idx}]" class="form-control form-control-sm" placeholder="cth: Biaya Penerbitan"
                    value="${item ? (item.name || '') : ''}" required />
            </td>
            <td>
                <input type="text" name="item_detail[${idx}]" class="form-control form-control-sm" placeholder="cth: ISBN 978..."
                    value="${item ? (item.detail || '') : ''}" />
            </td>
            <td>
                <input type="number" name="item_qty[${idx}]" class="form-control form-control-sm text-center item-qty" min="1" step="1"
                    value="${item ? (item.qty || 1) : 1}" required />
            </td>
            <td>
                <input type="number" name="item_amount[${idx}]" class="form-control form-control-sm item-amount" min="0" step="1"
                    value="${item ? (item.amount || 0) : ''}" placeholder="0" required />
            </td>
            <td class="text-end fw-bold item-subtotal">${item ? formatRupiah((item.qty || 1) * (item.amount || 0)) : 'Rp 0'}</td>
            <td class="pe-4 text-center">
                <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-remove-item">
                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                </button>
            </td>
        `;

        tbody.appendChild(row);

        // Event listeners
        row.querySelector('.item-qty').addEventListener('input', calculateTotal);
        row.querySelector('.item-amount').addEventListener('input', calculateTotal);
        row.querySelector('.btn-remove-item').addEventListener('click', function() {
            row.remove();
            calculateTotal();
        });

        calculateTotal();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        if (existingItems.length > 0) {
            existingItems.forEach(item => addItemRow(item));
        }

        document.getElementById('btnAddItem').addEventListener('click', () => addItemRow());
    });
</script>
@endsection
