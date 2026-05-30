@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <i class="ki-duotone ki-message-text-2 fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Telegram Bots
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBotModal">
                        <i class="ki-duotone ki-plus fs-4"></i> Tambah Bot
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-50px">#</th>
                                <th class="min-w-150px">Nama Bot</th>
                                <th class="min-w-120px">Username</th>
                                <th class="min-w-200px">Token</th>
                                <th class="min-w-100px">Webhook</th>
                                <th class="min-w-80px">Status</th>
                                <th class="min-w-250px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bots as $key => $bot)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="fw-semibold">{{ $bot->name }}</td>
                                    <td>
                                        @if($bot->username)
                                            <span class="text-primary">@{{ $bot->username }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $token = $bot->token;
                                            $masked = strlen($token) > 10
                                                ? substr($token, 0, 5) . '****' . substr($token, -5)
                                                : '****';
                                        @endphp
                                        <code>{{ $masked }}</code>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-{{ $bot->webhook_active ? 'success' : 'danger' }}" id="webhookBadge{{ $bot->id }}">
                                            {{ $bot->webhook_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bot->is_active)
                                            <span class="badge badge-light-success">Aktif</span>
                                        @else
                                            <span class="badge badge-light-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div id="webhookBtnGroup{{ $bot->id }}">
                                            @if($bot->webhook_active)
                                                <button class="btn btn-sm btn-light-warning btn-webhook" data-bot-id="{{ $bot->id }}" data-action="unset">
                                                    <span class="indicator-label"><i class="ki-duotone ki-disconnect fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Unset</span>
                                                    <span class="indicator-progress d-none"><span class="spinner-border spinner-border-sm align-middle"></span></span>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-light-success btn-webhook" data-bot-id="{{ $bot->id }}" data-action="set">
                                                    <span class="indicator-label"><i class="ki-duotone ki-wifi fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Set Webhook</span>
                                                    <span class="indicator-progress d-none"><span class="spinner-border spinner-border-sm align-middle"></span></span>
                                                </button>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#editBotModal{{ $bot->id }}">
                                            <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-light-danger btn-delete" data-id="{{ $bot->id }}" data-name="{{ $bot->name }}">
                                            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                        </button>
                                        <form id="deleteForm{{ $bot->id }}" action="{{ route('back.crm.telegram.bots.destroy', $bot->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                                {{-- Edit Modal per row --}}
                                <div class="modal fade" id="editBotModal{{ $bot->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('back.crm.telegram.bots.update', $bot->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Bot: {{ $bot->name }}</h5>
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-5">
                                                        <label class="form-label required">Nama Bot</label>
                                                        <input type="text" name="name" class="form-control form-control-solid" value="{{ $bot->name }}" required>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="form-label">Token</label>
                                                        <input type="text" name="token" class="form-control form-control-solid" placeholder="Kosongkan jika tidak diubah">
                                                        <div class="form-text">Kosongkan untuk mempertahankan token saat ini.</div>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="form-label">Pesan Selamat Datang</label>
                                                        <textarea name="welcome_message" class="form-control form-control-solid" rows="3" placeholder="Pesan otomatis saat /start">{{ $bot->welcome_message }}</textarea>
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
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-10">
                                        <i class="ki-duotone ki-message-text-2 fs-2x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <div class="fs-6">Belum ada bot. Tambahkan bot pertama Anda.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Bot Modal --}}
    <div class="modal fade" id="addBotModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('back.crm.telegram.bots.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Bot Telegram</h5>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Bot</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Contoh: CS Bot" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Token Bot</label>
                            <input type="text" name="token" class="form-control form-control-solid" placeholder="Masukkan token dari @BotFather" required>
                            <div class="form-text">Dapatkan token dari <a href="https://t.me/BotFather" target="_blank">@BotFather</a> di Telegram.</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Pesan Selamat Datang</label>
                            <textarea name="welcome_message" class="form-control form-control-solid" rows="3" placeholder="Pesan otomatis yang dikirim saat pengguna mengirim /start"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah Bot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Webhook Set/Unset AJAX
    $(document).on('click', '.btn-webhook', function() {
        var btn = $(this);
        var botId = btn.data('bot-id');
        var action = btn.data('action');
        var url = action === 'set'
            ? '{{ route("back.crm.telegram.bots.set-webhook") }}'
            : '{{ route("back.crm.telegram.bots.unset-webhook") }}';

        btn.find('.indicator-label').addClass('d-none');
        btn.find('.indicator-progress').removeClass('d-none');
        btn.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                bot_id: botId,
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire('Berhasil', res.message, 'success');
                    if (action === 'set') {
                        $('#webhookBadge' + botId).removeClass('badge-light-danger').addClass('badge-light-success').text('Aktif');
                        $('#webhookBtnGroup' + botId).html(
                            '<button class="btn btn-sm btn-light-warning btn-webhook" data-bot-id="' + botId + '" data-action="unset">' +
                            '<span class="indicator-label"><i class="ki-duotone ki-disconnect fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Unset</span>' +
                            '<span class="indicator-progress d-none"><span class="spinner-border spinner-border-sm align-middle"></span></span>' +
                            '</button>'
                        );
                    } else {
                        $('#webhookBadge' + botId).removeClass('badge-light-success').addClass('badge-light-danger').text('Nonaktif');
                        $('#webhookBtnGroup' + botId).html(
                            '<button class="btn btn-sm btn-light-success btn-webhook" data-bot-id="' + botId + '" data-action="set">' +
                            '<span class="indicator-label"><i class="ki-duotone ki-wifi fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Set Webhook</span>' +
                            '<span class="indicator-progress d-none"><span class="spinner-border spinner-border-sm align-middle"></span></span>' +
                            '</button>'
                        );
                    }
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            },
            complete: function() {
                btn.find('.indicator-label').removeClass('d-none');
                btn.find('.indicator-progress').addClass('d-none');
                btn.prop('disabled', false);
            }
        });
    });

    // Delete with SweetAlert
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Hapus Bot?',
            text: 'Bot "' + name + '" akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteForm' + id).submit();
            }
        });
    });
</script>
@endsection
