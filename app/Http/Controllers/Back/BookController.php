<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private function normalizeTagifyInput($value): array
    {
        if (is_array($value)) {
            return collect($value)->filter()->values()->all();
        }

        if (!is_string($value) || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return collect($decoded)->filter()->values()->all();
        }

        return collect(preg_split('/\s*(?:,|;|\n)\s*/', $value, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->map(fn ($item) => ['value' => $item])
            ->all();
    }

    private function normalizeAuthorString(?string $authorString, array $authors): ?string
    {
        return filled($authorString) ? trim($authorString) : null;
    }

    public function category()
    {
        $data = [
            'title' => 'Kategori Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Kategori Buku',
                    'link' => route('back.book.category')
                ]
            ],
            'categories' => BookCategory::all()
        ];

        return view('back.pages.book.category', $data);
    }

    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:book_categories,name',
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        BookCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'meta_title' => $request->name,
            'meta_description' => $request->description,
            'meta_keywords' => implode(", ", explode(" ", $request->name)),
        ]);

        return redirect()->route('back.book.category')->with('success', 'Kategori Buku berhasil ditambahkan');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:book_categories,name,' . $id,
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.unique' => 'Nama kategori sudah ada'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $category = BookCategory::find($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'meta_title' => $request->name,
            'meta_description' => $request->description,
            'meta_keywords' => implode(", ", explode(" ", $request->name)),
        ]);

        return redirect()->route('back.book.category')->with('success', 'Kategori Buku berhasil diubah');
    }

    public function categoryDestroy($id)
    {
        $category = BookCategory::find($id);
        $category->delete();

        return redirect()->route('back.book.category')->with('success', 'Kategori Buku berhasil dihapus');
    }

    public function index()
    {
        $data = [
            'title' => 'Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Buku',
                    'link' => route('back.book.index')
                ]
            ],
            'list_books' => Book::with('category')->get()
        ];

        return view('back.pages.book.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Buku',
                    'link' => route('back.book.index')
                ],
                [
                    'name' => 'Tambah Buku',
                    'link' => route('back.book.create')
                ]
            ],
            'categories' => BookCategory::all()
        ];

        return view('back.pages.book.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
                'preview_file' => 'nullable|mimes:pdf|max:30720',
                'attachment' => 'nullable|mimes:pdf|max:30720',
                'title' => 'required',
                'category_id' => 'required',
                'status' => 'required',
                'authorString' => 'nullable|string',
                'authors' => 'nullable',
                'publisher' => 'nullable',
                'isbn' => 'nullable|unique:books,isbn',
                'edition' => 'nullable',
                'publish_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'pages' => 'nullable|integer',
                'size' => 'nullable',
                'weight' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer',
                'language' => 'nullable|in:en,id,jp',
                'description' => 'nullable',
                'keywords' => 'nullable',
            ],
            [
                'required' => 'Kolom :attribute harus diisi',
                'image' => 'File harus berupa gambar',
                'mimes' => 'Format file harus :values',
                'max' => 'Ukuran file maksimal :max KB',
                'unique' => ':attribute sudah ada',
                'integer' => ':attribute harus berupa angka',
                'numeric' => ':attribute harus berupa angka',
                'min' => ':attribute minimal :min',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $slug = "";
        if (Book::where('slug', Str::slug($request->title))->count() > 0) {
            $slug = Str::slug($request->title) . '-' . rand(1000, 9999);
        } else {
            $slug = Str::slug($request->title);
        }

        $authors = $this->normalizeTagifyInput($request->authors);

        $book = new Book();
        $book->title = $request->title;
        $book->slug = $slug;
        $book->authorString = $this->normalizeAuthorString($request->authorString, $authors);
        $book->authors = $authors ?: null;
        $book->publisher = $request->publisher;
        $book->isbn = $request->isbn;
        $book->edition = $request->edition;
        $book->publish_year = $request->publish_year;
        $book->pages = $request->pages;
        $book->size = $request->size;
        $book->weight = $request->weight;
        $book->price = $request->price ?? 0;
        $book->stock = $request->stock ?? 0;
        $book->language = $request->language ?? 'id';
        $book->description = $request->description;
        $book->book_category_id = $request->category_id;
        $book->status = $request->status;
        $book->keywords = $request->keywords ? json_decode($request->keywords) : null;
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $book->thumbnail = $thumbnail->storeAs('books', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('preview_file')) {
            $preview = $request->file('preview_file');
            $book->preview_file = $preview->storeAs('books/preview', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $preview->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $book->attachment = $attachment->storeAs('books/attachment', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $attachment->getClientOriginalExtension(), 'public');
        }

        $book->save();

        return redirect()->route('back.book.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Buku',
            'breadcrumbs' => [
                [
                    'name' => 'Buku',
                    'link' => route('back.book.index')
                ],
                [
                    'name' => 'Edit Buku',
                    'link' => route('back.book.edit', $id)
                ]
            ],
            'categories' => BookCategory::all(),
            'book' => Book::find($id)
        ];

        return view('back.pages.book.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        $validator = Validator::make(
            $request->all(),
            [
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'preview_file' => 'nullable|mimes:pdf|max:10240',
                'attachment' => 'nullable|mimes:pdf|max:10240',
                'title' => 'required',
                'category_id' => 'required',
                'status' => 'required',
                'authorString' => 'nullable|string',
                'authors' => 'nullable',
                'publisher' => 'nullable',
                'isbn' => 'nullable|unique:books,isbn,' . $id,
                'edition' => 'nullable',
                'publish_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'pages' => 'nullable|integer',
                'size' => 'nullable',
                'weight' => 'nullable|numeric',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer',
                'language' => 'nullable|in:en,id,jp',
                'description' => 'nullable',
                'keywords' => 'nullable',
            ],
            [
                'required' => 'Kolom :attribute harus diisi',
                'image' => 'File harus berupa gambar',
                'mimes' => 'Format file harus :values',
                'max' => 'Ukuran file maksimal :max KB',
                'unique' => ':attribute sudah ada',
                'integer' => ':attribute harus berupa angka',
                'numeric' => ':attribute harus berupa angka',
                'min' => ':attribute minimal :min',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->all());
        }

        $slug = "";
        if (Book::where('slug', Str::slug($request->title))->where('id', '!=', $id)->count() > 0) {
            $slug = Str::slug($request->title) . '-' . rand(1000, 9999);
        } else {
            $slug = Str::slug($request->title);
        }

        $authors = $this->normalizeTagifyInput($request->authors);

        $book->title = $request->title;
        $book->slug = $slug;
        $book->authorString = $this->normalizeAuthorString($request->authorString, $authors);
        $book->authors = $authors ?: null;
        $book->publisher = $request->publisher;
        $book->isbn = $request->isbn;
        $book->edition = $request->edition;
        $book->publish_year = $request->publish_year;
        $book->pages = $request->pages;
        $book->size = $request->size;
        $book->weight = $request->weight;
        $book->price = $request->price ?? 0;
        $book->stock = $request->stock ?? 0;
        $book->language = $request->language ?? 'id';
        $book->description = $request->description;
        $book->book_category_id = $request->category_id;
        $book->status = $request->status;
        $book->keywords = $request->keywords ? json_decode($request->keywords) : null;
        if ($request->hasFile('thumbnail')) {
            if ($book->thumbnail && Storage::disk('public')->exists($book->thumbnail)) {
                Storage::disk('public')->delete($book->thumbnail);
            }
            $thumbnail = $request->file('thumbnail');
            $book->thumbnail = $thumbnail->storeAs('books', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $thumbnail->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('preview_file')) {
            if ($book->preview_file && Storage::disk('public')->exists($book->preview_file)) {
                Storage::disk('public')->delete($book->preview_file);
            }
            $preview = $request->file('preview_file');
            $book->preview_file = $preview->storeAs('books/preview', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $preview->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('attachment')) {
            if ($book->attachment && Storage::disk('public')->exists($book->attachment)) {
                Storage::disk('public')->delete($book->attachment);
            }
            $attachment = $request->file('attachment');
            $book->attachment = $attachment->storeAs('books/attachment', date('YmdHis') . '_' . Str::slug($request->title) . '.' . $attachment->getClientOriginalExtension(), 'public');
        }

        $book->save();

        return redirect()->route('back.book.index')->with('success', 'Buku berhasil diubah');
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if ($book->thumbnail && Storage::disk('public')->exists($book->thumbnail)) {
            Storage::disk('public')->delete($book->thumbnail);
        }
        if ($book->preview_file && Storage::disk('public')->exists($book->preview_file)) {
            Storage::disk('public')->delete($book->preview_file);
        }
        if ($book->attachment && Storage::disk('public')->exists($book->attachment)) {
            Storage::disk('public')->delete($book->attachment);
        }

        $book->delete();

        return redirect()->back()->with('success', 'Buku berhasil dihapus');
    }
}
