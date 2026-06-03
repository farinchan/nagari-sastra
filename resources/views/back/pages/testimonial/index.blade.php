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
                        <input type="text" data-kt-ecommerce-category-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Testimonial" />
                    </div>
                </div>
                <div class="card-toolbar">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_testimonial" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Tambah Testimonial
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-80px">Avatar</th>
                            <th class="min-w-200px">Nama</th>
                            <th class="min-w-150px">Instansi</th>
                            <th class="min-w-100px">Rating</th>
                            <th class="min-w-80px">Status</th>
                            <th class="min-w-70px">Urutan</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($testimonials as $testimonial)
                            <tr>
                                <td>
                                    <div class="symbol symbol-50px">
                                        <img src="{{ $testimonial->getAvatar() }}" alt="{{ $testimonial->name }}" />
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-gray-800">{{ $testimonial->name }}</div>
                                    <div class="text-muted fs-7">{{ $testimonial->position }}</div>
                                </td>
                                <td>{{ $testimonial->company ?? '-' }}</td>
                                <td>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="ki-duotone ki-star fs-6 {{ $i <= $testimonial->rating ? 'text-warning' : 'text-gray-300' }}">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    @endfor
                                </td>
                                <td>
                                    @if ($testimonial->is_active)
                                        <span class="badge badge-light-success">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $testimonial->order }}</td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#edit_testimonial{{ $testimonial->id }}">Edit</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#delete_testimonial{{ $testimonial->id }}">Delete</a>
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

    <!-- ADD MODAL -->
    <div class="modal fade" id="add_testimonial" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Testimonial</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form action="{{ route('back.testimonial.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama</label>
                            <input type="text" name="name" class="form-control" required />
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="position" class="form-control" placeholder="Contoh: Dosen, Mahasiswa" />
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Instansi</label>
                            <input type="text" name="company" class="form-control" placeholder="Contoh: Universitas ABC" />
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Isi Testimonial</label>
                            <textarea name="content" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row mb-5">
                            <div class="col-6">
                                <label class="form-label required">Rating</label>
                                <select name="rating" class="form-select">
                                    <option value="5" selected>⭐⭐⭐⭐⭐ (5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                    <option value="3">⭐⭐⭐ (3)</option>
                                    <option value="2">⭐⭐ (2)</option>
                                    <option value="1">⭐ (1)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="order" class="form-control" value="0" />
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*" />
                        </div>
                        <div class="mb-5">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active_add" checked />
                                <label class="form-check-label" for="is_active_add">Aktif</label>
                            </div>
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

    <!-- EDIT & DELETE MODALS -->
    @foreach ($testimonials as $testimonial)
        <!-- Edit Modal -->
        <div class="modal fade" id="edit_testimonial{{ $testimonial->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Testimonial</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <form action="{{ route('back.testimonial.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-5">
                                <label class="form-label required">Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $testimonial->name }}" required />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Jabatan</label>
                                <input type="text" name="position" class="form-control" value="{{ $testimonial->position }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Instansi</label>
                                <input type="text" name="company" class="form-control" value="{{ $testimonial->company }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label required">Isi Testimonial</label>
                                <textarea name="content" class="form-control" rows="4" required>{{ $testimonial->content }}</textarea>
                            </div>
                            <div class="row mb-5">
                                <div class="col-6">
                                    <label class="form-label required">Rating</label>
                                    <select name="rating" class="form-select">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ $testimonial->rating == $i ? 'selected' : '' }}>
                                                {{ str_repeat('⭐', $i) }} ({{ $i }})
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="order" class="form-control" value="{{ $testimonial->order }}" />
                                </div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Avatar</label>
                                @if ($testimonial->avatar)
                                    <div class="mb-3">
                                        <img src="{{ $testimonial->getAvatar() }}" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" />
                                    </div>
                                @endif
                                <input type="file" name="avatar" class="form-control" accept="image/*" />
                                <div class="form-text">Kosongkan jika tidak ingin mengubah avatar</div>
                            </div>
                            <div class="mb-5">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                        id="is_active_edit{{ $testimonial->id }}" {{ $testimonial->is_active ? 'checked' : '' }} />
                                    <label class="form-check-label" for="is_active_edit{{ $testimonial->id }}">Aktif</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="delete_testimonial{{ $testimonial->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Testimonial</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus testimonial dari <strong>{{ $testimonial->name }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('back.testimonial.destroy', $testimonial->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
