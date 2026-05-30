<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;

class MidtransWebhookController extends Controller
{
    /**
     * Handle Midtrans server-to-server notification (callback/webhook).
     */
    public function handle(Request $request)
    {
        // Configure Midtrans SDK with server key
        MidtransConfig::$serverKey = config('midtrans.serverKey');
        MidtransConfig::$isProduction = (bool) config('midtrans.isProduction');

        $data = $request->json()->all();

        Log::info('Midtrans webhook received', ['payload' => $data]);

        $signatureReceived = $data['signature_key'] ?? null;
        $orderId = $data['order_id'] ?? null;
        $statusCode = $data['status_code'] ?? null;
        $grossAmount = isset($data['gross_amount']) ? (string) $data['gross_amount'] : null;
        $transactionStatus = $data['transaction_status'] ?? null;
        $fraudStatus = $data['fraud_status'] ?? null;
        $transactionId = $data['transaction_id'] ?? null;
        $paymentMethod = $data['payment_type'] ?? null;

        // Validate required fields
        if (!$orderId || !$statusCode || $grossAmount === null) {
            Log::warning('Midtrans webhook missing required fields', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
            ]);
            return response()->json(['message' => 'Missing required fields'], 400);
        }

        // Verify signature (order_id + status_code + gross_amount + serverKey)
        $serverKey = config('midtrans.serverKey') ?? '';
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!$signatureReceived || !hash_equals($expectedSignature, $signatureReceived)) {
            Log::warning('Midtrans webhook signature mismatch', [
                'order_id' => $orderId,
                'expected' => $expectedSignature,
                'received' => $signatureReceived,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Extract invoice number from order_id (format: PAY-{invoice_number}-{YmdHis})
        $invoiceNumber = null;
        if (preg_match('/^PAY-(.+)-\d{14}$/', $orderId, $m)) {
            $invoiceNumber = $m[1];
        }

        if (!$invoiceNumber) {
            Log::warning('Midtrans webhook: cannot extract invoice number', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invoice number not found in order_id'], 400);
        }

        $invoice = PaymentInvoice::where('invoice_number', $invoiceNumber)->first();
        if (!$invoice) {
            Log::warning('Midtrans webhook: invoice not found', ['invoice_number' => $invoiceNumber]);
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // Determine action based on transaction_status
        switch ($transactionStatus) {
            case 'settlement':
                // Settlement = pembayaran berhasil dikonfirmasi
                $this->markAsPaid($invoice, $data, $transactionId, $grossAmount, $paymentMethod);
                Log::info('Midtrans webhook: invoice marked PAID (settlement)', [
                    'invoice_number' => $invoiceNumber,
                    'transaction_id' => $transactionId,
                ]);
                break;

            case 'capture':
                // Capture = untuk kartu kredit, cek fraud status
                if ($fraudStatus === 'accept' || $fraudStatus === null) {
                    $this->markAsPaid($invoice, $data, $transactionId, $grossAmount, $paymentMethod);
                    Log::info('Midtrans webhook: invoice marked PAID (capture, fraud accepted)', [
                        'invoice_number' => $invoiceNumber,
                        'transaction_id' => $transactionId,
                    ]);
                } else {
                    // fraud_status = challenge atau deny
                    $invoice->midtrans_response = $data;
                    $invoice->save();
                    Log::warning('Midtrans webhook: capture with fraud issue', [
                        'invoice_number' => $invoiceNumber,
                        'fraud_status' => $fraudStatus,
                    ]);
                }
                break;

            case 'pending':
                // Pending = menunggu pembayaran
                $invoice->midtrans_response = $data;
                $invoice->midtrans_transaction_id = $transactionId;
                $invoice->midtrans_payment_method = $paymentMethod;
                $invoice->save();
                Log::info('Midtrans webhook: payment pending', [
                    'invoice_number' => $invoiceNumber,
                    'transaction_id' => $transactionId,
                ]);
                break;

            case 'expire':
                // Expire = pembayaran kadaluarsa
                $invoice->midtrans_response = $data;
                $invoice->save();
                Log::info('Midtrans webhook: payment expired', [
                    'invoice_number' => $invoiceNumber,
                ]);
                break;

            case 'cancel':
            case 'deny':
                // Cancel/Deny = pembayaran dibatalkan atau ditolak
                $invoice->midtrans_response = $data;
                $invoice->save();
                Log::info('Midtrans webhook: payment ' . $transactionStatus, [
                    'invoice_number' => $invoiceNumber,
                ]);
                break;

            case 'refund':
            case 'partial_refund':
                // Refund = dana dikembalikan, set kembali ke belum lunas
                $invoice->is_paid = false;
                $invoice->midtrans_response = $data;
                $invoice->save();
                Log::info('Midtrans webhook: payment refunded', [
                    'invoice_number' => $invoiceNumber,
                    'status' => $transactionStatus,
                ]);
                break;

            default:
                // Unknown status, log and save response
                $invoice->midtrans_response = $data;
                $invoice->save();
                Log::info('Midtrans webhook: unhandled status', [
                    'invoice_number' => $invoiceNumber,
                    'status' => $transactionStatus,
                ]);
                break;
        }

        return response()->json(['message' => 'ok'], 200);
    }

    /**
     * Mark invoice as paid with Midtrans transaction details.
     */
    private function markAsPaid(PaymentInvoice $invoice, array $data, ?string $transactionId, ?string $grossAmount, ?string $paymentMethod): void
    {
        // Idempotency: jika sudah lunas, hanya update response saja
        if ($invoice->is_paid) {
            $invoice->midtrans_response = $data;
            $invoice->save();
            return;
        }

        $invoice->is_paid = true;
        $invoice->midtrans_transaction_id = $transactionId;
        $invoice->midtrans_gross_amount_paid = $grossAmount ? (float) $grossAmount : null;
        $invoice->midtrans_payment_method = $paymentMethod;
        $invoice->midtrans_paid_at = now();
        $invoice->midtrans_response = $data;
        $invoice->confirmed_at = now();
        $invoice->save();
    }
}
