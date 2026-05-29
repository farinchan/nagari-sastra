@extends('front.app')
@section('seo')
    <title>{{ $meta['description'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Nagari Sastra">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
    <link rel="canonical" href="{{ route('home') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')
    <!-- HERO-4
                                           ============================================= -->
    <section id="hero-4" class="hero-section division">
        <div class="container">


            <!-- HERO TEXT -->
            <div class="row align-items-end">

                <!-- TITLE -->
                <div class="col-lg-7">
                    <div class="hero-4-title">

                        <!-- Section ID -->
                        <div class="section-id grey-color">Tentang Kami</div>

                        <!-- Title -->
                        <h2 class="h2-xl deepgrey-color">Publikasikan Karya Ilmiah dan Penelitian</h2>

                    </div>
                </div>

                <!-- TEXT -->
                <div class="col-lg-5">
                    <div class="hero-4-txt pc-25">
                        <p class="p-lg grey-color">
                            Kami adalah unit dari Torkata Tech solution yang berfokus pada publikasi ilmiah, penelitian
                            terapan, serta pelatihan dan edukasi di berbagai bidang ilmu pengetahuan dan teknologi untuk
                            mendukung kemajuan ilmu pengetahuan dan inovasi di Indonesia.
                        </p>
                    </div>
                </div>

            </div> <!-- END HERO TEXT -->


            <!-- HERO IMAGES -->
            <div class="hero-4-images">
                <div class="row">

                    <!-- IMAGE-1 -->
                    <div id="img-4-1" class="col-md-6 col-lg wow fadeInUp" data-wow-delay="0.4s">
                        <img class="img-fluid radius-06" src="{{ asset('front/images/hero-4-1.jpg') }}" alt="hero-image">
                    </div>

                    <!-- IMAGE-2 -->
                    <div id="img-4-2" class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0.8s">
                        <img class="img-fluid radius-06" src="{{ asset('front/images/hero-4-2.jpg') }}" alt="hero-image">
                    </div>

                    <!-- IMAGE-3 -->
                    <div id="img-4-3" class="col-md-6 col-lg wow fadeInUp" data-wow-delay="1.2s">
                        <img class="img-fluid radius-06" src="{{ asset('front/images/hero-4-3.jpg') }}" alt="hero-image">
                    </div>

                    <!-- IMAGE-4 -->
                    <div id="img-4-4" class="col-md-6 col-lg wow fadeInUp" data-wow-delay="1.6s">
                        <img class="img-fluid radius-06" src="{{ asset('front/images/hero-4-4.jpg') }}" alt="hero-image">
                    </div>

                </div> <!-- End row -->
            </div> <!-- END HERO IMAGES -->


        </div> <!-- End container -->
    </section>
    <!-- END HERO-4 -->


    <!-- FEATURES-2
                                           ============================================= -->
    <section id="features-2" class="wide-60 features-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-80">

                        <!-- Text -->
                        <p class="p-xl">
                            berikut adalah beberapa layanan yang kami sediakan untuk mendukung kebutuhan penelitian dan
                            pengembangan ilmu pengetahuan Anda.
                        </p>

                    </div>
                </div>
            </div>


            <!-- FEATURES-2 WRAPPER -->
            <div class="fbox-2-wrapper">
                <div class="row">

                    <!-- FEATURE BOX #1 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="fbox-2 mb-40 wow fadeInUp" data-wow-delay="1s">

                            <!-- Icon -->
                            <div class="fbox-ico ico-65 grey-color"><span class="flaticon-monitor"></span></div>

                            <!-- Text -->
                            <div class="fbox-txt">
                                <h5 class="h5-xs">Penerbitan</h5>
                                <p class="p-md grey-color">
                                    Layanan penerbitan buku ilmiah, monograf, buku ajar, dan prosiding berkualitas tinggi ber-ISBN.
                                </p>
                            </div>

                        </div>
                    </div>


                    <!-- FEATURE BOX #2 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="fbox-2 mb-40 wow fadeInUp" data-wow-delay="1.2s">

                            <!-- Icon -->
                            <div class="fbox-ico ico-65 grey-color"><span class="flaticon-language"></span></div>

                            <!-- Text -->
                            <div class="fbox-txt">
                                <h5 class="h5-xs">Publikasi</h5>
                                <p class="p-md grey-color">
                                    Bimbingan dan pendampingan publikasi artikel ilmiah pada jurnal nasional terakreditasi maupun internasional bereputasi.
                                </p>
                            </div>

                        </div>
                    </div>


                    <!-- FEATURE BOX #3 -->
                    <div class="col-md-6 col-lg-4">
                        <div class="fbox-2 mb-40 wow fadeInUp" data-wow-delay="1.4s">

                            <!-- Icon -->
                            <div class="fbox-ico ico-65 grey-color"><span class="flaticon-help"></span></div>

                            <!-- Text -->
                            <div class="fbox-txt">
                                <h5 class="h5-xs">Teknologi</h5>
                                <p class="p-md grey-color">
                                    Solusi teknologi informasi, sistem digital terintegrasi, dan platform riset modern untuk kemajuan inovasi Anda.
                                </p>
                            </div>

                        </div>
                    </div>


                </div> <!-- End row -->
            </div> <!-- END FEATURES-2 WRAPPER -->


        </div> <!-- End container -->
    </section> <!-- END FEATURES-2 -->



    <!-- BLOG-1
                                           ============================================= -->
    <section id="blog-1" class="bg-lightgrey wide-60 reviews-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">

                        <!-- Title 	-->
                        <h2 class="h2-xs">
                            Berita Terbaru
                        </h2>

                        <!-- Text -->
                        <p class="p-xl">
                            Temukan berita dan artikel terbaru seputar penelitian, publikasi, pengembangan ilmu pengetahuan
                            dan lainnya disini
                        </p>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <!-- BLOG POSTS -->
                    <div class=" owl-carousel owl-theme reviews-wrapper">


                        @foreach ($list_news as $news)
                            <div class="mr-3">
                                <div id="bp-1-1" class="blog-1-post wow fadeInUp" data-wow-delay="0.4s">

                                    <!-- BLOG POST IMAGE -->
                                    <div class="blog-post-img rel">
                                        <div class="hover-overlay">
                                            <img class="img-fluid" src="{{ $news->getThumbnail() }}"
                                                alt="blog-post-image" />
                                            <div class="item-overlay"></div>
                                        </div>
                                    </div>

                                    <!-- BLOG POST TEXT -->
                                    <div class="blog-post-txt">

                                        <!-- Post Tag -->
                                        <p class="p-sm post-tag txt-upcase"><a href="#">{{ $news->category->name }}
                                        </p>

                                        <!-- Post Title -->
                                        <h5 class="h5-xs">{{ $news->title }}</h5>

                                        <!-- Author Data -->
                                        <div class="post-author">
                                            <span>{{ $news->created_at->format('M d, Y') }}</span>
                                            <span>By {{ $news->user->name }}</span>
                                        </div>

                                        <!-- Post Link -->
                                        <div class="post-link ico-20">
                                            <a href="single-post.html"><span class="flaticon-right-arrow"></span></a>
                                        </div>

                                    </div> <!-- END BLOG POST TEXT -->

                                </div>
                            </div>
                        @endforeach



                    </div> <!-- END BLOG POSTS -->

                </div>
            </div>

        </div> <!-- End container -->
    </section>
    <!-- END BLOG-1 -->






    <!-- STATISTIC-4
                                           ============================================= -->
    <section id="statistic-4" class="bg-07 statistic-section division">
        <div class="container white-color">
            <div class="row">


                <!-- STATISTIC BLOCK #1 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="0.4s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-book"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number"><span class="count-element">{{ $count_book ?: 0 }}</span></h3>
                        <p class="p-md txt-400">Buku Terbit</p>

                    </div>
                </div>


                <!-- STATISTIC BLOCK #2 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="0.6s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-files"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number"><span class="count-element">{{ $count_submission_published ?: 0 }}</span></h3>
                        <p class="p-md txt-400">Artikel Terbit</p>
                    </div>
                </div>


                <!-- STATISTIC BLOCK #3 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="0.8s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-browser"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number"><span class="count-element">{{ $count_journal ?: 0 }}</span></h3>
                        <p class="p-md txt-400">Jurnal Terkelola</p>

                    </div>
                </div>


                <!-- STATISTIC BLOCK #4 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="1s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-monitor"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number"><span class="count-element">3</span></h3>
                        <p class="p-md txt-400">Klien Teknologi</p>

                    </div>
                </div>


            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END STATISTIC-4 -->


    <!-- BOOKS SECTION
    ============================================= -->
    <style>
        .books-scroll-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 24px;
            padding: 15px 5px 30px 5px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .books-scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .books-scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .books-scroll-container::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
            transition: background 0.3s;
        }
        .books-scroll-container::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
        .book-card {
            width: 260px;
            min-width: 260px;
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
            border-color: #d1d1d1;
        }
        .book-img-wrapper {
            position: relative;
            height: 340px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .book-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .book-card:hover .book-img {
            transform: scale(1.05);
        }
        .book-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .book-price-badge {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: #007bff;
            color: #fff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .book-price-free {
            background: #28a745;
        }
        .book-info {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .book-title {
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.4;
            margin-bottom: 8px;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 40px;
        }
        .book-author {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-btn {
            margin-top: auto;
            width: 100%;
            padding: 8px 16px;
            background: #f1f3f5;
            color: #495057;
            text-align: center;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .book-card:hover .book-btn {
            background: #007bff;
            color: #ffffff;
        }
        .book-scroll-nav {
            display: flex;
            gap: 10px;
        }
        .scroll-nav-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .scroll-nav-btn:hover {
            background: #007bff;
            border-color: #007bff;
            color: #fff;
        }
    </style>

    <section id="books-latest" class="wide-60 division bg-lightgrey">
        <div class="container">
            <!-- SECTION TITLE -->
            <div class="row align-items-center mb-40">
                <div class="col-md-8">
                    <div class="section-title text-left">
                        <div class="section-id grey-color">Koleksi Buku</div>
                        <h3 class="h3-sm">Buku Terbaru</h3>
                        <p class="p-lg grey-color">Jelajahi berbagai buku berkualitas dari para penulis.</p>
                    </div>
                </div>
                @if(!$list_book->isEmpty())
                <div class="col-md-4 text-right d-none d-md-flex align-items-center justify-content-end">
                    <div class="book-scroll-nav mr-3">
                        <button id="slide-left-btn" class="scroll-nav-btn"><span class="flaticon-left-arrow"></span></button>
                        <button id="slide-right-btn" class="scroll-nav-btn"><span class="flaticon-right-arrow"></span></button>
                    </div>
                    <a href="{{ route('book.index') }}" class="btn btn-tra-grey theme-hover btn-sm">Semua Buku</a>
                </div>
                @endif
            </div>

            <!-- SCROLLABLE CONTAINER / EMPTY STATE -->
            @if($list_book->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-4">
                        <div class="no-books-box p-5" style="background: #ffffff; border: 1px dashed #ddd; border-radius: 16px; max-width: 550px; margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                            <div class="ico-55 mb-20" style="color: #bbb;"><span class="flaticon-book"></span></div>
                            <h5 class="h5-xs" style="color: #444; font-weight: 700; margin-bottom: 10px;">Belum Ada Koleksi Buku</h5>
                            <p class="p-md grey-color">Saat ini belum ada buku yang diterbitkan. Buku terbaru yang terbit akan tampil di sini.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="books-scroll-container" id="books-scroll">
                    @foreach($list_book as $book)
                        <div class="book-card">
                            <div class="book-img-wrapper">
                                <span class="book-badge">{{ $book->category->name ?? 'Buku' }}</span>
                                <img class="book-img" src="{{ $book->getThumbnail() }}" alt="{{ $book->title }}">
                                @if($book->price == 0)
                                    <span class="book-price-badge book-price-free">Gratis</span>
                                @else
                                    <span class="book-price-badge">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <div class="book-info">
                                <h5 class="book-title">{{ $book->title }}</h5>
                                <p class="book-author">{{ $book->author ?? 'Penulis' }}</p>
                                <a href="{{ route('book.show', $book->slug) }}" class="book-btn">Detail Buku</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- MOBILE ALL BOOKS LINK -->
                <div class="row d-md-none mt-3">
                    <div class="col text-center">
                        <a href="{{ route('book.index') }}" class="btn btn-tra-grey theme-hover btn-sm">Semua Buku</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
 
    <script>
        if (document.getElementById('slide-left-btn')) {
            document.getElementById('slide-left-btn').addEventListener('click', function() {
                document.getElementById('books-scroll').scrollBy({ left: -300, behavior: 'smooth' });
            });
        }
        if (document.getElementById('slide-right-btn')) {
            document.getElementById('slide-right-btn').addEventListener('click', function() {
                document.getElementById('books-scroll').scrollBy({ left: 300, behavior: 'smooth' });
            });
        }
    </script>

    <!-- FEATURES-10
                                       ============================================= -->
    <section id="features-10" class="wide-40 features-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-md-11 col-lg-9 col-xl-8">
                    <div class="section-title">

                        <!-- Section ID -->
                        <div class="section-id grey-color">Jurnal Kami</div>

                        <!-- Title -->
                        <h4 class="h4-md">
                            Kami mengelola beberapa jurnal yang mungkin cocok untuk anda
                    </div>
                </div>
            </div>


            <!-- FEATURES-10 WRAPPER -->
            <div class="fbox-10-wrapper">
                <div class="row">

                    @foreach ($list_journal as $journal)
                        <!-- FEATURE BOX #{{ $loop->iteration }} -->
                        <div class="col-md-4 col-lg-4">
                            <div id="fb-10-{{ $loop->iteration }}" class="fbox-10 pc-10 mb-40 wow fadeInUp"
                                data-wow-delay="{{ 0.2 + $loop->iteration * 0.2 }}s">

                                <!-- Image -->
                                <div class="fbox-img radius-04"><img class="img-fluid"
                                        src="{{ $journal->getJournalThumbnail() }}" alt="features-image"></div>

                                <!-- Text -->
                                <h5 class="h5-sm">{{ $journal->title }}</h5>
                                <p class="p-md grey-color">{{ Str::limit(strip_tags($journal->description), 100) }}</p>

                            </div>
                        </div>
                    @endforeach



                </div> <!-- End row -->
            </div> <!-- END FEATURES-10 WRAPPER -->


        </div> <!-- End container -->
    </section> <!-- END FEATURES-10 -->



    <!-- CONTENT-6
                                           ============================================= -->
    <section id="content-6" class="wide-60 content-section division">
        <div class="container">
            <div class="row d-flex align-items-center m-row">


                <!-- TEXT BLOCK -->
                <div class="col-md-7 col-lg-6 m-bottom">
                    <div class="txt-block left-column pc-30 mb-40 wow fadeInLeft" data-wow-delay="0.4s">

                        <!-- Section ID -->
                        <div class="section-id grey-color">Kenapa Memilih Kami</div>

                        <!-- Title -->
                        <h3 class="h3-sm">
                            Kami menyediakan layanan terbaik untuk kebutuhan penelitian dan pengembangan ilmu pengetahuan
                        </h3>

                        <!-- Text List -->
                        <ul class="simple-list grey-color">

                            <li class="list-item">
                                <p class="p-md">Tim profesional dan berpengalaman di bidang penelitian dan publikasi
                                    ilmiah.</p>
                            </li>
                            <li class="list-item">
                                <p class="p-md">Layanan lengkap mulai dari publikasi, penelitian, hingga pelatihan dan
                                    edukasi.</p>
                            </li>
                            <li class="list-item">
                                <p class="p-md">Jaringan luas dengan berbagai institusi dan jurnal bereputasi nasional
                                    maupun internasional.</p>
                            </li>

                        </ul> <!-- End Text List -->

                        <!--  Button -->
                        <a href="#faqs-1" class="btn btn-md btn-tra-grey theme-hover">Read The FAQs</a>

                    </div>
                </div> <!-- END TEXT BLOCK -->


                <!-- IMAGE BLOCK -->
                <div class="col-md-5 col-lg-6 m-top">
                    <div class="content-6-img right-column wow fadeInRight" data-wow-delay="0.4s">
                        <img class="img-fluid" src="{{ asset('front/images/tablet-4.png') }}" alt="content-image">
                    </div>
                </div>


            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END CONTENT-6 -->


    <!-- TESTIMONIALS-3
                                   ============================================= -->
    <section id="reviews-3" class="wide-100 reviews-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">

                        <!-- Title 	-->
                        <h2 class="h2-xs deepgrey-color">
                            Apa Kata Mereka
                        </h2>

                        <!-- Text -->
                        <p class="p-xl">
                            Berikut adalah beberapa testimoni dari klien kami yang telah menggunakan layanan kami untuk
                            kebutuhan mereka.
                        </p>

                    </div>
                </div>
            </div>


            <!-- TESTIMONIALS CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme reviews-wrapper">


                        <!-- TESTIMONIAL #1 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">Etiam sapien sem at sagittis congue an augue massa varius
                                    egestas undo suscipit magna tempus undo aliquet
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- Scott Boxer</h6>
                                <p class="p-sm">Manager</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #1 -->


                        <!-- TESTIMONIAL #2 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">At sagittis congue augue undo egestas magna ipsum vitae purus
                                    and ipsum primis suscipit
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star-half-empty"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- Wendy T.</h6>
                                <p class="p-sm">Manager</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #2 -->


                        <!-- TESTIMONIAL #3 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">Mauris donec ociis magnis and sapien etiam sapien congue undo
                                    augue pretium and ligula augue a lectus aenean magna
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- pebz13</h6>
                                <p class="p-sm">House Wife</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #3 -->


                        <!-- TESTIMONIAL #4 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">An augue in cubilia laoreet magna and suscipit egestas magna
                                    ipsum
                                    purus ipsum and suscipit
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star-1"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- Scott Boxer</h6>
                                <p class="p-sm">Manager</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #4 -->


                        <!-- TESTIMONIAL #5 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">Mauris donec magnis sapien undo etiam sapien and congue augue
                                    egestas ultrice a vitae purus velna integer tempor congue
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star-half-empty"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- John Sweet</h6>
                                <p class="p-sm">Manager</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #5 -->


                        <!-- TESTIMONIAL #6 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">An augue cubilia laoreet undo magna a suscipit undo egestas
                                    magna ipsum ligula vitae purus ipsum primis cubilia blandit
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- Leslie D.</h6>
                                <p class="p-sm">Manager</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #6 -->


                        <!-- TESTIMONIAL #7 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">Augue egestas volutpat and egestas augue in cubilia laoreet
                                    magna undo suscipit luctus
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star-half-empty"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- Marisol19</h6>
                                <p class="p-sm">Internet Surfer</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #7 -->


                        <!-- TESTIMONIAL #8 -->
                        <div class="review-3 radius-04">

                            <!-- Testimonial Text -->
                            <div class="review-3-txt">

                                <!-- Text -->
                                <p class="p-md grey-color">Aliquam augue suscipit luctus neque purus ipsum neque dolor
                                    primis libero tempus at blandit posuere varius magna
                                </p>

                                <!-- App Rating -->
                                <div class="app-rating ico-20 yellow-color">
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star"></span>
                                    <span class="flaticon-star-half-empty"></span>
                                </div>

                                <!-- Testimonial Author -->
                                <h6 class="h6-sm deepgrey-color">- AJ</h6>
                                <p class="p-sm">Programmer</p>

                            </div>

                        </div> <!-- END TESTIMONIAL #8 -->


                    </div>
                </div>
            </div> <!-- END TESTIMONIALS CONTENT -->


        </div> <!-- End container -->
    </section> <!-- END TESTIMONIALS-3 -->



    {{-- <!-- BRANDS-2
           ============================================= -->
    <section id="brands-2" class="wide-70 brands-section division">
        <div class="container">


            <!-- BRANDS TITLE -->
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="brands-title text-center">

                        <!-- Section ID -->
                        <div class="section-id grey-color">Our Clients</div>

                        <!-- Title -->
                        <h4 class="h4-xs">Trusted by thousands companies of all sizes all around the world</h4>

                    </div>
                </div>
            </div>


            <!-- BRANDS-2 WRAPPER -->
            <div class="brands-2-wrapper">
                <div class="row">
                    <div class="col-md-12">

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-1.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-2.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-3.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-4.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-5.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-6.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-7.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-8.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="{{ asset('front/images/brand-9.png') }}" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-10.png" alt="brand-logo" />
                            </a>
                        </div>

                    </div>
                </div>
            </div> <!-- END BRANDS-2 WRAPPER -->


        </div> <!-- End container -->
    </section> <!-- END BRANDS-2 -->
 --}}


    @include('front.partials.calll_to_action')
@endsection
@section('scripts')
    <script>
        $.ajax({
            url: "{{ route('visit.ajax') }}",
            type: "GET",
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    </script>
@endsection
