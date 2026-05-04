@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('back.book.update', $book->id) }}" method="POST" enctype="multipart/form-data"
                id="book_form">
                @method('PUT')
                @csrf
                <div class="card-body">
                    <div class="row mb-6">
                        <div class="col-lg-6">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Judul Buku" value="{{ old('title', $book->title) }}" />
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                data-control="select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $book->book_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-6">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                placeholder="Nama Penulis" value="{{ old('author', $book->author) }}" />
                            @error('author')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="publisher"
                                class="form-control @error('publisher') is-invalid @enderror"
                                placeholder="Nama Penerbit" value="{{ old('publisher', $book->publisher) }}" />
                            @error('publisher')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-4">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                placeholder="ISBN" value="{{ old('isbn', $book->isbn) }}" />
                            @error('isbn')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Edisi</label>
                            <input type="text" name="edition" class="form-control @error('edition') is-invalid @enderror"
                                placeholder="Edisi" value="{{ old('edition', $book->edition) }}" />
                            @error('edition')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="number" name="publish_year"
                                class="form-control @error('publish_year') is-invalid @enderror"
                                placeholder="Tahun Terbit" value="{{ old('publish_year', $book->publish_year) }}"
                                min="1900" max="{{ date('Y') }}" />
                            @error('publish_year')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-3">
                            <label class="form-label">Halaman</label>
                            <input type="number" name="pages" class="form-control @error('pages') is-invalid @enderror"
                                placeholder="Jumlah Halaman" value="{{ old('pages', $book->pages) }}" />
                            @error('pages')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Ukuran</label>
                            <input type="text" name="size" class="form-control @error('size') is-invalid @enderror"
                                placeholder="Ukuran (cm)" value="{{ old('size', $book->size) }}" />
                            @error('size')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Berat (gr)</label>
                            <input type="number" step="0.01" name="weight"
                                class="form-control @error('weight') is-invalid @enderror"
                                placeholder="Berat" value="{{ old('weight', $book->weight) }}" />
                            @error('weight')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">Bahasa</label>
                            <input type="text" name="language" class="form-control @error('language') is-invalid @enderror"
                                placeholder="Bahasa" value="{{ old('language', $book->language ?? 'Indonesia') }}" />
                            @error('language')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-4">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" step="0.01" name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="Harga" value="{{ old('price', $book->price) }}" />
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Stok</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                placeholder="Stok" value="{{ old('stock', $book->stock) }}" />
                            @error('stock')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror"
                                data-control="select2">
                                <option value="">Pilih Status</option>
                                <option value="draft" {{ old('status', $book->status) == 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published" {{ old('status', $book->status) == 'published' ? 'selected' : '' }}>
                                    Published</option>
                                <option value="archived" {{ old('status', $book->status) == 'archived' ? 'selected' : '' }}>
                                    Archived</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-4">
                            <label class="form-label">Thumbnail Cover</label>
                            @if ($book->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($book->thumbnail) }}" alt="Thumbnail" class="img-fluid"
                                        style="max-width: 150px;" />
                                </div>
                            @endif
                            <div class="input-group">
                                <input type="file" name="thumbnail"
                                    class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*" />
                            </div>
                            @error('thumbnail')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Format: JPEG, PNG, GIF, SVG (Max: 2MB)</small>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">File Preview</label>
                            @if ($book->preview_file)
                                <div class="mb-2">
                                    <a href="{{ $book->getPreviewFile() }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="ki-duotone ki-file-pdf"></i> Preview File
                                    </a>
                                </div>
                            @endif
                            <div class="input-group">
                                <input type="file" name="preview_file"
                                    class="form-control @error('preview_file') is-invalid @enderror" accept=".pdf" />
                            </div>
                            @error('preview_file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Format: PDF (Max: 10MB)</small>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">File Attachment</label>
                            @if ($book->attachment)
                                <div class="mb-2">
                                    <a href="{{ $book->getAttachment() }}" target="_blank" class="btn btn-sm btn-light">
                                        <i class="ki-duotone ki-file-pdf"></i> Attachment
                                    </a>
                                </div>
                            @endif
                            <div class="input-group">
                                <input type="file" name="attachment"
                                    class="form-control @error('attachment') is-invalid @enderror" accept=".pdf" />
                            </div>
                            @error('attachment')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Format: PDF (Max: 10MB)</small>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Deskripsi Buku" rows="5">{{ old('description', $book->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-lg-12">
                            <label class="form-label">Keywords</label>
                            <input id="keyword_tagify" name="keywords" class="form-control mb-2"
                                value="{{ old('keywords', $book->keywords ? json_encode($book->keywords) : '') }}" />
                            @error('keywords')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <div class="text-muted fs-7">
                                Keywords digunakan untuk pencarian, pisahkan dengan koma <code>,</code> jika lebih dari satu.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('back.book.index') }}" class="btn btn-light me-3">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Buku</button>
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
    </script>
@endsection
