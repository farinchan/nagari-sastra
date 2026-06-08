@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">

        {{-- Row 1: Summary Cards --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                    style="background-color: #009ef7;background-image:url('/metronic8/demo1/assets/media/patterns/vector-1.png')">
                    <div class="card-header pt-5 pb-4">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1">{{ number_format($total_news) }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Berita</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Published</span>
                                <span class="fw-bold fs-6 text-white">{{ number_format($total_published) }}</span>
                            </div>
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Draft</span>
                                <span class="fw-bold fs-6 text-white">{{ number_format($total_draft) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                    style="background-color: #50cd89;background-image:url('/metronic8/demo1/assets/media/patterns/vector-2.png')">
                    <div class="card-header pt-5 pb-4">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1">{{ number_format($total_views) }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Views</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Rata-rata/berita</span>
                                <span class="fw-bold fs-6 text-white">{{ $total_news > 0 ? number_format($total_views / $total_news) : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                    style="background-color: #7239ea;background-image:url('/metronic8/demo1/assets/media/patterns/vector-3.png')">
                    <div class="card-header pt-5 pb-4">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1">{{ number_format($total_comments) }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Komentar</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Rata-rata/berita</span>
                                <span class="fw-bold fs-6 text-white">{{ $total_news > 0 ? number_format($total_comments / $total_news, 1) : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5 pb-4">
                        <div class="card-title d-flex flex-column">
                            <span class="card-label fw-bold text-gray-900">Berita per Kategori</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-7">{{ $news_by_category->count() }} kategori</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 px-5" style="max-height: 160px; overflow-y: auto;">
                        @foreach($news_by_category as $cat)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-gray-700 fw-semibold fs-7">{{ $cat->category_name }}</span>
                                <span class="badge badge-light-primary fs-8">{{ $cat->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Visitor Trend & Distribution --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Trend Pengunjung Berita</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">30 hari terakhir</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div id="chart_visitor_trend" class="px-5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Platform OS</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Distribusi pengunjung</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column align-items-center">
                        <div id="chart_platform"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Berita Terpopuler & Browser --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Berita Terpopuler</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Berdasarkan jumlah views</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 px-5">
                        <div class="table-responsive">
                            <table class="table table-row-dashed gs-7 gy-4">
                                <thead>
                                    <tr class="fw-semibold fs-7 text-gray-500 text-uppercase border-bottom border-gray-200">
                                        <th>Berita</th>
                                        <th class="text-end min-w-80px">Views</th>
                                        <th class="text-end min-w-80px">Komentar</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($news_popular as $idx => $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-30px me-3">
                                                        <span class="symbol-label bg-light-{{ ['primary','success','info','warning','danger'][$idx % 5] }} fw-bold text-{{ ['primary','success','info','warning','danger'][$idx % 5] }}">
                                                            {{ $idx + 1 }}
                                                        </span>
                                                    </div>
                                                    <a class="text-gray-800 text-hover-primary fw-bold" href="{{ route('news.detail', $item->slug) }}" target="_blank">
                                                        {{ Str::limit($item->title, 60) }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-light-success fs-7">
                                                    <i class="ki-duotone ki-eye fs-7 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    {{ number_format($item->viewers_count) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-light-primary fs-7">
                                                    <i class="ki-duotone ki-message-text-2 fs-7 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    {{ $item->comments->count() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Browser</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Distribusi pengunjung</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column align-items-center">
                        <div id="chart_browser"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 4: Berita Terbaru & Penulis --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Berita Terbaru</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">5 berita terakhir</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 px-5">
                        <div class="table-responsive">
                            <table class="table table-row-dashed gs-7 gy-4">
                                <thead>
                                    <tr class="fw-semibold fs-7 text-gray-500 text-uppercase border-bottom border-gray-200">
                                        <th>Berita</th>
                                        <th class="text-end min-w-100px">Tanggal</th>
                                        <th class="text-end min-w-80px">Views</th>
                                        <th class="text-end min-w-80px">Komentar</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($news_new as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->thumbnail)
                                                        <div class="symbol symbol-40px symbol-2by3 me-3">
                                                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="" class="object-fit-cover" loading="lazy">
                                                        </div>
                                                    @endif
                                                    <a class="text-gray-800 text-hover-primary fw-bold" href="{{ route('news.detail', $item->slug) }}" target="_blank">
                                                        {{ Str::limit($item->title, 50) }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-end text-muted fs-7">{{ $item->created_at->format('d M Y') }}</td>
                                            <td class="text-end">
                                                <span class="badge badge-light-success fs-7">{{ $item->viewers->count() }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-light-primary fs-7">{{ $item->comments->count() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Penulis Berita</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Kontribusi tertinggi</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        @foreach ($news_writer as $idx => $writer)
                            @php
                                $colors = ['primary','success','info','warning','danger'];
                                $color = $colors[$idx % 5];
                                $maxTotal = $news_writer->max('total');
                                $pct = $maxTotal > 0 ? ($writer->total / $maxTotal * 100) : 0;
                            @endphp
                            <div class="d-flex flex-stack mb-4">
                                <div class="d-flex align-items-center me-3">
                                    <div class="symbol symbol-35px me-3">
                                        <span class="symbol-label bg-light-{{ $color }} fw-bold text-{{ $color }}">
                                            {{ strtoupper(substr($writer->name ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6">{{ $writer->name ?? 'User #' . $writer->user_id }}</span>
                                        <span class="text-muted fw-semibold fs-7">{{ $writer->email ?? '' }}</span>
                                    </div>
                                </div>
                                <span class="badge badge-light-{{ $color }} fs-7 fw-bold">{{ $writer->total }} berita</span>
                            </div>
                            <div class="h-4px w-100 bg-light mb-5">
                                <div class="bg-{{ $color }} rounded h-4px" style="width: {{ $pct }}%;"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // ── Area Chart: Visitor Trend ──────────────────────────────
    var chartVisitorTrend = new ApexCharts(document.querySelector("#chart_visitor_trend"), {
        series: [{ name: 'Pengunjung', data: [] }],
        chart: { height: 350, type: 'area', toolbar: { show: true }, zoom: { enabled: true } },
        colors: ['#009ef7'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
        grid: { borderColor: '#e7e7e7' },
        xaxis: { categories: [], labels: { rotate: -45, style: { fontSize: '11px' } } },
        yaxis: { labels: { formatter: function(v) { return parseInt(v).toLocaleString('id-ID'); } } },
        tooltip: { y: { formatter: function(v) { return v + ' pengunjung'; } } }
    });
    chartVisitorTrend.render();

    // ── Donut Chart: Platform OS ──────────────────────────────
    var chartPlatform = new ApexCharts(document.querySelector("#chart_platform"), {
        series: [],
        chart: { width: 320, type: 'donut' },
        labels: [],
        colors: ['#009ef7', '#50cd89', '#ffc700', '#f1416c', '#7239ea', '#181c32'],
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total',
            formatter: function(w) { return w.globals.seriesTotals.reduce((a,b) => a+b, 0).toLocaleString('id-ID'); }
        } } } } },
        legend: { position: 'bottom' },
    });
    chartPlatform.render();

    // ── Donut Chart: Browser ──────────────────────────────────
    var chartBrowser = new ApexCharts(document.querySelector("#chart_browser"), {
        series: [],
        chart: { width: 320, type: 'donut' },
        labels: [],
        colors: ['#50cd89', '#009ef7', '#ffc700', '#f1416c', '#7239ea', '#181c32'],
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total',
            formatter: function(w) { return w.globals.seriesTotals.reduce((a,b) => a+b, 0).toLocaleString('id-ID'); }
        } } } } },
        legend: { position: 'bottom' },
    });
    chartBrowser.render();

    // ── Load Data via AJAX ────────────────────────────────────
    $.ajax({
        url: "{{ route('back.dashboard.news.stat') }}",
        type: "GET",
        success: function(response) {
            // Visitor trend
            if (response.news_viewer_monthly && response.news_viewer_monthly.length > 0) {
                chartVisitorTrend.updateSeries([{
                    name: 'Pengunjung',
                    data: response.news_viewer_monthly.map(i => i.total)
                }]);
                chartVisitorTrend.updateOptions({
                    xaxis: { categories: response.news_viewer_monthly.map(i => i.date) }
                });
            }

            // Platform
            if (response.news_viewer_platfrom && response.news_viewer_platfrom.length > 0) {
                chartPlatform.updateSeries(response.news_viewer_platfrom.map(i => i.total));
                chartPlatform.updateOptions({
                    labels: response.news_viewer_platfrom.map(i => i.platform == '0' ? 'Unknown' : i.platform)
                });
            }

            // Browser
            if (response.news_viewer_browser && response.news_viewer_browser.length > 0) {
                chartBrowser.updateSeries(response.news_viewer_browser.map(i => i.total));
                chartBrowser.updateOptions({
                    labels: response.news_viewer_browser.map(i => i.browser == '0' ? 'Unknown' : i.browser)
                });
            }
        },
        error: function(xhr) {
            console.error('Failed to load news stats', xhr);
        }
    });
</script>
@endsection
