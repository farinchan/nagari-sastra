<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\SettingWebsite;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $setting_web = SettingWebsite::first();
        $search = $request->q;

        $books = Book::where('status', 'published')
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('authorString', 'like', "%$search%")
                    ->orWhere('authors', 'like', "%$search%")
                    ->orWhere('publisher', 'like', "%$search%");
            })
            ->with('category')
            ->latest()
            ->paginate(6);
        $books->appends(['q' => $search]);

        $data = [
            'title' => 'Buku | ' . $setting_web->name,
            'meta' => [
                'title' => 'Buku | ' . $setting_web->name,
                'description' => strip_tags($setting_web->about),
                'keywords' => $setting_web->name . ', Buku, Koleksi Buku, Katalog, Perpustakaan',
                'favicon' => $setting_web->favicon
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Buku',
                    'link' => route('book.index')
                ]
            ],
            'books' => $books,
            'categories' => BookCategory::withCount('books')->get(),
        ];

        return view('front.pages.book.index', $data);
    }

    public function show($slug)
    {
        $setting_web = SettingWebsite::first();
        $book = Book::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Get related books from same category
        $related_books = $book->category->books()
            ->where('id', '!=', $book->id)
            ->where('status', 'published')
            ->limit(4)
            ->get();

        $data = [
            'title' => $book->title,
            'meta' => [
                'title' => $book->title . ' | ' . $setting_web->name,
                'description' => strip_tags($book->description),
                'keywords' => $setting_web->name . ', ' . $book->title . ', ' . ($book->authorString ?: $book->author) . ', ' . $book->publisher,
                'favicon' => $book->getThumbnail() ?? $setting_web->favicon
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Buku',
                    'link' => route('book.index')
                ],
                [
                    'name' => $book->category->name,
                    'link' => route('book.category', $book->category->slug)
                ],
                [
                    'name' => $book->isbn,
                    'link' => route('book.show', $book->slug)
                ]
            ],
            'book' => $book,
            'related_books' => $related_books,
            'categories' => BookCategory::withCount('books')->get(),
        ];

        return view('front.pages.book.show', $data);
    }

    public function preview($slug)
    {
        $setting_web = SettingWebsite::first();
        $book = Book::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $data = [
            'title' => $book->title . ' Preview',
            'meta' => [
                'title' => $book->title . ' Preview | ' . $setting_web->name,
                'description' => strip_tags($book->description),
                'keywords' => $setting_web->name . ', ' . $book->title . ', Preview Buku',
                'favicon' => $book->getThumbnail() ?? $setting_web->favicon
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Buku',
                    'link' => route('book.index')
                ],
                [
                    'name' => $book->category->name,
                    'link' => route('book.category', $book->category->slug)
                ],
                [
                    'name' => $book->title,
                    'link' => route('book.show', $book->slug)
                ],
                [
                    'name' => 'Preview',
                    'link' => route('book.preview', $book->slug)
                ]
            ],
            'book' => $book,
            'preview_url' => $book->getPreviewFile(),
            'categories' => BookCategory::withCount('books')->get(),
        ];

        return view('front.pages.book.preview', $data);
    }

    public function category($slug)
    {
        $setting_web = SettingWebsite::first();
        $category = BookCategory::where('slug', $slug)->firstOrFail();

        $books = $category->books()
            ->where('status', 'published')
            ->latest()
            ->paginate(6);

        $data = [
            'title' => $category->name . ' | ' . $setting_web->name,
            'meta' => [
                'title' => $category->name . ' | ' . $setting_web->name,
                'description' => strip_tags($setting_web->about),
                'keywords' => $setting_web->name . ', ' . $category->name . ', Buku, Koleksi Buku, Katalog',
                'favicon' => $setting_web->favicon
            ],
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'link' => route('home')
                ],
                [
                    'name' => 'Buku',
                    'link' => route('book.index')
                ],
                [
                    'name' => $category->name,
                    'link' => route('book.category', $category->slug)
                ]
            ],
            'category' => $category,
            'books' => $books,
            'categories' => BookCategory::withCount('books')->get(),
        ];

        return view('front.pages.book.category', $data);
    }
}
