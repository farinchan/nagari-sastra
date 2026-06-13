<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PaymentInvoice;
use App\Models\Product;
use App\Models\SettingWebsite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        $already_purchased = $this->hasPurchased(Auth::id(), $product->id);

        if ($already_purchased) {
            Alert::error('Gagal', 'Anda sudah pernah membeli produk ini.');
            return redirect()->route('product.show', $product->slug);
        }

        $effectivePrice = $product->discount_price > 0 ? $product->discount_price : $product->price;

        try {
            // Generate invoice number (sama seperti journal & book)
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $lastInvoice = PaymentInvoice::whereYear('created_at', $currentYear)
                ->orderBy('id', 'desc')
                ->first();
            $nextNumber = $lastInvoice && $lastInvoice->invoice_number
                ? str_pad((int) $lastInvoice->invoice_number + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $formattedInvoice = function_exists('format_nomor')
                ? format_nomor($nextNumber, 'INV', 'NSG', $currentMonth, $currentYear)
                : 'INV/' . $currentYear . '/' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) . '/NSG/' . $nextNumber;

            $invoiceItems = [
                [
                    'id' => 'PROD-' . $product->id,
                    'name' => $product->name,
                    'detail' => $product->category->name ?? 'Produk Digital',
                    'qty' => 1,
                    'amount' => $effectivePrice,
                ],
            ];

            // Create PaymentInvoice (sama seperti journal & book, tanpa snap_token)
            $invoice = PaymentInvoice::create([
                'user_id' => Auth::id(),
                'invoice' => $formattedInvoice,
                'invoice_number' => $nextNumber,
                'items' => $invoiceItems,
                'payment_amount' => $effectivePrice,
                'payment_percent' => 100,
                'is_paid' => false,
                'is_custom' => false,
                'source_type' => 'product',
                'kepada' => Auth::user()->name,
                'kepada_detail' => Auth::user()->email,
                'keterangan' => 'Pembelian produk digital: ' . $product->name,
                'payment_due_date' => Carbon::now()->addDays(1),
            ]);

            // Redirect ke halaman payment yang sudah ada (PaymentController@show)
            // Format URL: /payment/{invoice} (dengan slash diganti dash)
            $invoiceSlug = str_replace('/', '-', $formattedInvoice);
            return redirect()->route('payment.show', ['invoice_number' => $invoiceSlug]);

        } catch (\Throwable $th) {
            Log::error('Product checkout error: ' . $th->getMessage(), [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'trace' => $th->getTraceAsString(),
            ]);
            Alert::error('Gagal', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function myProducts(Request $request)
    {
        $setting_web = SettingWebsite::first();

        // Load all paid product invoices for current user
        $invoices = PaymentInvoice::where('user_id', Auth::id())
            ->where('source_type', 'product')
            ->where('is_paid', true)
            ->latest()
            ->get();

        // Extract product IDs from invoice items and load products
        $productIds = $invoices->flatMap(function ($invoice) {
            return collect($invoice->items ?? [])->map(function ($item) {
                $id = $item['id'] ?? '';
                return str_starts_with($id, 'PROD-') ? (int) substr($id, 5) : null;
            })->filter();
        })->unique()->values();

        $products = Product::whereIn('id', $productIds)->with('category')->get();

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
                ['name' => 'Beranda', 'link' => route('home')],
                ['name' => 'Produk Digital', 'link' => route('product.index')],
                ['name' => 'Produk Saya', 'link' => route('product.my-products')],
            ],
            'products' => $products,
        ];

        return view('front.pages.product.my-products', $data);
    }

    public function download($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Check user has a paid invoice containing this product
        $has_purchased = $this->hasPurchased(Auth::id(), $product->id);

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
     * Check if a user has purchased a specific product via PaymentInvoice.
     */
    private function hasPurchased($userId, $productId): bool
    {
        $productKey = 'PROD-' . $productId;

        return PaymentInvoice::where('user_id', $userId)
            ->where('source_type', 'product')
            ->where('is_paid', true)
            ->get()
            ->contains(function ($invoice) use ($productKey) {
                return collect($invoice->items ?? [])->contains('id', $productKey);
            });
    }
}
