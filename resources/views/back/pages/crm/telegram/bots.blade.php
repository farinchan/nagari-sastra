@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-ecommerce-product-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Bot" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">Semua</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBotModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Bot
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_ecommerce_products_table .form-check-input"
                                        value="1" />
                                </div>
                            </th>
                            <th class="min-w-200px">Bot</th>
                            <th class="min-w-150px">Token</th>
                            <th class="text-end min-w-80px">Webhook</th>
                            <th class="text-end min-w-80px">Status</th>
                            <th class="text-end min-w-70px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($bots as $bot)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $bot->name }}</span>
                                        @if($bot->username)
                                            <span class="text-primary fs-7">@{{ $bot->username }}</span>
                                        @else
                                            <span class="text-muted fs-7">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $token = $bot->token;
                                        $masked = strlen($token) > 10
                                            ? substr($token, 0, 5) . '****' . substr($token, -5)
                                            : '****';
                                    @endphp
                                    <code class="fs-7">{{ $masked }}</code>
                                </td>
                                <td class="text-end pe-0">
                                    <span class="badge badge-light-{{ $bot->webhook_active ? 'success' : 'danger' }}" id="webhookBadge{{ $bot->id }}">
                                        {{ $bot->webhook_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-end pe-0">
                                    @if($bot->is_active)
                                        <div class="badge badge-light-success">Aktif</div>
                                    @else
                                        <div class="badge badge-light-danger">Nonaktif</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-175px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3" id="webhookBtnGroup{{ $bot->id }}">
                                            @if($bot->webhook_active)
                                                <a href="#" class="menu-link px-3 btn-webhook" data-bot-id="{{ $bot->id }}" data-action="unset">
                                                    <i class="ki-duotone ki-disconnect fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Unset Webhook
                                                </a>
                                            @else
                                                <a href="#" class="menu-link px-3 btn-webhook" data-bot-id="{{ $bot->id }}" data-action="set">
                                                    <i class="ki-duotone ki-wifi fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Set Webhook
                                                </a>
                                            @endif
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#editBotModal{{ $bot->id }}">
                                                <i class="ki-duotone ki-pencil fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Edit
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-delete" data-id="{{ $bot->id }}" data-name="{{ $bot->name }}">
                                                <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                            </a>
                                        </div>
                                    </div>
                                    <form id="deleteForm{{ $bot->id }}" action="{{ route('back.crm.telegram.bots.destroy', $bot->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
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

    {{-- Edit Modals --}}
    @foreach($bots as $bot)
        <div class="modal fade" id="editBotModal{{ $bot->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('back.crm.telegram.bots.update', $bot->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Bot: {{ $bot->name }}</h5>
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
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
    @endforeach
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/telegram-bots.js') }}"></script>
    <script>
        // Webhook Set/Unset AJAX
        $(document).on('click', '.btn-webhook', function(e) {
            e.preventDefault();
            var btn = $(this);
            var botId = btn.data('bot-id');
            var action = btn.data('action');
            var url = action === 'set'
                ? '{{ route("back.crm.telegram.bots.set-webhook") }}'
                : '{{ route("back.crm.telegram.bots.unset-webhook") }}';

            btn.html('<span class="spinner-border spinner-border-sm align-middle"></span>');
            btn.addClass('disabled');

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
                                '<a href="#" class="menu-link px-3 btn-webhook" data-bot-id="' + botId + '" data-action="unset">' +
                                '<i class="ki-duotone ki-disconnect fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Unset Webhook</a>'
                            );
                        } else {
                            $('#webhookBadge' + botId).removeClass('badge-light-success').addClass('badge-light-danger').text('Nonaktif');
                            $('#webhookBtnGroup' + botId).html(
                                '<a href="#" class="menu-link px-3 btn-webhook" data-bot-id="' + botId + '" data-action="set">' +
                                '<i class="ki-duotone ki-wifi fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Set Webhook</a>'
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
                    // Button already replaced via html(), no need to restore
                }
            });
        });

        // Delete with SweetAlert
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
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
