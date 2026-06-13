@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="row">
            {{-- Order Detail --}}
            <div class="col-lg-8">
                {{-- Order Info Card --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title">
                            <h3>Detail Pesanan</h3>
                        </div>
                        <div class="card-toolbar">
                            @if($order->is_paid)
                                <span class="badge badge-light-success fs-6">Lunas</span>
                            @else
                                <span class="badge badge-light-warning fs-6">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-7">
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Invoice</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $order->invoice }}</div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Tanggal</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            @if($order->midtrans_payment_method)
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Metode Pembayaran</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ ucfirst($order->midtrans_payment_method) }}</div>
                            </div>
                            @endif
                            @if($order->keterangan)
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Keterangan</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $order->keterangan }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="separator separator-dashed my-5"></div>

                        {{-- Buyer Info --}}
                        <h5 class="text-gray-700 fw-bold mb-4">Informasi Pembeli</h5>
                        <div class="row mb-7">
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Nama</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $order->user->name ?? $order->kepada ?? '-' }}</div>
                            </div>
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Email</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $order->user->email ?? $order->kepada_detail ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="separator separator-dashed my-5"></div>

                        {{-- Order Items --}}
                        <h5 class="text-gray-700 fw-bold mb-4">Item Pesanan</h5>
                        <table class="table align-middle table-row-dashed fs-6 gy-4">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-200px">Nama Produk</th>
                                    <th class="text-end min-w-100px">Harga</th>
                                    <th class="text-end min-w-50px">Qty</th>
                                    <th class="text-end min-w-100px">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @foreach ($order->items ?? [] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold">{{ $item['name'] ?? '-' }}</span>
                                            </div>
                                            <span class="text-muted fs-7">{{ $item['detail'] ?? '' }}</span>
                                        </td>
                                        <td class="text-end">Rp{{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $item['qty'] ?? 1 }}</td>
                                        <td class="text-end fw-bold">Rp{{ number_format(($item['amount'] ?? 0) * ($item['qty'] ?? 1), 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold fs-5">Total</td>
                                    <td class="text-end fw-bolder fs-4 text-primary">Rp{{ number_format($order->payment_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Midtrans Response --}}
                @if(isset($order->midtrans_response) && $order->midtrans_response)
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Detail Pembayaran (Midtrans)</h3></div>
                    </div>
                    <div class="card-body">
                        @php
                            $midtrans = is_string($order->midtrans_response) ? json_decode($order->midtrans_response, true) : $order->midtrans_response;
                        @endphp
                        @if($midtrans && is_array($midtrans))
                            <table class="table table-row-dashed fs-6 gy-3">
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($midtrans as $key => $value)
                                        @if(!is_array($value))
                                        <tr>
                                            <td class="text-gray-500 fw-bold w-200px">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                            <td class="text-gray-800">{{ $value }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-muted">Data Midtrans tidak tersedia.</div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-4">
                {{-- Update Status --}}
                @if(!$order->is_paid)
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Update Status</h3></div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('back.product.order.status', $order->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-5">
                                <label class="form-label required">Status</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror"
                                    data-control="select2" data-hide-search="true" required>
                                    <option value="unpaid" {{ !$order->is_paid ? 'selected' : '' }}>Belum Lunas</option>
                                    <option value="paid" {{ $order->is_paid ? 'selected' : '' }}>Lunas</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-check fs-2"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Order Summary --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Ringkasan</h3></div>
                    </div>
                    <div class="card-body py-5">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-purchase fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ $order->invoice }}</span>
                                <span class="text-muted fs-7">Invoice</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-wallet fs-5 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">Rp{{ number_format($order->payment_amount, 0, ',', '.') }}</span>
                                <span class="text-muted fs-7">Total Pembayaran</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-calendar fs-5 text-info"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                <span class="text-muted fs-7">Tanggal Order</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-profile-user fs-5 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ $order->user->name ?? $order->kepada ?? '-' }}</span>
                                <span class="text-muted fs-7">Pembeli</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-body py-5">
                        <a href="{{ route('back.finance.invoice.show', $order->id) }}" class="btn btn-light-primary w-100 mb-3">
                            <i class="ki-duotone ki-document fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Lihat Invoice
                        </a>
                        <a href="{{ route('back.product.order.index') }}" class="btn btn-light w-100">
                            <i class="ki-duotone ki-arrow-left fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
