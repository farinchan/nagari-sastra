@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('back.book.store') }}" method="POST" enctype="multipart/form-data" id="book_form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Left Sidebar -->
                        <div class="col-lg-4">
                            <div class="sticky-top" style="top: 80px; z-index: 1;">
                                <!-- Thumbnail Section -->
                                <div class="mb-6">
                                    <label class="form-label">Thumbnail Cover</label>
                                    <div class="mb-3">
                                        <div class="symbol symbol-150px bg-light">
                                            <span class="symbol-label d-flex align-items-center justify-content-center">
                                                <i class="ki-duotone ki-image fs-3x text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                                        accept="image/*" id="thumbnail_input" />
                                    @error('thumbnail')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">JPEG, PNG, GIF, SVG (Max: 2MB)</small>
                                </div>

                                <!-- File Upload Section -->
                                <div class="mb-6">
                                    <label class="form-label">File Preview</label>
                                    <input type="file" name="preview_file"
                                        class="form-control @error('preview_file') is-invalid @enderror" accept=".pdf" />
                                    @error('preview_file')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">PDF (Max: 10MB)</small>
                                </div>

                                <div class="mb-6">
                                    <label class="form-label">File Attachment</label>
                                    <input type="file" name="attachment"
                                        class="form-control @error('attachment') is-invalid @enderror" accept=".pdf" />
                                    @error('attachment')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">PDF (Max: 10MB)</small>
                                </div>

                                <!-- Status Section -->
                                <div class="mb-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        data-control="select2">
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Right Main Content -->
                        <div class="col-lg-8">
                            <!-- Title & Category -->
                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Judul Buku</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Judul Buku" value="{{ old('title') }}" />
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Kategori</label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                        data-control="select2">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Author String -->
                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Author String</label>
                                    <input type="text" name="authorString" class="form-control @error('authorString') is-invalid @enderror"
                                        placeholder="Semua penulis beserta gelar" value="{{ old('authorString') }}" />
                                    @error('authorString')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Authors -->
                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Authors</label>
                                    <input id="authors_tagify" name="authors" class="form-control @error('authors') is-invalid @enderror"
                                        value="{{ old('authors') }}" placeholder="Nama penulis tanpa gelar, tekan Enter" />
                                    @error('authors')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <div class="text-muted fs-7 mt-1">Gunakan untuk nama penulis tanpa gelar, tersimpan sebagai array.</div>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Penerbit</label>
                                    <input type="text" name="publisher"
                                        class="form-control @error('publisher') is-invalid @enderror"
                                        placeholder="Nama Penerbit" value="{{ old('publisher') }}" />
                                    @error('publisher')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- ISBN, Edition, Publish Year -->
                            <div class="row mb-6">
                                <div class="col-lg-4">
                                    <label class="form-label">ISBN</label>
                                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                        placeholder="ISBN" value="{{ old('isbn') }}" />
                                    @error('isbn')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Edisi</label>
                                    <input type="text" name="edition" class="form-control @error('edition') is-invalid @enderror"
                                        placeholder="Edisi" value="{{ old('edition') }}" />
                                    @error('edition')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Tahun Terbit</label>
                                    <input type="number" name="publish_year"
                                        class="form-control @error('publish_year') is-invalid @enderror"
                                        placeholder="Tahun Terbit" value="{{ old('publish_year') }}" min="1900"
                                        max="{{ date('Y') }}" />
                                    @error('publish_year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pages, Size, Weight, Language -->
                            <div class="row mb-6">
                                <div class="col-lg-3">
                                    <label class="form-label">Halaman</label>
                                    <input type="number" name="pages" class="form-control @error('pages') is-invalid @enderror"
                                        placeholder="Jumlah Halaman" value="{{ old('pages') }}" />
                                    @error('pages')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Ukuran</label>
                                    <input type="text" name="size" class="form-control @error('size') is-invalid @enderror"
                                        placeholder="Ukuran (cm)" value="{{ old('size') }}" />
                                    @error('size')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Berat (gr)</label>
                                    <input type="number" step="0.01" name="weight"
                                        class="form-control @error('weight') is-invalid @enderror"
                                        placeholder="Berat" value="{{ old('weight') }}" />
                                    @error('weight')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Bahasa</label>
                                    <select name="language" class="form-select @error('language') is-invalid @enderror"
                                        data-control="select2">
                                        <option value="">Pilih Bahasa</option>
                                        <option value="id" {{ old('language', 'id') == 'id' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="jp" {{ old('language') == 'jp' ? 'selected' : '' }}>Japanese</option>
                                    </select>
                                    @error('language')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price & Stock -->
                            <div class="row mb-6">
                                <div class="col-lg-6">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" step="0.01" name="price"
                                        class="form-control @error('price') is-invalid @enderror"
                                        placeholder="Harga" value="{{ old('price', 0) }}" />
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Stok</label>
                                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                        placeholder="Stok" value="{{ old('stock', 0) }}" />
                                    @error('stock')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Deskripsi Buku" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keywords -->
                            <div class="row mb-6">
                                <div class="col-12">
                                    <label class="form-label">Keywords</label>
                                    <input id="keyword_tagify" name="keywords" class="form-control mb-2"
                                        value="{{ old('keywords') }}" />
                                    @error('keywords')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <div class="text-muted fs-7">
                                        Pisahkan dengan koma <code>,</code> jika lebih dari satu.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="reset" class="btn btn-light me-3">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var tagify = new Tagify(document.querySelector("#keyword_tagify"), {
            whitelist: [],
            dropdown: {
                maxItems: 20,
                classname: "tags-look",
                enabled: 0,
                closeOnSelect: true
            }
        });

        var authorsTagify = new Tagify(document.querySelector("#authors_tagify"), {
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
