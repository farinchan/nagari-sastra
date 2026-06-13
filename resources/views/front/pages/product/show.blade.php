@extends('front.app')
@section('seo')
    <meta name="description" content="{{ $product->short_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta name="keywords" content="{{ collect($product->tags ?? [])->map(fn($t) => is_array($t) ? ($t['value'] ?? '') : $t)->filter()->implode(', ') }}">
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ $product->short_description ?? '' }}">
    <meta property="og:image" content="{{ $product->getThumbnail() }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ route('product.show', $product->slug) }}">
@endsection

@section('content')
    <!-- PRODUCT DETAIL
    ============================================= -->
    <section id="product-detail" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">

                <!-- MAIN CONTENT -->
                <div class="col-lg-8">
                    <div class="posts-wrapper pr-25">

                        <!-- PRODUCT HEADER -->
                        <div class="row mb-40">
                            <div class="col-md-5 mb-30">
                                {{-- Main Image with Lightbox --}}
                                <div class="radius-06 overflow-hidden">
                                    <a href="{{ $product->getThumbnail() }}" class="image-link" data-mfp-src="{{ $product->getThumbnail() }}" title="{{ $product->name }}">
                                        <img id="main-product-image" src="{{ $product->getThumbnail() }}" alt="{{ $product->name }}"
                                             class="img-fluid w-100" style="min-height: 300px; object-fit: cover; cursor: zoom-in;">
                                    </a>
                                </div>

                                {{-- Screenshot Gallery Thumbnails --}}
                                @if($product->screenshots && $product->screenshots->count() > 0)
                                    <div class="product-gallery d-flex mt-15" style="gap: 8px; overflow-x: auto; scrollbar-width: thin; padding-bottom: 5px;">
                                        <a href="{{ $product->getThumbnail() }}" class="gallery-item radius-04 overflow-hidden active-thumb"
                                           style="width: 65px; height: 50px; flex-shrink: 0; border: 2px solid #007bff; display: block;"
                                           title="{{ $product->name }}">
                                            <img src="{{ $product->getThumbnail() }}" alt="Thumbnail"
                                                 class="w-100 h-100" style="object-fit: cover;">
                                        </a>
                                        @foreach($product->screenshots as $screenshot)
                                            <a href="{{ $screenshot->getImage() }}" class="gallery-item radius-04 overflow-hidden"
                                               style="width: 65px; height: 50px; flex-shrink: 0; border: 2px solid transparent; display: block;"
                                               title="{{ $screenshot->caption ?? 'Screenshot ' . $loop->iteration }}">
                                                <img src="{{ $screenshot->getImage() }}" alt="Screenshot {{ $loop->iteration }}"
                                                     class="w-100 h-100" style="object-fit: cover;">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <p class="post-tag txt-upcase mb-10">
                                    <a class="theme-color">{{ $product->category->name ?? '' }}</a>
                                </p>

                                <h2 class="h4-lg mb-10">{{ $product->name }}</h2>

                                @if($product->short_description)
                                    <p class="p-md grey-color mb-20">{{ $product->short_description }}</p>
                                @endif

                                <!-- INFO BADGES -->
                                <div class="mb-20">
                                    @if($product->version)
                                        <span class="badge badge-light p-2 mr-1 mb-1">
                                            <span class="flaticon-settings mr-1"></span> v{{ $product->version }}
                                        </span>
                                    @endif
                                    @if($product->compatibility)
                                        <span class="badge badge-light p-2 mr-1 mb-1">
                                            <span class="flaticon-check mr-1"></span> {{ $product->compatibility }}
                                        </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge badge-warning p-2 mr-1 mb-1">★ Featured</span>
                                    @endif
                                </div>

                                <!-- PRICE BOX -->
                                <div class="bg-lightgrey p-3 radius-04 mb-20">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="grey-color d-block mb-1">Harga</small>
                                            @if($product->discount_price && $product->discount_price > 0)
                                                <small class="grey-color" style="text-decoration: line-through;">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </small>
                                                <h3 class="h3-lg mb-0 theme-color">
                                                    Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                                </h3>
                                                <span class="badge badge-danger mt-1">Hemat {{ $product->getDiscountPercentage() }}%</span>
                                            @else
                                                <h3 class="h3-lg mb-0 theme-color">
                                                    @if($product->price > 0)
                                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                                    @else
                                                        Gratis
                                                    @endif
                                                </h3>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            @php $avgRating = $product->getAverageRating(); @endphp
                                            @if($avgRating > 0)
                                                <small class="grey-color d-block mb-1">Rating</small>
                                                <span style="color: #f5a623;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= round($avgRating))★@else☆@endif
                                                    @endfor
                                                </span>
                                                <small class="grey-color d-block">{{ number_format($avgRating, 1) }} ({{ $product->reviews->count() }})</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- STATS -->
                                <div class="row">
                                    <div class="col-6">
                                        <small class="grey-color d-block">
                                            <span class="flaticon-eye mr-1"></span> Views
                                        </small>
                                        <p class="p-sm txt-500 mb-0">{{ number_format($product->view_count) }}</p>
                                    </div>
                                    <div class="col-6">
                                        <small class="grey-color d-block">
                                            <span class="flaticon-download mr-1"></span> Downloads
                                        </small>
                                        <p class="p-sm txt-500 mb-0">{{ number_format($product->download_count) }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- DESCRIPTION -->
                        @if($product->description)
                            <div class="mb-40">
                                <h5 class="h5-md mb-20">Deskripsi Produk</h5>
                                <div class="post-txt">
                                    {!! $product->description !!}
                                </div>
                            </div>
                        @endif

                        <!-- TAGS -->
                        @php
                            $parsedTags = collect($product->tags ?? [])->map(function($tag) {
                                if (is_array($tag) && isset($tag['value'])) return $tag['value'];
                                if (is_string($tag)) return $tag;
                                return null;
                            })->filter()->values();
                        @endphp
                        @if($parsedTags->count() > 0)
                            <div class="mb-40">
                                <h5 class="h5-md mb-20">Tags</h5>
                                <div>
                                    @foreach($parsedTags as $tag)
                                        <span class="badge badge-light mr-1 mb-2 p-2">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- REVIEWS -->
                        <div class="mb-40">
                            <h5 class="h5-md mb-20">Review ({{ $product->reviews->where('is_approved', true)->count() }})</h5>

                            @forelse($product->reviews->where('is_approved', true) as $review)
                                <div class="d-flex mb-20 {{ !$loop->last ? 'pb-20 b-bottom' : '' }}">
                                    <div class="mr-15" style="flex-shrink: 0;">
                                        <div style="width: 45px; height: 45px; background: #007bff; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px;">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <h6 class="h6-xs mb-0">{{ $review->user->name ?? 'Pengguna' }}</h6>
                                            <small class="grey-color ml-10">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="mb-5" style="color: #f5a623;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)★@else☆@endif
                                            @endfor
                                        </div>
                                        @if($review->comment)
                                            <p class="p-sm grey-color mb-0">{{ $review->comment }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="grey-color">Belum ada review untuk produk ini.</p>
                            @endforelse

                            <!-- REVIEW FORM -->
                            @auth
                                @if($has_purchased && !$has_reviewed)
                                    <div class="mt-20 pt-20 b-top">
                                        <h6 class="h6-xl mb-15">Tulis Review</h6>
                                        <form action="{{ route('product.review', $product->slug) }}" method="POST">
                                            @csrf
                                            <div class="mb-15">
                                                <label class="grey-color mb-5 d-block">Rating</label>
                                                <div class="star-rating">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                                        <label for="star{{ $i }}">★</label>
                                                    @endfor
                                                </div>
                                                @error('rating') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <div class="mb-15">
                                                <label class="grey-color mb-5 d-block">Komentar (opsional)</label>
                                                <textarea name="comment" class="form-control" rows="3" maxlength="1000" placeholder="Tulis komentar Anda...">{{ old('comment') }}</textarea>
                                                @error('comment') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <button type="submit" class="btn btn-theme btn-sm">Kirim Review</button>
                                        </form>
                                    </div>
                                @elseif($has_reviewed)
                                    <p class="grey-color mt-15">✔ Anda sudah memberikan review.</p>
                                @elseif(!$has_purchased)
                                    <p class="grey-color mt-15">Beli produk ini untuk memberikan review.</p>
                                @endif
                            @else
                                <p class="mt-15">
                                    <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-outline-secondary btn-sm">
                                        Login untuk menulis review
                                    </a>
                                </p>
                            @endauth
                        </div>

                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-4">

                    <!-- PURCHASE CARD -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Beli Produk</h6>

                        <!-- Price Display -->
                        <div class="bg-lightgrey p-3 radius-04 mb-20 text-center">
                            @if($product->discount_price && $product->discount_price > 0)
                                <small class="grey-color" style="text-decoration: line-through;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </small>
                                <h3 class="h3-lg mb-0 theme-color">
                                    Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                </h3>
                            @else
                                <h3 class="h3-lg mb-0 theme-color">
                                    @if($product->price > 0)
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @else
                                        Gratis
                                    @endif
                                </h3>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @auth
                            @if($has_purchased)
                                <a href="{{ route('product.download', $product->slug) }}" class="btn btn-success btn-block mb-10">
                                    <span class="flaticon-download mr-2"></span> Download
                                </a>
                                <a href="{{ route('product.my-products') }}" class="btn btn-outline-secondary btn-block">
                                    Produk Saya
                                </a>
                            @else
                                <form action="{{ route('product.checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-theme btn-block">
                                        Beli Sekarang
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="btn btn-theme btn-block">
                                Login untuk Membeli
                            </a>
                        @endauth
                    </div>

                    <!-- PRODUCT INFO -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Informasi Produk</h6>

                        <ul class="blog-category-list clearfix">
                            <li>
                                <p><span class="grey-color">Kategori</span>
                                <span class="theme-color" style="float: right;">{{ $product->category->name ?? '-' }}</span></p>
                            </li>
                            @if($product->version)
                                <li>
                                    <p><span class="grey-color">Versi</span>
                                    <span style="float: right;">{{ $product->version }}</span></p>
                                </li>
                            @endif
                            @if($product->compatibility)
                                <li>
                                    <p><span class="grey-color">Kompatibilitas</span>
                                    <span style="float: right;">{{ $product->compatibility }}</span></p>
                                </li>
                            @endif
                            <li>
                                <p><span class="grey-color">Downloads</span>
                                <span style="float: right;">{{ number_format($product->download_count) }}</span></p>
                            </li>
                            <li>
                                <p><span class="grey-color">Diperbarui</span>
                                <span style="float: right;">{{ $product->updated_at->translatedFormat('d M Y') }}</span></p>
                            </li>
                        </ul>
                    </div>

                    {{-- DEMO & DOCUMENTATION LINKS --}}
                    @if($product->demo_url || $product->documentation_url)
                        <div class="sidebar-div mb-50">
                            <h6 class="h6-xl">Link Tambahan</h6>
                            @if($product->demo_url)
                                <a href="{{ $product->demo_url }}" target="_blank" rel="noopener"
                                   class="btn btn-tra-grey tra-grey-hover btn-block mb-10" style="text-align: left;">
                                    <span class="flaticon-visibility mr-2"></span> Lihat Demo
                                </a>
                            @endif
                            @if($product->documentation_url)
                                <a href="{{ $product->documentation_url }}" target="_blank" rel="noopener"
                                   class="btn btn-tra-grey tra-grey-hover btn-block mb-10" style="text-align: left;">
                                    <span class="flaticon-document mr-2"></span> Dokumentasi
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- RELATED PRODUCTS -->
                    @if($related_products->count() > 0)
                        <div class="sidebar-div mb-50">
                            <h6 class="h6-xl">Produk Terkait</h6>

                            @foreach($related_products as $related)
                                <div class="d-flex align-items-center mb-20 {{ !$loop->last ? 'pb-20 b-bottom' : '' }}">
                                    <div class="mr-15" style="width: 65px; height: 65px; overflow: hidden; border-radius: 4px; flex-shrink: 0;">
                                        <a href="{{ route('product.show', $related->slug) }}">
                                            <img src="{{ $related->getThumbnail() }}" alt="{{ $related->name }}"
                                                 class="w-100 h-100" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    <div>
                                        <h6 class="h6-xs mb-1" style="line-height: 1.3;">
                                            <a href="{{ route('product.show', $related->slug) }}">
                                                {{ Str::limit($related->name, 40) }}
                                            </a>
                                        </h6>
                                        <p class="p-sm grey-color mb-0">
                                            @if($related->getEffectivePrice() > 0)
                                                Rp {{ number_format($related->getEffectivePrice(), 0, ',', '.') }}
                                            @else
                                                Gratis
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </aside>

            </div>
        </div>
    </section>

@endsection

@push('styles')
<style>
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 2px; }
    .star-rating input { display: none; }
    .star-rating label { cursor: pointer; font-size: 24px; color: #ddd; transition: color 0.2s; }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #f5a623; }
    .post-txt img { max-width: 100%; height: auto; }
    .gallery-item { transition: border-color 0.2s ease, transform 0.2s ease; }
    .gallery-item:hover { border-color: #007bff !important; transform: scale(1.05); }
    .gallery-item.active-thumb { border-color: #007bff !important; }
    #main-product-image { transition: opacity 0.3s ease; }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Main image lightbox - klik gambar besar
        $('.image-link').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            },
            image: {
                titleSrc: 'title'
            },
            zoom: {
                enabled: true,
                duration: 300
            }
        });

        // Gallery thumbnails - klik untuk ganti gambar utama + buka lightbox
        $('.product-gallery').magnificPopup({
            delegate: '.gallery-item',
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                titleSrc: 'title'
            },
            zoom: {
                enabled: true,
                duration: 300
            }
        });

        // Klik thumbnail = ganti gambar utama + highlight border
        $('.gallery-item').on('click', function(e) {
            var newSrc = $(this).attr('href');
            var mainImg = $('#main-product-image');
            // Update main image
            mainImg.css('opacity', 0.5);
            mainImg.attr('src', newSrc);
            mainImg.on('load', function() {
                $(this).css('opacity', 1);
            });
            // Update lightbox link
            $('.image-link').attr('href', newSrc).attr('data-mfp-src', newSrc);
            // Active border
            $('.gallery-item').css('border-color', 'transparent');
            $(this).css('border-color', '#007bff');
        });
    });
</script>
@endpush
