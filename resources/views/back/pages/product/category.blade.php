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
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Kategori" />
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
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_category_table .form-check-input"
                                        value="1" />
                                </div>
                            </th>
                            <th class="min-w-250px">Kategori</th>
                            <th class="min-w-150px">Slug</th>
                            <th class="min-w-100px">Jumlah Produk</th>
                            <th class="min-w-100px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @if($category->icon)
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-primary">
                                                <i class="{{ $category->icon }} fs-4 text-primary"></i>
                                            </span>
                                        </div>
                                        @endif
                                        <div class="ms-2">
                                            <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1"
                                                data-kt-ecommerce-category-filter="category_name">{{ $category->name }}</a>
                                            @if($category->description)
                                                <div class="text-muted fs-7 fw-bold">{{ Str::limit($category->description, 60) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge badge-light">{{ $category->slug }}</div>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $category->products_count ?? $category->products->count() ?? 0 }}</span>
                                </td>
                                <td>
                                    @if ($category->is_active)
                                        <div class="badge badge-light-success">Active</div>
                                    @else
                                        <div class="badge badge-light-danger">Inactive</div>
                                    @endif
                                </td>
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
                                            <a href="#" class="menu-link px-3 text-danger btn-delete-category"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}">
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

    {{-- Add Category Modal --}}
    <div class="modal fade" tabindex="-1" id="add_category">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Kategori</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{ route('back.product.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label required">Nama Kategori</label>
                            <input type="text" class="form-control form-control-solid" id="name" name="name"
                                placeholder="Kategori Baru" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control form-control-solid" id="description" name="description" rows="3"
                                placeholder="Deskripsi Kategori"></textarea>
                            <small class="text-muted">
                                Disarankan untuk mengisi deskripsi kategori
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="text" class="form-control form-control-solid" id="icon" name="icon"
                                placeholder="cth: ki-duotone ki-code">
                            <small class="text-muted">Gunakan class icon Metronic (ki-duotone)</small>
                        </div>
                        <div class="mb-3">
                            <label for="order" class="form-label">Urutan</label>
                            <input type="number" class="form-control form-control-solid" id="order" name="order"
                                placeholder="0" min="0" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center">
                                <span class="form-check form-switch form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                </span>
                                Aktif
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Buat Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit & Delete Category Modals --}}
    @foreach ($categories as $category)
        <div class="modal fade" tabindex="-1" id="edit_category{{ $category->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Kategori</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <form action="{{ route('back.product.category.update', $category->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label required">Nama Kategori</label>
                                <input type="text" class="form-control form-control-solid" id="name"
                                    name="name" placeholder="Kategori Baru" value="{{ $category->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control form-control-solid" id="description" name="description" rows="3"
                                    placeholder="Deskripsi Kategori">{{ $category->description }}</textarea>
                                <small class="text-muted">
                                    Disarankan untuk mengisi deskripsi kategori
                                </small>
                            </div>
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon</label>
                                <input type="text" class="form-control form-control-solid" id="icon" name="icon"
                                    placeholder="cth: ki-duotone ki-code" value="{{ $category->icon }}">
                                <small class="text-muted">Gunakan class icon Metronic (ki-duotone)</small>
                            </div>
                            <div class="mb-3">
                                <label for="order" class="form-label">Urutan</label>
                                <input type="number" class="form-control form-control-solid" id="order" name="order"
                                    placeholder="0" min="0" value="{{ $category->order ?? 0 }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-flex align-items-center">
                                    <span class="form-check form-switch form-check-custom form-check-solid me-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            {{ $category->is_active ? 'checked' : '' }}>
                                    </span>
                                    Aktif
                                </label>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form action="{{ route('back.product.category.destroy', $category->id) }}" method="POST" class="d-none" id="delete-category-form-{{ $category->id }}">
            @method('DELETE')
            @csrf
        </form>
    @endforeach
@endsection


@section('scripts')
    <script>
        // Client-side search filter
        document.querySelector('[data-kt-ecommerce-category-filter="search"]').addEventListener('keyup', function(e) {
            var value = e.target.value.toLowerCase();
            document.querySelectorAll('#kt_ecommerce_category_table tbody tr').forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(value) > -1 ? '' : 'none';
            });
        });

        // Delete category with SweetAlert
        $(document).on('click', '.btn-delete-category', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Hapus Kategori?',
                text: 'Yakin ingin menghapus kategori "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-category-form-' + id).submit();
                }
            });
        });
    </script>
@endsection
