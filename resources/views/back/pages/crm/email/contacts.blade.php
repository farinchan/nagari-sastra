@extends('back.app')

@section('content')
    <div id="kt_content_container" class="container-xxl">

        {{-- Back Button --}}
        <div class="mb-5">
            <a href="{{ route('back.crm.email.groups') }}" class="btn btn-sm btn-light-primary">
                <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i> Kembali ke Grup
            </a>
        </div>

        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-kt-ecommerce-product-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Kontak" />
                    </div>
                    <span class="bullet bullet-dot h-10px w-10px ms-5 me-2" style="background-color: {{ $group->color }}"></span>
                    <span class="fw-bold text-gray-800">{{ $group->name }}</span>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">Semua</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Berhenti">Berhenti</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="ki-duotone ki-file-up fs-2"><span class="path1"></span><span class="path2"></span></i> Import CSV
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Kontak
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
                            <th class="min-w-200px">Kontak</th>
                            <th class="min-w-100px">Telepon</th>
                            <th class="min-w-100px">Perusahaan</th>
                            <th class="text-end min-w-80px">Status</th>
                            <th class="text-end min-w-70px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($contacts as $contact)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $contact->name }}</span>
                                        <span class="text-muted fs-7">{{ $contact->email }}</span>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $contact->phone ?? '-' }}</td>
                                <td class="text-muted">{{ $contact->company ?? '-' }}</td>
                                <td class="text-end pe-0">
                                    @if($contact->is_subscribed)
                                        <div class="badge badge-light-success">Aktif</div>
                                    @else
                                        <div class="badge badge-light-danger">Berhenti</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-danger btn-delete-contact" data-id="{{ $contact->id }}" data-name="{{ $contact->name }}">
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

    {{-- Add Contact Modal --}}
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.contacts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email_group_id" value="{{ $group->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kontak</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama</label>
                            <input type="text" name="name" class="form-control form-control-solid" placeholder="Nama lengkap" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Email</label>
                            <input type="email" name="email" class="form-control form-control-solid" placeholder="email@contoh.com" required>
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control form-control-solid" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Perusahaan</label>
                            <input type="text" name="company" class="form-control form-control-solid" placeholder="Nama perusahaan">
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

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.contacts.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Kontak dari CSV</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <div class="modal-body">
                        <div class="notice d-flex bg-light-info rounded border-info border border-dashed mb-5 p-4">
                            <i class="ki-duotone ki-information-5 fs-2x text-info me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-7 text-gray-700">
                                        Format CSV: <strong>nama,email</strong> per baris.<br>
                                        Baris pertama akan dilewati jika berisi header (name/nama).
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">File CSV</label>
                            <input type="file" name="csv_file" class="form-control form-control-solid" accept=".csv,.txt" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteContactForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/email-contacts.js') }}"></script>
    <script>
        // Delete Contact
        document.querySelectorAll('.btn-delete-contact').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.dataset.id;
                var name = this.dataset.name;

                Swal.fire({
                    title: 'Hapus Kontak?',
                    text: 'Kontak "' + name + '" akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var form = document.getElementById('deleteContactForm');
                        form.action = '/back/crm/email/contacts/' + id + '/destroy';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
