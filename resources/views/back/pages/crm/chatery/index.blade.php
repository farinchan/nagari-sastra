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
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Session" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">Semua</option>
                            <option value="Connected">Connected</option>
                            <option value="Disconnected">Disconnected</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Session
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
                            <th class="min-w-200px">Session</th>
                            <th class="min-w-120px">No. HP</th>
                            <th class="text-end min-w-80px">Default</th>
                            <th class="text-end min-w-80px">Status</th>
                            <th class="text-end min-w-70px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($sessions as $session)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $session->name }}</span>
                                        <span class="text-muted fs-7"><code>{{ $session->session_id }}</code></span>
                                        <span class="text-muted fs-8 text-truncate" style="max-width: 200px;" title="{{ $session->api_url }}">{{ $session->api_url }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-success">{{ $session->phone_number ?? '-' }}</span>
                                </td>
                                <td class="text-end pe-0">
                                    @if($session->is_default)
                                        <div class="badge badge-light-success">Default</div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-0" id="status-badge-{{ $session->id }}">
                                    @if($session->is_connected)
                                        <div class="badge badge-light-success">Connected</div>
                                    @else
                                        <div class="badge badge-light-danger">Disconnected</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-check-status" data-id="{{ $session->id }}">
                                                <i class="ki-duotone ki-wifi fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Cek Status
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-connect" data-id="{{ $session->id }}">
                                                <i class="ki-duotone ki-rocket fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Connect
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-disconnect" data-id="{{ $session->id }}">
                                                <i class="ki-duotone ki-disconnect fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Disconnect
                                            </a>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#editSessionModal-{{ $session->id }}">
                                                <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Edit
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-delete-session" data-id="{{ $session->id }}" data-name="{{ $session->name }}">
                                                <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                    <form id="deleteSessionForm-{{ $session->id }}" action="{{ route('back.crm.chatery.destroy', $session->id) }}" method="POST" class="d-none">
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

    {{-- Add Session Modal --}}
    <div class="modal fade" id="addSessionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('back.crm.chatery.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Session Chatery</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="e.g. CS Nagari Sastra" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">Session ID</label>
                            <input type="text" name="session_id" class="form-control form-control-solid" placeholder="e.g. nagari_sastra" required>
                            <div class="form-text">Hanya huruf, angka, dash, dan underscore.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">API URL</label>
                            <input type="url" name="api_url" class="form-control form-control-solid" placeholder="http://localhost:3000" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">API Key</label>
                            <input type="text" name="api_key" class="form-control form-control-solid" placeholder="Opsional">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="phone_number" class="form-control form-control-solid" placeholder="e.g. 628123456789">
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_default" value="1" id="addIsDefault">
                            <label class="form-check-label" for="addIsDefault">Jadikan Default</label>
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

    {{-- Edit Session Modals --}}
    @foreach($sessions as $session)
        <div class="modal fade" id="editSessionModal-{{ $session->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('back.crm.chatery.update', $session->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Session: {{ $session->name }}</h5>
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label required">Nama</label>
                                <input type="text" name="name" class="form-control form-control-solid" value="{{ $session->name }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">Session ID</label>
                                <input type="text" name="session_id" class="form-control form-control-solid" value="{{ $session->session_id }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">API URL</label>
                                <input type="url" name="api_url" class="form-control form-control-solid" value="{{ $session->api_url }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">API Key</label>
                                <input type="text" name="api_key" class="form-control form-control-solid" placeholder="Kosongkan jika tidak diubah">
                                <div class="form-text">Kosongkan untuk mempertahankan API key saat ini.</div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="phone_number" class="form-control form-control-solid" value="{{ $session->phone_number }}">
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    id="editIsActive-{{ $session->id }}" {{ $session->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="editIsActive-{{ $session->id }}">Aktif</label>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                    id="editIsDefault-{{ $session->id }}" {{ $session->is_default ? 'checked' : '' }}>
                                <label class="form-check-label" for="editIsDefault-{{ $session->id }}">Jadikan Default</label>
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

    {{-- QR Code Modal --}}
    <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Scan QR Code</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                </div>
                <div class="modal-body text-center py-10">
                    <p class="text-muted mb-5">Scan QR code berikut menggunakan WhatsApp di ponsel Anda.</p>
                    <div id="qr-loading" class="mb-5">
                        <span class="spinner-border spinner-border-lg text-primary" role="status"></span>
                        <p class="text-muted mt-3">Memuat QR code...</p>
                    </div>
                    <img id="qr-image" src="" alt="QR Code" class="img-fluid rounded shadow" style="max-width: 300px; display: none;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-refresh-qr">
                        <i class="ki-duotone ki-arrows-circle fs-4"><span class="path1"></span><span class="path2"></span></i> Refresh QR
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/chatery-sessions.js') }}"></script>
    <script>
        // Check Status
        document.querySelectorAll('.btn-check-status').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const badge = document.getElementById('status-badge-' + id);
                badge.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

                fetch("{{ url('back/crm/chatery') }}/" + id + "/status")
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const status = data.data?.data?.status || data.data?.status || 'unknown';
                            if (status === 'connected') {
                                badge.innerHTML = '<div class="badge badge-light-success">Connected</div>';
                            } else {
                                badge.innerHTML = '<div class="badge badge-light-danger">' + status + '</div>';
                            }
                        } else {
                            badge.innerHTML = '<div class="badge badge-light-danger">Error</div>';
                        }
                    })
                    .catch(() => {
                        badge.innerHTML = '<div class="badge badge-light-danger">Error</div>';
                    });
            });
        });

        // Connect
        let currentConnectId = null;
        document.querySelectorAll('.btn-connect').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                currentConnectId = this.dataset.id;
                const qrModal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
                document.getElementById('qr-loading').style.display = 'block';
                document.getElementById('qr-image').style.display = 'none';

                fetch("{{ url('back/crm/chatery') }}/" + currentConnectId + "/connect", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.qr_url) {
                        const img = document.getElementById('qr-image');
                        img.src = data.qr_url + '?t=' + Date.now();
                        img.onload = function() {
                            document.getElementById('qr-loading').style.display = 'none';
                            img.style.display = 'block';
                        };
                        img.onerror = function() {
                            document.getElementById('qr-loading').innerHTML = '<p class="text-muted">QR code belum tersedia. Coba refresh.</p>';
                        };
                    } else {
                        document.getElementById('qr-loading').innerHTML = '<p class="text-danger">' + (data.message || 'Gagal menghubungkan') + '</p>';
                    }
                })
                .catch(err => {
                    document.getElementById('qr-loading').innerHTML = '<p class="text-danger">Error: ' + err.message + '</p>';
                });

                qrModal.show();
            });
        });

        // Refresh QR
        document.getElementById('btn-refresh-qr').addEventListener('click', function() {
            if (!currentConnectId) return;
            document.getElementById('qr-loading').style.display = 'block';
            document.getElementById('qr-image').style.display = 'none';

            fetch("{{ url('back/crm/chatery') }}/" + currentConnectId + "/connect", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.qr_url) {
                    const img = document.getElementById('qr-image');
                    img.src = data.qr_url + '?t=' + Date.now();
                    img.onload = function() {
                        document.getElementById('qr-loading').style.display = 'none';
                        img.style.display = 'block';
                    };
                }
            });
        });

        // Disconnect
        document.querySelectorAll('.btn-disconnect').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;

                Swal.fire({
                    title: 'Disconnect Session?',
                    text: 'Yakin ingin memutuskan session ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Disconnect!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        fetch("{{ url('back/crm/chatery') }}/" + id + "/disconnect", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('status-badge-' + id).innerHTML = '<div class="badge badge-light-danger">Disconnected</div>';
                                Swal.fire('Berhasil', 'Session berhasil diputuskan.', 'success');
                            } else {
                                Swal.fire('Gagal', data.message || 'Gagal memutuskan session.', 'error');
                            }
                        })
                        .catch(err => Swal.fire('Error', err.message, 'error'));
                    }
                });
            });
        });

        // Delete
        document.querySelectorAll('.btn-delete-session').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const name = this.dataset.name;

                Swal.fire({
                    title: 'Hapus Session?',
                    text: 'Session "' + name + '" akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        document.getElementById('deleteSessionForm-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
