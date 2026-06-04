@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">

        <div class="card card-flush">
            <div class="card-header align-items-center py-5">
                <div class="card-title">
                    <i class="ki-duotone ki-code fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    Kelola Widget Webchat
                </div>
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createWidgetModal">
                        <i class="ki-duotone ki-plus fs-4"></i> Tambah Widget
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">

                @if($widgets->isEmpty())
                    <div class="text-center text-muted py-15">
                        <i class="ki-duotone ki-code fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <div class="fs-6">Belum ada widget webchat.</div>
                        <div class="fs-7 text-gray-400 mt-1">Buat widget baru untuk mendapatkan embed code.</div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>Widget</th>
                                    <th>Warna</th>
                                    <th>Status</th>
                                    <th>Percakapan</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widgets as $widget)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-gray-800">{{ $widget->name }}</span>
                                                <span class="text-muted fs-8">{{ $widget->header_title }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:24px;height:24px;border-radius:6px;background:linear-gradient(135deg, {{ $widget->primary_color }} 0%, {{ $widget->secondary_color }} 100%);"></div>
                                                <span class="text-muted fs-8">{{ $widget->primary_color }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($widget->is_active)
                                                <span class="badge badge-light-success">Aktif</span>
                                            @else
                                                <span class="badge badge-light-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $widget->conversations_count }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-icon btn-light-info me-1" title="Embed Code"
                                                    onclick="showEmbed('{{ $widget->token }}')">
                                                <i class="ki-duotone ki-code fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            <button class="btn btn-sm btn-icon btn-light-primary me-1" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editWidgetModal{{ $widget->id }}">
                                                <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                            <form action="{{ route('back.crm.webchat.widgets.destroy', $widget->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus widget ini? Semua percakapan terkait juga akan dihapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus">
                                                    <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- CREATE WIDGET MODAL --}}
    <div class="modal fade" id="createWidgetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('back.crm.webchat.widgets.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Widget Webchat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama Widget</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="contoh: Website Utama" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">Judul Header</label>
                            <input type="text" name="header_title" class="form-control form-control-solid" value="Customer Support" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Sub-judul Header</label>
                            <input type="text" name="header_subtitle" class="form-control form-control-solid" value="Online — Siap membantu Anda">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Pesan Sambutan</label>
                            <textarea name="greeting_message" class="form-control form-control-solid" rows="2">Halo! 👋 Selamat datang. Ada yang bisa kami bantu?</textarea>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">Warna Utama</label>
                                <input type="color" name="primary_color" class="form-control form-control-solid form-control-color" value="#667eea" style="height: 45px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Warna Sekunder</label>
                                <input type="color" name="secondary_color" class="form-control form-control-solid form-control-color" value="#764ba2" style="height: 45px;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Domain yang Diizinkan</label>
                            <input type="text" name="allowed_domains" class="form-control form-control-solid" placeholder="contoh: example.com, sub.example.com (kosongkan = semua domain)">
                            <div class="form-text">Pisahkan dengan koma. Kosongkan untuk mengizinkan semua domain.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT WIDGET MODALS --}}
    @foreach($widgets as $widget)
    <div class="modal fade" id="editWidgetModal{{ $widget->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('back.crm.webchat.widgets.update', $widget->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Widget: {{ $widget->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label required">Nama Widget</label>
                            <input type="text" name="name" class="form-control form-control-solid" value="{{ $widget->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">Judul Header</label>
                            <input type="text" name="header_title" class="form-control form-control-solid" value="{{ $widget->header_title }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Sub-judul Header</label>
                            <input type="text" name="header_subtitle" class="form-control form-control-solid" value="{{ $widget->header_subtitle }}">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Pesan Sambutan</label>
                            <textarea name="greeting_message" class="form-control form-control-solid" rows="2">{{ $widget->greeting_message }}</textarea>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">Warna Utama</label>
                                <input type="color" name="primary_color" class="form-control form-control-solid form-control-color" value="{{ $widget->primary_color }}" style="height: 45px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Warna Sekunder</label>
                                <input type="color" name="secondary_color" class="form-control form-control-solid form-control-color" value="{{ $widget->secondary_color }}" style="height: 45px;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Domain yang Diizinkan</label>
                            <input type="text" name="allowed_domains" class="form-control form-control-solid" value="{{ $widget->allowed_domains }}" placeholder="kosongkan = semua domain">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select form-select-solid">
                                <option value="1" {{ $widget->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$widget->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    {{-- EMBED CODE MODAL --}}
    <div class="modal fade" id="embedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Embed Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted fs-7 mb-4">Salin kode di bawah ini dan tempelkan sebelum tag <code>&lt;/body&gt;</code> di website Anda.</p>
                    <div class="bg-light-dark rounded p-4">
                        <code id="embedCodeText" class="d-block text-break fs-7" style="white-space: pre-wrap; word-break: break-all;"></code>
                    </div>
                    <button type="button" class="btn btn-sm btn-light-primary mt-3 w-100" onclick="copyEmbed()">
                        <i class="ki-duotone ki-copy fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Salin Kode
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var embedBaseUrl = '{{ url("/api/webchat/embed") }}';

    function showEmbed(token) {
        var code = '<scr' + 'ipt src="' + embedBaseUrl + '/' + token + '.js">' + '</scr' + 'ipt>';
        document.getElementById('embedCodeText').textContent = code;
        var modal = new bootstrap.Modal(document.getElementById('embedModal'));
        modal.show();
    }

    function copyEmbed() {
        var text = document.getElementById('embedCodeText').textContent;
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Embed code berhasil disalin.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
</script>
@endsection
