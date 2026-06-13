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
                        <input type="text" data-kt-ecommerce-product-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Produk" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">Semua</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <a href="{{ route('back.product.create') }}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Produk</a>
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
                            <th class="min-w-250px">Produk</th>
                            <th class="text-end min-w-100px">Kategori</th>
                            <th class="text-end min-w-100px">Harga</th>
                            <th class="text-end min-w-100px">Status</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('back.product.show', $product->id) }}" class="symbol symbol-50px">
                                            <img src="@if ($product->thumbnail) {{ Storage::url($product->thumbnail) }} @else {{ asset('back/media/svg/files/blank-image.svg') }} @endif"
                                                alt="{{ $product->name }}" class="symbol-label" loading="lazy" style="object-fit: cover;" />
                                        </a>
                                        <div class="ms-5">
                                            <a href="{{ route('back.product.show', $product->id) }}" class="text-gray-800 text-hover-primary fs-5 fw-bold mb-1"
                                                data-kt-ecommerce-product-filter="product_name">{{ $product->name }}</a>
                                            <div class="text-muted fs-7 fw-bold">
                                                {{ Str::limit(strip_tags($product->short_description), 80) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="fw-bold">{{ $product->category->name ?? '-' }}</span>
                                </td>
                                <td class="text-end pe-0">
                                    @if ($product->discount_price)
                                        <span class="fw-bold text-danger">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</span>
                                        <br>
                                        <span class="text-muted text-decoration-line-through fs-8">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="fw-bold">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-0">
                                    @if ($product->is_active)
                                        <div class="badge badge-light-success">Active</div>
                                    @else
                                        <div class="badge badge-light-danger">Inactive</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        Aksi
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="{{ route('back.product.show', $product->id) }}" class="menu-link px-3">
                                                <i class="ki-duotone ki-eye fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                Detail
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="{{ route('back.product.edit', $product->id) }}" class="menu-link px-3">
                                                <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i>
                                                Edit
                                            </a>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 text-danger btn-delete"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                                <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                Hapus
                                            </a>
                                            <form action="{{ route('back.product.destroy', $product->id) }}" method="POST" class="delete-form d-none" id="delete-form-{{ $product->id }}">
                                                @method('DELETE')
                                                @csrf
                                            </form>
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
@endsection
@section('scripts')
    <script>
        // Client-side search filter
        document.querySelector('[data-kt-ecommerce-product-filter="search"]').addEventListener('keyup', function(e) {
            var value = e.target.value.toLowerCase();
            document.querySelectorAll('#kt_ecommerce_products_table tbody tr').forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(value) > -1 ? '' : 'none';
            });
        });

        // Client-side status filter
        $('[data-kt-ecommerce-product-filter="status"]').on('change', function() {
            var value = $(this).val();
            $('#kt_ecommerce_products_table tbody tr').each(function() {
                var status = $(this).find('.badge').text().trim().toLowerCase();
                if (value === 'all' || value === '') {
                    $(this).show();
                } else {
                    $(this).toggle(status === value);
                }
            });
        });

        // Delete with SweetAlert
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            Swal.fire({
                title: 'Hapus Produk?',
                text: 'Yakin ingin menghapus produk "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-form-' + id).submit();
                }
            });
        });
    </script>
@endsection
