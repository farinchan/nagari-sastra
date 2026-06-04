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

    <style>
        .book-price-label {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 4px;
            z-index: 2;
        }
        .blog-1-post .h5-xs {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .announcement-list a:hover > div {
            border-color: #ccc !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        }
        .announcement-list a {
            text-decoration: none !important;
        }
    </style>
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
                        <h2 class="h2-xl deepgrey-color">Penerbitan, Publikasi & Teknologi</h2>

                    </div>
                </div>

                <!-- TEXT -->
                <div class="col-lg-5">
                    <div class="hero-4-txt pc-25">
                        <p class="p-lg grey-color">
                            Nagari Sastra Group merupakan perusahaan yang bergerak di bidang penerbitan buku ber-ISBN,
                            publikasi jurnal ilmiah nasional dan internasional, serta pengembangan teknologi informasi
                            untuk mendukung ekosistem riset dan pendidikan di Indonesia.
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
                        <img class="img-fluid radius-06" src="{{ asset('front/images/image1.jpeg') }}" alt="hero-image">
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
                                        <a href="{{ route('news.detail', $news->slug) }}">
                                            <div class="hover-overlay">
                                                <img class="img-fluid" src="{{ $news->getThumbnail() }}"
                                                    alt="blog-post-image" />
                                                <div class="item-overlay"></div>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- BLOG POST TEXT -->
                                    <div class="blog-post-txt">

                                        <!-- Post Tag -->
                                        <p class="p-sm post-tag txt-upcase">{{ $news->category->name }}</p>

                                        <!-- Post Title -->
                                        <h5 class="h5-xs">
                                            <a href="{{ route('news.detail', $news->slug) }}">{{ $news->title }}</a>
                                        </h5>

                                        <!-- Author Data -->
                                        <div class="post-author">
                                            <span>{{ $news->created_at->format('M d, Y') }}</span>
                                            <span>By {{ $news->user->name }}</span>
                                        </div>

                                        <!-- Post Link -->
                                        <div class="post-link ico-20">
                                            <a href="{{ route('news.detail', $news->slug) }}"><span class="flaticon-right-arrow"></span></a>
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


    <!-- EVENT & ANNOUNCEMENT SECTION
    ============================================= -->
    <section id="event-announcement" class="wide-60 division">
        <div class="container">

            <div class="row">

                <!-- EVENT COLUMN (LEFT) -->
                <div class="col-lg-7 mb-40">

                    <!-- Section Header -->
                    <div class="d-flex align-items-center justify-content-between mb-30">
                        <div>
                            <div class="section-id grey-color" style="margin-bottom: 0px">Kegiatan</div>
                            <h4 class="h4-md mb-0">Event Terbaru</h4>
                        </div>
                        @if(!$list_event->isEmpty())
                            <a href="{{ route('event.index') }}" class="btn btn-tra-grey theme-hover btn-sm d-none d-md-inline-block">Lihat Semua</a>
                        @endif
                    </div>

                    @if($list_event->isEmpty())
                        <div class="bg-lightgrey p-4 text-center" style="border: 2px dashed #ddd; border-radius: 6px;">
                            <div class="ico-50 mb-15 grey-color"><span class="flaticon-calendar"></span></div>
                            <h6 class="h6-xs">Belum Ada Event</h6>
                            <p class="p-md grey-color mb-0">Event terbaru akan tampil di sini.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($list_event->take(4) as $event)
                                <div class="col-sm-6">
                                    <div class="blog-1-post radius-06 mb-30 wow fadeInUp" data-wow-delay="{{ $loop->index * 0.15 }}s">
                                        <!-- IMAGE -->
                                        <div class="blog-post-img" style="position: relative; overflow: hidden;">
                                            <a href="{{ route('event.show', $event->slug) }}">
                                                <img class="img-fluid" src="{{ $event->getThumbnail() }}" alt="{{ $event->name }}"
                                                     style="width: 100%; height: 180px; object-fit: cover;">
                                            </a>
                                            <div class="post-tag txt-upcase" style="position: absolute; top: 10px; left: 10px; background: #fff; padding: 3px 12px; border-radius: 4px; font-size: 11px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                                {{ $event->type ?? 'Event' }}
                                            </div>
                                            @if($event->status)
                                                <span style="position: absolute; top: 10px; right: 10px; background: {{ $event->status == 'selesai' ? '#6c757d' : '#28a745' }}; color: #fff; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 4px;">
                                                    {{ ucfirst($event->status) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- TEXT -->
                                        <div class="blog-post-txt">
                                            <p class="post-date mb-5" style="font-size: 13px;">
                                                <span class="flaticon-clock mr-1"></span>
                                                {{ $event->datetime ?: '-' }}
                                            </p>
                                            <h6 class="h6-xs" style="line-height: 1.4; margin-bottom: 8px;">
                                                <a href="{{ route('event.show', $event->slug) }}">{{ Str::limit($event->name, 45) }}</a>
                                            </h6>
                                            <p class="p-sm grey-color mb-0" style="font-size: 13px;">
                                                <span class="flaticon-pin mr-1" style="font-size: 11px;"></span>
                                                {{ Str::limit($event->location ?? 'Online', 30) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- MOBILE LINK -->
                        <div class="d-md-none text-center mt-2">
                            <a href="{{ route('event.index') }}" class="btn btn-tra-grey theme-hover btn-sm">Lihat Semua Event</a>
                        </div>
                    @endif

                </div>
                <!-- END EVENT COLUMN -->


                <!-- ANNOUNCEMENT COLUMN (RIGHT) -->
                <div class="col-lg-5 mb-40">

                    <!-- Section Header -->
                    <div class="d-flex align-items-center justify-content-between mb-30">
                        <div>
                            <div class="section-id grey-color" style="margin-bottom: 0px">Informasi</div>
                            <h4 class="h4-md mb-0">Pengumuman</h4>
                        </div>
                        @if(!$list_announcement->isEmpty())
                            <a href="{{ route('announcement.index') }}" class="btn btn-tra-grey theme-hover btn-sm d-none d-md-inline-block">Lihat Semua</a>
                        @endif
                    </div>

                    @if($list_announcement->isEmpty())
                        <div class="bg-lightgrey p-4 text-center" style="border: 2px dashed #ddd; border-radius: 6px;">
                            <div class="ico-50 mb-15 grey-color"><span class="flaticon-chat-1"></span></div>
                            <h6 class="h6-xs">Belum Ada Pengumuman</h6>
                            <p class="p-md grey-color mb-0">Pengumuman terbaru akan tampil di sini.</p>
                        </div>
                    @else
                        <div class="announcement-list">
                            @foreach($list_announcement->take(6) as $announcement)
                                <a href="{{ route('announcement.show', $announcement->slug) }}" class="d-block text-decoration-none wow fadeInUp" data-wow-delay="{{ $loop->index * 0.1 }}s">
                                    <div class="d-flex align-items-center p-3 mb-2 bg-white radius-06" style="border: 1px solid #eee; transition: all 0.3s ease;">
                                        <!-- Text -->
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <h6 class="h6-xs mb-1 deepgrey-color" style="line-height: 1.4; font-size: 14px;">{{ Str::limit($announcement->title, 60) }}</h6>
                                            <p class="p-sm grey-color mb-0" style="font-size: 12px;">
                                                <span class="flaticon-clock mr-1" style="font-size: 11px;"></span>
                                                {{ $announcement->created_at->diffForHumans() }}
                                                @if($announcement->file)
                                                    <span class="ml-2"><span class="flaticon-pdf" style="font-size: 11px;"></span> Lampiran</span>
                                                @endif
                                            </p>
                                        </div>
                                        <!-- Arrow -->
                                        <div class="flex-shrink-0 ml-2">
                                            <span class="flaticon-right-arrow grey-color" style="font-size: 12px;"></span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- MOBILE LINK -->
                        <div class="d-md-none text-center mt-3">
                            <a href="{{ route('announcement.index') }}" class="btn btn-tra-grey theme-hover btn-sm">Lihat Semua Pengumuman</a>
                        </div>
                    @endif

                </div>
                <!-- END ANNOUNCEMENT COLUMN -->

            </div> <!-- End row -->

        </div> <!-- End container -->
    </section>
    <!-- END EVENT & ANNOUNCEMENT SECTION -->




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
    <section id="books-latest" class="wide-60 division bg-lightgrey">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row mb-40">
                <div class="col-md-8">
                    <div class="section-title text-left">
                        <div class="section-id grey-color">Koleksi Buku</div>
                        <h3 class="h3-sm">Buku Terbaru</h3>
                        <p class="p-lg grey-color">Jelajahi berbagai buku berkualitas dari para penulis</p>
                    </div>
                </div>
                @if(!$list_book->isEmpty())
                <div class="col-md-4 text-right d-none d-md-flex align-items-end justify-content-end pb-3">
                    <a href="{{ route('book.index') }}" class="btn btn-tra-grey theme-hover btn-sm">Lihat Semua Buku</a>
                </div>
                @endif
            </div>

            <!-- BOOKS GRID -->
            @if($list_book->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-4">
                        <div class="bg-white p-5" style="border: 2px dashed #ddd; border-radius: 6px; max-width: 500px; margin: 0 auto;">
                            <div class="ico-55 mb-20 grey-color"><span class="flaticon-book"></span></div>
                            <h5 class="h5-xs">Belum Ada Koleksi Buku</h5>
                            <p class="p-md grey-color mb-0">Buku terbaru yang terbit akan tampil di sini.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach($list_book as $book)
                        <div class="col-sm-6 col-lg-3">
                            <div class="blog-1-post radius-06 wow fadeInUp" data-wow-delay="{{ $loop->index * 0.1 }}s">

                                <!-- IMAGE -->
                                <div class="blog-post-img" style="position: relative; overflow: hidden;">
                                    <a href="{{ route('book.show', $book->slug) }}">
                                        <img class="img-fluid" src="{{ $book->getThumbnail() }}" alt="{{ $book->title }}"
                                             style="width: 100%; height: 280px; object-fit: cover;">
                                    </a>
                                    <!-- CATEGORY -->
                                    <div class="post-tag txt-upcase" style="position: absolute; top: 10px; left: 10px; background: #fff; padding: 3px 12px; border-radius: 4px; font-size: 11px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                        {{ $book->category->name ?? 'Buku' }}
                                    </div>
                                    <!-- PRICE -->
                                    @if($book->price == 0)
                                        <span class="book-price-label bg-success">Gratis</span>
                                    @else
                                        <span class="book-price-label bg-theme">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <!-- TEXT -->
                                <div class="blog-post-txt">
                                    <h6 class="h6-xs mb-15" style="line-height: 1.4; margin-bottom: 8px;">
                                        <a href="{{ route('book.show', $book->slug) }}">{{ Str::limit($book->title, 50) }}</a>
                                    </h6>
                                    <a href="{{ route('book.show', $book->slug) }}" class="btn btn-tra-grey theme-hover btn-sm btn-block">
                                        Detail Buku
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- MOBILE LINK -->
                <div class="row d-md-none mt-3">
                    <div class="col text-center">
                        <a href="{{ route('book.index') }}" class="btn btn-tra-grey theme-hover btn-sm">Lihat Semua Buku</a>
                    </div>
                </div>
            @endif
        </div>
    </section>


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
                                <div class="fbox-img radius-04">
                                    <a href="{{ route('journal.detail', $journal->url_path) }}">
                                        <img class="img-fluid" src="{{ $journal->getJournalThumbnail() }}" alt="features-image">
                                    </a>
                                </div>

                                <!-- Text -->
                                <h5 class="h5-sm"><a href="{{ route('journal.detail', $journal->url_path) }}">{{ $journal->title }}</a></h5>
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
    @if($list_testimonial->count() > 0)
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

                        @foreach($list_testimonial as $testimonial)
                            <!-- TESTIMONIAL #{{ $loop->iteration }} -->
                            <div class="review-3 radius-04">
                                <div class="review-3-txt">

                                    <!-- Text -->
                                    <p class="p-md grey-color">{{ $testimonial->content }}</p>

                                    <!-- App Rating -->
                                    <div class="app-rating ico-20 yellow-color">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $testimonial->rating)
                                                <span class="flaticon-star"></span>
                                            @else
                                                <span class="flaticon-star-1"></span>
                                            @endif
                                        @endfor
                                    </div>

                                    <!-- Testimonial Author -->
                                    <h6 class="h6-sm deepgrey-color">- {{ $testimonial->name }}</h6>
                                    <p class="p-sm">{{ $testimonial->position }}{{ $testimonial->company ? ', ' . $testimonial->company : '' }}</p>

                                </div>
                            </div> <!-- END TESTIMONIAL #{{ $loop->iteration }} -->
                        @endforeach


                    </div>
                </div>
            </div> <!-- END TESTIMONIALS CONTENT -->


        </div> <!-- End container -->
    </section> <!-- END TESTIMONIALS-3 -->
    @endif



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
