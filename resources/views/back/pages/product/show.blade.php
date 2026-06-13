@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        {{-- Product Header Card --}}
        <div class="card mb-9">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    @if($product->thumbnail)
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <img src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}" class="rounded" style="object-fit: cover;" />
                        </div>
                    </div>
                    @endif
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-1">
                                    <a class="text-gray-800 fs-2 fw-bold me-3">{{ $product->name }}</a>
                                    @if($product->is_active)
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactive</span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge badge-light-warning ms-2">Unggulan</span>
                                    @endif
                                </div>
                                <div class="d-flex flex-wrap fw-semibold mb-4 fs-5 text-gray-500">
                                    {{ $product->category->name ?? '-' }}
                                    @if($product->version)
                                        &bull; v{{ $product->version }}
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('back.product.edit', $product->id) }}" class="btn btn-sm btn-light-warning">
                                    <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap">
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="fw-semibold fs-6 text-gray-500">Harga</div>
                                <div class="fw-bold fs-4">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            @if($product->discount_price)
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="fw-semibold fs-6 text-gray-500">Harga Diskon</div>
                                <div class="fw-bold fs-4 text-danger">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="fw-semibold fs-6 text-gray-500">Download</div>
                                <div class="fw-bold fs-4">{{ $product->download_count ?? 0 }}</div>
                            </div>
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="fw-semibold fs-6 text-gray-500">Dilihat</div>
                                <div class="fw-bold fs-4">{{ $product->view_count ?? 0 }}</div>
                            </div>
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="fw-semibold fs-6 text-gray-500">Dibuat</div>
                                <div class="fw-bold fs-4">{{ $product->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="separator"></div>
            </div>
        </div>

        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Product Info --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Informasi Produk</h3></div>
                    </div>
                    <div class="card-body">
                        @if($product->short_description)
                        <div class="mb-7">
                            <h5 class="text-gray-700 fw-bold mb-2">Deskripsi Singkat</h5>
                            <p class="text-gray-600 fs-6">{{ $product->short_description }}</p>
                        </div>
                        @endif

                        <div class="mb-7">
                            <h5 class="text-gray-700 fw-bold mb-2">Deskripsi</h5>
                            <div class="text-gray-600 fs-6">
                                {!! $product->description !!}
                            </div>
                        </div>

                        <div class="separator separator-dashed my-5"></div>

                        <div class="row mb-5">
                            @if($product->version)
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Versi</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $product->version }}</div>
                            </div>
                            @endif
                            @if($product->compatibility)
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Kompatibilitas</div>
                                <div class="fw-bold text-gray-800 fs-6">{{ $product->compatibility }}</div>
                            </div>
                            @endif
                            @if($product->demo_url)
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Demo URL</div>
                                <div class="fw-bold text-gray-800 fs-6">
                                    <a href="{{ $product->demo_url }}" target="_blank" class="text-primary">
                                        {{ $product->demo_url }}
                                        <i class="ki-duotone ki-exit-right-corner fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                            @if($product->documentation_url)
                            <div class="col-lg-6 mb-4">
                                <div class="fw-semibold text-gray-500 fs-7">Dokumentasi URL</div>
                                <div class="fw-bold text-gray-800 fs-6">
                                    <a href="{{ $product->documentation_url }}" target="_blank" class="text-primary">
                                        {{ $product->documentation_url }}
                                        <i class="ki-duotone ki-exit-right-corner fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($product->tags)
                        <div class="mb-3">
                            <div class="fw-semibold text-gray-500 fs-7 mb-2">Tags</div>
                            <div>
                                @php
                                    $tags = is_array($product->tags) ? $product->tags : explode(',', $product->tags);
                                @endphp
                                @foreach($tags as $tag)
                                    <span class="badge badge-light-primary fs-7 me-1 mb-1">{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Screenshots --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Screenshots</h3></div>
                        <div class="card-toolbar">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#upload_screenshot" class="btn btn-sm btn-light-primary">
                                <i class="ki-duotone ki-plus fs-5"></i> Upload Screenshot
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($product->screenshots) && count($product->screenshots) > 0)
                            <div class="row g-5">
                                @foreach($product->screenshots as $screenshot)
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card card-flush border border-gray-300 h-100">
                                            <div class="card-body p-3 text-center">
                                                <img src="{{ Storage::url($screenshot->image) }}" alt="Screenshot"
                                                    class="rounded" style="max-width: 100%; height: auto; max-height: 200px;" loading="lazy" />
                                            </div>
                                            <div class="card-footer p-3 text-center">
                                                <form action="{{ route('back.product.screenshot.destroy', [$product->id, $screenshot->id]) }}" method="POST" class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light-danger btn-delete-screenshot">
                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-10">
                                <i class="ki-duotone ki-picture fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fs-6">Belum ada screenshot</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Reviews --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Ulasan</h3></div>
                    </div>
                    <div class="card-body">
                        @if(isset($product->reviews) && count($product->reviews) > 0)
                            @foreach($product->reviews as $review)
                                <div class="d-flex mb-7">
                                    <div class="symbol symbol-45px me-5">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div>
                                                <span class="text-gray-800 fw-bold fs-6">{{ $review->user->name ?? 'Unknown' }}</span>
                                                <span class="text-muted fs-7 ms-2">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div>
                                                @if($review->status == 'approved')
                                                    <span class="badge badge-light-success">Approved</span>
                                                @else
                                                    <span class="badge badge-light-warning">Pending</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="ki-duotone ki-star fs-6 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                                @else
                                                    <i class="ki-duotone ki-star fs-6 text-gray-300"><span class="path1"></span><span class="path2"></span></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 fs-7 mb-2">{{ $review->comment }}</p>
                                        <div class="d-flex gap-2">
                                            @if($review->status != 'approved')
                                                <form action="{{ route('back.product.review.approve', $review->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-light-success">
                                                        <i class="ki-duotone ki-check fs-5"></i> Approve
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('back.product.review.destroy', $review->id) }}" method="POST" class="d-inline">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light-danger btn-delete-review">
                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="separator separator-dashed my-5"></div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center text-muted py-10">
                                <i class="ki-duotone ki-message-text fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="fs-6">Belum ada ulasan</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-4">
                {{-- File Download --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>File Produk</h3></div>
                    </div>
                    <div class="card-body">
                        @if($product->file)
                            <a href="{{ Storage::url($product->file) }}" target="_blank" class="btn btn-light-primary w-100 mb-3">
                                <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i>
                                Download File
                            </a>
                            <div class="text-muted fs-7 text-center">{{ basename($product->file) }}</div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="ki-duotone ki-file fs-3x text-gray-400 mb-3"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fs-7">Belum ada file</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Statistik --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Statistik</h3></div>
                    </div>
                    <div class="card-body py-5">
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-eye fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ $product->view_count ?? 0 }}</span>
                                <span class="text-muted fs-7">Total Dilihat</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-success">
                                    <i class="ki-duotone ki-arrow-down fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ $product->download_count ?? 0 }}</span>
                                <span class="text-muted fs-7">Total Download</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-warning">
                                    <i class="ki-duotone ki-star fs-5 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ isset($product->reviews) ? $product->reviews->count() : 0 }}</span>
                                <span class="text-muted fs-7">Total Ulasan</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-30px me-5">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-calendar fs-5 text-info"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-gray-800 fs-6">{{ $product->created_at->format('d M Y') }}</span>
                                <span class="text-muted fs-7">Tanggal Dibuat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Upload Screenshot Modal --}}
    <div class="modal fade" tabindex="-1" id="upload_screenshot">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Upload Screenshot</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form action="{{ route('back.product.screenshot.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="screenshot_image" class="form-label required">Screenshot</label>
                            <input type="file" class="form-control form-control-solid" id="screenshot_image" name="screenshot"
                                accept="image/png,image/jpeg,image/webp,.png,.jpg,.jpeg,.webp" required>
                            <small class="text-muted">Format: .png, .jpg, .jpeg, .webp (Max: 2MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Delete screenshot confirmation
        $(document).on('click', '.btn-delete-screenshot', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Screenshot?',
                text: 'Yakin ingin menghapus screenshot ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Delete review confirmation
        $(document).on('click', '.btn-delete-review', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Ulasan?',
                text: 'Yakin ingin menghapus ulasan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
