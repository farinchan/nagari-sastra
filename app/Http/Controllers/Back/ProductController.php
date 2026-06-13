<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductScreenshot;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    // ==========================================
    // PRODUCT CRUD
    // ==========================================

    public function index(Request $request)
    {
        $data = [
            'title' => 'Daftar Produk',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'link' => route('back.dashboard')],
                ['name' => 'Produk'],
            ],
            'products' => Product::with('category')->get(),
        ];

        return view('back.pages.product.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Produk',
            'breadcrumbs' => [
                ['name' => 'Produk', 'link' => route('back.product.index')],
                ['name' => 'Tambah Produk'],
            ],
            'categories' => ProductCategory::where('is_active', true)->orderBy('name')->get(),
        ];

        return view('back.pages.product.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'product_category_id' => 'required|exists:product_categories,id',
                'price' => 'required|numeric|min:0',
                'short_description' => 'nullable',
                'description' => 'nullable',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'file' => 'nullable|mimes:zip,rar,gz,tar|max:102400',
                'demo_url' => 'nullable|url',
                'documentation_url' => 'nullable|url',
                'version' => 'nullable|string',
                'compatibility' => 'nullable|string',
                'discount_price' => 'nullable|numeric|min:0',
                'tags' => 'nullable|string',
                'is_active' => 'nullable',
                'is_featured' => 'nullable',
            ],
            [
                'name.required' => 'Nama produk harus diisi',
                'product_category_id.required' => 'Kategori harus dipilih',
                'product_category_id.exists' => 'Kategori tidak valid',
                'price.required' => 'Harga harus diisi',
                'price.numeric' => 'Harga harus berupa angka',
                'price.min' => 'Harga minimal :min',
                'image' => 'File harus berupa gambar',
                'mimes' => 'Format file harus :values',
                'max' => 'Ukuran file maksimal :max KB',
                'demo_url.url' => 'URL demo tidak valid',
                'documentation_url.url' => 'URL dokumentasi tidak valid',
                'discount_price.numeric' => 'Harga diskon harus berupa angka',
                'discount_price.min' => 'Harga diskon minimal :min',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $slug = Product::where('slug', Str::slug($request->name))->count() > 0
            ? Str::slug($request->name) . '-' . rand(1000, 9999)
            : Str::slug($request->name);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->product_category_id = $request->product_category_id;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->demo_url = $request->demo_url;
        $product->documentation_url = $request->documentation_url;
        $product->version = $request->version;
        $product->compatibility = $request->compatibility;
        $product->tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : null;
        $product->is_active = $request->has('is_active');
        $product->is_featured = $request->has('is_featured');
        $product->user_id = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $product->thumbnail = $thumbnail->storeAs(
                'products/thumbnails',
                date('YmdHis') . '_' . Str::slug($request->name) . '.' . $thumbnail->getClientOriginalExtension(),
                'public'
            );
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $product->file = $file->storeAs(
                'products/files',
                date('YmdHis') . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension(),
                'public'
            );
        }

        $product->save();

        Alert::success('Berhasil', 'Produk berhasil ditambahkan');

        return redirect()->route('back.product.index');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'screenshots', 'reviews.user'])->findOrFail($id);

        $data = [
            'title' => $product->name,
            'breadcrumbs' => [
                ['name' => 'Produk', 'link' => route('back.product.index')],
                ['name' => $product->name],
            ],
            'product' => $product,
        ];

        return view('back.pages.product.show', $data);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $data = [
            'title' => 'Edit Produk',
            'breadcrumbs' => [
                ['name' => 'Produk', 'link' => route('back.product.index')],
                ['name' => 'Edit Produk'],
            ],
            'product' => $product,
            'categories' => ProductCategory::where('is_active', true)->orderBy('name')->get(),
        ];

        return view('back.pages.product.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'product_category_id' => 'required|exists:product_categories,id',
                'price' => 'required|numeric|min:0',
                'short_description' => 'nullable',
                'description' => 'nullable',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'file' => 'nullable|mimes:zip,rar,gz,tar|max:102400',
                'demo_url' => 'nullable|url',
                'documentation_url' => 'nullable|url',
                'version' => 'nullable|string',
                'compatibility' => 'nullable|string',
                'discount_price' => 'nullable|numeric|min:0',
                'tags' => 'nullable|string',
                'is_active' => 'nullable',
                'is_featured' => 'nullable',
            ],
            [
                'name.required' => 'Nama produk harus diisi',
                'product_category_id.required' => 'Kategori harus dipilih',
                'product_category_id.exists' => 'Kategori tidak valid',
                'price.required' => 'Harga harus diisi',
                'price.numeric' => 'Harga harus berupa angka',
                'price.min' => 'Harga minimal :min',
                'image' => 'File harus berupa gambar',
                'mimes' => 'Format file harus :values',
                'max' => 'Ukuran file maksimal :max KB',
                'demo_url.url' => 'URL demo tidak valid',
                'documentation_url.url' => 'URL dokumentasi tidak valid',
                'discount_price.numeric' => 'Harga diskon harus berupa angka',
                'discount_price.min' => 'Harga diskon minimal :min',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        // Update slug only if name changed
        if ($product->name !== $request->name) {
            $slug = Product::where('slug', Str::slug($request->name))->where('id', '!=', $id)->count() > 0
                ? Str::slug($request->name) . '-' . rand(1000, 9999)
                : Str::slug($request->name);
            $product->slug = $slug;
        }

        $product->name = $request->name;
        $product->product_category_id = $request->product_category_id;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->demo_url = $request->demo_url;
        $product->documentation_url = $request->documentation_url;
        $product->version = $request->version;
        $product->compatibility = $request->compatibility;
        $product->tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : null;
        $product->is_active = $request->has('is_active');
        $product->is_featured = $request->has('is_featured');

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnail = $request->file('thumbnail');
            $product->thumbnail = $thumbnail->storeAs(
                'products/thumbnails',
                date('YmdHis') . '_' . Str::slug($request->name) . '.' . $thumbnail->getClientOriginalExtension(),
                'public'
            );
        }

        if ($request->hasFile('file')) {
            if ($product->file && Storage::disk('public')->exists($product->file)) {
                Storage::disk('public')->delete($product->file);
            }
            $file = $request->file('file');
            $product->file = $file->storeAs(
                'products/files',
                date('YmdHis') . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension(),
                'public'
            );
        }

        $product->save();

        Alert::success('Berhasil', 'Produk berhasil diperbarui');

        return redirect()->route('back.product.index');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        if ($product->file && Storage::disk('public')->exists($product->file)) {
            Storage::disk('public')->delete($product->file);
        }

        $product->delete();

        Alert::success('Berhasil', 'Produk berhasil dihapus');

        return redirect()->route('back.product.index');
    }

    // ==========================================
    // PRODUCT SCREENSHOTS
    // ==========================================

    public function screenshotStore(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make(
            array_merge($request->all(), $request->allFiles()),
            [
                'screenshot' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
                'caption' => 'nullable|string|max:255',
            ],
            [
                'screenshot.required' => 'File gambar harus diunggah',
                'screenshot.image' => 'File harus berupa gambar',
                'screenshot.mimes' => 'Format file harus :values',
                'screenshot.max' => 'Ukuran file maksimal :max KB',
            ]
        );

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        $file = $request->file('screenshot');

        if (!$file || !$file->isValid()) {
            Alert::error('Gagal', 'File gambar tidak valid atau gagal diunggah.');
            return redirect()->back();
        }

        $path = $file->storeAs(
            'products/screenshots',
            date('YmdHis') . '_' . Str::slug($product->name) . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        $maxOrder = $product->screenshots()->max('order') ?? 0;

        ProductScreenshot::create([
            'product_id' => $product->id,
            'image' => $path,
            'caption' => $request->caption,
            'order' => $maxOrder + 1,
        ]);

        Alert::success('Berhasil', 'Screenshot berhasil ditambahkan');

        return redirect()->back();
    }

    public function screenshotDestroy($id, $screenshotId)
    {
        $product = Product::findOrFail($id);
        $screenshot = ProductScreenshot::where('product_id', $product->id)->findOrFail($screenshotId);

        if ($screenshot->image && Storage::disk('public')->exists($screenshot->image)) {
            Storage::disk('public')->delete($screenshot->image);
        }

        $screenshot->delete();

        Alert::success('Berhasil', 'Screenshot berhasil dihapus');

        return redirect()->back();
    }

    // ==========================================
    // PRODUCT CATEGORIES
    // ==========================================

    public function categoryIndex(Request $request)
    {
        $data = [
            'title' => 'Kategori Produk',
            'breadcrumbs' => [
                ['name' => 'Produk', 'link' => route('back.product.index')],
                ['name' => 'Kategori Produk'],
            ],
            'categories' => ProductCategory::withCount('products')->get(),
        ];

        return view('back.pages.product.category', $data);
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories,name',
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        ProductCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        Alert::success('Berhasil', 'Kategori produk berhasil ditambahkan');

        return redirect()->back();
    }

    public function categoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories,name,' . $id,
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $category = ProductCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        Alert::success('Berhasil', 'Kategori produk berhasil diperbarui');

        return redirect()->back();
    }

    public function categoryDestroy($id)
    {
        $category = ProductCategory::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            Alert::error('Gagal', 'Kategori tidak dapat dihapus karena masih memiliki ' . $category->products_count . ' produk');
            return redirect()->back();
        }

        $category->delete();

        Alert::success('Berhasil', 'Kategori produk berhasil dihapus');

        return redirect()->back();
    }

    // ==========================================
    // PRODUCT REVIEWS
    // ==========================================

    public function reviewApprove($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->is_approved = !$review->is_approved;
        $review->save();

        $status = $review->is_approved ? 'disetujui' : 'dibatalkan persetujuannya';
        Alert::success('Berhasil', 'Review berhasil ' . $status);

        return redirect()->back();
    }

    public function reviewDestroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();

        Alert::success('Berhasil', 'Review berhasil dihapus');

        return redirect()->back();
    }
}
