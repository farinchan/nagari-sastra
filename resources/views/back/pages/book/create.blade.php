@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ $title }}</h2>
                </div>
            </div>
            <form action="{{ route('back.book.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="mb-6">
                                <label class="form-label required fs-5">Judul Buku</label>
                                <input type="text" name="title"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" placeholder="Masukkan judul buku..." required />
                                @error('title')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="form-label required fs-5">Kategori</label>
                                <select name="category_id"
                                    class="form-select form-select-lg @error('category_id') is-invalid @enderror"
                                    data-control="select2" data-placeholder="Pilih kategori..." required>
                                    <option></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-6">
                                <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-stack flex-grow-1">
                                    <div class="fw-semibold">
                                        <div class="fs-6 text-gray-700">
                                            Buku akan dibuat dengan status <strong>Draft</strong>. Anda bisa melengkapi informasi detail seperti ISBN, penerbit, penulis, dan lainnya di halaman detail buku setelah dibuat.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ route('back.book.index') }}" class="btn btn-light btn-active-light-primary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i> Buat Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
