<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PaymentInvoice;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $invoiceNumber = trim((string) ($request->invoice_number ?? $request->q ?? ''));
        $setting_web = SettingWebsite::first();
        $data = [
            'title' => 'Pembayaran | ' . $setting_web->name,
            'meta' => [
                'title' => 'Pembayaran | ' . $setting_web->name,
                'description' => 'Halaman pembayaran ' . $setting_web->name,
                'keywords' => $setting_web->name . ', pembayaran, invoice, kota padang, sumatera barat',
                'favicon' => $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'noindex, nofollow',
                'canonical' => route('payment.index'),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Pembayaran',
                    'link' => route('payment.index')
                ]
            ],
            'invoice_number' => $invoiceNumber,
            'payment_invoices' => filled($invoiceNumber)
                ? PaymentInvoice::with(['submissions.issue.journal'])
                    ->where(function ($q) use ($invoiceNumber) {
                        $q->where('invoice', 'like', '%' . $invoiceNumber . '%')
                          ->orWhere('invoice_number', 'like', '%' . $invoiceNumber . '%')
                          ->orWhere('kepada', 'like', '%' . $invoiceNumber . '%');
                    })
                    ->latest()
                    ->get()
                : collect(),
        ];
        return view('front.pages.payment.index', $data);
    }

    public function show(Request $request, $invoice_number)
    {
        // Convert hyphens back to slashes for database lookup
        $invoice_number = str_replace('-', '/', $invoice_number);

        $paymentInvoice = PaymentInvoice::with(['submissions.issue.journal'])
            ->where('invoice', $invoice_number)
            ->firstOrFail();

        $submission = $paymentInvoice->submissions->first();
        $journal = $submission?->issue?->journal;

        $setting_web = SettingWebsite::first();

        $snapToken = null;
        if (!$paymentInvoice->is_paid) {
            try {
                MidtransConfig::$serverKey = config('midtrans.serverKey');
                MidtransConfig::$clientKey = config('midtrans.clientKey');
                MidtransConfig::$isProduction = (bool) config('midtrans.isProduction');
                MidtransConfig::$isSanitized = (bool) config('midtrans.isSanitized');
                MidtransConfig::$is3ds = (bool) config('midtrans.is3ds');

                $customerName = $paymentInvoice->kepada ?? ($submission?->authors[0]['name'] ?? 'Pembayaran');
                $customerEmail = $submission?->authors[0]['email'] ?? null;

                $snapParams = [
                    'transaction_details' => [
                        'order_id' => 'PAY-' . $paymentInvoice->invoice_number . '-' . now()->format('YmdHis'),
                        'gross_amount' => (int) round($paymentInvoice->payment_amount),
                    ],
                    'item_details' => [
                        [
                            'id' => $paymentInvoice->invoice,
                            'price' => (int) round($paymentInvoice->payment_amount),
                            'quantity' => 1,
                            'name' => Str($paymentInvoice->invoice)->limit(50),
                        ]
                    ],
                    'customer_details' => [
                        'first_name' => $customerName,
                        'email' => $customerEmail,
                    ],
                ];

                $snapToken = MidtransSnap::getSnapToken($snapParams);
            } catch (\Throwable $th) {
                // Midtrans not configured or error, continue without snap
                $snapToken = null;
            }
        }

        $data = [
            'title' => 'Pembayaran - Invoice ' . $paymentInvoice->invoice,
            'meta' => [
                'title' => 'Pembayaran - ' . $paymentInvoice->invoice . ' | ' . $setting_web->name,
                'description' => 'Pembayaran invoice ' . $paymentInvoice->invoice,
                'keywords' => $setting_web->name . ', pembayaran, invoice, kota padang, sumatera barat',
                'favicon' => $journal?->getJournalThumbnail() ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'noindex, nofollow',
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Pembayaran',
                    'link' => route('payment.index')
                ],
                [
                    'name' => $paymentInvoice->invoice,
                    'link' => '#'
                ]
            ],
            'submission' => $submission,
            'journal' => $journal,
            'payment_invoice' => $paymentInvoice,
            'snap_token' => $snapToken,
        ];

        return view('front.pages.payment.show', $data);
    }
}
