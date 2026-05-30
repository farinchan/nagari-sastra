@extends('back.app')

@section('content')
    <div id="kt_content_container" class=" container-xxl ">
        <form action="{{ route('back.crm.email.campaigns.store') }}" method="POST">
            @csrf
            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Buat Email Campaign</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Isi detail campaign dan simpan sebagai draft</span>
                    </h3>
                </div>
                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label required">Nama Campaign</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Contoh: Newsletter Mei 2026" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label required">Subject Email</label>
                            <input type="text" name="subject" class="form-control form-control-solid" placeholder="Subject yang menarik..." value="{{ old('subject') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="form-label required">Akun Pengirim</label>
                            <select name="email_account_id" class="form-select form-select-solid" required>
                                <option value="">-- Pilih Akun Email --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" @if(old('email_account_id') == $account->id) selected @endif>
                                        {{ $account->name }} ({{ $account->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="form-label required">Grup Target</label>
                            <select name="email_group_id" class="form-select form-select-solid" required>
                                <option value="">-- Pilih Grup Kontak --</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" @if(old('email_group_id') == $group->id) selected @endif>
                                        {{ $group->name }} ({{ $group->contacts_count }} kontak)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Body Email</label>
                        <textarea name="body_html" class="form-control form-control-solid" rows="15" placeholder="Tulis konten email Anda di sini... (Mendukung HTML)" required>{{ old('body_html') }}</textarea>
                        <div class="form-text text-muted">Mendukung HTML. Anda dapat menggunakan tag HTML untuk memformat email.</div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('back.crm.email.campaigns') }}" class="btn btn-light me-3">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-file fs-4"><span class="path1"></span><span class="path2"></span></i> Simpan sebagai Draft
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
