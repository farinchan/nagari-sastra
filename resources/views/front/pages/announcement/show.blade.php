@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($meta['description']), 160) }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Nagari Sastra">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($meta['description']), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('announcement.show', $announcement->slug) }}">
    <link rel="canonical" href="{{ route('announcement.show', $announcement->slug) }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')

    <style>
        .announcement-content {
            font-size: 15px;
            line-height: 1.85;
            color: #444;
        }
        .announcement-content p {
            margin-bottom: 18px;
        }
        .announcement-content img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin: 15px 0;
        }
        .announcement-content ul,
        .announcement-content ol {
            padding-left: 20px;
            margin-bottom: 18px;
        }
        .announcement-content li {
            margin-bottom: 6px;
        }
        .announcement-content blockquote {
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            background: #f8f9ff;
            border-radius: 0 6px 6px 0;
            font-style: italic;
            color: #555;
        }
        .announcement-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .announcement-content table th,
        .announcement-content table td {
            border: 1px solid #ddd;
            padding: 10px 14px;
            text-align: left;
        }
        .announcement-content table th {
            background: #f5f5f5;
            font-weight: 600;
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }
        .info-card-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .info-card-item:last-child {
            margin-bottom: 0;
        }
        .info-card-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 12px;
        }
        .info-card-icon span {
            color: #fff;
            font-size: 15px;
        }
        .attachment-box {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        .attachment-box:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.12);
        }
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f0f0f0;
            color: #555;
            font-size: 16px;
            transition: all 0.3s ease;
            text-decoration: none !important;
            margin-right: 8px;
        }
        .share-btn:hover {
            background: #667eea;
            color: #fff;
            transform: translateY(-2px);
        }
        .sidebar-announcement-item {
            display: flex;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.2s ease;
        }
        .sidebar-announcement-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .sidebar-announcement-item:hover h6 {
            color: #667eea;
        }
        .sidebar-num {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 12px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
        }
    </style>

    <!-- ANNOUNCEMENT DETAIL
    ============================================= -->
    <section id="announcement-detail" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row">

                <!-- MAIN CONTENT -->
                <div class="col-lg-8">
                    <div class="posts-wrapper pr-25">

                        <!-- TITLE -->
                        <h4 class="h4-lg mb-15" style="line-height: 1.4;">{{ $announcement->title }}</h4>

                        <!-- META INFO -->
                        <div class="info-card mb-30">
                            <div class="info-card-item">
                                <div class="info-card-icon">
                                    <span class="flaticon-clock"></span>
                                </div>
                                <div>
                                    <small class="grey-color d-block" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Publikasi</small>
                                    <p class="p-sm txt-500 mb-0">{{ $announcement->created_at->format('d F Y, H:i') }} WIB</p>
                                </div>
                            </div>
                            @if($announcement->user)
                                <div class="info-card-item">
                                    <div class="info-card-icon">
                                        <span class="flaticon-user"></span>
                                    </div>
                                    <div>
                                        <small class="grey-color d-block" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Penulis</small>
                                        <p class="p-sm txt-500 mb-0">{{ $announcement->user->name }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($announcement->updated_at && $announcement->updated_at->gt($announcement->created_at->addMinutes(1)))
                                <div class="info-card-item">
                                    <div class="info-card-icon">
                                        <span class="flaticon-refresh"></span>
                                    </div>
                                    <div>
                                        <small class="grey-color d-block" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Terakhir Diperbarui</small>
                                        <p class="p-sm txt-500 mb-0">{{ $announcement->updated_at->format('d F Y, H:i') }} WIB</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- CONTENT -->
                        <div class="announcement-content mb-30">
                            {!! $announcement->content !!}
                        </div>

                        <!-- ATTACHMENT -->
                        @if($announcement->file)
                            <div class="mb-30">
                                <h6 class="h6-md mb-15">
                                    <span class="flaticon-document mr-2" style="font-size: 16px;"></span>
                                    Lampiran
                                </h6>
                                <div class="attachment-box">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-15" style="width: 44px; height: 44px; background: #fef3f2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <span class="flaticon-pdf" style="font-size: 20px; color: #dc3545;"></span>
                                        </div>
                                        <div>
                                            <p class="p-sm txt-500 mb-0">File Lampiran</p>
                                            <small class="grey-color">Klik untuk mengunduh</small>
                                        </div>
                                    </div>
                                    <a href="{{ $announcement->getFile() }}" target="_blank" class="btn btn-tra-grey theme-hover btn-sm" download>
                                        <span class="flaticon-download mr-1" style="font-size: 12px;"></span> Unduh
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- SHARE -->
                        <div class="mb-30">
                            <h6 class="h6-sm mb-15">Bagikan</h6>
                            <div class="d-flex align-items-center">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('announcement.show', $announcement->slug)) }}"
                                   target="_blank" class="share-btn" title="Share to Facebook">
                                    <i class="fab fa-facebook-f" style="font-size: 14px;"></i>
                                    <span class="flaticon-share" style="font-size: 14px;"></span>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('announcement.show', $announcement->slug)) }}&text={{ urlencode($announcement->title) }}"
                                   target="_blank" class="share-btn" title="Share to Twitter">
                                    <span class="flaticon-twitter" style="font-size: 14px;"></span>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($announcement->title . ' ' . route('announcement.show', $announcement->slug)) }}"
                                   target="_blank" class="share-btn" title="Share to WhatsApp">
                                    <span class="flaticon-whatsapp" style="font-size: 14px;"></span>
                                </a>
                                <a href="javascript:void(0);" class="share-btn" title="Copy Link"
                                   onclick="copyLink()">
                                    <span class="flaticon-link" style="font-size: 14px;"></span>
                                </a>
                            </div>
                        </div>

                        <!-- BACK BUTTON -->
                        <div>
                            <a href="{{ route('announcement.index') }}" class="btn btn-tra-grey theme-hover btn-sm">
                                <span class="flaticon-left-arrow mr-2" style="font-size: 10px;"></span> Kembali ke Daftar Pengumuman
                            </a>
                        </div>

                    </div>
                </div>

                <!-- SIDEBAR -->
                <aside id="sidebar" class="col-lg-4">

                    <!-- LATEST ANNOUNCEMENTS -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Pengumuman Lainnya</h6>

                        @php
                            $latestAnnouncements = \App\Models\Announcement::where('is_active', true)
                                ->where('id', '!=', $announcement->id)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        @if($latestAnnouncements->isEmpty())
                            <p class="p-sm grey-color">Belum ada pengumuman lainnya.</p>
                        @else
                            @foreach($latestAnnouncements as $latest)
                                <a href="{{ route('announcement.show', $latest->slug) }}" class="d-block text-decoration-none">
                                    <div class="sidebar-announcement-item">
                                        <div class="sidebar-num">{{ $loop->iteration }}</div>
                                        <div>
                                            <h6 class="h6-xs mb-1 deepgrey-color" style="line-height: 1.4; transition: color 0.2s;">
                                                {{ Str::limit($latest->title, 55) }}
                                            </h6>
                                            <p class="p-sm grey-color mb-0" style="font-size: 12px;">
                                                <span class="flaticon-clock mr-1" style="font-size: 11px;"></span>
                                                {{ $latest->created_at->format('d M Y') }}
                                                @if($latest->file)
                                                    <span class="ml-2">
                                                        <span class="flaticon-pdf" style="font-size: 11px;"></span>
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <!-- QUICK LINKS -->
                    <div class="sidebar-div mb-50">
                        <h6 class="h6-xl">Menu Terkait</h6>
                        <ul class="simple-list grey-color" style="list-style: none; padding: 0;">
                            <li class="mb-10">
                                <a href="{{ route('event.index') }}" class="p-sm" style="text-decoration: none;">
                                    <span class="flaticon-right-arrow mr-2" style="font-size: 10px;"></span> Agenda Kegiatan
                                </a>
                            </li>
                            <li class="mb-10">
                                <a href="{{ route('news.index') }}" class="p-sm" style="text-decoration: none;">
                                    <span class="flaticon-right-arrow mr-2" style="font-size: 10px;"></span> Berita Terbaru
                                </a>
                            </li>
                            <li class="mb-10">
                                <a href="{{ route('contact.index') }}" class="p-sm" style="text-decoration: none;">
                                    <span class="flaticon-right-arrow mr-2" style="font-size: 10px;"></span> Hubungi Kami
                                </a>
                            </li>
                        </ul>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script>
    function copyLink() {
        var url = '{{ route('announcement.show', $announcement->slug) }}';
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                alert('Link berhasil disalin!');
            });
        } else {
            var input = document.createElement('input');
            input.value = url;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Link berhasil disalin!');
        }
    }
</script>
@endsection
