@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        {{-- Row 1: Summary Cards --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-3">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                    style="background-color: #50cd89;background-image:url('/metronic8/demo1/assets/media/patterns/vector-1.png')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1" id="total_income_card">Rp 0</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Pemasukan</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Manual</span>
                                <span class="fw-bold fs-6 text-white" id="manual_income">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Pembayaran</span>
                                <span class="fw-bold fs-6 text-white" id="payment_income">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                    style="background-color: #f1416c;background-image:url('/metronic8/demo1/assets/media/patterns/vector-2.png')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1" id="total_expense_card">Rp 0</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Pengeluaran</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Rata-rata/hari</span>
                                <span class="fw-bold fs-6 text-white" id="daily_avg_expense">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">vs Bulan Lalu</span>
                                <span class="fw-bold fs-6 text-white" id="expense_mom_badge">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                    style="background-color: #009ef7;background-image:url('/metronic8/demo1/assets/media/patterns/vector-3.png')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1" id="total_balance_card">Rp 0</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Saldo Bersih</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Status</span>
                                <span class="fw-bold fs-6 text-white" id="balance_status">Sehat</span>
                            </div>
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-white opacity-75">Rata-rata/hari</span>
                                <span class="fw-bold fs-6 text-white" id="daily_avg_income">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card card-flush h-xl-100">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1" id="transaction_count">0</span>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Transaksi</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-gray-500">Bulan Ini</span>
                                <span class="fw-bold fs-6 text-gray-900" id="monthly_transactions">0</span>
                            </div>
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bolder fs-6 text-gray-500">Invoice Pending</span>
                                <span class="fw-bold fs-6 text-warning" id="pending_invoices">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Trend Cashflow & Distribusi --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Trend Cashflow Harian</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Pemasukan vs Pengeluaran</span>
                        </h3>
                        <div class="card-toolbar">
                            <select class="form-select form-select-sm form-select-solid w-125px" id="chart_period">
                                <option value="30">30 Hari</option>
                                <option value="60">60 Hari</option>
                                <option value="90">90 Hari</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div id="cashflow_chart" class="px-5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Distribusi Transaksi</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Pemasukan vs Pengeluaran</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column align-items-center">
                        <div id="transaction_type_chart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Monthly Bar Chart & Top Pengeluaran --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Ringkasan Bulanan</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">12 bulan terakhir</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div id="monthly_bar_chart" class="px-5"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Top 5 Pengeluaran</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Kategori terbesar</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5" id="top_expenses_container">
                        <div class="text-center text-muted py-10">
                            <span class="spinner-border spinner-border-sm me-2"></span> Memuat data...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 4: Payment Methods & Recent Transactions --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-4">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Metode Pembayaran</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Distribusi transaksi</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 d-flex flex-column align-items-center">
                        <div id="payment_method_chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Transaksi Terbaru</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">10 transaksi terakhir</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0 px-5">
                        <div class="table-responsive">
                            <table class="table table-row-dashed gs-7 gy-4" id="recent_transactions_table">
                                <thead>
                                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                        <th>Transaksi</th>
                                        <th>Tanggal</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 5: Quick Actions --}}
        <div class="row g-5 gx-xl-10">
            <div class="col-xl-12">
                <div class="card card-flush">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Aksi Cepat</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Navigasi ke halaman terkait</span>
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-5">
                            <div class="col-xxl-3 col-md-6">
                                <a href="{{ route('back.finance.invoice.index') }}" class="card bg-light-warning hoverable card-xl-stretch mb-xl-8">
                                    <div class="card-body">
                                        <i class="ki-duotone ki-verification fs-2x text-warning">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="text-gray-900 fw-bold fs-6 mt-5">Verifikasi Pembayaran</div>
                                        <div class="text-gray-500 fw-semibold fs-7">Konfirmasi pembayaran masuk</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xxl-3 col-md-6">
                                <a href="{{ route('back.finance.report.index') }}" class="card bg-light-info hoverable card-xl-stretch mb-xl-8">
                                    <div class="card-body">
                                        <i class="ki-duotone ki-chart-pie-2 fs-2x text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="text-gray-900 fw-bold fs-6 mt-5">Laporan Jurnal</div>
                                        <div class="text-gray-500 fw-semibold fs-7">Detail Laporan Jurnal</div>
                                    </div>
                                </a>
                            </div>
                             <div class="col-xxl-3 col-md-6">
                                <a href="{{ route('back.finance.cashflow.index') }}" class="card bg-light-primary hoverable card-xl-stretch mb-xl-8">
                                    <div class="card-body">
                                        <i class="ki-duotone ki-chart-simple fs-2x text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        <div class="text-gray-900 fw-bold fs-6 mt-5">Kelola Cashflow</div>
                                        <div class="text-gray-500 fw-semibold fs-7">Lihat detail transaksi</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xxl-3 col-md-6">
                                <div class="card bg-light-success hoverable card-xl-stretch mb-xl-8" data-bs-toggle="modal" data-bs-target="#export_modal">
                                    <div class="card-body">
                                        <i class="ki-duotone ki-file-down fs-2x text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="text-gray-900 fw-bold fs-6 mt-5">Export Data</div>
                                        <div class="text-gray-500 fw-semibold fs-7">Download laporan Excel/PDF</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" tabindex="-1" id="export_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Export Laporan Cashflow</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('back.finance.cashflow.export') }}" method="GET">
                        <div class="mb-5">
                            <label class="form-label">Tipe Transaksi</label>
                            <select class="form-select form-select-solid" name="type">
                                <option value="all">Semua</option>
                                <option value="income">Pemasukan</option>
                                <option value="expense">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="row mb-5">
                            <div class="col-6">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control form-control-solid" name="date_start" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control form-control-solid" name="date_end" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Export Excel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<style>
    .hoverable {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .hoverable:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-xl-stretch {
        min-height: 120px;
    }
</style>
<script>
    $(document).ready(function() {
        loadCashflowData();
        setInterval(loadCashflowData, 300000);
        $('#chart_period').on('change', function() {
            loadCashflowData();
        });
    });

    function formatRupiah(amount) {
        return 'Rp ' + parseInt(amount || 0).toLocaleString('id-ID');
    }

    function loadCashflowData() {
        $('#total_income_card, #total_expense_card, #total_balance_card').text('Loading...');

        $.ajax({
            url: "{{ route('back.dashboard.cashflow.stat') }}",
            type: "GET",
            success: function(response) {
                if (response && response.summary) {
                    updateSummaryCards(response.summary);
                    updateCashflowChart(response.monthly_cashflow || []);
                    updateTransactionTypeChart(response.transaction_types || []);
                    updateMonthlyBarChart(response.monthly_aggregated || []);
                    updateRecentTransactionsTable(response.recent_transactions || []);
                    updateTopExpenses(response.top_expenses || []);
                    updatePaymentMethodChart(response.payment_methods || []);
                }
            },
            error: function(xhr, status, error) {
                handleAjaxError(xhr, status, error);
                updateSummaryCards({
                    total_income: 0, total_expense: 0, total_balance: 0,
                    finance_income: 0, payment_income: 0,
                    transaction_count: 0, monthly_transactions: 0,
                    daily_avg_income: 0, daily_avg_expense: 0,
                    prev_month_income: 0, prev_month_expense: 0,
                    current_month_income: 0, current_month_expense: 0,
                    pending_invoices: 0
                });
            }
        });
    }

    function updateSummaryCards(s) {
        $('#total_income_card').text(formatRupiah(s.total_income));
        $('#total_expense_card').text(formatRupiah(s.total_expense));
        $('#total_balance_card').text(formatRupiah(s.total_balance));
        $('#manual_income').text(formatRupiah(s.finance_income));
        $('#payment_income').text(formatRupiah(s.payment_income));
        $('#transaction_count').text((s.transaction_count || 0).toLocaleString('id-ID'));
        $('#monthly_transactions').text((s.monthly_transactions || 0).toLocaleString('id-ID'));
        $('#daily_avg_income').text(formatRupiah(s.daily_avg_income));
        $('#daily_avg_expense').text(formatRupiah(s.daily_avg_expense));
        $('#pending_invoices').text((s.pending_invoices || 0).toLocaleString('id-ID'));

        // MoM expense comparison
        var prevExp = s.prev_month_expense || 0;
        var curExp = s.current_month_expense || 0;
        if (prevExp > 0) {
            var pct = ((curExp - prevExp) / prevExp * 100).toFixed(1);
            var arrow = pct >= 0 ? '↑' : '↓';
            $('#expense_mom_badge').text(arrow + ' ' + Math.abs(pct) + '%');
        } else {
            $('#expense_mom_badge').text('-');
        }

        // Balance status
        if (s.total_balance >= 0) {
            $('#balance_status').text('Sehat');
        } else {
            $('#balance_status').text('Defisit');
        }
    }

    // ── Charts ─────────────────────────────────────────────────
    var cashflowChart = new ApexCharts(document.querySelector("#cashflow_chart"), {
        series: [{ name: 'Pemasukan', data: [] }, { name: 'Pengeluaran', data: [] }, { name: 'Saldo', data: [] }],
        chart: { height: 350, type: 'area', toolbar: { show: true }, zoom: { enabled: true } },
        colors: ['#50cd89', '#f1416c', '#009ef7'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] } },
        grid: { borderColor: '#e7e7e7' },
        xaxis: { categories: [], labels: { rotate: -45, style: { fontSize: '11px' } } },
        yaxis: { labels: { formatter: function(v) { return formatRupiah(v); } } },
        legend: { position: 'top', horizontalAlign: 'right' },
        tooltip: { y: { formatter: function(v) { return formatRupiah(v); } } }
    });

    var transactionTypeChart = new ApexCharts(document.querySelector("#transaction_type_chart"), {
        series: [],
        chart: { width: 320, type: 'donut' },
        labels: [],
        colors: ['#50cd89', '#f1416c'],
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: function(w) { return formatRupiah(w.globals.seriesTotals.reduce((a,b)=>a+b,0)); } } } } } },
        legend: { position: 'bottom' },
        tooltip: { y: { formatter: function(v) { return formatRupiah(v); } } }
    });

    var monthlyBarChart = new ApexCharts(document.querySelector("#monthly_bar_chart"), {
        series: [{ name: 'Pemasukan', data: [] }, { name: 'Pengeluaran', data: [] }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
        colors: ['#50cd89', '#f1416c'],
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: { categories: [] },
        yaxis: { labels: { formatter: function(v) { return formatRupiah(v); } } },
        fill: { opacity: 1 },
        legend: { position: 'top', horizontalAlign: 'left' },
        tooltip: { y: { formatter: function(v) { return formatRupiah(v); } } }
    });

    var paymentMethodChart = new ApexCharts(document.querySelector("#payment_method_chart"), {
        series: [],
        chart: { width: 320, type: 'donut' },
        labels: [],
        colors: ['#009ef7', '#7239ea', '#ffc700', '#50cd89', '#f1416c', '#181c32'],
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', formatter: function(w) { return w.globals.seriesTotals.reduce((a,b)=>a+b,0) + ' trx'; } } } } } },
        legend: { position: 'bottom' },
        tooltip: { y: { formatter: function(v) { return formatRupiah(v); } } }
    });

    cashflowChart.render();
    transactionTypeChart.render();
    monthlyBarChart.render();
    paymentMethodChart.render();

    function updateCashflowChart(data) {
        if (!data || data.length === 0) data = [];
        const categories = data.map(i => i.date).reverse();
        const income = data.map(i => parseInt(i.income || 0)).reverse();
        const expense = data.map(i => parseInt(i.expense || 0)).reverse();
        const balance = data.map(i => parseInt(i.balance || 0)).reverse();
        cashflowChart.updateSeries([{ name: 'Pemasukan', data: income }, { name: 'Pengeluaran', data: expense }, { name: 'Saldo', data: balance }]);
        cashflowChart.updateOptions({ xaxis: { categories: categories } });
    }

    function updateTransactionTypeChart(data) {
        if (!data || data.length === 0) { transactionTypeChart.updateSeries([]); return; }
        transactionTypeChart.updateSeries(data.map(i => parseInt(i.total || 0)));
        transactionTypeChart.updateOptions({ labels: data.map(i => i.type === 'income' ? 'Pemasukan' : 'Pengeluaran') });
    }

    function updateMonthlyBarChart(data) {
        if (!data || data.length === 0) { monthlyBarChart.updateSeries([{ name: 'Pemasukan', data: [] }, { name: 'Pengeluaran', data: [] }]); return; }
        const months = data.map(i => {
            const parts = i.month.split('-');
            const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
            return monthNames[parseInt(parts[1]) - 1] + ' ' + parts[0].substring(2);
        });
        monthlyBarChart.updateSeries([
            { name: 'Pemasukan', data: data.map(i => i.income) },
            { name: 'Pengeluaran', data: data.map(i => i.expense) }
        ]);
        monthlyBarChart.updateOptions({ xaxis: { categories: months } });
    }

    function updatePaymentMethodChart(data) {
        if (!data || data.length === 0) { paymentMethodChart.updateSeries([]); return; }
        paymentMethodChart.updateSeries(data.map(i => parseInt(i.total || 0)));
        paymentMethodChart.updateOptions({ labels: data.map(i => i.payment_method || 'Lainnya') });
    }

    function updateTopExpenses(data) {
        var container = $('#top_expenses_container');
        if (!data || data.length === 0) {
            container.html('<div class="text-center text-muted py-10">Belum ada data pengeluaran</div>');
            return;
        }
        var maxTotal = Math.max(...data.map(i => parseInt(i.total || 0)));
        var html = '';
        var colors = ['danger', 'warning', 'primary', 'info', 'success'];
        data.forEach(function(item, idx) {
            var total = parseInt(item.total || 0);
            var pct = maxTotal > 0 ? (total / maxTotal * 100).toFixed(0) : 0;
            var color = colors[idx % colors.length];
            html += '<div class="d-flex flex-stack mb-5">' +
                '<div class="d-flex align-items-center flex-row-fluid flex-wrap">' +
                    '<div class="flex-grow-1 me-2">' +
                        '<span class="text-gray-800 fw-bold d-block fs-6">' + (item.name || '-') + '</span>' +
                        '<span class="text-muted fw-semibold d-block fs-7">' + (item.count || 0) + ' transaksi</span>' +
                    '</div>' +
                    '<span class="badge badge-light-' + color + ' fs-7 fw-bold">' + formatRupiah(total) + '</span>' +
                '</div>' +
            '</div>' +
            '<div class="h-4px w-100 bg-light mb-5">' +
                '<div class="bg-' + color + ' rounded h-4px" style="width: ' + pct + '%;"></div>' +
            '</div>';
        });
        container.html(html);
    }

    function updateRecentTransactionsTable(data) {
        const tbody = $('#recent_transactions_table tbody');
        tbody.empty();
        if (data && data.length > 0) {
            data.forEach(function(t) {
                const typeClass = t.type === 'income' ? 'text-success' : 'text-danger';
                const typeIcon = t.type === 'income'
                    ? '<i class="ki-duotone ki-arrow-up fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>'
                    : '<i class="ki-duotone ki-arrow-down fs-5 text-danger"><span class="path1"></span><span class="path2"></span></i>';
                const typeSymbol = t.type === 'income' ? '+' : '-';
                const date = new Date(t.date).toLocaleDateString('id-ID');
                const amount = parseInt(t.amount || 0);
                tbody.append(`
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px me-3">
                                    <span class="symbol-label bg-light-${t.type === 'income' ? 'success' : 'danger'}">${typeIcon}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">${t.name || 'Transaksi'}</span>
                                    <span class="text-muted fw-semibold fs-7">${t.description || '-'}</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-gray-600 fw-bold">${date}</td>
                        <td class="text-end">
                            <span class="fw-bold ${typeClass}">${typeSymbol}${formatRupiah(amount).replace('Rp ', 'Rp ')}</span>
                        </td>
                    </tr>
                `);
            });
        } else {
            tbody.append('<tr><td colspan="3" class="text-center text-gray-500 py-5">Tidak ada data transaksi</td></tr>');
        }
    }

    function handleAjaxError(xhr, status, error) {
        console.error('AJAX Error:', {xhr, status, error});
        let message = 'Terjadi kesalahan saat memuat data';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Error', text: message, icon: 'error', timer: 3000 });
        }
    }
</script>
@endsection
