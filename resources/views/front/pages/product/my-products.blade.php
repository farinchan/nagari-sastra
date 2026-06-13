@extends('front.app')

@section('content')

    <!-- BREADCRUMB
    ============================================= -->
    @include('front.partials.breadcrumb', ['title' => 'Produk Saya', 'breadcrumbs' => $breadcrumbs])

    <!-- MY PRODUCTS
    ============================================= -->
    <section id="my-products" class="wide-60 blog-page-section division">
        <div class="container">

            <!-- Success Alert -->
            @if (request('payment') == 'success')
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="alert alert-success alert-dismissible fade show mb-40" role="alert">
                            <strong>Pembayaran Berhasil!</strong> Terima kasih, produk Anda sudah tersedia untuk diunduh.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($products->count() > 0)
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-6 col-lg-4 mb-40">
                            <div class="blog-post">

                                <!-- Product Image -->
                                <div class="blog-post-img">
                                    <img class="img-fluid" src="{{ asset($product->thumbnail ?? 'images/default-product.png') }}" alt="{{ $product->name }}" style="width: 100%; height: 260px; object-fit: cover;">
                                </div>

                                <!-- Product Content -->
                                <div class="blog-post-txt">

                                    <!-- Category Tag -->
                                    @if ($product->category)
                                        <span class="post-tag txt-upcase theme-color">{{ $product->category->name }}</span>
                                    @endif

                                    <!-- Product Name -->
                                    <h6 class="h6-xl mb-10">{{ $product->name }}</h6>

                                    <!-- Version Badge -->
                                    @if ($product->version)
                                        <span class="badge badge-secondary mb-15">v{{ $product->version }}</span>
                                    @endif

                                    <!-- Download Button -->
                                    <a href="{{ route('product.download', $product->slug) }}" class="btn btn-success btn-block">
                                        <span class="flaticon-download mr-2"></span>Download
                                    </a>

                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- End row -->
            @else
                <!-- Empty State -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="alert alert-info text-center py-4">
                            <h6 class="h6-xl mb-10">Belum Ada Produk</h6>
                            <p class="p-md grey-color mb-15">Anda belum memiliki produk yang dibeli. Jelajahi toko kami untuk menemukan produk yang Anda butuhkan.</p>
                            <a href="{{ route('product.index') }}" class="btn btn-theme">
                                Lihat Produk
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <!-- End container -->
    </section>
    <!-- END MY PRODUCTS -->

    <!-- NEWSLETTER
    ============================================= -->
    @include('front.partials.newsletter')

@endsection
