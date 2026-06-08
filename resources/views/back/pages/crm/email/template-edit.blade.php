@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="d-flex align-items-center mb-5">
            <span class="text-muted fw-semibold fs-7">
                <a href="{{ route('back.dashboard') }}" class="text-muted">Dashboard</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                CRM
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <a href="{{ route('back.crm.email.templates') }}" class="text-muted">Templates</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <span class="text-dark">Edit: {{ $template->name }}</span>
            </span>
        </div>

        <form action="{{ route('back.crm.email.templates.update', $template->id) }}" method="POST" id="templateForm">
            @csrf
            @method('PUT')
            <div class="row g-5">
                {{-- Left: Editor --}}
                <div class="col-lg-7">
                    <div class="card card-flush mb-5">
                        <div class="card-header">
                            <div class="card-title">Detail Template</div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="mb-4">
                                <label class="form-label required">Nama Template</label>
                                <input type="text" name="name" class="form-control form-control-solid" value="{{ $template->name }}" required>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActiveEdit" value="1" @if($template->is_active) checked @endif>
                                <label class="form-check-label" for="isActiveEdit">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="card card-flush mb-5">
                        <div class="card-header">
                            <div class="card-title">Konten Template</div>
                        </div>
                        <div class="card-body pt-2">
                            <textarea name="body_html" id="bodyEditor" class="tinymce-editor">{!! $template->body_html !!}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-4"><span class="path1"></span><span class="path2"></span></i> Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-light-info" id="refreshPreviewBtn">
                            <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Refresh Preview
                        </button>
                        <a href="{{ route('back.crm.email.templates') }}" class="btn btn-light">Kembali</a>
                    </div>
                </div>

                {{-- Right: Live Preview --}}
                <div class="col-lg-5">
                    <div class="card card-flush" style="position: sticky; top: 80px;">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="ki-duotone ki-eye fs-4 me-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                Live Preview
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <iframe id="previewFrame" style="width: 100%; min-height: 600px; border: none; background: #fff;"></iframe>
                        </div>
                    </div>
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
    height: 500,
    menubar: true,
    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
    toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
    promotion: false,
    branding: false,
    setup: function(editor) {
        editor.on('change keyup', function() {
            editor.save();
        });
    }
});

function updatePreview() {
    tinymce.triggerSave();
    var body = document.getElementById('bodyEditor').value || '';
    var fullHtml = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>body{margin:0;padding:0;font-family:Arial,sans-serif;}</style></head><body>' + body + '</body></html>';

    var frame = document.getElementById('previewFrame');
    var doc = frame.contentDocument || frame.contentWindow.document;
    doc.open();
    doc.write(fullHtml);
    doc.close();
}

setTimeout(updatePreview, 1000);

document.getElementById('refreshPreviewBtn').addEventListener('click', updatePreview);
</script>
@endsection
