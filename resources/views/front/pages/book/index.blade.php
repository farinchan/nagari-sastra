@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Torkata Research">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('book.index') }}">
    <link rel="canonical" href="{{ route('book.index') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')
    <!-- BOOK LISTING
        ============================================= -->
    <section id="book-listing" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">

                <!-- BOOK LISTING WRAPPER -->
                <div class="col-lg-9">
                    <div class="posts-wrapper pr-25">

                        <div class="row">
                            @forelse ($books as $book)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="book-card h-100 rounded shadow-sm overflow-hidden p-3 d-flex flex-column"
                                        style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.12)';">
                                        <div class="book-card-img overflow-hidden rounded mb-3" style="height: 260px;">
                                            <a href="{{ route('book.show', $book->slug) }}">
                                                <img class="img-fluid w-100 h-100" src="{{ $book->getThumbnail() }}"
                                                    alt="{{ $book->title }}" style="object-fit: cover;">
                                            </a>
                                        </div>

                                        <h6 class="mb-2" style="line-height: 1.4;">
                                            <a href="{{ route('book.show', $book->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($book->title, 55) }}
                                            </a>
                                        </h6>

                                        <div class="mt-auto">
                                            <p class="mb-0 theme-color fw-bold">
                                                Rp {{ number_format($book->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info" role="alert">
                                        Tidak ada buku yang ditemukan.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        <div class="row">
                            <div class="col-12 text-center">
                                {{ $books->links('pagination::bootstrap-4') }}
                            </div>
                        </div>

                    </div>
                </div> <!-- END BOOK LISTING WRAPPER -->

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-3">

                    <!-- SEARCH FIELD -->
                    <div id="search-field" class="sidebar-div ico-20 mb-50">
                        <div class="input-group mb-3">
                            <form action="" method="GET" class="d-flex w-100">
                                <input type="text" class="form-control" placeholder="Cari Buku..." aria-label="Search"
                                    value="{{ request('q') }}" name="q" aria-describedby="search-field">
                                <div class="input-group-append">
                                    <button class="btn" type="submit">
                                        <span class="flaticon-magnifying-glass"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- BOOK CATEGORIES -->
                    <div class="blog-categories sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Kategori</h6>

                        <ul class="blog-category-list clearfix">
                            <li>
                                <p><a href="{{ route('book.index') }}" @if (!request()->routeIs('book.category')) class="theme-color" @endif>
                                    Semua
                                </a>
                                <span>({{ \App\Models\Book::where('status', 'published')->count() }})</span>
                                </p>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <p><a href="{{ route('book.category', $category->slug) }}">
                                        {{ $category->name }}
                                    </a>
                                    <span>({{ $category->books_count }})</span>
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection
