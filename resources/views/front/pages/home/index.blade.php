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
                                <h5 class="h5-xs">Publikasi</h5>
                                <p class="p-md grey-color">
                                    Publikasikan karya ilmiah Anda di jurnal bereputasi untuk meningkatkan visibilitas
                                    penelitian.
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
                                <h5 class="h5-xs">Penelitian</h5>
                                <p class="p-md grey-color">
                                    Lakukan penelitian terapan untuk mengembangkan solusi inovatif di berbagai bidang ilmu.
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
                                <h5 class="h5-xs">Training & Education</h5>
                                <p class="p-md grey-color">
                                    Ikuti pelatihan dan workshop untuk meningkatkan keterampilan riset dan penulisan ilmiah.
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
                        <div class="statistic-ico ico-60"><span class="flaticon-browser"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number">1,<span class="count-element">186</span></h3>
                        <p class="p-md txt-400">Finished Projects</p>

                    </div>
                </div>


                <!-- STATISTIC BLOCK #2 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="0.6s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-like-1"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number">1,<span class="count-element">122</span></h3>
                        <p class="p-md txt-400">Happy Customers</p>
                    </div>
                </div>


                <!-- STATISTIC BLOCK #3 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="0.8s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-users"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number">3,<span class="count-element">659</span></h3>
                        <p class="p-md txt-400">Active Accounts</p>

                    </div>
                </div>


                <!-- STATISTIC BLOCK #4 -->
                <div class="col-sm-6 col-md-3">
                    <div class="statistic-block text-center mb-40 wow fadeInUp" data-wow-delay="1s">

                        <!-- Icon  -->
                        <div class="statistic-ico ico-60"><span class="flaticon-help"></span></div>

                        <!-- Text -->
                        <h3 class="h3-xs statistic-number"><span class="count-element">648</span></h3>
                        <p class="p-md txt-400">Tickets Closed</p>

                    </div>
                </div>


            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END STATISTIC-4 -->


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
