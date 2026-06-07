@extends('front.app')

@section('content')
    <!-- BLOG POSTS LISTING-1
               ============================================= -->
    <section id="blog-listing-1" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">


                <!-- BLOG POSTS WRAPPER -->
                <div class="col-lg-9">
                    <div class="posts-wrapper pr-25">
                        <h2>{{ $category->name }}</h2>
                        <p>{{ $category->description }}</p>
                        <hr style="margin-bottom: 40px;">

                        @foreach ($news as $n)
                            <div class="blog-post b-bottom mb-40">
                                <div class="row d-flex align-items-center">


                                    <!-- BLOG POST IMAGE -->
                                    <div class="col-md-5">
                                        <div class="blog-post-img">
                                            <img class="img-fluid" src="{{ $n->getThumbnail() }}" alt="blog-post-image">
                                        </div>
                                    </div>


                                    <!-- BLOG POST TEXT -->
                                    <div class="col-md-7">
                                        <div class="blog-post-txt">

                                            <!-- Post Tag -->
                                            <p class="post-tag txt-upcase"><a
                                                    href="{{ route('news.category', $n->category->slug) }}"
                                                    class="theme-color">
                                                    {{ $n->category->name }}
                                                </a> </p>

                                            <!-- Post Link -->
                                            <h5 class="h5-md">
                                                <a href="single-post.html">
                                                    {{ $n->title }}
                                                </a>
                                            </h5>

                                            <!-- Post Text -->
                                            <p class="p-md grey-color">
                                                {{ Str::limit(strip_tags($n->content), 150, '...') }}
                                            </p>

                                            <!-- Author Data -->
                                            <div class="post-author">
                                                <span>{{ $n->created_at->format('M d, Y') }}</span>
                                                <span>{{ $n->user->name }}</span>
                                            </div>

                                        </div>
                                    </div> <!-- END BLOG POST TEXT -->


                                </div>
                            </div>
                        @endforeach





                    </div>
                </div> <!-- END BLOG POSTS WRAPPER -->


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
                                Temukan berita terbaru, artikel menarik, dan informasi terkini seputar riset, teknologi, dan inovasi di Nagari Sastra. Selalu update wawasan Anda bersama kami!
                        </p>

                    </div> <!-- End Text Widget -->


                </aside> <!-- END SIDEBAR -->


            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END BLOG POSTS LISTING-1 -->




    <!-- PAGE PAGINATION
               ============================================= -->
    @if ($news->hasPages())
        <div class="page-pagination division">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        <nav aria-label="Page navigation">
                            <ul class="pagination ico-20 justify-content-center">
                                {{-- Previous Page Link --}}
                                @if ($news->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">
                                            <span class="flaticon-chevron-pointing-to-the-left"></span>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $news->previousPageUrl() }}" tabindex="-1">
                                            <span class="flaticon-chevron-pointing-to-the-left"></span>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($news->getUrlRange(1, $news->lastPage()) as $page => $url)
                                    @if ($page == $news->currentPage())
                                        <li class="page-item active">
                                            <a class="page-link" href="#">{{ $page }}
                                                <span class="sr-only">(current)</span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($news->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $news->nextPageUrl() }}">
                                            <span class="flaticon-right-chevron"></span>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">
                                            <span class="flaticon-right-chevron"></span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>

                    </div>
                </div> <!-- End row -->
            </div> <!-- End container -->
        </div> <!-- END PAGE PAGINATION -->
    @endif




   @include('front.partials.newsletter')
@endsection
