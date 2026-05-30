@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">
        {{-- Breadcrumb --}}
        <div class="d-flex align-items-center mb-5">
            <span class="text-muted fw-semibold fs-7">
                <a href="{{ route('back.dashboard') }}" class="text-muted">Dashboard</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                CRM
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <a href="{{ route('back.crm.email.inbox') }}" class="text-muted">Inbox</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <span class="text-dark">Tulis Email</span>
            </span>
        </div>

        <div class="card card-flush">
            <div class="card-header">
                <div class="card-title">
                    <i class="ki-duotone ki-pencil fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    Tulis Email
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('back.crm.email.send') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label required">Dari</label>
                        <select name="account_id" class="form-select" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ (isset($selectedAccountId) && $selectedAccountId == $acc->id) ? 'selected' : '' }}>
                                    {{ $acc->name }} &lt;{{ $acc->email }}&gt;
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label required">Kepada</label>
                        <input type="email" name="to" class="form-control" value="{{ $to ?? '' }}" placeholder="email@domain.com" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">CC</label>
                            <input type="text" name="cc" class="form-control" placeholder="email1@domain.com, email2@domain.com">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">BCC</label>
                            <input type="text" name="bcc" class="form-control" placeholder="email1@domain.com, email2@domain.com">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label required">Subjek</label>
                        <input type="text" name="subject" class="form-control" value="{{ $subject ?? '' }}" placeholder="Subjek email" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label required">Isi Email</label>
                        <textarea name="body" class="form-control" rows="12" placeholder="Tulis isi email..." required>{{ $replyBody ?? '' }}</textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Lampiran</label>
                        <input type="file" name="attachments[]" class="form-control" multiple>
                        <div class="form-text">Maksimal 10MB per file. Bisa pilih beberapa file.</div>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-send fs-4"><span class="path1"></span><span class="path2"></span></i> Kirim
                        </button>
                        <a href="{{ route('back.crm.email.inbox') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
