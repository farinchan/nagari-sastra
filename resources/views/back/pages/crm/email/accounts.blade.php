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
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Akun Email" />
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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Akun
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
                            <th class="min-w-200px">Akun</th>
                            <th class="min-w-120px">IMAP</th>
                            <th class="min-w-120px">SMTP</th>
                            <th class="text-end min-w-80px">Status</th>
                            <th class="text-end min-w-60px">Default</th>
                            <th class="text-end min-w-150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($accounts as $account)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $account->name }}</span>
                                        <span class="text-muted fs-7">{{ $account->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <code class="fs-7">{{ $account->imap_host }}:{{ $account->imap_port }}</code>
                                </td>
                                <td>
                                    <code class="fs-7">{{ $account->smtp_host }}:{{ $account->smtp_port }}</code>
                                </td>
                                <td class="text-end pe-0">
                                    @if($account->is_active)
                                        <div class="badge badge-light-success">Aktif</div>
                                    @else
                                        <div class="badge badge-light-danger">Nonaktif</div>
                                    @endif
                                </td>
                                <td class="text-end pe-0">
                                    @if($account->is_default)
                                        <i class="ki-duotone ki-star fs-3 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                    @else
                                        <i class="ki-duotone ki-star fs-3 text-gray-300"><span class="path1"></span><span class="path2"></span></i>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light-info me-1 btn-test-connection" data-account-id="{{ $account->id }}">
                                        <i class="ki-duotone ki-wifi fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i> Test
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light-primary me-1" data-bs-toggle="modal" data-bs-target="#editAccountModal{{ $account->id }}">
                                        <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                    <button class="btn btn-sm btn-light-danger btn-delete" data-id="{{ $account->id }}" data-name="{{ $account->email }}">
                                        <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
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

    {{-- Edit Modals --}}
    @foreach($accounts as $account)
        <div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="{{ route('back.crm.email.accounts.update', $account->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Akun Email</h5>
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
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
    @endforeach
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/email-accounts.js') }}"></script>
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
