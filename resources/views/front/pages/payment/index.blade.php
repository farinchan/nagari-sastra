@extends('front.app')

@section('content')

    <section id="payment-search-1" class="wide-60 payment-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="section-title text-center mb-50">
                        <h2 class="h2-xs">Cek Invoice Pembayaran</h2>
                        <p class="p-xl grey-color">
                            Masukkan nomor invoice atau nama penerima untuk menemukan tagihan pembayaran.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center" style="margin-bottom: 60px">
                <div class="col-lg-10 col-xl-8">
                    <div class="login-wrapper">
                        <div class="login-form-wrapper">
                            <form action="{{ route('payment.index') }}" method="GET" class="row g-3 align-items-end">
                                <div class="col-md-9">
                                    <label for="invoice_number" class="form-label">Nomor Invoice / Nama Penerima</label>
                                    <input type="text" id="invoice_number" name="invoice_number"
                                        class="form-control @error('invoice_number') is-invalid @enderror"
                                        placeholder="Contoh: 0001/INV/TR/V/2026 atau nama penerima"
                                        value="{{ request('invoice_number') ?? request('q') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-theme w-100">
                                        Cari Tagihan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if (filled($invoice_number))
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-8">
                        <div class="section-title text-center mb-40">
                            <h3 class="h3-sm">Hasil Pencarian</h3>
                            <p class="p-md grey-color">
                                @if ($payment_invoices->count())
                                    Ditemukan {{ $payment_invoices->count() }} invoice yang cocok dengan pencarian Anda.
                                @else
                                    Tidak ada invoice yang cocok dengan pencarian Anda.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        @forelse ($payment_invoices as $invoice)
                            <div class="project-2-description mb-4 p-4 bg-light rounded-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="project-title mb-2">
                                        <h4 class="h4-sm mb-1">{{ $invoice->invoice ?? '-' }}</h4>
                                        @if($invoice->kepada)
                                            <p class="mb-0"><strong>Kepada:</strong> {{ $invoice->kepada }}
                                                @if($invoice->kepada_detail)
                                                    <span class="grey-color">— {{ $invoice->kepada_detail }}</span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div>
                                        @if ($invoice->is_paid)
                                            <span style="display: inline-block; font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 20px; background-color: #198754; color: #fff; border: none;">Lunas</span>
                                        @else
                                            <span style="display: inline-block; font-size: 13px; font-weight: 600; padding: 6px 14px; border-radius: 20px; background-color: #dc3545; color: #fff; border: none;">Belum Lunas</span>
                                        @endif
                                    </div>
                                </div>

                                @if($invoice->submissions->first())
                                    <p class="mb-1 grey-color">{{ $invoice->submissions->first()?->fullTitle ?? '' }}</p>
                                    <p class="mb-0 small grey-color">
                                        Journal: {{ $invoice->submissions->first()?->issue?->journal?->title ?? '-' }}
                                    </p>
                                @endif

                                <hr style="margin: 12px 0;">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <p class="mb-1 small grey-color">Total Tagihan</p>
                                        <p class="mb-0"><strong style="font-size: 18px;">@money($invoice->payment_amount ?? 0)</strong></p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="mb-1 small grey-color">Batas Waktu</p>
                                        <p class="mb-0"><strong>{{ $invoice->payment_due_date ? \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y') : '-' }}</strong></p>
                                    </div>
                                    <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
                                        <a href="{{ route('payment.show', ['invoice_number' => str_replace('/', '-', $invoice->invoice)]) }}" class="btn btn-theme btn-sm">
                                            Lihat Detail & Bayar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row justify-content-center">
                                <div class="col-lg-10 col-xl-8">
                                    <div class="bg-lightgrey p-4 rounded-3 text-center">
                                        <h5 class="h5-xs mb-3">Tagihan tidak ditemukan</h5>
                                        <p class="p-md grey-color mb-0">
                                            Periksa kembali nomor invoice atau nama yang Anda masukkan.
                                            Jika masih belum berhasil, silakan hubungi admin.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="bg-lightgrey p-4 rounded-3 h-100">
                                    <h5 class="h5-xs mb-3">1. Cari Tagihan</h5>
                                    <p class="grey-color mb-0">Masukkan nomor invoice atau nama penerima yang ingin Anda cari.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="bg-lightgrey p-4 rounded-3 h-100">
                                    <h5 class="h5-xs mb-3">2. Periksa Detail</h5>
                                    <p class="grey-color mb-0">Pastikan jumlah tagihan, penerima, dan status tagihan sudah sesuai.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="bg-lightgrey p-4 rounded-3 h-100">
                                    <h5 class="h5-xs mb-3">3. Bayar</h5>
                                    <p class="grey-color mb-0">Lakukan pembayaran langsung melalui gateway pembayaran yang tersedia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
