@extends('back.app')

@section('content')
    <div id="kt_content_container" class=" container-xxl ">

            <!--begin::Account Switcher-->
            @if($accounts && $accounts->count() > 0)
            <div class="d-flex justify-content-end mb-5">
                <div class="d-flex align-items-center">
                    <span class="fw-semibold text-muted me-3">Akun Email:</span>
                    <select class="form-select form-select-solid form-select-sm w-250px" id="accountSwitcher">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" @if($selectedAccount && $selectedAccount->id == $acc->id) selected @endif>
                                {{ $acc->name }} ({{ $acc->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif

            <!--begin::Row 1 - Main Stats-->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-sms fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-2">{{ number_format($stats['total_emails']) }}</span>
                                    <span class="text-muted fw-semibold d-block fs-7">Total Email</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-duotone ki-eye fs-2x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-2">{{ number_format($stats['total_unread']) }}</span>
                                    <span class="text-muted fw-semibold d-block fs-7">Belum Dibaca</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-calendar fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-2">{{ number_format($stats['emails_today']) }}</span>
                                    <span class="text-muted fw-semibold d-block fs-7">Email Hari Ini</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card card-flush h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
                                <div class="symbol symbol-50px me-4">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-chart-simple fs-2x text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-2">{{ number_format($stats['emails_this_month']) }}</span>
                                    <span class="text-muted fw-semibold d-block fs-7">Email Bulan Ini</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Row 2 - Folder Breakdown-->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col">
                    <div class="card card-flush bg-light-primary">
                        <div class="card-body py-4 text-center">
                            <span class="text-primary fw-bold fs-2">{{ number_format($stats['total_inbox']) }}</span>
                            <span class="text-gray-600 fw-semibold d-block fs-7">Inbox</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush bg-light-success">
                        <div class="card-body py-4 text-center">
                            <span class="text-success fw-bold fs-2">{{ number_format($stats['total_sent']) }}</span>
                            <span class="text-gray-600 fw-semibold d-block fs-7">Sent</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush bg-light-danger">
                        <div class="card-body py-4 text-center">
                            <span class="text-danger fw-bold fs-2">{{ number_format($stats['total_spam']) }}</span>
                            <span class="text-gray-600 fw-semibold d-block fs-7">Spam</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush bg-light-dark">
                        <div class="card-body py-4 text-center">
                            <span class="text-dark fw-bold fs-2">{{ number_format($stats['total_trash']) }}</span>
                            <span class="text-gray-600 fw-semibold d-block fs-7">Trash</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush bg-light-warning">
                        <div class="card-body py-4 text-center">
                            <span class="text-warning fw-bold fs-2">{{ number_format($stats['total_unread']) }}</span>
                            <span class="text-gray-600 fw-semibold d-block fs-7">Unread</span>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Row 3 - Chart + Quick Stats-->
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col-md-8">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Aktivitas Email</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">30 hari terakhir</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <canvas id="emailChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-flush h-100">
                        <div class="card-header pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold text-dark">Ringkasan</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Marketing & Kontak</span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-people fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('back.crm.email.groups') }}" class="text-dark fw-bold text-hover-primary fs-6">Grup Kontak</a>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ $totalGroups }} grup</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-address-book fs-2x text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('back.crm.email.groups') }}" class="text-dark fw-bold text-hover-primary fs-6">Total Kontak</a>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ $totalContacts }} kontak</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-7">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-duotone ki-rocket fs-2x text-warning"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('back.crm.email.campaigns') }}" class="text-dark fw-bold text-hover-primary fs-6">Campaigns</a>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ $totalCampaigns }} campaign</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px me-4">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-calendar fs-2x text-info"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-dark fw-bold fs-6">Minggu Ini</span>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ $stats['emails_this_week'] }} email</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--begin::Row 4 - Recent Campaigns-->
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Campaign Terbaru</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">5 campaign terakhir</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('back.crm.email.campaigns') }}" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-arrow-right fs-4"></i> Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>Nama</th>
                                    <th>Grup</th>
                                    <th>Status</th>
                                    <th>Terkirim/Total</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCampaigns as $campaign)
                                <tr>
                                    <td class="fw-bold text-gray-800">{{ $campaign->name }}</td>
                                    <td>
                                        @if($campaign->group)
                                            <span class="badge badge-light-primary">{{ $campaign->group->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($campaign->status)
                                            @case('draft')
                                                <span class="badge badge-light-secondary">Draft</span>
                                                @break
                                            @case('sending')
                                                <span class="badge badge-light-warning">Mengirim</span>
                                                @break
                                            @case('sent')
                                                <span class="badge badge-light-success">Terkirim</span>
                                                @break
                                            @case('failed')
                                                <span class="badge badge-light-danger">Gagal</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $campaign->sent_count }}/{{ $campaign->total_recipients }}</td>
                                    <td class="text-muted">{{ $campaign->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">Belum ada campaign</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Account Switcher
    document.getElementById('accountSwitcher')?.addEventListener('change', function() {
        window.location.href = '{{ route("back.crm.email.overview") }}?account_id=' + this.value;
    });

    // Chart
    var chartData = @json($chartData);
    var ctx = document.getElementById('emailChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Email per Hari',
                    data: chartData.data,
                    borderColor: '#3699FF',
                    backgroundColor: 'rgba(54, 153, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#3699FF',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            maxTicksLimit: 15
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
