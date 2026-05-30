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
                <span class="text-dark">Email Accounts</span>
            </span>
        </div>

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <i class="ki-duotone ki-sms fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    Email Accounts
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="ki-duotone ki-plus fs-4"></i> Tambah Akun
                    </button>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3" id="accountsTable">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-50px">#</th>
                                <th class="min-w-150px">Nama</th>
                                <th class="min-w-150px">Email</th>
                                <th class="min-w-120px">IMAP Host</th>
                                <th class="min-w-120px">SMTP Host</th>
                                <th class="min-w-80px">Status</th>
                                <th class="min-w-60px">Default</th>
                                <th class="min-w-200px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $key => $account)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="fw-semibold">{{ $account->name }}</td>
                                    <td>{{ $account->email }}</td>
                                    <td><code>{{ $account->imap_host }}:{{ $account->imap_port }}</code></td>
                                    <td><code>{{ $account->smtp_host }}:{{ $account->smtp_port }}</code></td>
                                    <td>
                                        @if($account->is_active)
                                            <span class="badge badge-light-success">Aktif</span>
                                        @else
                                            <span class="badge badge-light-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($account->is_default)
                                            <i class="ki-duotone ki-star fs-3 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                        @else
                                            <i class="ki-duotone ki-star fs-3 text-gray-300"><span class="path1"></span><span class="path2"></span></i>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light-info btn-test-connection" data-account-id="{{ $account->id }}">
                                            <i class="ki-duotone ki-wifi fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Test
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal{{ $account->id }}">
                                            <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-light-danger btn-delete" data-id="{{ $account->id }}" data-name="{{ $account->email }}">
                                            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                        </button>
                                    </td>
                                </tr>

                                {{-- Edit Modal per row --}}
                                <div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ route('back.crm.email.accounts.update', $account->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Akun Email</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="nav nav-tabs nav-line-tabs mb-5" role="tablist">
                                                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#editGeneral{{ $account->id }}">General</a></li>
                                                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#editImap{{ $account->id }}">IMAP Settings</a></li>
                                                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#editSmtp{{ $account->id }}">SMTP Settings</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div class="tab-pane fade show active" id="editGeneral{{ $account->id }}">
                                                            <div class="mb-3">
                                                                <label class="form-label required">Nama</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $account->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label required">Email</label>
                                                                <input type="email" name="email" class="form-control" value="{{ $account->email }}" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $account->is_active ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Aktif</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" {{ $account->is_default ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Default</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="editImap{{ $account->id }}">
                                                            <div class="mb-3">
                                                                <label class="form-label required">IMAP Host</label>
                                                                <input type="text" name="imap_host" class="form-control" value="{{ $account->imap_host }}" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label required">Port</label>
                                                                    <input type="text" name="imap_port" class="form-control" value="{{ $account->imap_port }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label required">Encryption</label>
                                                                    <select name="imap_encryption" class="form-select" required>
                                                                        <option value="ssl" {{ $account->imap_encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                                        <option value="tls" {{ $account->imap_encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                                                        <option value="none" {{ $account->imap_encryption == 'none' ? 'selected' : '' }}>None</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label required">Username</label>
                                                                <input type="text" name="imap_username" class="form-control" value="{{ $account->imap_username }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Password</label>
                                                                <input type="password" name="imap_password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="editSmtp{{ $account->id }}">
                                                            <div class="mb-3">
                                                                <label class="form-label required">SMTP Host</label>
                                                                <input type="text" name="smtp_host" class="form-control" value="{{ $account->smtp_host }}" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label required">Port</label>
                                                                    <input type="text" name="smtp_port" class="form-control" value="{{ $account->smtp_port }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label required">Encryption</label>
                                                                    <select name="smtp_encryption" class="form-select" required>
                                                                        <option value="ssl" {{ $account->smtp_encryption == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                                        <option value="tls" {{ $account->smtp_encryption == 'tls' ? 'selected' : '' }}>TLS</option>
                                                                        <option value="none" {{ $account->smtp_encryption == 'none' ? 'selected' : '' }}>None</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label required">Username</label>
                                                                <input type="text" name="smtp_username" class="form-control" value="{{ $account->smtp_username }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Password</label>
                                                                <input type="password" name="smtp_password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                                                            </div>
                                                        </div>
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
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="ki-duotone ki-sms fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span></i>
                                        <p class="mb-0">Belum ada akun email yang dikonfigurasi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Account Modal --}}
    <div class="modal fade" id="addAccountModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.accounts.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Akun Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Quick Presets --}}
                        <div class="mb-5">
                            <label class="form-label fw-bold">Preset Provider</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline btn-outline-danger btn-preset" data-imap-host="imap.gmail.com" data-imap-port="993" data-imap-enc="ssl" data-smtp-host="smtp.gmail.com" data-smtp-port="587" data-smtp-enc="tls">
                                    <i class="ki-duotone ki-sms fs-4"></i> Gmail
                                </button>
                                <button type="button" class="btn btn-sm btn-outline btn-outline-primary btn-preset" data-imap-host="outlook.office365.com" data-imap-port="993" data-imap-enc="ssl" data-smtp-host="smtp.office365.com" data-smtp-port="587" data-smtp-enc="tls">
                                    <i class="ki-duotone ki-sms fs-4"></i> Outlook
                                </button>
                                <button type="button" class="btn btn-sm btn-outline btn-outline-info btn-preset" data-imap-host="imap.mail.yahoo.com" data-imap-port="993" data-imap-enc="ssl" data-smtp-host="smtp.mail.yahoo.com" data-smtp-port="587" data-smtp-enc="tls">
                                    <i class="ki-duotone ki-sms fs-4"></i> Yahoo
                                </button>
                                <button type="button" class="btn btn-sm btn-outline btn-outline-secondary btn-preset" data-imap-host="" data-imap-port="993" data-imap-enc="ssl" data-smtp-host="" data-smtp-port="587" data-smtp-enc="tls">
                                    <i class="ki-duotone ki-setting-2 fs-4"></i> Custom
                                </button>
                            </div>
                        </div>

                        <ul class="nav nav-tabs nav-line-tabs mb-5" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#addGeneral">General</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#addImap">IMAP Settings</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#addSmtp">SMTP Settings</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="addGeneral">
                                <div class="mb-3">
                                    <label class="form-label required">Nama</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nama Pengirim" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_default" value="1">
                                            <label class="form-check-label">Default</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="addImap">
                                <div class="mb-3">
                                    <label class="form-label required">IMAP Host</label>
                                    <input type="text" name="imap_host" id="add_imap_host" class="form-control" placeholder="imap.gmail.com" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Port</label>
                                        <input type="text" name="imap_port" id="add_imap_port" class="form-control" value="993" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Encryption</label>
                                        <select name="imap_encryption" id="add_imap_encryption" class="form-select" required>
                                            <option value="ssl" selected>SSL</option>
                                            <option value="tls">TLS</option>
                                            <option value="none">None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Username</label>
                                    <input type="text" name="imap_username" class="form-control" placeholder="email@domain.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Password</label>
                                    <input type="password" name="imap_password" class="form-control" placeholder="Password atau App Password" required>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="addSmtp">
                                <div class="mb-3">
                                    <label class="form-label required">SMTP Host</label>
                                    <input type="text" name="smtp_host" id="add_smtp_host" class="form-control" placeholder="smtp.gmail.com" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Port</label>
                                        <input type="text" name="smtp_port" id="add_smtp_port" class="form-control" value="587" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Encryption</label>
                                        <select name="smtp_encryption" id="add_smtp_encryption" class="form-select" required>
                                            <option value="ssl">SSL</option>
                                            <option value="tls" selected>TLS</option>
                                            <option value="none">None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Username</label>
                                    <input type="text" name="smtp_username" class="form-control" placeholder="email@domain.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">Password</label>
                                    <input type="password" name="smtp_password" class="form-control" placeholder="Password atau App Password" required>
                                </div>
                            </div>
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
@endsection

@section('scripts')
    <script>
        // Preset buttons
        document.querySelectorAll('.btn-preset').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('add_imap_host').value = this.dataset.imapHost;
                document.getElementById('add_imap_port').value = this.dataset.imapPort;
                document.getElementById('add_imap_encryption').value = this.dataset.imapEnc;
                document.getElementById('add_smtp_host').value = this.dataset.smtpHost;
                document.getElementById('add_smtp_port').value = this.dataset.smtpPort;
                document.getElementById('add_smtp_encryption').value = this.dataset.smtpEnc;

                // Visual feedback
                document.querySelectorAll('.btn-preset').forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');
            });
        });

        // Test connection
        document.querySelectorAll('.btn-test-connection').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var accountId = this.dataset.accountId;
                var button = this;
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Testing...';

                fetch('{{ route("back.crm.email.accounts.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ account_id: accountId })
                })
                .then(response => response.json())
                .then(data => {
                    var imapStatus = data.imap ? '<span class="text-success">✓ IMAP OK</span>' : '<span class="text-danger">✗ IMAP Gagal: ' + (data.imap_error || '') + '</span>';
                    var smtpStatus = data.smtp ? '<span class="text-success">✓ SMTP OK</span>' : '<span class="text-danger">✗ SMTP Gagal: ' + (data.smtp_error || '') + '</span>';

                    Swal.fire({
                        title: 'Hasil Test Koneksi',
                        html: imapStatus + '<br>' + smtpStatus,
                        icon: data.imap && data.smtp ? 'success' : 'warning',
                    });
                })
                .catch(error => {
                    Swal.fire('Error', 'Gagal menjalankan test koneksi.', 'error');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = '<i class="ki-duotone ki-wifi fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Test';
                });
            });
        });

        // Delete account
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                var name = this.dataset.name;

                Swal.fire({
                    title: 'Hapus Akun?',
                    text: 'Yakin ingin menghapus akun ' + name + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("back.crm.email.accounts.destroy", ":id") }}'.replace(':id', id);
                        form.innerHTML = '@csrf @method("DELETE")';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
