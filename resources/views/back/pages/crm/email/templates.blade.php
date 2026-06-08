@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="d-flex align-items-center mb-5">
            <span class="text-muted fw-semibold fs-7">
                <a href="{{ route('back.dashboard') }}" class="text-muted">Dashboard</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                CRM
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <span class="text-dark">Email Templates</span>
            </span>
        </div>

        <div class="card card-flush">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <i class="ki-duotone ki-abstract-26 fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    Email Templates
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="ki-duotone ki-plus fs-4"></i> Tambah Template
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                @if($templates->isEmpty())
                    <div class="text-center py-15">
                        <i class="ki-duotone ki-abstract-26 fs-4x text-gray-300 mb-5"><span class="path1"></span><span class="path2"></span></i>
                        <div class="fs-5 fw-semibold text-gray-400">Belum ada template email</div>
                        <div class="text-muted fs-7 mt-1">Buat template pertama untuk digunakan di compose dan campaign</div>
                    </div>
                @else
                    <div class="row g-5">
                        @foreach($templates as $template)
                            <div class="col-md-6 col-lg-4">
                                <div class="card card-bordered h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="fw-bold text-gray-800 mb-1">{{ $template->name }}</h5>
                                                @if(!$template->is_active)
                                                    <span class="badge badge-light-danger fs-9">Nonaktif</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="border rounded p-3 mb-3 flex-grow-1" style="background: #fafafa; max-height: 120px; overflow: hidden; font-size: 11px; color: #999;">
                                            @if($template->body_html)
                                                {!! Str::limit(strip_tags($template->body_html), 200) !!}
                                            @else
                                                <em>Template kosong</em>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('back.crm.email.templates.preview', $template->id) }}" target="_blank" class="btn btn-sm btn-light flex-grow-1">
                                                <i class="ki-duotone ki-eye fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Preview
                                            </a>
                                            <a href="{{ route('back.crm.email.templates.edit', $template->id) }}" class="btn btn-sm btn-light-primary flex-grow-1">
                                                <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i> Edit
                                            </a>
                                            <form action="{{ route('back.crm.email.templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger">
                                                    <i class="ki-duotone ki-trash fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-footer py-2 text-muted fs-9">
                                        Dibuat {{ $template->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Create Template Modal --}}
    <div class="modal fade" id="createTemplateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.templates.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Template Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama Template</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Contoh: Newsletter Modern" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Konten HTML Template</label>
                            <textarea name="body_html" class="form-control form-control-solid" rows="10" placeholder="Tulis HTML template email lengkap di sini..."></textarea>
                            <div class="form-text">HTML lengkap untuk template email. Bisa diedit lebih lanjut setelah disimpan.</div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" checked>
                            <label class="form-check-label" for="isActiveCheck">Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
