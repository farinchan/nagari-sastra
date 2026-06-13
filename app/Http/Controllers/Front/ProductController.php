<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory; 
use App\Models\PaymentInvoice;
use App\Models\ProductReview;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $setting_web = SettingWebsite::first();
        $search = strip_tags(trim($request->q ?? ''));
        $search = mb_substr($search, 0, 100);

        $products = Product::where('is_active', true)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('short_description', 'like', '%' . $search . '%')
                      ->orWhereJsonContains('tags', $search);
                });
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            })
            ->when($request->sort, function ($query) use ($request) {
                switch ($request->sort) {
                    case 'price_low':
                        $query->orderByRaw('COALESCE(discount_price, price) ASC');
                        break;
                    case 'price_high':
                        $query->orderByRaw('COALESCE(discount_price, price) DESC');
                        break;
                    case 'popular':
                        $query->orderBy('download_count', 'desc');
                        break;
                    case 'newest':
                    default:
                        $query->latest();
                        break;
                }
            }, function ($query) {
                $query->latest();
            })
            ->with('category')
            ->paginate(12);
        $products->appends([
            'q' => $search,
            'category' => $request->category,
            'sort' => $request->sort,
        ]);

        $data = [
            'title' => 'Produk Digital | ' . $setting_web->name,
            'meta' => [
                'title' => 'Produk Digital | ' . $setting_web->name,
                'description' => Str::limit('Katalog produk digital dari ' . strip_tags($setting_web->about), 155),
                'keywords' => $setting_web->name . ', produk digital, template, source code, aplikasi, tools',
                'favicon' => $setting_web->favicon,
                'og_image' => $setting_web->logo ?? $setting_web->favicon,
                'og_type' => 'website',
                'robots' => 'index, follow',
                'canonical' => route('product.index'),
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Produk Digital',
                    'link' => route('product.index')
                ]
            ],
            'products' => $products,
            'categories' => ProductCategory::where('is_active', true)
                ->withCount(['products' => function ($q) {
                    $q->where('is_active', true);
                }])
                ->orderBy('order')
                ->get(),
        ];

        return view('front.pages.product.index', $data);
    }

    public function show($slug)
    {
        $setting_web = SettingWebsite::first();
        $product = Product::with([
                'category',
                'screenshots' => function ($q) {
                    $q->orderBy('order');
                },
                'reviews' => function ($q) {
                    $q->where('is_approved', true)->with('user')->latest();
                },
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment view count
        $product->increment('view_count');

        // Related products from same category
        $related_products = Product::where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        // Check if authenticated user has purchased this product
        $has_purchased = false;
        $has_reviewed = false;
        if (Auth::check()) {
            $has_purchased = $this->hasPurchasedProduct(Auth::id(), $product->id);

            $has_reviewed = ProductReview::where('product_id', $product->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        $data = [
            'title' => $product->name . ' | ' . $setting_web->name,
            'meta' => [
                'title' => $product->name . ' | ' . $setting_web->name,
                'description' => Str::limit(strip_tags($product->short_description ?? $product->description), 155),
                'keywords' => $setting_web->name . ', ' . $product->name . ', ' . ($product->category->name ?? 'produk digital') . ', produk digital, download',
                'favicon' => $setting_web->favicon,
                'og_image' => $product->getThumbnail(),
                'og_type' => 'product',
                'robots' => 'index, follow',
                'canonical' => route('product.show', $product->slug),
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
                    'name' => $product->category->name ?? 'Kategori',
                    'link' => route('product.index', ['category' => $product->category->slug ?? ''])
                ],
                [
                    'name' => $product->name,
                    'link' => route('product.show', $product->slug)
                ]
            ],
            'product' => $product,
            'related_products' => $related_products,
            'has_purchased' => $has_purchased,
            'has_reviewed' => $has_reviewed,
        ];

        return view('front.pages.product.show', $data);
    }

    public function review(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'comment.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if user has already reviewed
        $existing_review = ProductReview::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existing_review) {
            Alert::error('Gagal', 'Anda sudah pernah memberikan review untuk produk ini.');
            return redirect()->back();
        }

        // Check if user has purchased the product
        $has_purchased = $this->hasPurchasedProduct(Auth::id(), $product->id);

        if (!$has_purchased) {
            Alert::error('Gagal', 'Anda harus membeli produk ini terlebih dahulu sebelum memberikan review.');
            return redirect()->back();
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => strip_tags($request->comment),
            'is_approved' => false,
        ]);

        Alert::success('Berhasil', 'Review berhasil dikirim dan menunggu moderasi.');
        return redirect()->back();
    }

    /**
     * Check if a user has purchased a specific product via PaymentInvoice.
     */
    private function hasPurchasedProduct($userId, $productId): bool
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
