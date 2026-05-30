@extends('back.app')

@section('content')
    <div id="kt_content_container" class=" container-xxl ">

            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Grup Kontak</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Kelola grup kontak email marketing</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                            <i class="ki-duotone ki-plus fs-4"></i> Tambah Grup
                        </button>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>Nama</th>
                                    <th>Warna</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Kontak</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                <tr>
                                    <td class="fw-bold text-gray-800">{{ $group->name }}</td>
                                    <td>
                                        <span class="bullet bullet-dot h-10px w-10px" style="background-color: {{ $group->color }}"></span>
                                    </td>
                                    <td class="text-muted">{{ Str::limit($group->description, 60) ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-light-primary fs-7">{{ $group->contacts_count }} kontak</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('back.crm.email.contacts', $group->id) }}" class="btn btn-sm btn-light-primary me-1" title="Lihat Kontak">
                                            <i class="ki-duotone ki-people fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light-warning me-1 btn-edit-group"
                                            data-id="{{ $group->id }}"
                                            data-name="{{ $group->name }}"
                                            data-description="{{ $group->description }}"
                                            data-color="{{ $group->color }}"
                                            title="Edit">
                                            <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-danger btn-delete-group" data-id="{{ $group->id }}" data-name="{{ $group->name }}" title="Hapus">
                                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-10">
                                        <i class="ki-duotone ki-people fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        <p class="mb-0">Belum ada grup kontak. Buat grup pertama Anda.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>

    <!--begin::Add Group Modal-->
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.groups.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Grup Kontak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Grup</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Nama grup" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control form-control-solid" rows="3" placeholder="Deskripsi grup (opsional)"></textarea>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Warna</label>
                            <input type="color" name="color" class="form-control form-control-solid" value="#3699FF" style="height: 45px;">
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

    <!--begin::Edit Group Modal-->
    <div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editGroupForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Grup Kontak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Grup</label>
                            <input type="text" name="name" id="editName" class="form-control form-control-solid" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="editDescription" class="form-control form-control-solid" rows="3"></textarea>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Warna</label>
                            <input type="color" name="color" id="editColor" class="form-control form-control-solid" style="height: 45px;">
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

    <!--begin::Delete Form-->
    <form id="deleteGroupForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
<script>
    // Edit Group
    document.querySelectorAll('.btn-edit-group').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var name = this.dataset.name;
            var description = this.dataset.description;
            var color = this.dataset.color;

            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editColor').value = color;
            document.getElementById('editGroupForm').action = '/back/crm/email/groups/' + id + '/update';

            var modal = new bootstrap.Modal(document.getElementById('editGroupModal'));
            modal.show();
        });
    });

    // Delete Group
    document.querySelectorAll('.btn-delete-group').forEach(function(btn) {
        btn.addEventListener('click', function() {
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
                    var form = document.getElementById('deleteGroupForm');
                    form.action = '/back/crm/email/groups/' + id + '/destroy';
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
