@php
    $category_news = \App\Models\NewsCategory::all();
    $news_list = \App\Models\News::latest()->where('status', 'published')->limit(3)->get();
    $news_popular = \App\Models\News::inRandomOrder()->where('status', 'published')->first();
    $journals = \App\Models\Journal::all();
@endphp
<header id="header" class="header tra-menu navbar-light">
    <div class="header-wrapper">


        <!-- MOBILE HEADER -->
        <div class="wsmobileheader clearfix">
            <span class="smllogo"><img src="images/logo-01.png" alt="mobile-logo" /></span>
            <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        </div>


        <!-- NAVIGATION MENU -->
        <div class="wsmainfull menu clearfix">
            <div class="wsmainwp clearfix">


                <!-- HEADER LOGO -->
                <div class="desktoplogo"><a href="#hero-13" class="logo-black"><img
                            src="{{ $setting_web?->getLogo() ?? '' }}" alt="header-logo"></a></div>
                <div class="desktoplogo"><a href="#hero-13" class="logo-white"><img
                            src="{{ $setting_web?->getLogo() ?? '' }}" alt="header-logo"></a></div>


                <!-- MAIN MENU -->
                <nav class="wsmenu clearfix">
                    <ul class="wsmenu-list nav-theme-hover">

                        <li class="nl-simple" aria-haspopup="true"><a href="{{ route('home') }}">Home</a></li>
                        <!-- DROPDOWN MENU -->
                        <li aria-haspopup="true"><a href="#">Jurnal <span class="wsarrow"></span></a>
                            <ul class="sub-menu">
                                @foreach ($journals as $journal)
                                    <li aria-haspopup="true"><a
                                            href="{{ route('journal.detail', $journal->url_path) }}">{{ $journal->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>


                        {{-- <li aria-haspopup="true"><a href="#">Pages <span class="wsarrow"></span></a>
                            <div class="wsmegamenu clearfix">
                                <div class="container">
                                    <div class="row">

                                        <!-- MEGAMENU LINKS -->
                                        <ul class="col-lg-3 col-md-12 col-xs-12 link-list">
                                            <li><a href="about.html">About Us Page</a></li>
                                            <li><a href="services-1.html">Our Services v1</a></li>
                                            <li><a href="services-2.html">Our Services v2</a></li>
                                            <li><a href="service-details-design.html">Service Details #1</a>
                                            </li>
                                            <li><a href="service-details.html">Service Details #2</a></li>
                                        </ul>

                                        <!-- MEGAMENU LINKS -->
                                        <ul class="col-lg-3 col-md-12 col-xs-12 link-list">
                                            <li><a href="service-details-constr.html">Service Details #3</a>
                                            </li>
                                            <li><a href="service-details-handyman.html">Service Details #4</a>
                                            </li>
                                            <li><a href="gallery-1.html">Our Projects v1</a></li>
                                            <li><a href="gallery-2.html">Our Projects v2</a></li>
                                            <li><a href="project-details-1.html">Project Details v1</a></li>
                                        </ul>

                                        <!-- MEGAMENU LINKS -->
                                        <ul class="col-lg-3 col-md-12 col-xs-12 link-list">
                                            <li><a href="project-details-2.html">Project Details v2</a></li>
                                            <li><a href="team.html">Meet The Team</a></li>
                                            <li><a href="pricing.html">Pricing Packages</a></li>
                                            <li><a href="customers.html">Our Customers</a></li>
                                            <li><a href="faqs.html">FAQs Page</a></li>
                                        </ul>

                                        <!-- MEGAMENU LINKS -->
                                        <ul class="col-lg-3 col-md-12 col-xs-12 link-list">
                                            <li><a href="blog-listing-1.html">Blog Listing Sidebar</a></li>
                                            <li><a href="blog-listing-2.html">Blog Listing Modern</a></li>
                                            <li><a href="single-post.html">Single Blog Post</a></li>
                                            <li><a href="contacts.html">Contact Us</a></li>
                                            <li><a href="terms.html">Terms & Privacy</a></li>
                                        </ul>

                                    </div> <!-- End row -->
                                </div> <!-- End container -->
                            </div> <!-- End wsmegamenu -->
                        </li> --}}


                        <!-- MEGAMENU -->
                        <li aria-haspopup="true"><a href="{{ route('news.index') }}">Berita<span
                                    class="wsarrow"></span></a>
                            <div class="wsmegamenu clearfix">
                                <div class="container">
                                    <div class="row">


                                        <!-- MEGAMENU QUICK LINKS -->
                                        <div class="col-md-12 col-lg-3">

                                            <!-- Title -->
                                            <h3 class="title">Kategori:</h3>

                                            <ul class="link-list clearfix">
                                                @foreach ($category_news as $category)
                                                    <li class="fst-li"><a
                                                            href="{{ route('news.category', $category->slug) }}">{{ $category->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div> <!-- END MEGAMENU QUICK LINKS -->


                                        <!-- MEGAMENU FEATURED NEWS -->
                                        <div class="col-md-12 col-lg-5">

                                            <!-- Title -->
                                            <h3 class="title">Berita Populer:</h3>

                                            <!-- Image -->
                                            <div class="fluid-width-video-wrapper mb-15"><img
                                                    src="{{ $news_popular->getThumbnail() }}" alt="featured-news"
                                                    style="width: 100%; height: 200px; object-fit: cover;" />
                                            </div>

                                            <!-- Text -->
                                            <h6 class="h6-md">
                                                <a href="{{ route('news.detail', $news_popular->slug) }}">
                                                    {{ $news_popular->title }}
                                                </a>
                                            </h6>

                                            <!-- Text -->
                                            <p class="wsmwnutxt">
                                                {{ Str::limit(strip_tags($news_popular->content), 150, '...') }}
                                            </p>

                                        </div> <!-- END MEGAMENU FEATURED NEWS -->


                                        <!-- MEGAMENU LATEST NEWS -->
                                        <div class="col-md-12 col-lg-4">

                                            <!-- Title -->
                                            <h3 class="title">Berita Terbaru:</h3>

                                            <!-- Latest News -->
                                            <ul class="latest-news">

                                                @foreach ($news_list as $news)
                                                    <!-- Post #1 -->
                                                    <li class="clearfix d-flex align-items-center">

                                                        <!-- Image -->
                                                        <img class="img-fluid" src="{{ $news->getThumbnail() }}"
                                                            alt="blog-post-preview" />

                                                        <!-- Text -->
                                                        <div class="post-summary">
                                                            <a
                                                                href="{{ route('news.detail', $news->slug) }}">{{ Str::limit($news->title, 40, '...') }}</a>
                                                            <p>{{ $news->created_at->diffForHumans() }}</p>
                                                        </div>

                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div> <!-- END MEGAMENU LATEST NEWS -->


                                    </div> <!-- End row -->
                                </div> <!-- End container -->
                            </div> <!-- End wsmegamenu -->
                        </li> <!-- END MEGAMENU -->


                        <!-- SIMPLE NAVIGATION LINK -->
                        <li class="nl-simple" aria-haspopup="true"><a href="{{ route('payment.index') }}">Pembayaran</a></li>
                        <li class="nl-simple" aria-haspopup="true"><a href="{{ route('contact.index') }}">Kontak</a></li>

                        @guest
                        <li aria-haspopup="true">
                            <a href="#"><span class="flaticon-user"></span>&nbsp;User</a>
                            <ul class="sub-menu">
                                <li aria-haspopup="true">
                                    <a href="{{ route('login') }}">Masuk</a>
                                </li>
                                <li aria-haspopup="true">
                                    <a href="{{ route('register') }}">Daftar</a>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li aria-haspopup="true">
                            <a href="#"><span class="flaticon-user"></span>&nbsp;{{ Auth::user()->name }}</a>
                            <ul class="sub-menu">
                                <li aria-haspopup="true">
                                    <a href="#">Profil Saya</a>
                                </li>
                                <li aria-haspopup="true">
                                    <a href="{{ route('back.dashboard') }}">Dashboard</a>
                                </li>
                                <li aria-haspopup="true">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Keluar
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endguest
                        {{-- HEADER CALL BUTTON --}}
                        {{-- <li class="nl-simple header-phone ico-25" aria-haspopup="true">
                            <a href="tel:123456789">
                                <span class="flaticon-phone-call bg-white theme-color"></span>+12 9 8765 4321
                            </a>
                        </li> --}}


                        <!-- HEADER BUTTON -->
                        {{-- <li class="nl-simple" aria-haspopup="true">
                                    <a href="#cta-6" class="btn btn-theme tra-white-hover last-link">Let's
                                        Started</a>
                                </li> --}}



                        {{-- HEADER SOCIAL LINKS --}}
                        {{-- <li class="nl-simple white-color header-socials ico-20 clearfix" aria-haspopup="true">
                            <span><a href="#" class="ico-facebook"><span
                                        class="flaticon-facebook"></span></a></span>
                            <span><a href="#" class="ico-twitter"><span
                                        class="flaticon-twitter"></span></a></span>
                            <span><a href="#" class="ico-instagram"><span
                                        class="flaticon-instagram"></span></a></span>
                            <span><a href="#" class="ico-dribbble"><span
                                        class="flaticon-dribbble"></span></a></span>
                        </li> --}}


                    </ul>
                </nav> <!-- END MAIN MENU -->


            </div>
        </div> <!-- END NAVIGATION MENU -->


    </div> <!-- End header-wrapper -->
</header>
