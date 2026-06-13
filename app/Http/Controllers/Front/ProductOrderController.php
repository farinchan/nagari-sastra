<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;
use RealRashid\SweetAlert\Facades\Alert;

class ProductOrderController extends Controller
{
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        $product = Product::where('id', $request->product_id)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            Alert::error('Gagal', 'Produk tidak tersedia atau sudah tidak aktif.');
            return redirect()->back();
        }

        // Check if user already purchased this product
        $already_purchased = ProductOrder::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();

        if ($already_purchased) {
            Alert::error('Gagal', 'Anda sudah pernah membeli produk ini.');
            return redirect()->route('product.show', $product->slug);
        }

        $effectivePrice = $product->discount_price ?? $product->price;

        try {
            $order = DB::transaction(function () use ($product, $effectivePrice) {
                $order = ProductOrder::create([
                    'user_id' => Auth::id(),
                    'order_number' => ProductOrder::generateOrderNumber(),
                    'total_amount' => $effectivePrice,
                    'status' => 'pending',
                ]);

                ProductOrderItem::create([
                    'product_order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $effectivePrice,
                    'quantity' => 1,
                ]);

                return $order;
            });

            // Generate Midtrans Snap token
            MidtransConfig::$serverKey = config('midtrans.serverKey');
            MidtransConfig::$clientKey = config('midtrans.clientKey');
            MidtransConfig::$isProduction = (bool) config('midtrans.isProduction');
            MidtransConfig::$isSanitized = (bool) config('midtrans.isSanitized');
            MidtransConfig::$is3ds = (bool) config('midtrans.is3ds');

            $user = Auth::user();

            $snapParams = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) round($effectivePrice),
                ],
                'item_details' => [
                    [
                        'id' => 'PROD-' . $product->id,
                        'price' => (int) round($effectivePrice),
                        'quantity' => 1,
                        'name' => Str($product->name)->limit(50),
                    ]
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ];

            $snapToken = MidtransSnap::getSnapToken($snapParams);
            $order->update(['snap_token' => $snapToken]);

            return redirect()->route('product.payment', $order->order_number);
        } catch (\Throwable $th) {
            Log::error('Product checkout error: ' . $th->getMessage(), [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ]);
            Alert::error('Gagal', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function payment($orderNumber)
    {
        $setting_web = SettingWebsite::first();

        $order = ProductOrder::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        // If already paid, redirect to my products
        if ($order->status === 'paid') {
            Alert::success('Berhasil', 'Pembayaran sudah berhasil. Produk tersedia di halaman produk saya.');
            return redirect()->route('product.my-products');
        }

        $clientKey = config('midtrans.clientKey');

        $data = [
            'title' => 'Pembayaran - ' . $order->order_number . ' | ' . $setting_web->name,
            'meta' => [
                'title' => 'Pembayaran - ' . $order->order_number . ' | ' . $setting_web->name,
                'description' => 'Halaman pembayaran pesanan ' . $order->order_number,
                'keywords' => $setting_web->name . ', pembayaran, produk digital, checkout',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'noindex, nofollow',
                'canonical' => route('product.payment', $order->order_number),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Produk Digital',
                    'link' => route('product.index')
                ],
                [
                    'name' => 'Pembayaran',
                    'link' => route('product.payment', $order->order_number)
                ]
            ],
            'order' => $order,
            'clientKey' => $clientKey,
        ];

        return view('front.pages.product.payment', $data);
    }

    public function callback(Request $request)
    {
        // Configure Midtrans
        MidtransConfig::$serverKey = config('midtrans.serverKey');
        MidtransConfig::$isProduction = (bool) config('midtrans.isProduction');

        $data = $request->json()->all();

        Log::info('Product Midtrans webhook received', ['payload' => $data]);

        $signatureReceived = $data['signature_key'] ?? null;
        $orderId = $data['order_id'] ?? null;
        $statusCode = $data['status_code'] ?? null;
        $grossAmount = isset($data['gross_amount']) ? (string) $data['gross_amount'] : null;
        $transactionStatus = $data['transaction_status'] ?? null;
        $fraudStatus = $data['fraud_status'] ?? null;
        $paymentMethod = $data['payment_type'] ?? null;

        // Validate required fields
        if (!$orderId || !$statusCode || $grossAmount === null) {
            Log::warning('Product Midtrans webhook missing required fields', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
            ]);
            return response()->json(['message' => 'Missing required fields'], 400);
        }

        // Verify signature
        $serverKey = config('midtrans.serverKey') ?? '';
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (!$signatureReceived || !hash_equals($expectedSignature, $signatureReceived)) {
            Log::warning('Product Midtrans webhook signature mismatch', [
                'order_id' => $orderId,
                'expected' => $expectedSignature,
                'received' => $signatureReceived,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find order by order_number (order_id in Midtrans = order_number in our DB)
        $order = ProductOrder::where('order_number', $orderId)->first();
        if (!$order) {
            Log::warning('Product Midtrans webhook: order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'settlement':
                $this->markAsPaid($order, $data, $paymentMethod);
                Log::info('Product Midtrans webhook: order marked PAID (settlement)', [
                    'order_number' => $order->order_number,
                ]);
                break;

            case 'capture':
                if ($fraudStatus === 'accept' || $fraudStatus === null) {
                    $this->markAsPaid($order, $data, $paymentMethod);
                    Log::info('Product Midtrans webhook: order marked PAID (capture, fraud accepted)', [
                        'order_number' => $order->order_number,
                    ]);
                } else {
                    $order->midtrans_response = $data;
                    $order->save();
                    Log::warning('Product Midtrans webhook: capture with fraud issue', [
                        'order_number' => $order->order_number,
                        'fraud_status' => $fraudStatus,
                    ]);
                }
                break;

            case 'pending':
                $order->status = 'pending';
                $order->payment_method = $paymentMethod;
                $order->midtrans_response = $data;
                $order->save();
                Log::info('Product Midtrans webhook: payment pending', [
                    'order_number' => $order->order_number,
                ]);
                break;

            case 'expire':
            case 'cancel':
            case 'deny':
                $order->status = 'cancelled';
                $order->midtrans_response = $data;
                $order->save();
                Log::info('Product Midtrans webhook: payment ' . $transactionStatus, [
                    'order_number' => $order->order_number,
                ]);
                break;

            case 'refund':
            case 'partial_refund':
                $order->status = 'refunded';
                $order->midtrans_response = $data;
                $order->save();
                Log::info('Product Midtrans webhook: payment refunded', [
                    'order_number' => $order->order_number,
                    'status' => $transactionStatus,
                ]);
                break;

            default:
                $order->midtrans_response = $data;
                $order->save();
                Log::info('Product Midtrans webhook: unhandled status', [
                    'order_number' => $order->order_number,
                    'status' => $transactionStatus,
                ]);
                break;
        }

        return response()->json(['message' => 'ok'], 200);
    }

    public function myProducts(Request $request)
    {
        $setting_web = SettingWebsite::first();

        // Load all paid orders for current user
        $orders = ProductOrder::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->with('items.product')
            ->latest()
            ->get();

        // Extract unique products
        $products = $orders->flatMap(function ($order) {
            return $order->items->map(function ($item) use ($order) {
                $product = $item->product;
                if ($product) {
                    $product->purchased_at = $order->paid_at;
                }
                return $product;
            });
        })->filter()->unique('id')->values();

        $data = [
            'title' => 'Produk Saya | ' . $setting_web->name,
            'meta' => [
                'title' => 'Produk Saya | ' . $setting_web->name,
                'description' => 'Daftar produk digital yang telah Anda beli',
                'keywords' => $setting_web->name . ', produk saya, download, produk digital',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'noindex, nofollow',
                'canonical' => route('product.my-products'),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Produk Digital',
                    'link' => route('product.index')
                ],
                [
                    'name' => 'Produk Saya',
                    'link' => route('product.my-products')
                ]
            ],
            'products' => $products,
            'orders' => $orders,
        ];

        return view('front.pages.product.my-products', $data);
    }

    public function download($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Check user has a paid order containing this product
        $has_purchased = ProductOrder::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();

        if (!$has_purchased) {
            abort(403, 'Anda belum membeli produk ini.');
        }

        // Check file exists
        if (!$product->file || !Storage::exists($product->file)) {
            Alert::error('Gagal', 'File produk tidak ditemukan.');
            return redirect()->back();
        }

        // Increment download count
        $product->increment('download_count');

        return Storage::download($product->file, Str::slug($product->name) . '.' . pathinfo($product->file, PATHINFO_EXTENSION));
    }

    /**
     * Mark order as paid with Midtrans transaction details.
     */
    private function markAsPaid(ProductOrder $order, array $data, ?string $paymentMethod): void
    {
        // Idempotency: jika sudah dibayar, hanya update response saja
        if ($order->status === 'paid') {
            $order->midtrans_response = $data;
            $order->save();
            return;
        }

        $order->status = 'paid';
        $order->payment_method = $paymentMethod;
        $order->paid_at = now();
        $order->midtrans_response = $data;
        $order->save();

        // Increment download_count for each product in order
        $order->load('items');
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('download_count');
            }
        }
    }
}
