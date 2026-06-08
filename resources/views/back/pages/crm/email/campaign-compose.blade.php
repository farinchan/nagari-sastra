@extends('back.app')

@section('content')
    <div id="kt_content_container" class="container-xxl">
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
                        <div class="col-md-4 mb-5">
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
                        <div class="col-md-4 mb-5">
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
                        <div class="col-md-4 mb-5">
                            <label class="form-label">Gunakan Template</label>
                            <div class="d-flex gap-2">
                                <select id="campaignTemplateSelect" class="form-select form-select-solid">
                                    <option value="">-- Tanpa Template --</option>
                                    @if(isset($templates))
                                        @foreach($templates as $tpl)
                                            <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <button type="button" id="loadCampaignTemplateBtn" class="btn btn-light-primary btn-sm text-nowrap">Terapkan</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label required">Body Email</label>
                        <textarea name="body_html" id="campaignBody" class="tinymce-editor">{{ old('body_html') }}</textarea>
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

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
@endsection

@section('scripts')
<script>
tinymce.init({
    selector: '.tinymce-editor',
    height: 400,
    menubar: true,
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | code | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 14px; }',
    promotion: false,
    branding: false,
    setup: function(editor) {
        editor.on('change keyup', function() {
            editor.save();
        });
    }
});

document.getElementById('loadCampaignTemplateBtn')?.addEventListener('click', function() {
    var tplId = document.getElementById('campaignTemplateSelect').value;
    if (!tplId) return;

    fetch('{{ url("back/crm/email/templates") }}/' + tplId + '/get', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.template.body_html) {
            var editor = tinymce.get('campaignBody');
            if (editor) {
                if (editor.getContent().trim() && !confirm('Konten saat ini akan diganti dengan template. Lanjutkan?')) return;
                editor.setContent(data.template.body_html);
            }
        }
    })
    .catch(err => alert('Gagal memuat template.'));
});
</script>
@endsection
