@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Torkata Research">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('payment.index') }}">
    <link rel="canonical" href="{{ route('payment.index') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')

    <section id="payment-search-1" class="wide-60 payment-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="section-title text-center mb-50">
                        <h2 class="h2-xs">Cek Invoice Pembayaran</h2>
                        <p class="p-xl grey-color">
                            Masukkan nomor invoice untuk menemukan tagihan pembayaran.
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
                                    <label for="invoice_number" class="form-label">Nomor Invoice</label>
                                    <input type="text" id="invoice_number" name="invoice_number"
                                        class="form-control @error('invoice_number') is-invalid @enderror"
                                        placeholder="Contoh: 000X/INV/TR/II/2024"
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
                                    Tidak ada invoice yang cocok dengan nomor invoice yang Anda masukkan.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        @forelse ($payment_invoices as $invoice)
                            <div class="project-2-description mb-4 p-4 bg-light rounded-3 shadow-sm">
                                <div class="project-title mb-2">
                                    <h4 class="h4-sm mb-1">{{ format_nomor($invoice->invoice_number, 'INV', 'TR', $invoice->created_at->month, $invoice->created_at->year) }}</h4>
                                    <p class="mb-0 grey-color">{{ $invoice->submission?->fullTitle ?? 'Judul submission belum tersedia' }}</p>
                                </div>

                                <div class="project-details mt-3">
                                    <div class="project-info">
                                        <p class="">Submission ID: <span>{{ $invoice->submission?->ojs_submission_id ?? '-' }}</span></p>
                                        <p class="">Journal: <span>{{ $invoice->submission?->issue?->journal?->title ?? '-' }}</span></p>
                                        <p class="">Persentase Pembayaran: <span>{{ $invoice->payment_percent ?? '-' }}%</span></p>
                                        <p class="">Jumlah Tagihan: <span>@money($invoice->payment_amount ?? 0)</span></p>
                                        <p class="mb-0">Batas Waktu: <span>{{ $invoice->payment_due_date ? \Carbon\Carbon::parse($invoice->payment_due_date)->translatedFormat('d F Y') : '-' }}</span></p>
                                        <p class="mb-0">Status: @if ($invoice->is_paid) <span class="text-success">Lunas</span> @else <span class="text-danger">Belum Lunas</span> @endif</p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3">


                                    <div>
                                        <p class="small grey-color mb-2">Periksa rincian invoice di halaman berikut, lalu lanjutkan untuk melakukan pembayaran.</p>
                                        @if ($invoice->submission && $invoice->submission->issue && $invoice->submission->issue->journal)
                                            <a href="{{ route('payment.show', ['invoice_number' => str_replace('/', '-', $invoice->invoice)]) }}" class="btn btn-theme btn-sm">
                                                Detail
                                            </a>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Tidak tersedia</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row justify-content-center">
                                <div class="col-lg-10 col-xl-8">
                                    <div class="bg-lightgrey p-4 rounded-3 text-center">
                                        <h5 class="h5-xs mb-3">Tagihan tidak ditemukan</h5>
                                        <p class="p-md grey-color mb-0">
                                            Periksa kembali submission ID, judul artikel, atau nama penulis yang Anda
                                            masukkan. Jika masih belum berhasil, silakan hubungi admin jurnal.
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
                                    <p class="grey-color mb-0">Masukkan Nomor Invoice yang ingin Anda bayar.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="bg-lightgrey p-4 rounded-3 h-100">
                                    <h5 class="h5-xs mb-3">2. Periksa Detail</h5>
                                    <p class="grey-color mb-0">Pastikan submission, jurnal, jumlah tagihan, dan status
                                        tagihan sudah sesuai.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="bg-lightgrey p-4 rounded-3 h-100">
                                    <h5 class="h5-xs mb-3">3. Lanjutkan Proses</h5>
                                    <p class="grey-color mb-0">Setelah tagihan cocok, ikuti petunjuk pembayaran yang
                                        tersedia dari pihak jurnal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
