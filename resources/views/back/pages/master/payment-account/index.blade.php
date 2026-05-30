@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-fluid">

        <div class="card card-flush">
            <div class="card-header py-5">
                <div class="card-title">
                    <h3 class="fw-bold m-0">
                        <i class="ki-duotone ki-bank fs-2 me-2 text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Rekening Pembayaran
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="javascript:;" data-repeater-create id="btn-add-account" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-plus fs-5"></i>
                        Tambah Rekening
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <form id="kt_account_profile_details_form" class="form" method="POST" enctype="multipart/form-data"
                    action="{{ route('back.master.payment-account.update') }}">
                    @method('PUT')
                    @csrf

                    <div id="kt_docs_repeater_basic">
                        <div data-repeater-list="payment_accounts">
                            @foreach ($payment_accounts as $account)
                                <div data-repeater-item>
                                    <input type="hidden" name="account_id" value="{{ $account->id ?? '' }}" id="account_id">
                                    <div class="border border-dashed rounded p-5 mb-5 bg-light-secondary">
                                        <div class="row align-items-end">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fs-7 fw-bold required">Nama Pemilik Rekening</label>
                                                <input type="text" class="form-control form-control-solid"
                                                    name="account_name" placeholder="Rekening Atas Nama"
                                                    value="{{ $account->account_name }}" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fs-7 fw-bold required">Bank</label>
                                                <input type="text" class="form-control form-control-solid" name="bank"
                                                    placeholder="Nama Bank" value="{{ $account->bank }}" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fs-7 fw-bold required">No. Rekening</label>
                                                <input type="text" class="form-control form-control-solid"
                                                    name="account_number" placeholder="Nomor Rekening Bank"
                                                    value="{{ $account->account_number }}" required />
                                            </div>
                                            <div class="col-md-2 mb-3 text-end">
                                                <a href="javascript:;" data-repeater-delete
                                                    class="btn btn-sm btn-icon btn-light-danger" title="Hapus rekening">
                                                    <i class="ki-duotone ki-trash fs-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" name="delete_account" id="delete_account" value="">

                    <div class="separator separator-dashed my-5"></div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-light">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-duotone ki-check fs-4 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('back/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            var repeater = $('#kt_docs_repeater_basic').repeater({
                initEmpty: false,

                show: function() {
                    $(this).slideDown();
                },

                hide: function(deleteElement) {
                    $(this).slideUp(deleteElement);

                    // Ambil id rekening yang dihapus
                    var account_id = $(this).find('#account_id').val();

                    // Tambahkan id rekening yang dihapus ke inputan delete_account
                    var delete_account = $('#delete_account').val();
                    if (account_id && account_id !== '') {
                        if (delete_account === '') {
                            $('#delete_account').val('[' + account_id + ']');
                        } else {
                            delete_account = delete_account.slice(0, -1) + ',' + account_id + ']';
                            $('#delete_account').val(delete_account);
                        }
                    }
                }
            });

            // Bind the add button in toolbar
            $('#btn-add-account').on('click', function() {
                repeater.repeater('add');
            });
        });
    </script>
@endsection
