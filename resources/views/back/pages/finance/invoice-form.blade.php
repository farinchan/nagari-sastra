@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="card card-flush">
            <div class="card-header py-5 gap-2 gap-md-5">
                <div class="card-title">
                    Buat Invoice
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="invoice_form" class="form" method="POST" action="{{ route('back.finance.invoice.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Invoice (otomatis)</label>
                            <input type="text" name="invoice" class="form-control form-control-solid" value="{{ $next_invoice ?? old('invoice') }}" readonly />
                            @error('invoice')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Nomor Urut <span class="text-muted">(sequence)</span></label>
                            <input type="text" name="invoice_number" class="form-control form-control-solid" value="{{ $next_invoice_number ?? old('invoice_number') }}" readonly />
                            <small class="text-muted">Nomor urut otomatis berdasarkan tahun {{ now()->year }}</small>
                            @error('invoice_number')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Persentase Pembayaran</label>
                            <div class="input-group mb-2">
                                <input type="number" name="payment_percent" class="form-control form-control-solid" placeholder="100" value="{{ old('payment_percent', 100) }}" min="0" max="100" />
                                <span class="input-group-text">%</span>
                            </div>
                            @error('payment_percent')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Jumlah Tagihan (Otomatis dari items)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="payment_amount" id="payment_amount" class="form-control form-control-solid" placeholder="0" value="{{ old('payment_amount', 0) }}" step="0.01" readonly />
                            </div>
                            <small class="text-muted">Otomatis dihitung dari qty × amount pada item invoice</small>
                            @error('payment_amount')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Tanggal Jatuh Tempo</label>
                            <input type="date" name="payment_due_date" class="form-control form-control-solid" value="{{ old('payment_due_date') }}" />
                            @error('payment_due_date')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">File Invoice (PDF/DOC)</label>
                            <input type="file" name="invoice_file" class="form-control form-control-solid" accept=".pdf,.doc,.docx" />
                            @error('invoice_file')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="form-label fs-6 fw-bold">Item Invoice <span class="text-danger">*</span></label>
                            <div id="kt_invoice_repeater">
                                <div class="form-group mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Daftar Item</h6>
                                        <button type="button" class="btn btn-sm btn-primary" data-repeater-create>
                                            <i class="ki-duotone ki-plus fs-6"></i>
                                            Tambah Item
                                        </button>
                                    </div>
                                </div>
                                <div data-repeater-list="items_list">
                                    <div data-repeater-item class="form-group mb-3 border rounded p-3">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label class="form-label fs-7 fw-bold">ID Item</label>
                                                <input type="text" class="form-control form-control-solid item-id" name="item_id" placeholder="INV001" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fs-7 fw-bold">Nama Item</label>
                                                <input type="text" class="form-control form-control-solid item-name" name="item_name" placeholder="Nama Item" />
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fs-7 fw-bold">Qty</label>
                                                <input type="number" class="form-control form-control-solid item-qty" name="item_qty" placeholder="1" min="1" step="1" value="1" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fs-7 fw-bold">Detail</label>
                                                <input type="text" class="form-control form-control-solid item-detail" name="item_detail" placeholder="Detail item" />
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label fs-7 fw-bold">Amount</label>
                                                <input type="number" class="form-control form-control-solid item-amount" name="item_amount" placeholder="0" step="0.01" value="0" />
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-sm btn-danger" data-repeater-delete>
                                                    <i class="ki-duotone ki-trash fs-6"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-3">Klik "Tambah Item" untuk menambahkan item. Total tagihan akan otomatis dihitung dari qty × amount setiap item</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Simpan Invoice
                                </button>
                                <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-light">
                                    <i class="ki-duotone ki-arrow-left fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('back/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize form-repeater
            $('#kt_invoice_repeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                    setTimeout(function() {
                        calculateTotal();
                    }, 100);
                },
                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);
                    setTimeout(function() {
                        calculateTotal();
                    }, 100);
                }
            });

            // Bind calculation to item inputs
            $(document).on('change input', '.item-qty, .item-amount', function() {
                calculateTotal();
            });

            function calculateTotal() {
                let total = 0;

                $('[data-repeater-item]').each(function() {
                    let qty = parseFloat($(this).find('.item-qty').val() || 0);
                    let amount = parseFloat($(this).find('.item-amount').val() || 0);
                    total += (qty * amount);
                });

                $('#payment_amount').val(total);
            }

            $('#invoice_form').on('submit', function(e) {
                let items = [];
                let hasItems = false;

                $('[data-repeater-item]').each(function() {
                    let id = $(this).find('.item-id').val();
                    let name = $(this).find('.item-name').val();
                    let qty = $(this).find('.item-qty').val();
                    let detail = $(this).find('.item-detail').val();
                    let amount = $(this).find('.item-amount').val();

                    // Only add if at least one field is filled
                    if (id || name || qty || detail || amount) {
                        hasItems = true;
                        items.push({
                            id: id || '',
                            name: name || '',
                            qty: qty ? parseFloat(qty) : 0,
                            detail: detail || '',
                            amount: amount ? parseFloat(amount) : 0
                        });
                    }
                });

                if (!hasItems) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Error',
                        text: 'Minimal tambahkan 1 item invoice',
                        icon: 'error'
                    });
                    return false;
                }

                // Create hidden input with JSON data
                let itemsInput = $('input[name="items"]');
                if (itemsInput.length === 0) {
                    itemsInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'items'
                    });
                    $('#invoice_form').append(itemsInput);
                }
                itemsInput.val(JSON.stringify(items));

                return true;
            });

            // Calculate on page load
            calculateTotal();
        });
    </script>
@endsection
