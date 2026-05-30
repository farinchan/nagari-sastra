@extends('back.app')

@section('content')
    <div id="kt_content_container" class=" container-xxl ">

            <!--begin::Back Button-->
            <div class="mb-5">
                <a href="{{ route('back.crm.email.groups') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i> Kembali ke Grup
                </a>
            </div>

            <div class="card card-flush">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">
                            <span class="bullet bullet-dot h-10px w-10px me-2" style="background-color: {{ $group->color }}"></span>
                            {{ $group->name }}
                        </span>
                        <span class="text-muted mt-1 fw-semibold fs-7">{{ $group->description ?? 'Daftar kontak dalam grup ini' }}</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="ki-duotone ki-file-up fs-4"><span class="path1"></span><span class="path2"></span></i> Import CSV
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addContactModal">
                            <i class="ki-duotone ki-plus fs-4"></i> Tambah Kontak
                        </button>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Perusahaan</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                <tr>
                                    <td class="fw-bold text-gray-800">{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td class="text-muted">{{ $contact->phone ?? '-' }}</td>
                                    <td class="text-muted">{{ $contact->company ?? '-' }}</td>
                                    <td>
                                        @if($contact->is_subscribed)
                                            <span class="badge badge-light-success">Aktif</span>
                                        @else
                                            <span class="badge badge-light-danger">Berhenti</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-light-danger btn-delete-contact" data-id="{{ $contact->id }}" data-name="{{ $contact->name }}">
                                            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-10">
                                        <i class="ki-duotone ki-address-book fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <p class="mb-0">Belum ada kontak dalam grup ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!--begin::Pagination-->
                    <div class="d-flex justify-content-center mt-5">
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>


    <!--begin::Add Contact Modal-->
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.contacts.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email_group_id" value="{{ $group->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kontak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

    <!--begin::Import Modal-->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('back.crm.email.contacts.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Kontak dari CSV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

    <!--begin::Delete Form-->
    <form id="deleteContactForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
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
