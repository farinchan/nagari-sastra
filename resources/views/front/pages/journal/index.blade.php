@extends('front.app')

@section('content')
    <!-- GALLERY-3
                   ============================================= -->
    <section id="gallery-3" class="pt-100 gallery-section division">
        <div class="container">


            <!-- GALLERY FILTERING BUTTONS -->
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="masonry-filter theme-filter ico-20 mb-50">
                        <button data-filter="*" class="is-checked"> Semua</button>
                        {{-- <button data-filter=".non-sinta"> Non Sinta</button> --}}
                    </div>
                </div>
            </div>


            <!-- GALLERY IMAGES WRAPPER -->
            <div class="row">
                <div class="col-md-12 gallery-items-list">
                    <div class="masonry-wrap grid-loaded">


                        @foreach ($journals as $journal)
                            <!-- IMAGE #1 -->
                            <div class="masonry-image illustration">
                                <div class="hover-overlay">

                                    <!-- Image -->
                                    <img class="img-fluid" src="{{ $journal->getJournalThumbnail() }}"
                                        alt="gallery-image" />
                                    <div class="item-overlay"></div>

                                    <!-- Project Meta -->
                                    <div class="project-meta white-color">

                                        <div class="project-meta-txt">

                                            <!-- Project Meta -->
                                            <span>
                                                @foreach ($journal->indexing ?? ([] ?? []) as $akreditasi_item)
                                                    {{ $akreditasi_item }},
                                                @endforeach
                                            </span>

                                            <h6 class="h6-md txt-upcase">{{ $journal->title }}</h6>

                                            <!-- Project Rating -->
                                            {{-- <div class="project-rating white-color clearfix">
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span class="flaticon-star"></span>
                                                <span>(118)</span>
                                            </div> --}}

                                        </div>

                                        <!-- Project Link -->
                                        <div class="project-link">
                                            <a href="{{ route('journal.detail', $journal->url_path) }}"><span
                                                    class="flaticon-right-arrow"></span></a>
                                        </div>

                                    </div>

                                </div>
                            </div> <!-- END IMAGE #1 -->
                        @endforeach


                    </div>
                </div>
            </div> <!-- END GALLERY IMAGES WRAPPER -->


        </div> <!-- End container -->
    </section> <!-- END GALLERY-3 -->




    <!-- BRANDS-2
                   ============================================= -->
    {{-- <section id="brands-2" class="pt-80 pb-70 brands-section division">
        <div class="container">


            <!-- BRANDS TITLE -->
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="brands-title text-center">
                        <h4 class="h4-xs">Indexing & Abstracting</h4>
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
                                <img class="img-fluid" src="images/brand-1.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-2.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-3.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-4.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-5.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-6.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-7.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-8.png" alt="brand-logo" />
                            </a>
                        </div>

                        <!-- BRAND LOGO IMAGE -->
                        <div class="brand-logo">
                            <a href="#">
                                <img class="img-fluid" src="images/brand-9.png" alt="brand-logo" />
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
    </section> --}}
    <!-- END BRANDS-2 -->
@endsection
