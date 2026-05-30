@extends('back.app')
@section('content')
<div id="kt_content_container" class=" container-xxl ">
    <form class="form d-flex flex-column flex-lg-row" action="{{ route('back.outgoing-mail.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Tambah Surat Keluar</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required form-label">Kategori Surat</label>
                                <select name="outgoing_mail_category_id" class="form-select mb-2" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('outgoing_mail_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->kode }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('outgoing_mail_category_id')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                                <div class="text-muted fs-7">Nomor surat akan digenerate otomatis berdasarkan kategori</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required form-label">Tujuan</label>
                                <input type="text" name="tujuan" class="form-control mb-2" placeholder="Masukkan tujuan surat" value="{{ old('tujuan') }}" required />
                                @error('tujuan')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" class="form-control mb-2" value="{{ old('tanggal_surat') }}" required />
                                @error('tanggal_surat')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fv-row">
                                <label class="required form-label">Klasifikasi</label>
                                <select name="klasifikasi" class="form-select mb-2" required>
                                    <option value="biasa" {{ old('klasifikasi') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                                    <option value="penting" {{ old('klasifikasi') == 'penting' ? 'selected' : '' }}>Penting</option>
                                    <option value="rahasia" {{ old('klasifikasi') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                                    <option value="sangat_rahasia" {{ old('klasifikasi') == 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
                                </select>
                                @error('klasifikasi')
                                    <div class="text-danger fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-7 fv-row">
                        <label class="required form-label">Perihal</label>
                        <input type="text" name="perihal" class="form-control mb-2" placeholder="Masukkan perihal surat" value="{{ old('perihal') }}" required />
                        @error('perihal')
                            <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-7">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control mb-2" rows="4" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-7">
                        <label class="form-label">File Surat</label>
                        <input type="file" name="file_surat" class="form-control mb-2" accept=".pdf,.jpg,.jpeg,.png" />
                        @error('file_surat')
                            <div class="text-danger fs-7">{{ $message }}</div>
                        @enderror
                        <div class="text-muted fs-7">
                            File surat, menerima file dengan ekstensi <code>.pdf</code>, <code>.jpg</code>, <code>.jpeg</code>, <code>.png</code>, dengan ukuran maksimal 16 MB
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('back.outgoing-mail.index') }}" class="btn btn-light me-5">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <span class="indicator-label">Simpan</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
