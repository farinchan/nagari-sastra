@extends('back.app')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- Add Group Button --}}
            <div class="d-flex justify-content-end mb-5">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                    <i class="ki-duotone ki-plus fs-2"></i> Tambah Grup
                </button>
            </div>

            {{-- Groups Table Card --}}
            <div class="card card-flush">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <i class="ki-duotone ki-people fs-2 me-2 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        Daftar Grup Kontak
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive" style="overflow: visible;">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-30px">#</th>
                                    <th class="min-w-200px">Nama Grup</th>
                                    <th class="min-w-100px">Jumlah Kontak</th>
                                    <th class="min-w-120px text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $index => $group)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold text-gray-800">{{ $group->name }}</div>
                                            @if($group->description)
                                                <div class="text-muted fs-8">{{ Str::limit($group->description, 80) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary fs-7">{{ $group->contacts_count }} kontak</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-flex btn-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Aksi <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end menu-sub menu-sub-dropdown menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4">
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" onclick="openContacts({{ $group->id }}, '{{ addslashes($group->name) }}'); return false;">
                                                            <i class="ki-duotone ki-people fs-4 me-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Lihat Kontak
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#editGroupModal-{{ $group->id }}">
                                                            <i class="ki-duotone ki-pencil fs-4 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i> Edit
                                                        </a>
                                                    </div>
                                                    <div class="separator my-2"></div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 btn-delete-group" data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                                            <i class="ki-duotone ki-trash fs-4 me-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-10">
                                            <i class="ki-duotone ki-people fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            <p class="mb-0">Belum ada grup kontak. Klik "Tambah Grup" untuk memulai.</p>
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

    {{-- ============================================================== --}}
    {{-- Add Group Modal --}}
    {{-- ============================================================== --}}
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('back.crm.chatery.groups.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Grup Kontak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Nama grup" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-solid" rows="3" placeholder="Deskripsi grup (opsional)"></textarea>
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

    {{-- ============================================================== --}}
    {{-- Edit Group Modals (one per group) --}}
    {{-- ============================================================== --}}
    @foreach($groups as $group)
        <div class="modal fade" id="editGroupModal-{{ $group->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('back.crm.chatery.groups.update', $group->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Edit Grup: {{ $group->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-5">
                                <label class="form-label required">Nama</label>
                                <input type="text" name="name" class="form-control form-control-solid" value="{{ $group->name }}" required>
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control form-control-solid" rows="3">{{ $group->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ============================================================== --}}
    {{-- Contact Detail Modal --}}
    {{-- ============================================================== --}}
    <div class="modal fade" id="contactsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="ki-duotone ki-people fs-3 me-2 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        Kontak: <span id="contactsGroupName"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Add Contact Inline Form --}}
                    <div class="d-flex align-items-end gap-2 mb-4">
                        <div class="flex-grow-1">
                            <label class="form-label fs-8 mb-1">Nama</label>
                            <input type="text" id="addContactName" class="form-control form-control-solid form-control-sm" placeholder="Nama kontak">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label fs-8 mb-1">No. HP</label>
                            <input type="text" id="addContactPhone" class="form-control form-control-solid form-control-sm" placeholder="628xxxxxxxxxx">
                        </div>
                        <div>
                            <button type="button" class="btn btn-success btn-sm" onclick="addContact()">
                                <i class="ki-duotone ki-plus fs-5"></i> Tambah
                            </button>
                        </div>
                    </div>

                    {{-- Import CSV --}}
                    <div class="d-flex align-items-center gap-2 mb-5">
                        <input type="file" id="csvFileInput" class="form-control form-control-solid form-control-sm" accept=".csv,.txt" style="max-width: 300px;">
                        <button type="button" class="btn btn-sm btn-light-primary" onclick="importCsv()">
                            <i class="ki-duotone ki-file-up fs-5"><span class="path1"></span><span class="path2"></span></i> Import CSV
                        </button>
                        <span class="text-muted fs-8">Format: <code>phone</code> atau <code>name,phone</code> per baris</span>
                    </div>

                    <div class="separator my-4"></div>

                    {{-- Contact List --}}
                    <div id="contactListWrapper" style="max-height: 400px; overflow-y: auto;">
                        <div id="contactList">
                            <div class="text-center text-muted py-10">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span> Memuat kontak...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- Delete Forms --}}
    {{-- ============================================================== --}}
    @foreach($groups as $group)
        <form id="deleteGroupForm-{{ $group->id }}" action="{{ route('back.crm.chatery.groups.destroy', $group->id) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection

@section('scripts')
<script>
    // ── State ──────────────────────────────────────────────────
    var currentGroupId = null;

    // ── Utility ────────────────────────────────────────────────
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text || ''));
        return div.innerHTML;
    }

    // ── Open Contacts Modal ────────────────────────────────────
    function openContacts(groupId, groupName) {
        currentGroupId = groupId;
        document.getElementById('contactsGroupName').textContent = groupName;
        document.getElementById('addContactName').value = '';
        document.getElementById('addContactPhone').value = '';
        document.getElementById('csvFileInput').value = '';

        var modal = new bootstrap.Modal(document.getElementById('contactsModal'));
        modal.show();

        loadContacts(groupId);
    }

    // ── Load Contacts via AJAX ─────────────────────────────────
    function loadContacts(groupId) {
        var container = document.getElementById('contactList');
        container.innerHTML = '<div class="text-center text-muted py-10"><span class="spinner-border spinner-border-sm me-2" role="status"></span> Memuat kontak...</div>';

        fetch("{{ url('back/crm/chatery/groups') }}/" + groupId + "/contacts", {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var contacts = data.data || data.contacts || data || [];
            if (!Array.isArray(contacts)) contacts = [];

            if (contacts.length === 0) {
                container.innerHTML = '<div class="text-center text-muted py-10">' +
                    '<i class="ki-duotone ki-people fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
                    '<p class="mb-0">Belum ada kontak di grup ini.</p>' +
                    '</div>';
                return;
            }

            var html = '<table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-2">' +
                '<thead><tr class="fw-bold text-muted fs-8">' +
                '<th>#</th><th>Nama</th><th>No. HP</th><th class="text-end">Aksi</th>' +
                '</tr></thead><tbody>';

            contacts.forEach(function(contact, i) {
                html += '<tr>' +
                    '<td class="text-muted fs-8">' + (i + 1) + '</td>' +
                    '<td class="fw-semibold text-gray-800 fs-7">' + escapeHtml(contact.name) + '</td>' +
                    '<td class="text-muted fs-7"><code>' + escapeHtml(contact.phone) + '</code></td>' +
                    '<td class="text-end">' +
                        '<button type="button" class="btn btn-sm btn-light-danger btn-icon" onclick="deleteContact(' + contact.id + ')" title="Hapus">' +
                            '<i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>' +
                        '</button>' +
                    '</td>' +
                    '</tr>';
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(function(err) {
            container.innerHTML = '<div class="text-center text-danger py-5">' +
                '<i class="ki-duotone ki-information-3 fs-3x mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>' +
                '<p class="mb-0">Gagal memuat kontak: ' + escapeHtml(err.message) + '</p>' +
                '</div>';
        });
    }

    // ── Add Contact ────────────────────────────────────────────
    function addContact() {
        var name = document.getElementById('addContactName').value.trim();
        var phone = document.getElementById('addContactPhone').value.trim();

        if (!phone) {
            alert('Nomor HP wajib diisi.');
            return;
        }

        fetch("{{ route('back.crm.chatery.contacts.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                group_id: currentGroupId,
                name: name || phone,
                phone: phone
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success || data.id || data.data) {
                document.getElementById('addContactName').value = '';
                document.getElementById('addContactPhone').value = '';
                loadContacts(currentGroupId);
            } else {
                alert(data.message || 'Gagal menambahkan kontak.');
            }
        })
        .catch(function(err) {
            alert('Error: ' + err.message);
        });
    }

    // ── Delete Contact ─────────────────────────────────────────
    function deleteContact(id) {
        if (!confirm('Hapus kontak ini?')) return;

        fetch("{{ url('back/crm/chatery/contacts') }}/" + id, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            loadContacts(currentGroupId);
        })
        .catch(function(err) {
            alert('Error: ' + err.message);
        });
    }

    // ── Import CSV ─────────────────────────────────────────────
    function importCsv() {
        var fileInput = document.getElementById('csvFileInput');
        var file = fileInput.files[0];

        if (!file) {
            alert('Pilih file CSV/TXT terlebih dahulu.');
            return;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            var text = e.target.result;
            var lines = text.split(/\r?\n/).filter(function(l) { return l.trim() !== ''; });

            if (lines.length === 0) {
                alert('File kosong atau format tidak valid.');
                return;
            }

            var contacts = [];
            lines.forEach(function(line) {
                var parts = line.split(',');
                if (parts.length >= 2) {
                    contacts.push({ name: parts[0].trim(), phone: parts[1].trim() });
                } else {
                    var phone = parts[0].trim();
                    contacts.push({ name: phone, phone: phone });
                }
            });

            if (!confirm('Import ' + contacts.length + ' kontak ke grup ini?')) return;

            var container = document.getElementById('contactList');
            container.innerHTML = '<div class="text-center text-muted py-10"><span class="spinner-border spinner-border-sm me-2" role="status"></span> Mengimport ' + contacts.length + ' kontak...</div>';

            var promises = contacts.map(function(c) {
                return fetch("{{ route('back.crm.chatery.contacts.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        group_id: currentGroupId,
                        name: c.name,
                        phone: c.phone
                    })
                }).then(function(r) { return r.json(); });
            });

            Promise.all(promises)
                .then(function(results) {
                    var success = results.filter(function(r) { return r.success || r.id || r.data; }).length;
                    var failed = results.length - success;
                    alert('Import selesai: ' + success + ' berhasil, ' + failed + ' gagal.');
                    fileInput.value = '';
                    loadContacts(currentGroupId);
                })
                .catch(function(err) {
                    alert('Terjadi kesalahan saat import: ' + err.message);
                    loadContacts(currentGroupId);
                });
        };
        reader.readAsText(file);
    }

    // ── Delete Group ───────────────────────────────────────────
    document.querySelectorAll('.btn-delete-group').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.dataset.id;
            var name = this.dataset.name;

            Swal.fire({
                title: 'Hapus Grup?',
                text: 'Grup "' + name + '" dan semua kontak di dalamnya akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('deleteGroupForm-' + id).submit();
                }
            });
        });
    });
</script>
@endsection
