@extends('back.app')

@section('title', 'Kelola Session Chatery')

@section('toolbar')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    WA Unofficial (Chatery)</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('back.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">CRM</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">Kelola Session</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- Add Session Button --}}
            <div class="d-flex justify-content-end mb-5">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSessionModal">
                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Session
                </button>
            </div>

            {{-- Sessions Table --}}
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold text-gray-800">Daftar Session Chatery</h3>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-150px">Nama</th>
                                    <th class="min-w-125px">Session ID</th>
                                    <th class="min-w-150px">API URL</th>
                                    <th class="min-w-100px">No. HP</th>
                                    <th class="min-w-80px text-center">Default</th>
                                    <th class="min-w-80px text-center">Status</th>
                                    <th class="min-w-150px text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($sessions as $index => $session)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $session->name }}</span>
                                        </td>
                                        <td><code>{{ $session->session_id }}</code></td>
                                        <td><small>{{ $session->api_url }}</small></td>
                                        <td>{{ $session->phone_number ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($session->is_default)
                                                <span class="badge badge-light-success">Default</span>
                                            @else
                                                <span class="badge badge-light-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center" id="status-badge-{{ $session->id }}">
                                            @if($session->is_connected)
                                                <span class="badge badge-light-success">Connected</span>
                                            @else
                                                <span class="badge badge-light-danger">Disconnected</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-light-info btn-check-status"
                                                    data-id="{{ $session->id }}" title="Cek Status">
                                                    <i class="ki-duotone ki-wifi fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-success btn-connect"
                                                    data-id="{{ $session->id }}" title="Connect">
                                                    <i class="ki-duotone ki-rocket fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-warning btn-disconnect"
                                                    data-id="{{ $session->id }}" title="Disconnect">
                                                    <i class="ki-duotone ki-disconnect fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary"
                                                    data-bs-toggle="modal" data-bs-target="#editSessionModal-{{ $session->id }}" title="Edit">
                                                    <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                                                </button>
                                                <form action="{{ route('back.crm.chatery.destroy', $session->id) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus session ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger" title="Hapus">
                                                        <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-10">
                                            Belum ada session. Klik "Tambah Session" untuk memulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. CS Nagari Sastra" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">Session ID</label>
                            <input type="text" name="session_id" class="form-control" placeholder="e.g. nagari_sastra" required>
                            <small class="text-muted">Hanya huruf, angka, dash, dan underscore.</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">API URL</label>
                            <input type="url" name="api_url" class="form-control" placeholder="http://localhost:3000" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">API Key</label>
                            <input type="text" name="api_key" class="form-control" placeholder="Opsional">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="e.g. 628123456789">
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-4">
                                <label class="form-label required">Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $session->name }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">Session ID</label>
                                <input type="text" name="session_id" class="form-control" value="{{ $session->session_id }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label required">API URL</label>
                                <input type="url" name="api_url" class="form-control" value="{{ $session->api_url }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">API Key</label>
                                <input type="text" name="api_key" class="form-control" placeholder="Kosongkan jika tidak diubah">
                                <small class="text-muted">Kosongkan untuk mempertahankan API key saat ini.</small>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ $session->phone_number }}">
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
<script>
    // Check Status
    document.querySelectorAll('.btn-check-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const badge = document.getElementById('status-badge-' + id);
            badge.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            fetch("{{ url('back/crm/chatery') }}/" + id + "/status")
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const status = data.data?.data?.status || data.data?.status || 'unknown';
                        if (status === 'connected') {
                            badge.innerHTML = '<span class="badge badge-light-success">Connected</span>';
                        } else {
                            badge.innerHTML = '<span class="badge badge-light-danger">' + status + '</span>';
                        }
                    } else {
                        badge.innerHTML = '<span class="badge badge-light-danger">Error</span>';
                    }
                })
                .catch(() => {
                    badge.innerHTML = '<span class="badge badge-light-danger">Error</span>';
                });
        });
    });

    // Connect
    let currentConnectId = null;
    document.querySelectorAll('.btn-connect').forEach(btn => {
        btn.addEventListener('click', function() {
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
        btn.addEventListener('click', function() {
            if (!confirm('Yakin ingin memutuskan session ini?')) return;
            const id = this.dataset.id;

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
                    document.getElementById('status-badge-' + id).innerHTML = '<span class="badge badge-light-danger">Disconnected</span>';
                    alert('Session berhasil diputuskan.');
                } else {
                    alert(data.message || 'Gagal memutuskan session.');
                }
            })
            .catch(err => alert('Error: ' + err.message));
        });
    });
</script>
@endsection
