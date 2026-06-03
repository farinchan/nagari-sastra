@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Nagari Sastra">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('event.index') }}">
    <link rel="canonical" href="{{ route('event.index') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')

    <!-- EVENT LIST
    ============================================= -->
    <section id="events-1" class="wide-60 blog-page-section division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">
                        <h3 class="h3-md">Agenda Kegiatan</h3>
                        <p class="p-xl grey-color">Ikuti berbagai kegiatan akademik, seminar, dan workshop yang diselenggarakan oleh Nagari Sastra</p>
                    </div>
                </div>
            </div>

            <!-- EVENTS -->
            @if($list_event->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-4">
                        <div class="bg-white p-5" style="border: 2px dashed #ddd; border-radius: 6px; max-width: 500px; margin: 0 auto;">
                            <div class="ico-55 mb-20 grey-color"><span class="flaticon-calendar"></span></div>
                            <h5 class="h5-xs">Belum Ada Agenda</h5>
                            <p class="p-md grey-color mb-0">Kegiatan terbaru akan ditampilkan di sini.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach($list_event as $event)
                        <div class="col-md-6 col-lg-4">
                            <div class="blog-1-post radius-06 wow fadeInUp" data-wow-delay="{{ $loop->index * 0.1 }}s">

                                <!-- IMAGE -->
                                <div class="blog-post-img" style="position: relative; overflow: hidden;">
                                    <a href="{{ route('event.show', $event->slug) }}">
                                        <img class="img-fluid" src="{{ $event->getThumbnail() }}" alt="{{ $event->name }}"
                                             style="width: 100%; height: 220px; object-fit: cover;">
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
                                    <!-- Date -->
                                    <p class="post-date mb-10">
                                        <span class="flaticon-clock mr-1"></span>
                                        {{ $event->datetime ? \Carbon\Carbon::parse($event->datetime)->format('d M Y, H:i') : '-' }}
                                    </p>

                                    <h6 class="h6-xs" style="line-height: 1.4; margin-bottom: 8px;">
                                        <a href="{{ route('event.show', $event->slug) }}">{{ Str::limit($event->name, 55) }}</a>
                                    </h6>

                                    <p class="p-sm grey-color mb-15">
                                        <span class="flaticon-pin mr-1" style="font-size: 12px;"></span>
                                        {{ $event->location ?? 'Online' }}
                                    </p>

                                    <a href="{{ route('event.show', $event->slug) }}" class="btn btn-tra-grey theme-hover btn-sm btn-block">
                                        Detail Kegiatan
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- PAGINATION -->
                <div class="row mt-30">
                    <div class="col-12 text-center">
                        {{ $list_event->links() }}
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
