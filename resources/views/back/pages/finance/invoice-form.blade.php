@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">
        <div class="card card-flush">
            <div class="card-header py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <h3 class="fw-bold m-0">Buat Invoice</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-sm btn-light">
                        <i class="ki-duotone ki-arrow-left fs-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Kembali
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="invoice_form" class="form" method="POST" action="{{ route('back.finance.invoice.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Section: Informasi Invoice --}}
                    <div class="separator separator-dashed my-6"></div>
                    <h5 class="fw-bold text-gray-800 mb-5">
                        <i class="ki-duotone ki-document fs-4 me-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Informasi Invoice
                    </h5>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Nomor Invoice <span class="text-muted fs-8">(otomatis)</span></label>
                            <input type="text" name="invoice" class="form-control form-control-solid bg-light-primary" value="{{ $next_invoice ?? old('invoice') }}" readonly />
                            @error('invoice')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Nomor Urut <span class="text-muted fs-8">(sequence)</span></label>
                            <input type="text" name="invoice_number" class="form-control form-control-solid bg-light-primary" value="{{ $next_invoice_number ?? old('invoice_number') }}" readonly />
                            <small class="text-muted">Otomatis berdasarkan tahun {{ now()->year }}</small>
                            @error('invoice_number')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Section: Penerima --}}
                    <div class="separator separator-dashed my-6"></div>
                    <h5 class="fw-bold text-gray-800 mb-5">
                        <i class="ki-duotone ki-profile-user fs-4 me-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        Penerima Invoice
                    </h5>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold required">Kepada (Nama Penerima)</label>
                            <input type="text" name="kepada" class="form-control form-control-solid" placeholder="Masukkan nama penerima invoice" value="{{ old('kepada') }}" required />
                            @error('kepada')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Detail Penerima <span class="text-muted fs-8">(Afiliasi / Alamat)</span></label>
                            <input type="text" name="kepada_detail" class="form-control form-control-solid" placeholder="Afiliasi atau alamat penerima" value="{{ old('kepada_detail') }}" />
                            @error('kepada_detail')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="form-label fs-6 fw-bold">Keterangan</label>
                            <textarea name="keterangan" class="form-control form-control-solid" rows="3" placeholder="Catatan atau keterangan tambahan untuk invoice ini">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Section: Item Invoice --}}
                    <div class="separator separator-dashed my-6"></div>
                    <h5 class="fw-bold text-gray-800 mb-5">
                        <i class="ki-duotone ki-basket fs-4 me-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        Item Invoice <span class="text-danger">*</span>
                    </h5>

                    <div id="kt_invoice_repeater">
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted fs-7">Klik "Tambah Item" untuk menambahkan item. Total tagihan otomatis dihitung.</span>
                                <button type="button" class="btn btn-sm btn-primary" data-repeater-create>
                                    <i class="ki-duotone ki-plus fs-6"></i>
                                    Tambah Item
                                </button>
                            </div>
                        </div>
                        <div data-repeater-list="items_list">
                            <div data-repeater-item class="form-group mb-3 border border-dashed rounded p-4 bg-light-secondary">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label fs-7 fw-bold">ID Item</label>
                                        <input type="text" class="form-control form-control-solid item-id" name="item_id" placeholder="INV001" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-7 fw-bold required">Nama Item</label>
                                        <input type="text" class="form-control form-control-solid item-name" name="item_name" placeholder="Nama item" />
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label fs-7 fw-bold">Qty</label>
                                        <input type="number" class="form-control form-control-solid item-qty" name="item_qty" placeholder="1" min="1" step="1" value="1" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-7 fw-bold">Detail</label>
                                        <input type="text" class="form-control form-control-solid item-detail" name="item_detail" placeholder="Keterangan item" />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fs-7 fw-bold required">Amount (Rp)</label>
                                        <input type="number" class="form-control form-control-solid item-amount" name="item_amount" placeholder="0" min="0" step="1" value="0" />
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger" data-repeater-delete title="Hapus item">
                                            <i class="ki-duotone ki-trash fs-5">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Pembayaran --}}
                    <div class="separator separator-dashed my-6"></div>
                    <h5 class="fw-bold text-gray-800 mb-5">
                        <i class="ki-duotone ki-wallet fs-4 me-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        Detail Pembayaran
                    </h5>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="form-label fs-6 fw-bold">Persentase Pembayaran</label>
                            <div class="input-group">
                                <input type="number" name="payment_percent" class="form-control form-control-solid" placeholder="100" value="{{ old('payment_percent', 100) }}" min="0" max="100" />
                                <span class="input-group-text">%</span>
                            </div>
                            @error('payment_percent')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-6 fw-bold">Total Tagihan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="payment_amount" id="payment_amount" class="form-control form-control-solid fw-bold bg-light-success" placeholder="0" value="{{ old('payment_amount', 0) }}" step="1" readonly />
                            </div>
                            <small class="text-muted">Otomatis dihitung dari items</small>
                            @error('payment_amount')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fs-6 fw-bold">Tanggal Jatuh Tempo</label>
                            <input type="date" name="payment_due_date" class="form-control form-control-solid" value="{{ old('payment_due_date') }}" />
                            @error('payment_due_date')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Section: File --}}
                    <div class="separator separator-dashed my-6"></div>
                    <h5 class="fw-bold text-gray-800 mb-5">
                        <i class="ki-duotone ki-file-up fs-4 me-1 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        File Invoice
                    </h5>

                    <div class="row mb-8">
                        <div class="col-md-6">
                            <label class="form-label fs-6 fw-bold">Upload File Invoice <span class="text-muted fs-8">(opsional)</span></label>
                            <input type="file" name="invoice_file" class="form-control form-control-solid" accept=".pdf,.doc,.docx" />
                            <div class="d-flex align-items-center mt-2">
                                <i class="ki-duotone ki-information-4 fs-6 text-primary me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <small class="text-muted">Kosongkan jika ingin file invoice di-generate otomatis sebagai PDF. Format: PDF, DOC, DOCX (maks. 10MB)</small>
                            </div>
                            @error('invoice_file')
                                <span class="text-danger fs-7">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="separator separator-dashed my-6"></div>
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('back.finance.invoice.index') }}" class="btn btn-light">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Simpan Invoice
                        </button>
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
                // Enforce minimum qty = 1
                if ($(this).hasClass('item-qty')) {
                    let val = parseInt($(this).val());
                    if (!val || val < 1) {
                        $(this).val(1);
                    }
                }
                calculateTotal();
            });

            function calculateTotal() {
                let total = 0;

                $('[data-repeater-item]:visible').each(function() {
                    let qty = parseInt($(this).find('.item-qty').val()) || 1;
                    let amount = parseFloat($(this).find('.item-amount').val()) || 0;

                    // Enforce minimum qty
                    if (qty < 1) qty = 1;

                    total += (qty * amount);
                });

                $('#payment_amount').val(total);
            }

            $('#invoice_form').on('submit', function(e) {
                let items = [];
                let hasValidItem = false;

                $('[data-repeater-item]:visible').each(function() {
                    let id = $(this).find('.item-id').val();
                    let name = $(this).find('.item-name').val();
                    let qty = parseInt($(this).find('.item-qty').val()) || 1;
                    let detail = $(this).find('.item-detail').val();
                    let amount = parseFloat($(this).find('.item-amount').val()) || 0;

                    // Enforce minimum qty
                    if (qty < 1) qty = 1;

                    // Item valid jika ada nama
                    if (name && name.trim() !== '') {
                        hasValidItem = true;
                        items.push({
                            id: id || '',
                            name: name.trim(),
                            qty: qty,
                            detail: detail || '',
                            amount: amount
                        });
                    }
                });

                if (!hasValidItem) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Item Diperlukan',
                        text: 'Minimal tambahkan 1 item invoice dengan nama item yang terisi.',
                        icon: 'warning',
                        confirmButtonText: 'Mengerti'
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
