@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">

        {{-- Header Card --}}
        <div class="card card-flush mb-6">
            <div class="card-header py-5">
                <div class="card-title">
                    <h3 class="fw-bold m-0">
                        <i class="ki-duotone ki-document fs-2 me-2 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Detail Invoice
                    </h3>
                </div>
                <div class="card-toolbar gap-3">
                    @if($invoice->invoice_file)
                        <a href="{{ asset('storage/' . $invoice->invoice_file) }}" target="_blank" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-file-down fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Unduh PDF
                        </a>
                    @endif
                    <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-sm btn-light">
                        <i class="ki-duotone ki-arrow-left fs-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Status & Summary --}}
        <div class="row g-5 mb-6">
            {{-- Invoice Info --}}
            <div class="col-md-4">
                <div class="card card-flush h-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-document fs-2x text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-gray-800 mb-0">{{ $invoice->invoice ?? '-' }}</h5>
                                <span class="text-muted fs-7">Sequence: {{ $invoice->invoice_number ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="mb-3">
                            <span class="text-muted fs-7 d-block">Status</span>
                            @if($invoice->is_paid)
                                <span class="badge badge-success fs-7 mt-1">
                                    <i class="ki-duotone ki-check-circle fs-6 me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Lunas
                                </span>
                            @else
                                <span class="badge badge-warning fs-7 mt-1">
                                    <i class="ki-duotone ki-time fs-6 me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Belum Lunas
                                </span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <span class="text-muted fs-7 d-block">Tipe</span>
                            <span class="fw-bold text-gray-800">{{ $invoice->is_custom ? 'Custom Invoice' : 'Invoice Otomatis' }}</span>
                        </div>
                        <div>
                            <span class="text-muted fs-7 d-block">Tanggal Dibuat</span>
                            <span class="fw-bold text-gray-800">{{ $invoice->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="col-md-4">
                <div class="card card-flush h-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-wallet fs-2x text-success">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                            <div>
                                <span class="text-muted fs-7">Total Tagihan</span>
                                <h4 class="fw-bolder text-gray-800 mb-0">Rp {{ number_format($invoice->payment_amount ?? 0, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        <div class="mb-3">
                            <span class="text-muted fs-7 d-block">Persentase</span>
                            <span class="fw-bold text-gray-800">{{ $invoice->payment_percent ?? 0 }}%</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted fs-7 d-block">Jatuh Tempo</span>
                            @if($invoice->payment_due_date)
                                @php
                                    $dueDate = \Carbon\Carbon::parse($invoice->payment_due_date);
                                    $isOverdue = !$invoice->is_paid && $dueDate->isPast();
                                @endphp
                                <span class="fw-bold {{ $isOverdue ? 'text-danger' : 'text-gray-800' }}">
                                    {{ $dueDate->translatedFormat('d M Y') }}
                                    @if($isOverdue)
                                        <span class="badge badge-light-danger fs-8 ms-1">Jatuh Tempo</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                        @if($invoice->midtrans_paid_at)
                        <div>
                            <span class="text-muted fs-7 d-block">Dibayar Pada</span>
                            <span class="fw-bold text-success">{{ \Carbon\Carbon::parse($invoice->midtrans_paid_at)->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recipient Info --}}
            <div class="col-md-4">
                <div class="card card-flush h-100">
                    <div class="card-body p-6">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-45px me-4">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-profile-user fs-2x text-info">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                            <div>
                                <span class="text-muted fs-7">Penerima</span>
                                <h5 class="fw-bold text-gray-800 mb-0">{{ $invoice->kepada ?? '-' }}</h5>
                            </div>
                        </div>
                        <div class="separator separator-dashed mb-4"></div>
                        @if($invoice->kepada_detail)
                        <div class="mb-3">
                            <span class="text-muted fs-7 d-block">Detail / Afiliasi</span>
                            <span class="fw-bold text-gray-800">{{ $invoice->kepada_detail }}</span>
                        </div>
                        @endif
                        @if($invoice->keterangan)
                        <div class="mb-3">
                            <span class="text-muted fs-7 d-block">Keterangan</span>
                            <span class="text-gray-800">{{ $invoice->keterangan }}</span>
                        </div>
                        @endif
                        @if($invoice->created_by)
                        <div>
                            <span class="text-muted fs-7 d-block">Dibuat Oleh</span>
                            <span class="fw-bold text-gray-800">{{ \App\Models\User::find($invoice->created_by)?->name ?? '-' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Manual Confirmation --}}
        <div class="card card-flush mb-6">
            <div class="card-header py-5">
                <div class="card-title">
                    <h5 class="fw-bold m-0">
                        <i class="ki-duotone ki-check-square fs-4 me-2 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Konfirmasi Pembayaran
                    </h5>
                </div>
            </div>
            <div class="card-body pt-0">
                @if(!$invoice->is_paid)
                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mb-5">
                        <i class="ki-duotone ki-information-4 fs-2tx text-warning me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-semibold">
                                <h6 class="text-gray-900 fw-bold">Invoice Belum Lunas</h6>
                                <div class="fs-7 text-gray-700">Invoice ini belum dikonfirmasi pembayarannya. Anda dapat melakukan konfirmasi manual di bawah ini.</div>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('back.finance.invoice.confirm', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="action" value="confirm">
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bold">Catatan Konfirmasi</label>
                                <textarea name="note" class="form-control form-control-solid" rows="3" placeholder="Catatan pembayaran (misal: transfer via BCA, bukti diterima, dll)"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bold">Bukti Pembayaran</label>
                                <input type="file" name="confirmation_file" class="form-control form-control-solid" accept=".jpg,.jpeg,.png,.pdf" />
                                <small class="text-muted">Format: JPG, PNG, PDF (maks. 10MB)</small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin mengkonfirmasi pembayaran invoice ini?')">
                            <i class="ki-duotone ki-check-circle fs-4 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Konfirmasi Lunas
                        </button>
                    </form>
                @else
                    <div class="notice d-flex bg-light-success rounded border-success border border-dashed p-6 mb-5">
                        <i class="ki-duotone ki-check-circle fs-2tx text-success me-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                            <div class="mb-3 mb-md-0 fw-semibold">
                                <h6 class="text-gray-900 fw-bold">Invoice Sudah Lunas</h6>
                                <div class="fs-7 text-gray-700">
                                    Pembayaran dikonfirmasi
                                    @if($invoice->confirmed_at)
                                        pada <strong>{{ \Carbon\Carbon::parse($invoice->confirmed_at)->translatedFormat('d M Y, H:i') }}</strong>
                                    @elseif($invoice->midtrans_paid_at)
                                        pada <strong>{{ \Carbon\Carbon::parse($invoice->midtrans_paid_at)->translatedFormat('d M Y, H:i') }}</strong>
                                    @endif
                                    @if($invoice->midtrans_payment_method)
                                        via <strong>{{ $invoice->midtrans_payment_method }}</strong>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        @if($invoice->confirmed_by)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-35px me-3">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-user fs-4 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-muted fs-8 d-block">Dikonfirmasi Oleh</span>
                                    <span class="fw-bold text-gray-800">{{ \App\Models\User::find($invoice->confirmed_by)?->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($invoice->confirmation_note)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-35px me-3">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-message-text fs-4 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-muted fs-8 d-block">Catatan</span>
                                    <span class="text-gray-800">{{ $invoice->confirmation_note }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($invoice->confirmation_file)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="symbol symbol-35px me-3">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-file-added fs-4 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-muted fs-8 d-block">Bukti Pembayaran</span>
                                    <a href="{{ asset('storage/' . $invoice->confirmation_file) }}" target="_blank" class="fw-bold text-primary">
                                        <i class="ki-duotone ki-eye fs-6 me-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        Lihat File
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('back.finance.invoice.confirm', $invoice->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" value="cancel">
                        <button type="submit" class="btn btn-light-danger btn-sm" onclick="return confirm('Yakin ingin membatalkan konfirmasi pembayaran? Status akan kembali ke Belum Lunas dan file bukti akan dihapus.')">
                            <i class="ki-duotone ki-cross-circle fs-5 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Batalkan Konfirmasi
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Items --}}
        @if($invoice->items && count($invoice->items) > 0)
            <div class="card card-flush mb-6">
                <div class="card-header py-5">
                    <div class="card-title">
                        <h5 class="fw-bold m-0">
                            <i class="ki-duotone ki-basket fs-4 me-2 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Item Invoice
                        </h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-4 gs-6 mb-0">
                            <thead>
                                <tr class="fw-bold text-gray-600 bg-light">
                                    <th class="ps-6" style="width: 50px;">No</th>
                                    <th>ID</th>
                                    <th>Nama Item</th>
                                    <th class="text-center" style="width: 80px;">Qty</th>
                                    <th>Detail</th>
                                    <th class="text-end pe-6" style="width: 150px;">Amount</th>
                                    <th class="text-end pe-6" style="width: 150px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $item)
                                    <tr>
                                        <td class="ps-6 text-muted">{{ $index + 1 }}</td>
                                        <td><span class="badge badge-light-primary">{{ $item['id'] ?? '-' }}</span></td>
                                        <td class="fw-bold text-gray-800">{{ $item['name'] ?? '-' }}</td>
                                        <td class="text-center fw-bold">{{ $item['qty'] ?? 1 }}</td>
                                        <td class="text-muted fs-7">{{ $item['detail'] ?? '-' }}</td>
                                        <td class="text-end pe-6">Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end pe-6 fw-bold">Rp {{ number_format(($item['qty'] ?? 1) * ($item['amount'] ?? 0), 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="6" class="text-end fw-bolder fs-5 pe-3">Total</td>
                                    <td class="text-end pe-6 fw-bolder fs-5 text-primary">Rp {{ number_format($invoice->payment_amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Midtrans Response --}}
        @if($invoice->midtrans_response)
            @php
                $mid = is_string($invoice->midtrans_response) ? json_decode($invoice->midtrans_response, true) : ($invoice->midtrans_response ?? []);
            @endphp
            <div class="card card-flush mb-6">
                <div class="card-header py-5">
                    <div class="card-title">
                        <h5 class="fw-bold m-0">
                            <i class="ki-duotone ki-credit-cart fs-4 me-2 text-primary">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Detail Pembayaran
                        </h5>
                    </div>
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#rawResponse">
                            <i class="ki-duotone ki-code fs-5 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                            Raw Response
                        </button>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Transaction ID</span>
                                <span class="fw-bold text-gray-800">{{ $invoice->midtrans_transaction_id ?? ($mid['transaction_id'] ?? '-') }}</span>
                            </div>
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Status Transaksi</span>
                                <span class="fw-bold text-gray-800">{{ $mid['transaction_status'] ?? ($mid['status_code'] ?? ($mid['type'] ?? '-')) }}</span>
                            </div>
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Metode Pembayaran</span>
                                <span class="fw-bold text-gray-800">{{ $invoice->midtrans_payment_method ?? ($mid['payment_type'] ?? '-') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Tanggal Pembayaran</span>
                                <span class="fw-bold text-gray-800">{{ $invoice->midtrans_paid_at ? \Carbon\Carbon::parse($invoice->midtrans_paid_at)->translatedFormat('d M Y H:i') : ($mid['settlement_time'] ?? ($mid['confirmed_at'] ?? '-')) }}</span>
                            </div>
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Jumlah Dibayar</span>
                                <span class="fw-bold text-gray-800">Rp {{ number_format($invoice->midtrans_gross_amount_paid ?? ($mid['gross_amount'] ?? 0), 0, ',', '.') }}</span>
                            </div>
                            @if(isset($mid['confirmed_by']))
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Dikonfirmasi Oleh</span>
                                <span class="fw-bold text-gray-800">{{ $mid['confirmed_by'] }}</span>
                            </div>
                            @endif
                            @if(isset($mid['note']) && $mid['note'])
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Catatan</span>
                                <span class="text-gray-800">{{ $mid['note'] }}</span>
                            </div>
                            @endif
                            @if(isset($mid['fraud_status']))
                            <div class="mb-4">
                                <span class="text-muted fs-7 d-block">Fraud Status</span>
                                <span class="fw-bold text-gray-800">{{ $mid['fraud_status'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if(isset($mid['va_numbers']) || isset($mid['bill_key']) || isset($mid['payment_code']))
                        <div class="separator separator-dashed my-4"></div>
                        <div class="mb-4">
                            <span class="text-muted fs-7 d-block mb-2">Rincian Channel Pembayaran</span>
                            @if(isset($mid['va_numbers']) && is_array($mid['va_numbers']))
                                @foreach($mid['va_numbers'] as $va)
                                    <span class="badge badge-light-info me-2 mb-1">{{ $va['bank'] ?? '-' }}: {{ $va['va_number'] ?? '-' }}</span>
                                @endforeach
                            @endif
                            @if(isset($mid['bill_key']))
                                <span class="badge badge-light-info me-2 mb-1">Bill Key: {{ $mid['bill_key'] }}</span>
                            @endif
                            @if(isset($mid['payment_code']))
                                <span class="badge badge-light-info me-2 mb-1">Payment Code: {{ $mid['payment_code'] }}</span>
                            @endif
                        </div>
                    @endif

                    <div class="collapse mt-4" id="rawResponse">
                        <div class="separator separator-dashed mb-4"></div>
                        <label class="form-label fs-7 fw-bold text-muted">Raw Response</label>
                        <pre class="bg-gray-100 rounded p-4" style="max-height: 300px; overflow: auto; font-size: 12px;">{{ json_encode($mid, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="card card-flush">
            <div class="card-body py-5">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-light-danger" onclick="deleteInvoice({{ $invoice->id }})">
                        <i class="ki-duotone ki-trash fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        Hapus Invoice
                    </button>
                    <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-light">
                        <i class="ki-duotone ki-arrow-left fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function deleteInvoice(id) {
            Swal.fire({
                title: 'Hapus Invoice?',
                text: 'Invoice ini akan dihapus secara permanen dan tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f1416c',
                cancelButtonColor: '#7e8299',
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
