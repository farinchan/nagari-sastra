@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">

        {{-- Summary Cards --}}
        <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-body d-flex align-items-center py-4">
                        <div class="symbol symbol-45px me-4">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-chart-line fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2hx fw-bold text-gray-900 lh-1" id="stat-total">-</div>
                            <div class="text-gray-500 fw-semibold fs-7">Total Log</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-body d-flex align-items-center py-4">
                        <div class="symbol symbol-45px me-4">
                            <span class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-calendar fs-2x text-success"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2hx fw-bold text-gray-900 lh-1" id="stat-today">-</div>
                            <div class="text-gray-500 fw-semibold fs-7">Hari Ini</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-body d-flex align-items-center py-4">
                        <div class="symbol symbol-45px me-4">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-duotone ki-people fs-2x text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2hx fw-bold text-gray-900 lh-1" id="stat-users">-</div>
                            <div class="text-gray-500 fw-semibold fs-7">User Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-body d-flex align-items-center py-4">
                        <div class="symbol symbol-45px me-4">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-duotone ki-abstract-26 fs-2x text-info"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                        </div>
                        <div>
                            <div class="fs-2hx fw-bold text-gray-900 lh-1" id="stat-types">-</div>
                            <div class="text-gray-500 fw-semibold fs-7">Tipe Model</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <h3 class="card-label fw-bold text-gray-900">
                        <i class="ki-duotone ki-scroll fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        Activity Log
                    </h3>
                </div>
                <div class="card-toolbar gap-3">
                    <button type="button" class="btn btn-sm btn-light-primary" id="btn-toggle-filters">
                        <i class="ki-duotone ki-filter fs-5"><span class="path1"></span><span class="path2"></span></i> Filter
                    </button>
                    <button type="button" class="btn btn-sm btn-light-danger" id="btn-reset-filters">
                        <i class="ki-duotone ki-cross fs-5"><span class="path1"></span><span class="path2"></span></i> Reset
                    </button>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div class="card-body pt-0 pb-0" id="filter-panel" style="display: none;">
                <div class="bg-light rounded p-5 mb-5">
                    <div class="row g-4">
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold fs-7">Log Name</label>
                            <select class="form-select form-select-sm form-select-solid" id="filter-log-name" data-control="select2" data-placeholder="Semua" data-allow-clear="true">
                                <option value="all">Semua</option>
                                @foreach($log_names as $logName)
                                    <option value="{{ $logName }}">{{ $logName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold fs-7">Event</label>
                            <select class="form-select form-select-sm form-select-solid" id="filter-event" data-control="select2" data-placeholder="Semua" data-allow-clear="true">
                                <option value="all">Semua</option>
                                @foreach($events as $event)
                                    <option value="{{ $event }}">{{ ucfirst($event) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold fs-7">Subject</label>
                            <select class="form-select form-select-sm form-select-solid" id="filter-subject-type" data-control="select2" data-placeholder="Semua" data-allow-clear="true">
                                <option value="all">Semua</option>
                                @foreach($subject_types as $type)
                                    <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold fs-7">User</label>
                            <input type="text" class="form-control form-control-sm form-control-solid" id="filter-causer" placeholder="Cari user...">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold fs-7">Dari Tanggal</label>
                            <input type="date" class="form-control form-control-sm form-control-solid" id="filter-date-from">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold fs-7">Sampai Tanggal</label>
                            <input type="date" class="form-control form-control-sm form-control-solid" id="filter-date-to">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-dashed align-middle fs-6 gy-4" id="activity-log-table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-30px">ID</th>
                                <th class="min-w-80px">Log</th>
                                <th class="min-w-150px">Deskripsi</th>
                                <th class="min-w-100px">Subject</th>
                                <th class="min-w-60px">Event</th>
                                <th class="min-w-100px">User</th>
                                <th class="min-w-200px">Properties</th>
                                <th class="min-w-120px">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
<script>
    var table;

    $(document).ready(function () {
        // ── DataTable (Server-side) ──
        table = $('#activity-log-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('back.logs.activity.data') }}",
                type: "GET",
                data: function (d) {
                    d.log_name = $('#filter-log-name').val();
                    d.event = $('#filter-event').val();
                    d.subject_type = $('#filter-subject-type').val();
                    d.causer = $('#filter-causer').val();
                    d.date_from = $('#filter-date-from').val();
                    d.date_to = $('#filter-date-to').val();
                },
                error: function (xhr, error, thrown) {
                    console.error('DataTable AJAX error:', error, thrown);
                    console.error('Response:', xhr.responseText);
                }
            },
            columns: [
                { data: 'id', className: 'text-center' },
                { data: 'log_name' },
                { data: 'description' },
                { data: 'subject' },
                { data: 'event', className: 'text-center' },
                { data: 'causer' },
                { data: 'properties', orderable: false, searchable: false },
                { data: 'created_at' },
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            drawCallback: function (settings) {
                var api = this.api();
                var json = api.ajax.json();
                if (json) {
                    $('#stat-total').text(json.recordsTotal.toLocaleString('id-ID'));
                }
            },
            language: {
                processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: "Tidak ada data log",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ log",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total)",
                lengthMenu: "Tampilkan _MENU_ data",
                search: "",
                searchPlaceholder: "Cari log...",
                paginate: {
                    first: '<i class="ki-duotone ki-double-left fs-5"></i>',
                    last: '<i class="ki-duotone ki-double-right fs-5"></i>',
                    next: '<i class="ki-duotone ki-right fs-5"></i>',
                    previous: '<i class="ki-duotone ki-left fs-5"></i>'
                }
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });

        // ── Filter toggle ──
        $('#btn-toggle-filters').on('click', function () {
            $('#filter-panel').slideToggle(200);
        });

        // ── Apply filters on change ──
        $('#filter-log-name, #filter-event, #filter-subject-type').on('change', function () {
            table.draw();
        });

        var causerTimeout;
        $('#filter-causer').on('keyup', function () {
            clearTimeout(causerTimeout);
            causerTimeout = setTimeout(function () { table.draw(); }, 500);
        });

        $('#filter-date-from, #filter-date-to').on('change', function () {
            table.draw();
        });

        // ── Reset filters ──
        $('#btn-reset-filters').on('click', function () {
            $('#filter-log-name').val('all').trigger('change');
            $('#filter-event').val('all').trigger('change');
            $('#filter-subject-type').val('all').trigger('change');
            $('#filter-causer').val('');
            $('#filter-date-from').val('');
            $('#filter-date-to').val('');
            table.draw();
        });

        // ── Load stat cards ──
        loadStats();
    });

    function loadStats() {
        $.get("{{ route('back.logs.activity.data') }}", { length: 0 }, function (res) {
            $('#stat-total').text(res.recordsTotal.toLocaleString('id-ID'));
        });

        // Count today's logs
        $.get("{{ route('back.logs.activity.data') }}", {
            length: 0,
            date_from: new Date().toISOString().split('T')[0],
            date_to: new Date().toISOString().split('T')[0]
        }, function (res) {
            $('#stat-today').text(res.recordsFiltered.toLocaleString('id-ID'));
        });

        // Count unique users
        $('#stat-users').text('{{ \Spatie\Activitylog\Models\Activity::select("causer_id")->whereNotNull("causer_id")->distinct()->count() }}');
        $('#stat-types').text('{{ \Spatie\Activitylog\Models\Activity::select("subject_type")->whereNotNull("subject_type")->distinct()->count() }}');
    }
</script>
@endsection
