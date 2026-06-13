@extends('front.app')

@section('content')

    <!-- PRODUCT LISTING
        ============================================= -->
    <section id="product-listing" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">

                <!-- PRODUCT LISTING WRAPPER -->
                <div class="col-lg-9">
                    <div class="posts-wrapper pr-25">

                        <div class="row">
                            @forelse ($products as $product)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="book-card h-100 rounded shadow-sm overflow-hidden p-3 d-flex flex-column"
                                        style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.15)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.12)';">
                                        <div class="book-card-img overflow-hidden rounded mb-3" style="height: 260px;">
                                            <a href="{{ route('product.show', $product->slug) }}">
                                                <img class="img-fluid w-100 h-100" src="{{ $product->getThumbnail() }}"
                                                    alt="{{ $product->name }}" style="object-fit: cover;">
                                            </a>
                                        </div>

                                        <p class="post-tag txt-upcase mb-10">
                                            <a class="theme-color">{{ $product->category->name ?? '-' }}</a>
                                        </p>

                                        <h6 class="mb-2" style="line-height: 1.4;">
                                            <a href="{{ route('product.show', $product->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($product->name, 55) }}
                                            </a>
                                        </h6>

                                        <p class="grey-color mb-2" style="font-size: 0.85rem;">
                                            {{ Str::limit($product->short_description, 70) }}
                                        </p>

                                        @if($product->version)
                                            <span class="post-tag txt-upcase mb-10" style="font-size: 10px;">
                                                v{{ $product->version }}
                                            </span>
                                        @endif

                                        <div class="mt-auto">
                                            @if($product->discount_price && $product->discount_price > 0)
                                                <p class="mb-0 grey-color" style="font-size: 0.85rem;">
                                                    <span style="text-decoration: line-through;">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                </p>
                                                <p class="mb-0 theme-color fw-bold">
                                                    Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                                </p>
                                            @else
                                                <p class="mb-0 theme-color fw-bold">
                                                    @if($product->price > 0)
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    @else
                                                        Gratis
                                                    @endif
                                                </p>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                            <small class="grey-color">
                                                <span class="flaticon-eye" style="font-size: 12px;"></span> {{ $product->view_count }}
                                            </small>
                                            <small class="grey-color">
                                                <span class="flaticon-download" style="font-size: 12px;"></span> {{ $product->download_count }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info" role="alert">
                                        Tidak ada produk yang ditemukan.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        <div class="row">
                            <div class="col-12 text-center">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div>
                        </div>

                    </div>
                </div> <!-- END PRODUCT LISTING WRAPPER -->

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-3">

                    <!-- SEARCH FIELD -->
                    <div id="search-field" class="sidebar-div ico-20 mb-50">
                        <div class="input-group mb-3">
                            <form action="{{ route('product.index') }}" method="GET" class="d-flex w-100">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                <input type="text" class="form-control" placeholder="Cari Produk..." aria-label="Search"
                                    value="{{ request('q') }}" name="q" aria-describedby="search-field">
                                <div class="input-group-append">
                                    <button class="btn" type="submit">
                                        <span class="flaticon-magnifying-glass"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- PRODUCT CATEGORIES -->
                    <div class="blog-categories sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Kategori</h6>

                        <ul class="blog-category-list clearfix">
                            <li>
                                <p><a href="{{ route('product.index', array_filter(['q' => request('q'), 'sort' => request('sort')])) }}" @if(!request('category')) class="theme-color" @endif>
                                    Semua Produk
                                </a>
                                <span>({{ $products->total() }})</span>
                                </p>
                            </li>
                            @foreach ($categories as $category)
                                <li>
                                    <p><a href="{{ route('product.index', array_filter(['category' => $category->slug, 'q' => request('q'), 'sort' => request('sort')])) }}" @if(request('category') == $category->slug) class="theme-color" @endif>
                                        {{ $category->name }}
                                    </a>
                                    <span>({{ $category->products_count }})</span>
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- SORT FILTER -->
                    <div class="blog-categories sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Urutkan</h6>

                        <ul class="blog-category-list clearfix">
                            <li>
                                <p><a href="{{ route('product.index', array_filter(['category' => request('category'), 'q' => request('q'), 'sort' => 'newest'])) }}" @if(request('sort', 'newest') == 'newest') class="theme-color" @endif>
                                    Terbaru
                                </a></p>
                            </li>
                            <li>
                                <p><a href="{{ route('product.index', array_filter(['category' => request('category'), 'q' => request('q'), 'sort' => 'price_low'])) }}" @if(request('sort') == 'price_low') class="theme-color" @endif>
                                    Harga Terendah
                                </a></p>
                            </li>
                            <li>
                                <p><a href="{{ route('product.index', array_filter(['category' => request('category'), 'q' => request('q'), 'sort' => 'price_high'])) }}" @if(request('sort') == 'price_high') class="theme-color" @endif>
                                    Harga Tertinggi
                                </a></p>
                            </li>
                            <li>
                                <p><a href="{{ route('product.index', array_filter(['category' => request('category'), 'q' => request('q'), 'sort' => 'popular'])) }}" @if(request('sort') == 'popular') class="theme-color" @endif>
                                    Terpopuler
                                </a></p>
                            </li>
                        </ul>
                    </div>

                </aside>

            </div>
        </div>
    </section>
@include('front.partials.newsletter')
@endsection
