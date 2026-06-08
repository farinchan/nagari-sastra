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
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Grup" />
                    </div>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Grup
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
                            <th class="min-w-200px">Grup</th>
                            <th class="min-w-150px">Deskripsi</th>
                            <th class="text-end min-w-100px">Jumlah Kontak</th>
                            <th class="text-end min-w-120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($groups as $group)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="bullet bullet-dot h-10px w-10px me-3" style="background-color: {{ $group->color }}"></span>
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $group->name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ Str::limit($group->description, 60) ?? '-' }}</td>
                                <td class="text-end pe-0">
                                    <span class="badge badge-light-primary fs-7">{{ $group->contacts_count }} kontak</span>
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="{{ route('back.crm.email.contacts', $group->id) }}" class="menu-link px-3">
                                                <i class="ki-duotone ki-people fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Kontak
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-edit-group"
                                                data-id="{{ $group->id }}"
                                                data-name="{{ $group->name }}"
                                                data-description="{{ $group->description }}"
                                                data-color="{{ $group->color }}">
                                                <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Edit
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-delete-group" data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                                <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Group Modal --}}
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.groups.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Grup Kontak</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
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

    {{-- Edit Group Modal --}}
    <div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editGroupForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Grup Kontak</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
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

    {{-- Delete Form --}}
    <form id="deleteGroupForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/email-groups.js') }}"></script>
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
