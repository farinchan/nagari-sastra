@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    Management Invoice
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="btn-group">
                        <a href="#" id="export_excel" class="btn btn-light-primary">
                            <i class="ki-duotone ki-file-down fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row mb-10">
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-bold">Status Pembayaran</label>
                        <select class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Pilih Status" name="filter_status" id="filter_status">
                            <option value="" selected>Semua Status</option>
                            <option value="paid">Lunas</option>
                            <option value="unpaid">Belum Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-bold">Dari Tanggal</label>
                        <input type="date" name="filter_date_start" class="form-control form-control-solid"
                            placeholder="Date Start" id="filter_date_start"
                            value="{{ \Carbon\Carbon::createFromDate(now()->year, 1, 1)->format('Y-m-d') }}" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-bold">Sampai Tanggal</label>
                        <input type="date" name="filter_date_end" class="form-control form-control-solid"
                            placeholder="Date End" id="filter_date_end"
                            value="{{ \Carbon\Carbon::createFromDate(now()->year, 12, 31)->format('Y-m-d') }}" />
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="d-flex gap-5 flex-wrap">
                            <div class="card bg-light-success border-0 flex-fill">
                                <div class="card-body py-4">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-6 fw-bold text-gray-600 me-3">Total Invoice:</span>
                                        <span class="fs-4 fw-bolder text-gray-800" id="total_invoices">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light-primary border-0 flex-fill">
                                <div class="card-body py-4">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-6 fw-bold text-gray-600 me-3">Total Tagihan:</span>
                                        <span class="fs-4 fw-bolder text-gray-800" id="total_amount">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light-success border-0 flex-fill">
                                <div class="card-body py-4">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-6 fw-bold text-gray-600 me-3">Sudah Lunas:</span>
                                        <span class="fs-4 fw-bolder text-success" id="total_paid">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light-warning border-0 flex-fill">
                                <div class="card-body py-4">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-6 fw-bold text-gray-600 me-3">Belum Lunas:</span>
                                        <span class="fs-4 fw-bolder text-warning" id="total_unpaid">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_invoice_management">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-200px">Invoice</th>
                            <th class="min-w-350px">Items</th>
                            <th class="min-w-150px">Tagihan</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-120px">Jatuh Tempo</th>
                            <th class="min-w-100px">File</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#table_invoice_management').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('back.finance.invoice.datatable') }}",
                    data: function(d) {
                        d.status = $('#filter_status').val();
                        d.date_start = $('#filter_date_start').val();
                        d.date_end = $('#filter_date_end').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'invoice_info',
                        name: 'invoice_info'
                    },
                    {
                        data: 'items_info',
                        name: 'items_info'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'due_date',
                        name: 'due_date'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            table.on('xhr', function() {
                let json = table.ajax.json();
                if (json) {
                    $('#total_invoices').text(json.total_invoices ?? 0);
                    $('#total_amount').text('Rp ' + (json.total_amount ?? 0).toLocaleString('id-ID'));
                    $('#total_paid').text('Rp ' + (json.total_paid ?? 0).toLocaleString('id-ID'));
                    $('#total_unpaid').text('Rp ' + (json.total_unpaid ?? 0).toLocaleString('id-ID'));
                }
            });

            $('#filter_status').on('change', function() {
                table.ajax.reload();
            });

            $('#filter_date_start, #filter_date_end').on('change', function() {
                table.ajax.reload();
            });

            $('#export_excel').on('click', function(e) {
                e.preventDefault();
                let url = "{{ route('back.finance.invoice.export') }}";
                url += '?status=' + encodeURIComponent($('#filter_status').val());
                url += '&date_start=' + encodeURIComponent($('#filter_date_start').val());
                url += '&date_end=' + encodeURIComponent($('#filter_date_end').val());
                window.location.href = url;
            });

        });
    </script>
@endsection
