@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="{{ $news->user->name ?? 'Nagari Sastra' }}">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('journal.detail', $journal->url_path) }}">
    <link rel="canonical" href="{{ route('journal.detail', $journal->url_path) }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')
    <!-- PROJECT DETAILS-2
       ============================================= -->
    <section id="project-details-2" class="wide-60 single-project division">
        <div class="container">
            <div class="row">


                <!-- PROJECT IMAGES -->
                <div class="col-lg-5">
                    <div class="project-2-img text-center mb-40">


                        <!-- IMAGE #1 -->
                        <div class="project-image">
                            <div class="hover-overlay">

                                <!-- Image -->
                                <img class="img-fluid" src="{{ $journal->getJournalThumbnail() }}" alt="gallery-image" />
                                <div class="item-overlay"></div>

                                <!-- Image Zoom -->
                                <div class="image-description white-color">
                                    <div class="image-data ico-70">
                                        <a class="image-link" href="{{ $journal->getJournalThumbnail() }}"><span
                                                class="flaticon-search"></span></a>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- END IMAGE #1 -->



                    </div>
                    <div class="mt-20">
                        <a href="{{ $journal->url }}" class="btn btn-md btn-tra-grey rose-hover w-100 d-block">
                            Visit Journal Website
                        </a>
                    </div>
                    <div class="mt-20">
                        <a href="{{ route('payment.index') }}" class="btn btn-md btn-tra-grey rose-hover w-100 d-block">
                            Pembayaran Publikasi
                        </a>
                    </div>
                </div> <!-- END PROJECT IMAGES -->


                <!-- PROJECT DISCRIPTION -->
                <div class="col-lg-7">
                    <div class="project-2-description mt-30 pl-15 mb-40">


                        <!-- PROJECT TITLE -->
                        <div class="project-title">
                            <h3 class="h3-lg">{{ $journal->title }}</h3>
                        </div>

                        <!-- PROJECT DETAILS -->
                        <div class="project-details">

                            <!-- PROJECT INFO -->
                            <div class="project-info mb-50">

                                <!-- Title -->
                                <h5 class="h5-xs">Journal Details:</h5>

                                <!-- Project Data -->
                                <p>e-ISSN: <span>{{ $journal?->onlineIssn??"-" }}</span></p>
                                <p>p-ISSN: <span>{{ $journal?->printIssn??"-" }}</span></p>
                                <p>Editor Chief: <span>{{ $journal?->editor_chief_name??"-" }}</span></p>
                                <p>Indexing: <span>{{ $journal?->indexing?implode(", ", $journal->indexing):"-" }}</span></p>
                                <p>Publication Fee: <span> @money($journal?->author_fee)</span></p>

                            </div> <!-- END PROJECT INFO -->




                        </div> <!-- END PROJECT DETAILS -->
                        <!-- PROJECT TEXT -->
                        <div class="project-text">

                            <!-- Text -->
                            <p class="p-md">
                                {!! $journal->description !!}
                            </p>

                        </div> <!-- END PROJECT TEXT -->
                    </div>
                </div> <!-- END PROJECT DISCRIPTION -->


            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> <!-- END PROJECT DETAILS-2 -->
@endsection

@section('scripts')
@endsection
