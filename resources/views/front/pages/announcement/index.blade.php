@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Nagari Sastra">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('announcement.index') }}">
    <link rel="canonical" href="{{ route('announcement.index') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')

    <style>
        .announcement-card {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .announcement-card:hover {
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.12);
            transform: translateY(-3px);
        }
        .announcement-card a {
            text-decoration: none !important;
        }
        .announcement-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }
        .announcement-meta span {
            font-size: 13px;
            color: #888;
        }
        .announcement-meta span .flaticon-clock,
        .announcement-meta span .flaticon-user {
            font-size: 12px;
            margin-right: 4px;
        }
        .announcement-excerpt {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .attachment-label {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f5f5f5;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
        }
    </style>

    <!-- ANNOUNCEMENT LIST
    ============================================= -->
    <section id="announcement-1" class="wide-60 blog-page-section division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">
                        <h3 class="h3-md">Pengumuman</h3>
                        <p class="p-xl grey-color">Informasi dan pengumuman resmi terbaru dari Nagari Sastra Group</p>
                    </div>
                </div>
            </div>

            <!-- ANNOUNCEMENT LIST -->
            @if($list_announcement->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-4">
                        <div class="bg-white p-5" style="border: 2px dashed #ddd; border-radius: 6px; max-width: 500px; margin: 0 auto;">
                            <div class="ico-55 mb-20 grey-color"><span class="flaticon-chat-1"></span></div>
                            <h5 class="h5-xs">Belum Ada Pengumuman</h5>
                            <p class="p-md grey-color mb-0">Pengumuman terbaru akan ditampilkan di sini.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        @foreach($list_announcement as $announcement)
                            <a href="{{ route('announcement.show', $announcement->slug) }}" class="d-block">
                                <div class="announcement-card wow fadeInUp" data-wow-delay="{{ $loop->index * 0.08 }}s">
                                    <!-- Meta -->
                                    <div class="announcement-meta">
                                        <span>
                                            <span class="flaticon-clock"></span>
                                            {{ $announcement->created_at->format('d M Y') }}
                                        </span>
                                        @if($announcement->user)
                                            <span>
                                                <span class="flaticon-user"></span>
                                                {{ $announcement->user->name }}
                                            </span>
                                        @endif
                                        @if($announcement->file)
                                            <span class="attachment-label">
                                                <span class="flaticon-pdf" style="font-size: 13px;"></span>
                                                Lampiran
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Title -->
                                    <h6 class="h6-md deepgrey-color mb-10" style="line-height: 1.4;">
                                        {{ $announcement->title }}
                                    </h6>

                                    <!-- Excerpt -->
                                    <p class="announcement-excerpt mb-10">
                                        {{ Str::limit(strip_tags($announcement->content), 150) }}
                                    </p>

                                    <!-- Footer -->
                                    <span class="p-sm theme-color" style="font-size: 13px; font-weight: 500;">
                                        Baca Selengkapnya <span class="flaticon-right-arrow ml-1" style="font-size: 10px;"></span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- PAGINATION -->
                <div class="row mt-30">
                    <div class="col-12 text-center">
                        {{ $list_announcement->links() }}
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
