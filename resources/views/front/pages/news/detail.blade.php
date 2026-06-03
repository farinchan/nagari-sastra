@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="{{ $news->user->name ?? 'Nagari Sastra' }}">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('news.detail', $news->slug) }}">
    <link rel="canonical" href="{{ route('news.detail', $news->slug) }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')
    <!-- BLOG POSTS LISTING-1
                                                                   ============================================= -->
    <section id="blog-listing-1" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">


                <!-- SINGLE POST CONTENT -->
                <div class="col-lg-9">
                    <div class="single-post-wrapper pr-30">


                        <!-- SINGLE POST TITLE -->
                        <div class="single-post-title">

                            @if ($news->thumbnail)
                                <div class="single-post-img mb-4">
                                    <img src="{{ $news->getThumbnail() }}" alt="{{ $news->title }}"
                                        class="img-fluid rounded">
                                </div>
                            @endif
                            <!-- CATEGORY -->
                            <p class="p-md post-title-tag theme-color txt-400" style="margin-bottom: 10px;">
                                {{ $news->category->name }}
                            </p>



                            <!-- TITLE -->
                            <h4 class="h4-lg">{{ $news->title }}</h4>



                            <!-- POST DATA -->
                            <div class="post-data clearfix">

                                <!-- Author Avatar -->
                                <div class="post-author-avatar" style="display: inline-block; float: left;;">
                                    <img src="{{ $news->user->getPhoto() }}" alt="author-avatar"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 100%; margin-right: 20px;">
                                </div>

                                <!-- Author Data -->
                                <div class="post-author">
                                    <span>{{ $news->user->name }} </span>
                                    <span>{{ $news->created_at->format('M d, Y') }}</span>
                                </div>

                            </div> <!-- END POST DATA -->


                        </div> <!-- END SINGLE POST TITLE -->


                        <!-- BLOG POST TEXT -->
                        <div class="single-post-txt">

                            {!! $news->content !!}

                        </div>




                        <!-- SINGLE POST SHARE LINKS -->
                        <div class="row post-share-links d-flex align-items-center">

                            <!-- POST TAGS -->
                            <div class="col-md-9 col-xl-8 post-tags-list">
                                @php
                                    $tags = explode(',', $news->meta_keywords ?? '');
                                @endphp
                                @foreach ($tags ?? [] as $tag)
                                    <span><a href="#">{{ $tag }}</a></span>
                                @endforeach
                            </div>

                            <!-- POST SHARE ICONS -->
                            <div class="col-md-3 col-xl-4 post-share-list text-right">
                                <ul class="share-social-icons ico-25 text-center clearfix">
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
                                            class="share-ico ico-facebook"><span class="flaticon-facebook"></span></a></li>
                                    <li><a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}"
                                            class="share-ico ico-twitter"><span class="flaticon-twitter"></span></a></li>
                                    <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(Request::fullUrl()) }}"
                                            class="share-ico ico-linkedin"><span class="flaticon-linkedin"></span></a></li>
                                    <li>
                                        <a href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}" target="_blank"
                                            class="share-ico ico-whatsapp">
                                            <span class="flaticon-whatsapp"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div> <!-- END SINGLE POST SHARE -->


                        <!-- OTHER POSTS -->
                        <div class="other-posts">
                            <div id="op-row" class="row d-flex align-items-center">

                                <!-- Previous Post -->
                                @if ($prev_news)
                                    <div class="col-md-5">
                                        <div class="prev-post mb-30 pr-45">
                                            <h6 class="h6-sm">Previous Post</h6>
                                            <a
                                                href="{{ route('news.detail', $prev_news->id) }}">{{ $prev_news->title }}</a>
                                        </div>
                                    </div>
                                @endif

                                <!-- All Posts -->
                                <div class="col-md-2 text-center">
                                    <div class="all-posts ico-35 mb-30">
                                        <a href="{{ route('news.index') }}"><span
                                                class="flaticon-four-black-squares"></span></a>
                                    </div>
                                </div>

                                <!-- Next Post -->
                                @if ($next_news)
                                    <div class="col-md-5 text-right">
                                        <div class="next-post mb-30 pl-45">
                                            <h6 class="h6-sm">Next Post</h6>
                                            <a
                                                href="{{ route('news.detail', $next_news->id) }}">{{ $next_news->title }}</a>
                                        </div>
                                    </div>
                                @endif

                            </div> <!-- End row -->
                        </div> <!-- END OTHER POSTS -->


                        <!-- COMMENTS WRAPPER -->
                        <div class="comments-wrapper">

                            <!-- Title -->
                            <h5 class="h5-md">{{ $news->comments->count() }} Comments</h5>

                            @foreach ($news->comments as $comment)
                                <!-- COMMENT #1 -->
                                <div class="media">

                                    <!-- Comment-1 Avatar -->
                                    <img class="mr-25"
                                        src="https://api.dicebear.com/9.x/bottts/png?seed={{ $comment->name }}"
                                        alt="comment-avatar">

                                    <div class="media-body">

                                        <!-- Comment-1 Meta -->
                                        <div class="comment-meta">
                                            <h6 class="h6-sm mt-0">{{ $comment->name }}</h6>
                                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}&#8194;-
                                            </span>

                                        </div>

                                        <!-- Comment-1 Text -->
                                        <p class="mb-40">{{ $comment->comment }}</p>


                                        @foreach ($comment->children as $reply)
                                            <hr />
                                            @if ($reply->user)
                                                <div class="media">

                                                    <!-- Comment-2 Avatar -->
                                                    <a href="#" class="mr-25">
                                                        <img src="{{ $reply->user->getPhoto() }}" alt="comment-avatar">
                                                    </a>

                                                    <div class="media-body">

                                                        <!-- Comment-2 Meta -->
                                                        <div class="comment-meta">
                                                            <h6 class="h6-sm mt-0">{{ $reply->user->name }}</h6>
                                                            <span
                                                                class="comment-date">{{ $reply->created_at->diffForHumans() }}&#8194;-
                                                            </span>

                                                        </div>

                                                        <!-- Comment-2 Text -->
                                                        <p> {{ $reply->comment }}</p>

                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach



                                    </div>
                                </div> <!-- END COMMENT #1 -->


                                <hr />
                            @endforeach


                            <!-- COMMENT FORM -->
                            <div id="leave-comment">

                                <!-- Title -->
                                <h5 class="h5-lg">Tambah Komentar</h5>

                                <!-- Text -->
                                <p class="p-sm grey-color">Alamat email Anda tidak akan dipublikasikan. Kolom yang wajib
                                    diisi
                                    ditandai *</p>

                                <form action="{{ route('news.comment') }}" method="POST" class="row comment-form">
                                    @csrf
                                    <input type="hidden" name="news_id" value="{{ $news->id }}">
                                    <div class="col-md-12 input-message">
                                        <p>Komentar Kamu *</p>
                                        <textarea class="form-control message" name="comment" rows="6"
                                            placeholder="Masukkan Komentar Anda Di Sini* ..." required>{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <p>Nama*</p>
                                        <input type="text" name="name" class="form-control name"
                                            value="{{ old('name') }}" placeholder="Enter Your Name*" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <p>Email*</p>
                                        <input type="email" name="email" class="form-control email"
                                            value="{{ old('email') }}" placeholder="Enter Your Email*" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}
                                        @error('g-recaptcha-response')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Contact Form Button -->
                                    <div class="col-lg-12 form-btn">
                                        <button type="submit" class="btn btn-theme tra-grey-hover submit">
                                            Posting Komentar
                                        </button>
                                    </div>

                                    <!-- Contact Form Message -->
                                    <div class="col-md-12 comment-form-msg text-center">
                                        <div class="sending-msg"><span class="loading"></span></div>
                                    </div>

                                </form>

                            </div> <!-- END COMMENT FORM -->

                        </div> <!-- END COMMENTS WRAPPER -->


                    </div>
                </div> <!-- END SINGLE POST CONTENT -->


                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-3">


                    <!-- SEARCH FIELD -->
                    <div id="search-field" class="sidebar-div ico-20 mb-50">
                        <div class="input-group mb-3">
                            <form action="" method="GET" class="d-flex">
                                <input type="text" class="form-control" placeholder="Search" aria-label="Search"
                                    value="{{ request('q') }}" name="q" aria-describedby="search-field">
                                <div class="input-group-append">
                                    <button class="btn" type="submit"><span
                                            class="flaticon-magnifying-glass"></span></button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <!-- BLOG CATEGORIES -->
                    <div class="blog-categories sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Kategori</h6>

                        <ul class="blog-category-list clearfix">
                            @foreach ($categories as $category)
                                <li>
                                    <p><a href="{{ route('news.category', $category->slug) }}">{{ $category->name }}</a>
                                        <span>({{ $category->news->count() }})</span>
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    <!-- POPULAR TAGS -->
                    {{-- <div id="popular-tags" class="sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Popular Tags</h6>

                        <span class="badge"><a href="#">video</a> 3</span>
                        <span class="badge"><a href="#">web</a> 17</span>
                        <span class="badge"><a href="#">music</a> 2</span>
                        <span class="badge"><a href="#">photoshop</a> 26</span>
                        <span class="badge"><a href="#">design</a> 54</span>
                        <span class="badge"><a href="#">web design</a> 46</span>
                        <span class="badge"><a href="#">typography</a> 6</span>
                        <span class="badge"><a href="#">journal</a> 9</span>
                        <span class="badge"><a href="#">graphic</a> 32</span>

                    </div> --}}

                    <!-- IMAGE WIDGET -->
                    {{-- <div id="image-widget" class="sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Image Widget</h6>

                        <img class="img-fluid" src="images/blog/image-widget.jpg" alt="image-widget" />

                    </div> --}}


                    <!-- TEXT WIDGET -->
                    <div id="text-widget" class="sidebar-div mb-50">

                        <!-- Title -->
                        <h6 class="h6-xl">Berita</h6>

                        <!-- Text -->
                        <p class="grey-color">
                            Temukan berita terbaru, artikel menarik, dan informasi terkini seputar riset, teknologi, dan
                            inovasi di Nagari Sastra. Selalu update wawasan Anda bersama kami!
                        </p>

                    </div> <!-- End Text Widget -->


                </aside> <!-- END SIDEBAR -->


            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END BLOG POSTS LISTING-1 -->



    <!-- BLOG-1
                                                       ============================================= -->
    <section id="blog-1" class="bg-lightgrey wide-60 blog-section division">
        <div class="container">


            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">

                        <!-- Title 	-->
                        <h3 class="h3-md">Tetap Membaca: Berita Populer</h3>

                        <!-- Text -->
                        <p class="p-xl">
                            Berikut adalah beberapa berita populer yang mungkin menarik minat Anda. Tetap terinformasi
                            dengan
                            berita terbaru dan tren terkini di berbagai bidang.
                        </p>

                    </div>
                </div>
            </div>


            <!-- BLOG POSTS -->
            <div class="row">

                @foreach ($news_trending as $news_trend)
                    <!-- BLOG POST #1 -->
                    <div class="col-md-6 col-lg-4">
                        <div id="bp-1-1" class="blog-1-post wow fadeInUp" data-wow-delay="0.4s">

                            <!-- BLOG POST IMAGE -->
                            <div class="blog-post-img rel">
                                <div class="hover-overlay">
                                    <img class="img-fluid" src="{{ $news_trend->getThumbnail() }}"
                                        alt="blog-post-image" />
                                    <div class="item-overlay"></div>
                                </div>
                            </div>

                            <!-- BLOG POST TEXT -->
                            <div class="blog-post-txt">

                                <!-- Post Tag -->
                                <p class="p-sm post-tag txt-upcase"><a
                                        href="{{ route('news.category', $news_trend->category->slug) }}">{{ $news_trend->category->name }}</a>
                                </p>

                                <!-- Post Title -->
                                <h5 class="h5-xs">{{ $news_trend->title }}</h5>

                                <!-- Author Data -->
                                <div class="post-author">
                                    <span>{{ $news_trend->created_at->format('M d, Y') }}</span>
                                    <span>By {{ $news_trend->user->name }}</span>
                                </div>

                                <!-- Post Link -->
                                <div class="post-link ico-20">
                                    <a href="{{ route('news.detail', $news_trend->slug) }}"><span
                                            class="flaticon-right-arrow"></span></a>
                                </div>

                            </div> <!-- END BLOG POST TEXT -->

                        </div>
                    </div> <!-- END  BLOG POST #1 -->
                @endforeach


            </div> <!-- END BLOG POSTS -->


        </div> <!-- End container -->
    </section> <!-- END BLOG-1 -->



@include('front.partials.newsletter')
@endsection

@section('scripts')
    <script>
        $.ajax({
            url: "{{ route('news.visit') }}",
            data: {
                news_id: {{ $news->id }}
            },
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
