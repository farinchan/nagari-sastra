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
                        <input type="text" data-kt-ecommerce-category-filter="search"
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari FAQ" />
                    </div>
                </div>
                <div class="card-toolbar">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_faq" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Tambah FAQ
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_category_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-250px">Pertanyaan</th>
                            <th class="min-w-250px">Jawaban</th>
                            <th class="min-w-80px">Status</th>
                            <th class="min-w-70px">Urutan</th>
                            <th class="text-end min-w-70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($faqs as $faq)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-gray-800">{{ Str::limit($faq->question, 80) }}</div>
                                </td>
                                <td>
                                    <div class="text-gray-600">{{ Str::limit(strip_tags($faq->answer), 80) }}</div>
                                </td>
                                <td>
                                    @if ($faq->is_active)
                                        <span class="badge badge-light-success">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $faq->order }}</td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                        data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#edit_faq{{ $faq->id }}">Edit</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                data-bs-target="#delete_faq{{ $faq->id }}">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="add_faq" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah FAQ</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <form action="{{ route('back.faq.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Pertanyaan</label>
                            <input type="text" name="question" class="form-control" placeholder="Masukkan pertanyaan" required />
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Jawaban</label>
                            <textarea name="answer" class="form-control" rows="5" placeholder="Masukkan jawaban" required></textarea>
                        </div>
                        <div class="row mb-5">
                            <div class="col-6">
                                <label class="form-label">Urutan</label>
                                <input type="number" name="order" class="form-control" value="0" />
                            </div>
                            <div class="col-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active_add" checked />
                                    <label class="form-check-label" for="is_active_add">Aktif</label>
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

    <!-- EDIT & DELETE MODALS -->
    @foreach ($faqs as $faq)
        <!-- Edit Modal -->
        <div class="modal fade" id="edit_faq{{ $faq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit FAQ</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <form action="{{ route('back.faq.update', $faq->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-5">
                                <label class="form-label required">Pertanyaan</label>
                                <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required />
                            </div>
                            <div class="mb-5">
                                <label class="form-label required">Jawaban</label>
                                <textarea name="answer" class="form-control" rows="5" required>{{ $faq->answer }}</textarea>
                            </div>
                            <div class="row mb-5">
                                <div class="col-6">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" name="order" class="form-control" value="{{ $faq->order }}" />
                                </div>
                                <div class="col-6 d-flex align-items-end">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            id="is_active_edit{{ $faq->id }}" {{ $faq->is_active ? 'checked' : '' }} />
                                        <label class="form-check-label" for="is_active_edit{{ $faq->id }}">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="delete_faq{{ $faq->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus FAQ</h5>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus FAQ:</p>
                        <p class="fw-bold">"{{ Str::limit($faq->question, 100) }}"</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('back.faq.destroy', $faq->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
