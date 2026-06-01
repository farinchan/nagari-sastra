@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <i class="ki-duotone ki-whatsapp fs-2 me-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                    WhatsApp Accounts
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="ki-duotone ki-plus fs-4"></i> Tambah Akun
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-50px">#</th>
                                <th class="min-w-150px">Nama</th>
                                <th class="min-w-120px">Nomor</th>
                                <th class="min-w-150px">Phone Number ID</th>
                                <th class="min-w-100px">Webhook</th>
                                <th class="min-w-80px">Status</th>
                                <th class="min-w-200px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $key => $account)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="fw-semibold">{{ $account->name }}</td>
                                    <td>
                                        <span class="text-success">{{ $account->phone_number }}</span>
                                    </td>
                                    <td><code>{{ $account->phone_number_id }}</code></td>
                                    <td>
                                        @if($account->webhook_active)
                                            <span class="badge badge-light-success">Aktif</span>
                                        @else
                                            <span class="badge badge-light-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($account->is_active)
                                            <span class="badge badge-light-success">Aktif</span>
                                        @else
                                            <span class="badge badge-light-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal{{ $account->id }}">
                                            <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-light-info btn-copy-webhook" data-url="{{ route('api.whatsapp.webhook', $account->id) }}" data-verify="{{ $account->verify_token }}">
                                            <i class="ki-duotone ki-copy fs-4"><span class="path1"></span><span class="path2"></span></i> Webhook URL
                                        </button>
                                        <button class="btn btn-sm btn-light-danger btn-delete" data-id="{{ $account->id }}" data-name="{{ $account->name }}">
                                            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                        </button>
                                        <form id="deleteForm{{ $account->id }}" action="{{ route('back.crm.whatsapp.accounts.destroy', $account->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('back.crm.whatsapp.accounts.update', $account->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Akun: {{ $account->name }}</h5>
                                                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                    </div>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-5">
                                                        <label class="form-label required">Nama Akun</label>
                                                        <input type="text" name="name" class="form-control form-control-solid" value="{{ $account->name }}" required>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="form-label required">Nomor Telepon</label>
                                                        <input type="text" name="phone_number" class="form-control form-control-solid" value="{{ $account->phone_number }}" required>
                                                        <div class="form-text">Format: 628xxxxxxxxxx</div>
                                                    </div>
                                                    <div class="row mb-5">
                                                        <div class="col-md-6">
                                                            <label class="form-label required">Phone Number ID</label>
                                                            <input type="text" name="phone_number_id" class="form-control form-control-solid" value="{{ $account->phone_number_id }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">WABA ID</label>
                                                            <input type="text" name="waba_id" class="form-control form-control-solid" value="{{ $account->waba_id }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="form-label">Access Token</label>
                                                        <input type="text" name="access_token" class="form-control form-control-solid" placeholder="Kosongkan jika tidak diubah">
                                                        <div class="form-text">Kosongkan untuk mempertahankan token saat ini.</div>
                                                    </div>
                                                    <div class="mb-5">
                                                        <label class="form-label">Verify Token</label>
                                                        <input type="text" name="verify_token" class="form-control form-control-solid" placeholder="Kosongkan jika tidak diubah">
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
                                        <i class="ki-duotone ki-whatsapp fs-2x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span></i>
                                        <div class="fs-6">Belum ada akun WhatsApp. Tambahkan akun pertama Anda.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Setup Guide --}}
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mt-6">
                    <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Cara Setup WhatsApp Business API</h4>
                            <div class="fs-6 text-gray-700">
                                1. Buat aplikasi di <a href="https://developers.facebook.com" target="_blank">Meta for Developers</a><br>
                                2. Aktifkan WhatsApp product dan dapatkan <strong>Phone Number ID</strong> & <strong>Access Token</strong><br>
                                3. Masukkan data di form tambah akun di atas<br>
                                4. Copy <strong>Webhook URL</strong> dan <strong>Verify Token</strong> ke pengaturan webhook Meta<br>
                                5. Subscribe ke event: <code>messages</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Account Modal --}}
    <div class="modal fade" id="addAccountModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('back.crm.whatsapp.accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Akun WhatsApp Business</h5>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Akun</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Contoh: CS Nagari Sastra" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Nomor Telepon</label>
                            <input type="text" name="phone_number" class="form-control form-control-solid" placeholder="628xxxxxxxxxx" required>
                            <div class="form-text">Nomor WhatsApp Business yang terdaftar di Meta.</div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label class="form-label required">Phone Number ID</label>
                                <input type="text" name="phone_number_id" class="form-control form-control-solid" placeholder="Dari Meta Dashboard" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WABA ID</label>
                                <input type="text" name="waba_id" class="form-control form-control-solid" placeholder="WhatsApp Business Account ID">
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Access Token</label>
                            <input type="text" name="access_token" class="form-control form-control-solid" placeholder="Permanent Access Token dari Meta" required>
                            <div class="form-text">Gunakan System User Token untuk akses permanent.</div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Verify Token</label>
                            <input type="text" name="verify_token" class="form-control form-control-solid" placeholder="Token bebas untuk verifikasi webhook" required>
                            <div class="form-text">Buat token acak untuk verifikasi webhook Meta. Contoh: <code>{{ \Illuminate\Support\Str::random(32) }}</code></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Copy Webhook URL
    $(document).on('click', '.btn-copy-webhook', function() {
        var url = $(this).data('url');
        var verify = $(this).data('verify');
        var text = "Webhook URL: " + url + "\nVerify Token: " + verify;

        Swal.fire({
            title: 'Webhook Configuration',
            html: '<div class="text-start">' +
                '<label class="form-label fw-bold">Callback URL</label>' +
                '<input type="text" class="form-control form-control-solid mb-4" value="' + url + '" readonly onclick="this.select()">' +
                '<label class="form-label fw-bold">Verify Token</label>' +
                '<input type="text" class="form-control form-control-solid" value="' + verify + '" readonly onclick="this.select()">' +
                '</div>',
            confirmButtonText: 'Tutup',
            width: '550px',
        });
    });

    // Delete with SweetAlert
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Hapus Akun?',
            text: 'Akun "' + name + '" dan semua percakapan akan dihapus!',
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
