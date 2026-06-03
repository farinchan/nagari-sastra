@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        @include('back.pages.book.show-header')

        <div class="row">
            {{-- Book Detail Form --}}
            <div class="col-lg-8">
                <form action="{{ route('back.book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="card mb-5 mb-lg-10">
                        <div class="card-header">
                            <div class="card-title"><h3>Informasi Buku</h3></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 mb-5">
                                    <label class="form-label required">Judul</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $book->title) }}" required />
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <label class="form-label required">Kategori</label>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                                        data-control="select2" data-placeholder="Pilih kategori..." required>
                                        <option></option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $book->book_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <label class="form-label required">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        data-control="select2">
                                        <option value="draft" {{ old('status', $book->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $book->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('status', $book->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <label class="form-label">ISBN</label>
                                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                        value="{{ old('isbn', $book->isbn) }}" />
                                    @error('isbn')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <label class="form-label">Penerbit</label>
                                    <input type="text" name="publisher" class="form-control"
                                        value="{{ old('publisher', $book->publisher) }}" />
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label class="form-label">Edisi</label>
                                    <input type="text" name="edition" class="form-control"
                                        value="{{ old('edition', $book->edition) }}" />
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label class="form-label">Tahun Terbit</label>
                                    <input type="number" name="publish_year" class="form-control"
                                        value="{{ old('publish_year', $book->publish_year) }}" min="1900" max="{{ date('Y') }}" />
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label class="form-label">Bahasa</label>
                                    <select name="language" class="form-select" data-control="select2">
                                        <option value="id" {{ old('language', $book->language) == 'id' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="en" {{ old('language', $book->language) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="jp" {{ old('language', $book->language) == 'jp' ? 'selected' : '' }}>Japanese</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label class="form-label">Halaman</label>
                                    <input type="number" name="pages" class="form-control"
                                        value="{{ old('pages', $book->pages) }}" />
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label class="form-label">Ukuran</label>
                                    <input type="text" name="size" class="form-control"
                                        value="{{ old('size', $book->size) }}" placeholder="cth: 15 x 23 cm" />
                                </div>

                                <div class="col-lg-4 mb-5">
                                    <label class="form-label">Berat (gram)</label>
                                    <input type="number" name="weight" class="form-control" step="0.01"
                                        value="{{ old('weight', $book->weight) }}" />
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <label class="form-label">Harga (Rp)</label>
                                    <input type="number" name="price" class="form-control" step="1" min="0"
                                        value="{{ old('price', intval($book->price)) }}" />
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <label class="form-label">Stok</label>
                                    <input type="number" name="stock" class="form-control" min="0"
                                        value="{{ old('stock', $book->stock) }}" />
                                </div>

                                <div class="col-lg-12 mb-5">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="8">{{ old('description', $book->description) }}</textarea>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-duotone ki-check fs-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Right Sidebar --}}
            <div class="col-lg-4">
                {{-- File & Lampiran --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>File & Lampiran</h3></div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('back.book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="mb-5">
                                <label class="form-label">Thumbnail Cover</label>
                                @if($book->thumbnail)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $book->thumbnail) }}" alt="{{ $book->title }}"
                                            class="rounded" style="max-width: 100%; height: auto; max-height: 200px;" />
                                    </div>
                                @endif
                                <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*" />
                                <small class="text-muted">Max: 8MB</small>
                            </div>

                            <div class="mb-5">
                                <label class="form-label">File Preview</label>
                                @if($book->preview_file)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $book->preview_file) }}" target="_blank" class="btn btn-sm btn-light-primary w-100">
                                            <i class="ki-duotone ki-file fs-2"><span class="path1"></span><span class="path2"></span></i> Lihat File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="preview_file" class="form-control form-control-sm" accept=".pdf" />
                                <small class="text-muted">PDF (Max: 30MB)</small>
                            </div>

                            <div class="mb-5">
                                <label class="form-label">File Attachment</label>
                                @if($book->attachment)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $book->attachment) }}" target="_blank" class="btn btn-sm btn-light-info w-100">
                                            <i class="ki-duotone ki-file-down fs-2"><span class="path1"></span><span class="path2"></span></i> Lihat File
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="attachment" class="form-control form-control-sm" accept=".pdf" />
                                <small class="text-muted">PDF (Max: 30MB)</small>
                            </div>

                            {{-- Hidden fields so update doesn't blank out other data --}}
                            <input type="hidden" name="title" value="{{ $book->title }}" />
                            <input type="hidden" name="category_id" value="{{ $book->book_category_id }}" />
                            <input type="hidden" name="status" value="{{ $book->status }}" />

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-check fs-2"></i> Upload File
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Editor Assignment --}}
                <div class="card mb-5 mb-lg-10">
                    <div class="card-header">
                        <div class="card-title"><h3>Editor</h3></div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('back.book.editor.update', $book->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="mb-5">
                                <label class="form-label fw-bold">Pilih Editor</label>
                                <select class="form-select" data-control="select2" data-placeholder="Pilih editor..."
                                    name="editor_ids[]" data-allow-clear="true" multiple="multiple">
                                    <option></option>
                                    @foreach ($editors as $editor)
                                        <option value="{{ $editor->id }}"
                                            {{ $book->editors->contains($editor->id) ? 'selected' : '' }}>
                                            {{ $editor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ki-duotone ki-check fs-2"></i> Simpan Editor
                            </button>
                        </form>

                        @if($book->editors->count() > 0)
                            <div class="separator my-5"></div>
                            <div class="fw-bold fs-6 mb-3">Editor Terpilih:</div>
                            @foreach($book->editors as $ed)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-35px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary text-primary fw-bold">{{ strtoupper(substr($ed->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-gray-800">{{ $ed->name }}</span><br>
                                        <span class="text-muted fs-7">{{ $ed->email }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
