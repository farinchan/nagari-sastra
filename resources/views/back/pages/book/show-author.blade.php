@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        @include('back.pages.book.show-header')

        <div class="card mb-5 mb-lg-10">
            <div class="card-header">
                <div class="card-title"><h3>Daftar Penulis</h3></div>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_author">
                        <i class="ki-duotone ki-plus fs-2"></i> Tambah Penulis
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                        <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                            <tr>
                                <th>No</th>
                                <th>Nama Tanpa Gelar</th>
                                <th>Nama Dengan Gelar</th>
                                <th>Email</th>
                                <th>Afiliasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-6 fw-semibold text-gray-600">
                            @forelse ($book->bookAuthors as $index => $author)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold text-gray-800">{{ $author->name }}</td>
                                    <td>{{ $author->name_with_title ?? '-' }}</td>
                                    <td>{{ $author->email ?? '-' }}</td>
                                    <td>{{ $author->affiliation ?? '-' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('back.book.author.certificate', [$book->id, $author->id]) }}"
                                            class="btn btn-sm btn-light-success me-1" data-bs-toggle="tooltip" title="Download Sertifikat">
                                            <i class="ki-duotone ki-medal-star fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-light-primary me-1" data-bs-toggle="modal"
                                            data-bs-target="#modal_edit_author_{{ $author->id }}">
                                            <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-light-danger" data-bs-toggle="modal"
                                            data-bs-target="#modal_delete_author_{{ $author->id }}">
                                            <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada penulis</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add Author --}}
    <div class="modal fade" id="modal_add_author" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Penulis</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                </div>
                <form action="{{ route('back.book.author.store', $book->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Tanpa Gelar</label>
                            <input type="text" name="name" class="form-control" required placeholder="cth: John Doe" />
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Nama Dengan Gelar</label>
                            <input type="text" name="name_with_title" class="form-control" placeholder="cth: Dr. John Doe, M.Sc." />
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" />
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Afiliasi</label>
                            <input type="text" name="affiliation" class="form-control" />
                        </div>
                        <div class="mb-5">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" />
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

    {{-- Edit & Delete Modals per author --}}
    @foreach ($book->bookAuthors as $author)
        {{-- Edit Modal --}}
        <div class="modal fade" id="modal_edit_author_{{ $author->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Edit Penulis</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <form action="{{ route('back.book.author.update', [$book->id, $author->id]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="modal-body">
                            <div class="mb-5">
                                <label class="form-label required">Nama Tanpa Gelar</label>
                                <input type="text" name="name" class="form-control" value="{{ $author->name }}" required />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Nama Dengan Gelar</label>
                                <input type="text" name="name_with_title" class="form-control" value="{{ $author->name_with_title }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $author->email }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Afiliasi</label>
                                <input type="text" name="affiliation" class="form-control" value="{{ $author->affiliation }}" />
                            </div>
                            <div class="mb-5">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ $author->phone }}" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Delete Modal --}}
        <div class="modal fade" id="modal_delete_author_{{ $author->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Hapus Penulis</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                    </div>
                    <form action="{{ route('back.book.author.destroy', [$book->id, $author->id]) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-body">
                            <p>Apakah anda yakin ingin menghapus penulis <strong>{{ $author->name }}</strong>?</p>
                            <span class="text-danger"><strong>Warning!</strong> Data yang sudah dihapus tidak dapat dikembalikan.</span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
