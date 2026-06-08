@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-ecommerce-product-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Widget" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">Semua</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWidgetModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Widget
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_products_table .form-check-input"
                                        value="1" />
                                </div>
                            </th>
                            <th class="min-w-200px">Widget</th>
                            <th class="min-w-80px">Warna</th>
                            <th class="text-end min-w-80px">Percakapan</th>
                            <th class="text-end min-w-80px">Status</th>
                            <th class="text-end min-w-70px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($widgets as $widget)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $widget->name }}</span>
                                        <span class="text-muted fs-7">{{ $widget->header_title }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg, {{ $widget->primary_color }} 0%, {{ $widget->secondary_color }} 100%);"></div>
                                        <span class="text-muted fs-8">{{ $widget->primary_color }}</span>
                                    </div>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="fw-semibold">{{ $widget->conversations_count }}</span>
                                </td>
                                <td class="text-end pe-0">
                                    @if($widget->is_active)
                                        <div class="badge badge-light-success">Aktif</div>
                                    @else
                                        <div class="badge badge-light-secondary">Nonaktif</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" onclick="showEmbed('{{ $widget->token }}'); return false;">
                                                <i class="ki-duotone ki-code fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Embed Code
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#editWidgetModal{{ $widget->id }}">
                                                <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Edit
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-delete-widget" data-id="{{ $widget->id }}" data-name="{{ $widget->name }}">
                                                <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                    <form id="deleteWidgetForm{{ $widget->id }}" action="{{ route('back.crm.webchat.widgets.destroy', $widget->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CREATE WIDGET MODAL --}}
    <div class="modal fade" id="createWidgetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('back.crm.webchat.widgets.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Widget Webchat</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama Widget</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="contoh: Website Utama" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">Judul Header</label>
                            <input type="text" name="header_title" class="form-control form-control-solid" value="Customer Support" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Sub-judul Header</label>
                            <input type="text" name="header_subtitle" class="form-control form-control-solid" value="Online — Siap membantu Anda">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Pesan Sambutan</label>
                            <textarea name="greeting_message" class="form-control form-control-solid" rows="2">Halo! 👋 Selamat datang. Ada yang bisa kami bantu?</textarea>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">Warna Utama</label>
                                <input type="color" name="primary_color" class="form-control form-control-solid form-control-color" value="#667eea" style="height: 45px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Warna Sekunder</label>
                                <input type="color" name="secondary_color" class="form-control form-control-solid form-control-color" value="#764ba2" style="height: 45px;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Domain yang Diizinkan</label>
                            <input type="text" name="allowed_domains" class="form-control form-control-solid" placeholder="contoh: example.com, sub.example.com (kosongkan = semua domain)">
                            <div class="form-text">Pisahkan dengan koma. Kosongkan untuk mengizinkan semua domain.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT WIDGET MODALS --}}
    @foreach($widgets as $widget)
    <div class="modal fade" id="editWidgetModal{{ $widget->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('back.crm.webchat.widgets.update', $widget->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Widget: {{ $widget->name }}</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama Widget</label>
                            <input type="text" name="name" class="form-control form-control-solid" value="{{ $widget->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">Judul Header</label>
                            <input type="text" name="header_title" class="form-control form-control-solid" value="{{ $widget->header_title }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Sub-judul Header</label>
                            <input type="text" name="header_subtitle" class="form-control form-control-solid" value="{{ $widget->header_subtitle }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Pesan Sambutan</label>
                            <textarea name="greeting_message" class="form-control form-control-solid" rows="2">{{ $widget->greeting_message }}</textarea>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">Warna Utama</label>
                                <input type="color" name="primary_color" class="form-control form-control-solid form-control-color" value="{{ $widget->primary_color }}" style="height: 45px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Warna Sekunder</label>
                                <input type="color" name="secondary_color" class="form-control form-control-solid form-control-color" value="{{ $widget->secondary_color }}" style="height: 45px;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Domain yang Diizinkan</label>
                            <input type="text" name="allowed_domains" class="form-control form-control-solid" value="{{ $widget->allowed_domains }}" placeholder="kosongkan = semua domain">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select form-select-solid">
                                <option value="1" {{ $widget->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$widget->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    {{-- EMBED CODE MODAL --}}
    <div class="modal fade" id="embedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Embed Code</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                </div>
                <div class="modal-body">
                    <p class="text-muted fs-7 mb-4">Salin kode di bawah ini dan tempelkan sebelum tag <code>&lt;/body&gt;</code> di website Anda.</p>
                    <div class="bg-light-dark rounded p-4">
                        <code id="embedCodeText" class="d-block text-break fs-7" style="white-space: pre-wrap; word-break: break-all;"></code>
                    </div>
                    <button type="button" class="btn btn-sm btn-light-primary mt-3 w-100" onclick="copyEmbed()">
                        <i class="ki-duotone ki-copy fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Salin Kode
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/webchat-widgets.js') }}"></script>
    <script>
        var embedBaseUrl = '{{ url("/api/webchat/embed") }}';

        function showEmbed(token) {
            var code = '<scr' + 'ipt src="' + embedBaseUrl + '/' + token + '">' + '</scr' + 'ipt>';
            document.getElementById('embedCodeText').textContent = code;
            var modal = new bootstrap.Modal(document.getElementById('embedModal'));
            modal.show();
        }

        function copyEmbed() {
            var text = document.getElementById('embedCodeText').textContent;
            navigator.clipboard.writeText(text).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Embed code berhasil disalin.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        // Delete Widget
        document.querySelectorAll('.btn-delete-widget').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.dataset.id;
                var name = this.dataset.name;

                Swal.fire({
                    title: 'Hapus Widget?',
                    text: 'Widget "' + name + '" dan semua percakapan terkait akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        document.getElementById('deleteWidgetForm' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
