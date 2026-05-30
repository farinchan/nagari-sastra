@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="card mb-10">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-1">
                                    <span class="text-gray-800 fs-2 fw-bold me-3">
                                        <i class="ki-duotone ki-chart-line fs-2 me-2 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Laporan Keuangan {{ $selected_year }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <select class="form-select form-select-solid form-select-sm" id="year_selector" style="width: 130px;">
                                    @foreach ($available_years as $year)
                                        <option value="{{ $year }}" {{ $year == $selected_year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-start">
                            <div class="d-flex flex-wrap">
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-arrow-down fs-3 text-success me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="fs-4 fw-bold" id="total_income_all">
                                            Rp {{ number_format($total_income_now, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-500">Total Income</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-duotone ki-arrow-up fs-3 text-danger me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="fs-4 fw-bold" id="total_outcome_all">
                                            Rp {{ number_format($total_outcome_now, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-500">Total Outcome</div>
                                </div>
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-4 fw-bold">
                                            Rp {{ number_format($total_balance_now, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-500">Balance</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    Cash Flow
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="btn-group">
                        <a href="#" class="btn btn-light-primary" id="export_excel">
                            <i class="ki-duotone ki-file-down fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export Excel
                        </a>
                        <a href="#" class="btn btn-light-info" data-bs-toggle="modal"
                            data-bs-target="#add_finance">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Tambah Transaksi
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row mb-10">
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-bold">Tipe</label>
                        <select class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select an option" name="type" id="type">
                            <option value="all">Semua</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-bold">Dari Tanggal</label>
                        <input type="date" name="date_start" class="form-control form-control-solid"
                            placeholder="Date Start" id="date_start"
                            value="{{ $selected_year }}-01-01" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-6 fw-bold">Sampai Tanggal</label>
                        <input type="date" name="date_end" class="form-control form-control-solid"
                            placeholder="Date End" id="date_end"
                            value="{{ $selected_year }}-12-31" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="fs-5 fw-bold text-gray-700 me-3 fs-3">Income:</span>
                                    <span class="fs-5 fw-bold text-success fs-2" id="total_income">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <span class="fs-5 fw-bold text-gray-700 me-3 fs-3">Expense:</span>
                                    <span class="fs-5 fw-bold text-danger fs-2" id="total_expense">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="d-flex  align-items-center">
                                    <span class="fs-5 fw-bold text-gray-700 me-3 fs-3">Balance:</span>
                                    <span class="fs-5 fw-bold fs-2  text-warning " id="balance">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_finance">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-200px">Transaksi</th>
                            <th class="min-w-125px">Tanggal</th>
                            <th class="min-w-150px">Jumlah</th>
                            <th class="min-w-50px">Type</th>
                            <th class="min-w-200px">Payment Info</th>
                            <th class="min-w-100px">Lampiran</th>
                            <th class="min-w-300px">Log</th>
                            <th class="min-w-100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="add_finance">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Data Keuangan</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form action="{{ route('back.finance.cashflow.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Transaksi</label>
                            <input type="text" class="form-control" placeholder="Nama transaksi keuangan"
                                name="name" value="{{ old('name') }}" required />
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" placeholder="Deskripsi transaksi keuangan" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Jumlah</label>
                            <div class="input-group mb-5">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" placeholder="Jumlah transaksi keuangan"
                                    value="{{ old('amount') }}" oninput="formatRupiah(this)" required />
                            </div>
                            <input type="hidden" id="rupiah_value" name="amount" value="{{ old('amount') }}">
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Tanggal</label>
                            <input type="datetime-local" class="form-control" placeholder="Tanggal transaksi keuangan"
                                name="date" value="{{ old('date') }}" required />
                            @error('date')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Type</label>
                            <select class="form-select" name="type" required>
                                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Income</option>
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                            @error('type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label ">Metode Pembayaran</label>
                                <input type="text" class="form-control" placeholder="Metode Pembayaran"
                                    name="payment_method" value="{{ old('payment_method') }}" />
                                @error('payment_method')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label ">No Ref</label>
                                <input type="text" class="form-control" placeholder="No Ref" name="payment_reference"
                                    value="{{ old('payment_reference') }}" />
                                @error('payment_reference')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label ">Note</label>
                            <textarea class="form-control" placeholder="Note" name="payment_note">{{ old('payment_note') }}</textarea>
                            @error('payment_note')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Lampiran</label>
                            <input type="file" class="form-control" name="attachment" />
                            <small class="text-muted">Format: jpg, jpeg, png, pdf. Maksimal 10 MB.</small>
                            @error('attachment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#table_finance').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('back.finance.cashflow.datatable') }}",
                    data: function(d) {
                        d.type = $('#type').val();
                        d.date_start = $('#date_start').val();
                        d.date_end = $('#date_end').val();
                    }
                },
                columns: [{
                        data: 'transaction',
                        name: 'transaction'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'payment_info',
                        name: 'payment_info'
                    },
                    {
                        data: 'attachment',
                        name: 'attachment'
                    },
                    {
                        data: 'log',
                        name: 'log'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            let summary = {
                total_income: 0,
                total_expense: 0,
                total_balance: 0
            };
            table.on('xhr', function() {
                let json = table.ajax.json();
                summary.total_income = json.total_income;
                summary.total_expense = json.total_expense;
                summary.total_balance = json.total_balance;
                $('#total_income').text('Rp ' + summary.total_income.toLocaleString());
                $('#total_expense').text('Rp ' + summary.total_expense.toLocaleString());
                if (summary.total_balance >= 0) {
                    $('#balance').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#balance').removeClass('text-success').addClass('text-danger');
                }
                $('#balance').text('Rp ' + summary.total_balance.toLocaleString());
                $('#export_excel').attr('href',
                    "{{ route('back.finance.cashflow.export') }}?type=" +
                    $('#type').val() + "&date_start=" + $('#date_start').val() + "&date_end=" +
                    $('#date_end').val());
            });
            $('#type').on('change', function() {
                table.ajax.reload();
            });
            $('#date_start').on('change', function() {
                table.ajax.reload();
            });
            $('#date_end').on('change', function() {
                table.ajax.reload();
            });

            // Year selector
            $('#year_selector').on('change', function() {
                window.location.href = "{{ route('back.finance.cashflow.index') }}?year=" + $(this).val();
            });
        });

        function formatRupiah(element) {
            let angka = element.value.replace(/\D/g, '');
            let formatted = angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            element.value = formatted;
            document.getElementById('rupiah_value').value = angka;
        }
    </script>
@endsection
