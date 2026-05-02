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

        // Verify signature (order_id + status_code + gross_amount + serverKey)
        if ($orderId && $statusCode && $grossAmount !== null) {
            $expected = hash('sha512', $orderId . $statusCode . $grossAmount . (config('midtrans.serverKey') ?? ''));
            if (! $signatureReceived || ! hash_equals($expected, $signatureReceived)) {
                Log::warning('Midtrans webhook signature mismatch', ['expected' => $expected, 'received' => $signatureReceived]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }
        }

        // Extract invoice number from order_id (format: PAY-{invoice_number}-{YmdHis})
        $invoiceNumber = null;
        if ($orderId && preg_match('/^PAY-(.+)-\d{14}$/', $orderId, $m)) {
            $invoiceNumber = $m[1];
        }

        if (! $invoiceNumber) {
            Log::warning('Midtrans webhook missing invoice number', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invoice number not found in order_id'], 400);
        }

        $invoice = PaymentInvoice::where('invoice_number', $invoiceNumber)->first();
        if (! $invoice) {
            Log::warning('Midtrans webhook invoice not found', ['invoice_number' => $invoiceNumber]);
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        $transactionStatus = $data['transaction_status'] ?? null;
        $fraudStatus = $data['fraud_status'] ?? null;

        // Determine paid status
        $paid = false;
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            // For card capture, also check fraud_status == 'accept' when present
            if ($transactionStatus === 'capture' && $fraudStatus !== null) {
                $paid = $fraudStatus === 'accept';
            } else {
                $paid = true;
            }
        }

        if ($paid) {
            // Extract transaction details from Midtrans response
            $transactionId = $data['transaction_id'] ?? null;
            $grossAmount = isset($data['gross_amount']) ? (float) $data['gross_amount'] : null;
            $paymentMethod = $data['payment_type'] ?? null;

            $invoice->is_paid = true;
            $invoice->midtrans_transaction_id = $transactionId;
            $invoice->midtrans_gross_amount_paid = $grossAmount;
            $invoice->midtrans_payment_method = $paymentMethod;
            $invoice->midtrans_paid_at = now();
            $invoice->midtrans_response = $data;
            $invoice->save();

            Log::info('Midtrans webhook marked invoice paid', [
                'invoice_number' => $invoiceNumber,
                'transaction_id' => $transactionId,
                'gross_amount' => $grossAmount,
                'payment_method' => $paymentMethod,
            ]);
        } else {
            // For non-paid statuses, still log and save response for audit trail
            $invoice->midtrans_response = $data;
            $invoice->save();

            Log::info('Midtrans webhook status received (not marking paid)', [
                'invoice_number' => $invoiceNumber,
                'status' => $transactionStatus,
                'fraud' => $fraudStatus,
            ]);
        }

        return response()->json(['message' => 'ok'], 200);
    }
}
