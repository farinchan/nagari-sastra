<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\Payment;
use App\Models\PaymentAccount;
use App\Models\PaymentInvoice;
use App\Models\SettingWebsite;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class PaymentController extends Controller
{
    private function resolveInvoiceData(string $invoice_number): array
    {
        // Convert hyphens back to slashes for database lookup
        // URL format: 0001-INV-TR-V-2026 -> DB format: 0001/INV/TR/V/2026
        $invoice_number = str_replace('-', '/', $invoice_number);

        $paymentInvoice = PaymentInvoice::with(['submissions.issue.journal'])
            ->where('invoice', $invoice_number)
            ->firstOrFail();

        $submission = $paymentInvoice->submissions->first();
        $journal = $submission?->issue?->journal;

        if (! $submission || ! $journal) {
            abort(404);
        }

        return [$paymentInvoice, $submission, $journal];
    }

    private function buildSnapParams(PaymentInvoice $paymentInvoice, Submission $submission): array
    {
        return [
            'transaction_details' => [
                'order_id' => 'PAY-' . $paymentInvoice->invoice . '-' . now()->format('YmdHis'),
                'gross_amount' => (int) round($paymentInvoice->payment_amount),
            ],
            'item_details' => [[
                'id' => $paymentInvoice->invoice,
                'price' => (int) round($paymentInvoice->payment_amount),
                'quantity' => 1,
                'name' =>  $paymentInvoice->invoice,
            ]],
            'customer_details' => [
                'first_name' => $submission->authors[0]['name'] ?? ($submission->fullTitle ?? 'Pembayaran'),
                'email' => $submission->authors[0]['email'] ?? null,
                'phone' => null,
            ],
        ];
    }

    public function index(Request $request)
    {
        $invoiceNumber = trim((string) ($request->invoice_number ?? $request->q ?? ''));
        $setting_web = SettingWebsite::first();
        $data = [
            'title' => 'Pembayaran | ' . $setting_web->name,
            'meta' => [
                'title' => 'Pembayaran | ' . $setting_web->name,
                'description' => strip_tags($setting_web->about),
                'keywords' => $setting_web->name . ', Journal, Research, OJS System, Open Journal System, Research Journal, Academic Journal, Publication',
                'favicon' => $setting_web->favicon
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
                    ->where('invoice', 'like', '%' . $invoiceNumber . '%')
                    ->latest()
                    ->get()
                : collect(),
        ];
        return view('front.pages.payment.index', $data);
    }


    public function show(Request $request, $invoice_number)
    {


        [$paymentInvoice, $submission, $journal] = $this->resolveInvoiceData($invoice_number);

        $setting_web = SettingWebsite::first();

        MidtransConfig::$serverKey = config('midtrans.serverKey');
        MidtransConfig::$clientKey = config('midtrans.clientKey');
        MidtransConfig::$isProduction = (bool) config('midtrans.isProduction');
        MidtransConfig::$isSanitized = (bool) config('midtrans.isSanitized');
        MidtransConfig::$is3ds = (bool) config('midtrans.is3ds');

        $snapParams = $this->buildSnapParams($paymentInvoice, $submission);

        $data = [
            'title' => 'Pembayaran - Invoice ' . $paymentInvoice->invoice,
            'meta' => [
                'title' => 'Pembayaran - ' . $paymentInvoice->invoice . ' | ' . $setting_web->name,
                'description' => 'Pembayaran invoice ' . $paymentInvoice->invoice . ' melalui Midtrans Snap.',
                'keywords' => $setting_web->name . ', ' . $paymentInvoice->invoice . ', Midtrans, Payment, Invoice',
                'favicon' => $journal?->getJournalThumbnail() ?? Storage::url($setting_web->favicon)
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
                    'link' => route('payment.show', ['invoice_number' => $paymentInvoice->invoice])
                ]

            ],
            'submission' => $submission,
            'journal' => $journal,
            'payment_invoice' => $paymentInvoice,
            'snap_token' => MidtransSnap::getSnapToken($snapParams),
            'snap_params' => $snapParams,
        ];

        return view('front.pages.payment.show', $data);
    }

}
