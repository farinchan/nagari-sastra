@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-ecommerce-category-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari kategori surat" />
                    </div>
                </div>
                <div class="card-toolbar">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_category" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Tambah Kategori
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-30px">No</th>
                            <th class="min-w-200px">Nama Kategori</th>
                            <th class="min-w-100px">Kode</th>
                            <th class="min-w-200px">Deskripsi</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="text-gray-800 fw-bold" data-kt-ecommerce-category-filter="category_name">{{ $category->name }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-primary fs-7">{{ $category->kode }}</span>
                                </td>
                                <td>{{ $category->description ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#edit_category{{ $category->id }}">
                                                Edit</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#delete_category{{ $category->id }}">
                                                Delete</a>
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

    {{-- Modal Add Category --}}
    <div class="modal fade" tabindex="-1" id="add_category">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Kategori Surat</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form action="{{ route('back.outgoing-mail.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="required form-label">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Letter of Acceptance" required />
                        </div>
                        <div class="mb-5">
                            <label class="required form-label">Kode</label>
                            <input type="text" name="kode" class="form-control" placeholder="Contoh: LOA" required />
                            <div class="text-muted fs-7 mt-1">Kode singkat untuk nomor surat, contoh: LOA, INV, SPK</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi kategori (opsional)"></textarea>
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

    {{-- Modal Edit & Delete Category --}}
    @foreach ($categories as $category)
        <div class="modal fade" tabindex="-1" id="edit_category{{ $category->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Kategori Surat</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <form action="{{ route('back.outgoing-mail.category.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-5">
                                <label class="required form-label">Nama Kategori</label>
                                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required />
                            </div>
                            <div class="mb-5">
                                <label class="required form-label">Kode</label>
                                <input type="text" name="kode" class="form-control" value="{{ $category->kode }}" required />
                                <div class="text-muted fs-7 mt-1">Kode singkat untuk nomor surat, contoh: LOA, INV, SPK</div>
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
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

        <div class="modal fade" tabindex="-1" id="delete_category{{ $category->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Hapus Kategori Surat</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <form action="{{ route('back.outgoing-mail.category.destroy', $category->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $category->name }} ({{ $category->kode }})</strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/ecommerce/catalog/categories.js') }}"></script>
@endsection
