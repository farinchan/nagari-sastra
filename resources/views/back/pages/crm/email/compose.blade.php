@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">
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
                <form action="{{ route('back.crm.email.send') }}" method="POST" enctype="multipart/form-data" id="composeForm">
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

                    {{-- Template Selector --}}
                    @if(isset($templates) && $templates->count() > 0)
                    <div class="mb-4">
                        <label class="form-label">Gunakan Template</label>
                        <div class="d-flex gap-2">
                            <select id="templateSelect" class="form-select" style="max-width: 400px;">
                                <option value="">-- Tanpa Template --</option>
                                @foreach($templates as $tpl)
                                    <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="loadTemplateBtn" class="btn btn-light-primary btn-sm">Terapkan</button>
                        </div>
                        <div class="form-text">Pilih template lalu klik Terapkan untuk mengisi konten email.</div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label required">Isi Email</label>
                        <textarea name="body" id="composeBody" class="tinymce-editor">{{ $replyBody ?? '' }}</textarea>
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

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
@endsection

@section('scripts')
<script>
tinymce.init({
    selector: '.tinymce-editor',
    height: 350,
    menubar: false,
    plugins: 'advlist autolink lists link image charmap preview searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
    toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 14px; }',
    promotion: false,
    branding: false,
    setup: function(editor) {
        editor.on('change keyup', function() {
            editor.save();
        });
    }
});

@if(isset($templates) && $templates->count() > 0)
document.getElementById('loadTemplateBtn')?.addEventListener('click', function() {
    var tplId = document.getElementById('templateSelect').value;
    if (!tplId) return;

    fetch('{{ url("back/crm/email/templates") }}/' + tplId + '/get', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.template.body_html) {
            var editor = tinymce.get('composeBody');
            if (editor) {
                if (editor.getContent().trim() && !confirm('Konten saat ini akan diganti dengan template. Lanjutkan?')) return;
                editor.setContent(data.template.body_html);
            }
        }
    })
    .catch(err => alert('Gagal memuat template.'));
});
@endif
</script>
@endsection
