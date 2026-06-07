@extends('front.app')
@section('seo')
   
    <style>
        /* Override theme badge reset */
        .payment-detail-section .status-badge {
            display: inline-block;
            font-size: 13px !important;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            line-height: 1.4;
            border: none;
        }
        .payment-detail-section .status-badge.status-paid {
            background-color: #198754 !important;
            color: #fff !important;
        }
        .payment-detail-section .status-badge.status-unpaid {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        /* Override theme alert reset */
        .payment-detail-section .payment-alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            font-size: 15px;
        }
        .payment-detail-section .payment-alert.alert-paid {
            background-color: #d1e7dd;
            border: 1px solid #badbcc;
            color: #0f5132;
        }
        .payment-detail-section .payment-alert.alert-overdue {
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
        }
        .payment-detail-section .payment-alert svg {
            flex-shrink: 0;
            margin-right: 14px;
        }

        /* Info card */
        .payment-detail-section .info-card {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 24px;
            height: 100%;
        }
        .payment-detail-section .info-card h5 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #333;
        }

        /* Detail table */
        .payment-detail-section .detail-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .payment-detail-section .detail-table td {
            padding: 8px 0;
            vertical-align: top;
            font-size: 15px;
            border-bottom: 1px solid #eee;
        }
        .payment-detail-section .detail-table td.label-col {
            color: #888;
            width: 145px;
            padding-right: 12px;
        }
        .payment-detail-section .detail-table td.value-col {
            color: #333;
            font-weight: 600;
        }
        .payment-detail-section .detail-table tr:last-child td {
            border-bottom: none;
        }

        /* Item list */
        .payment-detail-section .item-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px dashed #ddd;
        }
        .payment-detail-section .item-row:last-of-type {
            border-bottom: none;
        }
        .payment-detail-section .item-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 2px;
        }
        .payment-detail-section .item-detail {
            font-size: 13px;
            color: #888;
        }
        .payment-detail-section .item-price {
            text-align: right;
            white-space: nowrap;
            margin-left: 14px;
        }
        .payment-detail-section .item-price .qty {
            font-size: 12px;
            color: #888;
        }
        .payment-detail-section .item-price .amount {
            font-weight: 700;
            font-size: 14px;
            color: #333;
        }

        /* Total row */
        .payment-detail-section .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            margin-top: 4px;
            border-top: 2px solid #ddd;
        }
        .payment-detail-section .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #333;
        }
        .payment-detail-section .total-amount {
            font-size: 20px;
            font-weight: 800;
            color: #198754;
        }

        /* Payment result box */
        .payment-detail-section .result-box {
            padding: 16px;
            border-radius: 10px;
            text-align: center;
        }
        .payment-detail-section .result-box.result-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .payment-detail-section .result-box.result-warning {
            background-color: #fff3cd;
            color: #664d03;
        }

        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 8px rgba(13, 110, 253, 0.4); }
            50% { box-shadow: 0 0 20px rgba(13, 110, 253, 0.7), 0 0 40px rgba(13, 110, 253, 0.3); }
        }

        .payment-detail-section .btn-pay {
            display: block;
            width: 100%;
            padding: 14px 20px;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(
                110deg,
                #0d6efd 0%,
                #0d6efd 30%,
                #5ba3ff 50%,
                #0d6efd 70%,
                #0d6efd 100%
            );
            background-size: 200% 100%;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            animation: shimmer 2.5s ease-in-out infinite, pulse-glow 3s ease-in-out infinite;
            transition: transform 0.2s, filter 0.2s;
            position: relative;
            overflow: hidden;
        }
        .payment-detail-section .btn-pay:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            animation: shimmer 1.5s ease-in-out infinite, pulse-glow 1.5s ease-in-out infinite;
        }
        .payment-detail-section .btn-pay:active {
            transform: translateY(0);
            filter: brightness(0.95);
        }

        .payment-detail-section .btn-download {
            display: block;
            width: 100%;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .payment-detail-section .btn-download:hover {
            border-color: #999;
            color: #000;
        }

        .payment-detail-section .btn-back {
            display: block;
            width: 100%;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #555;
            background-color: transparent;
            border: 2px solid #ddd;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }
        .payment-detail-section .btn-back:hover {
            border-color: #999;
            color: #000;
        }

        .payment-detail-section .keterangan-text {
            font-size: 14px;
            color: #555;
            margin: 0;
        }
    </style>
@endsection
@section('content')
    <section id="payment-show-1" class="payment-section payment-detail-section division">
        <div class="" style="max-width: 1900px; margin: 0 auto; padding: 40px 20px;">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="section-title text-center mb-50">
                        <h2 class="h2-xs">Pembayaran Invoice</h2>
                        <p class="p-xl grey-color">
                            Invoice <strong>{{ $payment_invoice->invoice }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">

                    {{-- Status Banner --}}
                    @if($payment_invoice->is_paid)
                        <div class="payment-alert alert-paid">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                            <div>
                                <strong>Invoice Sudah Lunas</strong> — Pembayaran telah dikonfirmasi.
                                @if($payment_invoice->midtrans_paid_at)
                                    Dibayar pada {{ \Carbon\Carbon::parse($payment_invoice->midtrans_paid_at)->translatedFormat('d F Y, H:i') }}.
                                @endif
                            </div>
                        </div>
                    @else
                        @php
                            $isOverdue = $payment_invoice->payment_due_date && \Carbon\Carbon::parse($payment_invoice->payment_due_date)->isPast();
                        @endphp
                        @if($isOverdue)
                            <div class="payment-alert alert-overdue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                                <div>
                                    <strong>Invoice Jatuh Tempo!</strong> — Batas waktu pembayaran telah lewat. Segera lakukan pembayaran.
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="row">
                        {{-- Detail Invoice --}}
                        <div class="col-md-7 mb-4">
                            <div class="info-card">
                                <h5>Detail Invoice</h5>

                                <table class="detail-table">
                                    <tr>
                                        <td class="label-col">Nomor Invoice</td>
                                        <td class="value-col">{{ $payment_invoice->invoice }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Kepada</td>
                                        <td class="value-col">
                                            {{ $payment_invoice->kepada ?? '-' }}
                                            @if($payment_invoice->kepada_detail)
                                                <br><span style="font-weight: 400; font-size: 13px; color: #888;">{{ $payment_invoice->kepada_detail }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($submission)
                                    <tr>
                                        <td class="label-col">Judul Artikel</td>
                                        <td class="value-col">{{ $submission->fullTitle ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Penulis</td>
                                        <td class="value-col">{{ collect($submission->authors ?? [])->pluck('name')->filter()->implode(', ') ?: '-' }}</td>
                                    </tr>
                                    @endif
                                    @if($journal)
                                    <tr>
                                        <td class="label-col">Jurnal</td>
                                        <td class="value-col">{{ $journal->title ?? '-' }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="label-col">Batas Waktu</td>
                                        <td class="value-col">{{ $payment_invoice->payment_due_date ? \Carbon\Carbon::parse($payment_invoice->payment_due_date)->translatedFormat('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-col">Status</td>
                                        <td>
                                            @if($payment_invoice->is_paid)
                                                <span class="status-badge status-paid">Lunas</span>
                                            @else
                                                <span class="status-badge status-unpaid">Belum Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                                @if($payment_invoice->keterangan)
                                    <hr style="margin: 16px 0;">
                                    <p style="font-size: 13px; color: #888; margin-bottom: 4px;">Keterangan:</p>
                                    <p class="keterangan-text">{{ $payment_invoice->keterangan }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Sidebar --}}
                        <div class="col-md-5 mb-4">
                            {{-- Items --}}
                            @if($payment_invoice->items && count($payment_invoice->items) > 0)
                                <div class="info-card" style="height: auto; margin-bottom: 16px;">
                                    <h5>Rincian Item</h5>
                                    @foreach($payment_invoice->items as $item)
                                        <div class="item-row">
                                            <div style="flex: 1;">
                                                <div class="item-name">{{ $item['name'] ?? '-' }}</div>
                                                @if(!empty($item['detail']))
                                                    <div class="item-detail">{{ $item['detail'] }}</div>
                                                @endif
                                            </div>
                                            <div class="item-price">
                                                <span class="qty">{{ $item['qty'] ?? 1 }}x</span>
                                                <span class="amount">@money($item['amount'] ?? 0)</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="total-row">
                                        <span class="total-label">Total</span>
                                        <span class="total-amount">@money($payment_invoice->payment_amount ?? 0)</span>
                                    </div>
                                </div>
                            @else
                                <div class="info-card" style="height: auto; margin-bottom: 16px;">
                                    <h5>Total Tagihan</h5>
                                    <div class="total-amount">@money($payment_invoice->payment_amount ?? 0)</div>
                                </div>
                            @endif

                            {{-- Download Invoice --}}
                            @if($payment_invoice->invoice_file)
                                <div class="info-card" style="height: auto; margin-bottom: 16px;">
                                    <h5>File Invoice</h5>
                                    <p style="font-size: 13px; color: #888; margin-bottom: 12px;">Unduh file invoice untuk arsip Anda.</p>
                                    <a href="{{ asset('storage/' . $payment_invoice->invoice_file) }}" target="_blank" class="btn-download">
                                        ⬇ Download Invoice
                                    </a>
                                </div>
                            @endif

                            {{-- Payment --}}
                            <div class="info-card" style="height: auto; margin-bottom: 16px;">
                                <h5>Pembayaran</h5>
                                @if ($payment_invoice->is_paid)
                                    <div class="result-box result-success">
                                        <strong>Invoice ini sudah lunas.</strong>
                                        <br>Terima kasih atas pembayaran Anda.
                                    </div>
                                @elseif($snap_token)
                                    <p style="font-size: 13px; color: #888; margin-bottom: 12px;">Klik tombol di bawah untuk memilih metode pembayaran dan menyelesaikan transaksi.</p>
                                    <button type="button" id="pay-button" class="btn-pay">
                                        Bayar Sekarang — @money($payment_invoice->payment_amount ?? 0)
                                    </button>
                                @else
                                    <div class="result-box result-warning">
                                        <strong>Pembayaran online tidak tersedia.</strong>
                                        <br>Silakan hubungi admin untuk informasi pembayaran.
                                    </div>
                                @endif
                            </div>

                            {{-- Back --}}
                            <a href="{{ route('payment.index') }}" class="btn-back">
                                ← Kembali ke Pencarian
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    @if (!$payment_invoice->is_paid && $snap_token)
        <script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const payButton = document.getElementById('pay-button');
                if (payButton) {
                    payButton.addEventListener('click', function () {
                        snap.pay(@json($snap_token), {
                            onSuccess: function () { window.location.reload(); },
                            onPending: function () { window.location.reload(); },
                            onError: function () { alert('Pembayaran gagal diproses. Silakan coba lagi.'); },
                            onClose: function () { console.log('Popup pembayaran ditutup sebelum selesai.'); }
                        });
                    });
                }
            });
        </script>
    @endif
@endsection
