@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="card card-flush">
            <div class="card-header py-5 gap-2 gap-md-5">
                <div class="card-title">
                    Detail Invoice
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="btn-group">
                        <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-light">
                            <i class="ki-duotone ki-arrow-left fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="card bg-light-primary border-0">
                            <div class="card-body p-5">
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-bold text-gray-600">Nama Invoice</label>
                                    <p class="fs-5 fw-bold text-gray-800">{{ $invoice->invoice ?? '-' }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-bold text-gray-600">Nomor Invoice</label>
                                    <p class="fs-5 fw-bold text-gray-800">{{ $invoice->invoice_number ?? '-' }}</p>
                                    <small class="text-muted">Tahun: {{ $invoice->created_at?->year ?? '-' }}</small>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-bold text-gray-600">Status Pembayaran</label>
                                    <p class="fs-5">
                                        @if($invoice->is_paid)
                                            <span class="badge badge-light-success">Lunas</span>
                                        @else
                                            <span class="badge badge-light-warning">Belum Lunas</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light-success border-0">
                            <div class="card-body p-5">
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-bold text-gray-600">Jumlah Tagihan</label>
                                    <p class="fs-4 fw-bolder text-gray-800">Rp {{ number_format($invoice->payment_amount ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-bold text-gray-600">Persentase Pembayaran</label>
                                    <p class="fs-5 fw-bold text-gray-800">{{ $invoice->payment_percent ?? 0 }}%</p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fs-7 fw-bold text-gray-600">Tanggal Jatuh Tempo</label>
                                    <p class="fs-5 fw-bold text-gray-800">
                                        @if($invoice->payment_due_date)
                                            {{ \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($invoice->items && count($invoice->items) > 0)
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Item Invoice</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover gy-4 gs-4">
                                            <thead>
                                                <tr class="fw-bold text-gray-800 border-bottom border-gray-200">
                                                    <th>ID Item</th>
                                                    <th>Nama</th>
                                                    <th class="text-end">Qty</th>
                                                    <th>Detail</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoice->items as $item)
                                                    <tr>
                                                        <td><span class="fw-bold">{{ $item['id'] ?? '-' }}</span></td>
                                                        <td><span class="text-gray-800">{{ $item['name'] ?? '-' }}</span></td>
                                                        <td class="text-end"><span class="fw-bold">{{ $item['qty'] ?? 1 }}</span></td>
                                                        <td><span class="text-muted fs-7">{{ $item['detail'] ?? '-' }}</span></td>
                                                        <td class="text-end"><span class="fw-bold">{{ isset($item['amount']) ? 'Rp ' . number_format($item['amount'], 0, ',', '.') : '-' }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Tambahan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label fs-7 fw-bold">Tanggal Dibuat</label>
                                        <p class="text-gray-800">{{ $invoice->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-7 fw-bold">Terakhir Diupdate</label>
                                        <p class="text-gray-800">{{ $invoice->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                                    </div>
                                </div>
                                @if($invoice->invoice_file)
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <label class="form-label fs-7 fw-bold">File Invoice</label>
                                            <p>
                                                <a href="{{ asset('storage/' . $invoice->invoice_file) }}" target="_blank" class="btn btn-light-primary">
                                                    <i class="ki-duotone ki-document fs-5"><span class="path1"></span><span class="path2"></span></i>
                                                    Unduh File
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                @if($invoice->midtrans_response)
                                    @php
                                        $mid = is_string($invoice->midtrans_response) ? json_decode($invoice->midtrans_response, true) : ($invoice->midtrans_response ?? []);
                                    @endphp
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label class="form-label fs-7 fw-bold">Transaction ID</label>
                                            <p class="text-gray-800">{{ $invoice->midtrans_transaction_id ?? ($mid['transaction_id'] ?? '-') }}</p>

                                            <label class="form-label fs-7 fw-bold mt-3">Status Transaksi</label>
                                            <p class="text-gray-800">{{ $mid['transaction_status'] ?? ($mid['status_code'] ?? '-') }}</p>

                                            <label class="form-label fs-7 fw-bold mt-3">Metode Pembayaran (Midtrans)</label>
                                            <p class="text-gray-800">{{ $invoice->midtrans_payment_method ?? ($mid['payment_type'] ?? '-') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fs-7 fw-bold">Tanggal Pembayaran</label>
                                            <p class="text-gray-800">{{ $invoice->midtrans_paid_at?->translatedFormat('d M Y H:i') ?? ($mid['settlement_time'] ?? ($mid['transaction_time'] ?? '-')) }}</p>

                                            <label class="form-label fs-7 fw-bold mt-3">Jumlah Yang Dibayar</label>
                                            <p class="text-gray-800">Rp {{ number_format($invoice->midtrans_gross_amount_paid ?? ($mid['gross_amount'] ?? 0), 0, ',', '.') }}</p>

                                            <label class="form-label fs-7 fw-bold mt-3">Fraud / Status Tambahan</label>
                                            <p class="text-gray-800">{{ $mid['fraud_status'] ?? ($mid['status_message'] ?? '-') }}</p>
                                        </div>
                                    </div>

                                    @if(isset($mid['va_numbers']) || isset($mid['bill_key']) || isset($mid['payment_code']) || isset($mid['actions']))
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label class="form-label fs-7 fw-bold">Rincian Channel Pembayaran</label>
                                                <div class="card p-3">
                                                    @if(isset($mid['va_numbers']) && is_array($mid['va_numbers']))
                                                        @foreach($mid['va_numbers'] as $va)
                                                            <p class="mb-1"><strong>VA:</strong> {{ $va['bank'] ?? '-' }} - {{ $va['va_number'] ?? '-' }}</p>
                                                        @endforeach
                                                    @endif
                                                    @if(isset($mid['bill_key']))
                                                        <p class="mb-1"><strong>Bill Key:</strong> {{ $mid['bill_key'] }}</p>
                                                    @endif
                                                    @if(isset($mid['payment_code']))
                                                        <p class="mb-1"><strong>Payment Code:</strong> {{ $mid['payment_code'] }}</p>
                                                    @endif
                                                    @if(isset($mid['actions']) && is_array($mid['actions']))
                                                        @foreach($mid['actions'] as $act)
                                                            <p class="mb-1"><strong>Action:</strong> <a href="{{ $act['url'] ?? '#' }}" target="_blank">{{ $act['name'] ?? 'Open' }}</a></p>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label class="form-label fs-7 fw-bold">Raw Midtrans Response</label>
                                            <pre class="bg-light p-3" style="max-height:300px;overflow:auto;">{{ json_encode($mid, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-danger" onclick="deleteInvoice({{ $invoice->id }})">
                                <i class="ki-duotone ki-trash fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Hapus Invoice
                            </button>
                            <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-light">
                                <i class="ki-duotone ki-arrow-left fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function deleteInvoice(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Invoice ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('back.finance.invoice.destroy', ['id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);

                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    let tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = "{{ csrf_token() }}";

                    form.appendChild(methodInput);
                    form.appendChild(tokenInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
