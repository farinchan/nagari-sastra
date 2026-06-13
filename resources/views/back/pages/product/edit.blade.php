@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">
        <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row"
            action="{{ route('back.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                {{-- Thumbnail --}}
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Thumbnail</h2>
                        </div>
                    </div>
                    <div class="card-body text-center pt-0">
                        <style>
                            .image-input-placeholder {
                                background-image: url('@if ($product->thumbnail) {{ Storage::url($product->thumbnail) }} @else {{ asset('back/media/svg/files/blank-image.svg') }} @endif');
                            }
                        </style>
                        <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                            data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Ubah Thumbnail">
                                <i class="ki-duotone ki-pencil fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="file" name="thumbnail" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Batalkan Thumbnail">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Hapus Thumbnail">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">
                            Set Thumbnail Produk, hanya menerima file dengan ekstensi .png, .jpg, .jpeg
                        </div>
                        @error('thumbnail')
                            <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- File Produk --}}
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>File Produk</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if ($product->file)
                            <div class="mb-3">
                                <a href="{{ Storage::url($product->file) }}" target="_blank" class="btn btn-sm btn-light-primary w-100">
                                    <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    File saat ini: {{ basename($product->file) }}
                                </a>
                            </div>
                        @endif
                        <div class="mb-5">
                            <label class="form-label">Ganti File</label>
                            <input type="file" name="file" class="form-control form-control-sm"
                                accept=".zip,.rar,.gz,.tar" />
                            <small class="text-muted">Format: .zip, .rar, .gz, .tar (Max: 50MB). Kosongkan jika tidak ingin mengubah.</small>
                        </div>
                        @error('file')
                            <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Harga --}}
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Harga</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-5">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', intval($product->price)) }}" min="0" step="1" placeholder="0" />
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Harga Diskon (Rp)</label>
                            <input type="number" name="discount_price" class="form-control @error('discount_price') is-invalid @enderror"
                                value="{{ old('discount_price', intval($product->discount_price)) }}" min="0" step="1" placeholder="0" />
                            @error('discount_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="text-muted fs-7 mt-1">Kosongkan jika tidak ada diskon</div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-5">
                            <label class="form-label d-flex align-items-center">
                                <span class="form-check form-switch form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $product->is_active) ? 'checked' : '' }} />
                                </span>
                                Aktif
                            </label>
                            <div class="text-muted fs-7">Produk akan ditampilkan jika status aktif</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label d-flex align-items-center">
                                <span class="form-check form-switch form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                        {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} />
                                </span>
                                Unggulan
                            </label>
                            <div class="text-muted fs-7">Produk akan ditampilkan di halaman utama</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Informasi Produk</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control mb-2 @error('name') is-invalid @enderror"
                                placeholder="Nama Produk" value="{{ old('name', $product->name) }}" required />
                            @error('name')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 fv-row">
                            <label class="required form-label">Kategori</label>
                            <select name="product_category_id" class="form-select mb-2 @error('product_category_id') is-invalid @enderror"
                                data-control="select2" data-hide-search="true" data-placeholder="Pilih Kategori Produk" required>
                                <option></option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('product_category_id')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10 fv-row">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="short_description" class="form-control mb-2 @error('short_description') is-invalid @enderror"
                                rows="3" placeholder="Deskripsi singkat produk">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-10">
                            <label class="form-label">Deskripsi</label>
                            <div id="quill_content" class="min-h-400px mb-2">
                                {!! old('description', $product->description) !!}
                            </div>
                            <input type="hidden" name="description" id="description">
                            @error('description')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6 fv-row mb-5">
                                <label class="form-label">Versi</label>
                                <input type="text" name="version" class="form-control @error('version') is-invalid @enderror"
                                    value="{{ old('version', $product->version) }}" placeholder="cth: 1.0.0" />
                                @error('version')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 fv-row mb-5">
                                <label class="form-label">Kompatibilitas</label>
                                <input type="text" name="compatibility" class="form-control @error('compatibility') is-invalid @enderror"
                                    value="{{ old('compatibility', $product->compatibility) }}" placeholder="cth: Laravel 10, PHP 8.1+" />
                                @error('compatibility')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-lg-6 fv-row mb-5">
                                <label class="form-label">Demo URL</label>
                                <input type="url" name="demo_url" class="form-control @error('demo_url') is-invalid @enderror"
                                    value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://demo.example.com" />
                                @error('demo_url')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6 fv-row mb-5">
                                <label class="form-label">Dokumentasi URL</label>
                                <input type="url" name="documentation_url" class="form-control @error('documentation_url') is-invalid @enderror"
                                    value="{{ old('documentation_url', $product->documentation_url) }}" placeholder="https://docs.example.com" />
                                @error('documentation_url')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Tags</label>
                            <input id="keyword_tagify" name="tags" class="form-control mb-2"
                                value="{{ old('tags', is_array($product->tags) ? implode(',', $product->tags) : $product->tags) }}" />
                            @error('tags')
                                <div class="text-danger fs-7">{{ $message }}</div>
                            @enderror
                            <div class="text-muted fs-7">
                                Tags digunakan untuk pencarian, pisahkan dengan koma <code>,</code> jika lebih dari satu tag
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('back.product.index') }}" id="kt_ecommerce_add_product_cancel"
                        class="btn btn-light me-5">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var quill = new Quill('#quill_content', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    [{
                        header: [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'align': []
                    }],
                    ['clean']
                ]
            },
            placeholder: 'Tulis deskripsi produk disini...',
            theme: 'snow'
        });

        $("#description").val(quill.root.innerHTML);
        quill.on('text-change', function() {
            $("#description").val(quill.root.innerHTML);
        });

        var tagify = new Tagify(document.querySelector("#keyword_tagify"), {
            whitelist: [],
            dropdown: {
                maxItems: 20,
                classname: "tags-look",
                enabled: 0,
                closeOnSelect: true
            }
        });
    </script>
@endsection
