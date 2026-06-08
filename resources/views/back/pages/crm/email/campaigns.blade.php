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
                            class="form-control form-control-solid w-250px ps-12" placeholder="Cari Campaign" />
                    </div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <div class="w-100 mw-150px">
                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                            data-placeholder="Status" data-kt-ecommerce-product-filter="status">
                            <option></option>
                            <option value="all">Semua</option>
                            <option value="Draft">Draft</option>
                            <option value="Mengirim">Mengirim</option>
                            <option value="Terkirim">Terkirim</option>
                            <option value="Gagal">Gagal</option>
                        </select>
                    </div>
                    <a href="{{ route('back.crm.email.campaigns.create') }}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i> Buat Campaign
                    </a>
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
                            <th class="min-w-200px">Campaign</th>
                            <th class="min-w-120px">Grup Target</th>
                            <th class="text-end min-w-80px">Progress</th>
                            <th class="text-end min-w-80px">Status</th>
                            <th class="text-end min-w-100px">Tanggal</th>
                            <th class="text-end min-w-70px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach($campaigns as $campaign)
                            <tr>
                                <td>
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="1" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 fw-bold fs-6"
                                            data-kt-ecommerce-product-filter="product_name">{{ $campaign->name }}</span>
                                        <span class="text-muted fs-7">{{ Str::limit($campaign->subject, 50) }}</span>
                                        @if($campaign->emailAccount)
                                            <span class="text-muted fs-8">via {{ $campaign->emailAccount->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($campaign->group)
                                        <span class="badge badge-light-primary">{{ $campaign->group->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-0">
                                    <span class="text-muted fs-7">{{ $campaign->sent_count }}/{{ $campaign->total_recipients }}</span>
                                </td>
                                <td class="text-end pe-0">
                                    @switch($campaign->status)
                                        @case('draft')
                                            <div class="badge badge-light-secondary">Draft</div>
                                            @break
                                        @case('sending')
                                            <div class="badge badge-light-warning">Mengirim</div>
                                            @break
                                        @case('sent')
                                            <div class="badge badge-light-success">Terkirim</div>
                                            @break
                                        @case('failed')
                                            <div class="badge badge-light-danger">Gagal</div>
                                            @break
                                    @endswitch
                                </td>
                                <td class="text-end pe-0">
                                    <span class="text-muted fs-7">{{ $campaign->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="#"
                                        class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-4"
                                        data-kt-menu="true">
                                        @if($campaign->status === 'draft')
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 btn-send-campaign"
                                                    data-id="{{ $campaign->id }}"
                                                    data-name="{{ $campaign->name }}">
                                                    <i class="ki-duotone ki-send fs-5 me-2"><span class="path1"></span><span class="path2"></span></i> Kirim
                                                </a>
                                            </div>
                                        @endif
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3 btn-delete-campaign"
                                                data-id="{{ $campaign->id }}"
                                                data-name="{{ $campaign->name }}">
                                                <i class="ki-duotone ki-trash fs-5 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                                            </a>
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

    {{-- Delete Form --}}
    <form id="deleteCampaignForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script src="{{ asset('back/js/custom/apps/crm/email-campaigns.js') }}"></script>
    <script>
        // Send Campaign
        document.querySelectorAll('.btn-send-campaign').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.dataset.id;
                var name = this.dataset.name;

                Swal.fire({
                    title: 'Kirim Campaign?',
                    text: 'Campaign "' + name + '" akan dikirim ke semua kontak di grup target. Proses ini tidak bisa dibatalkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#50cd89',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengirim...',
                            text: 'Mohon tunggu, sedang mengirim email ke semua kontak.',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });

                        fetch('{{ route("back.crm.email.campaigns.send") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ campaign_id: id })
                        })
                        .then(function(response) { return response.json(); })
                        .then(function(data) {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', data.message, 'error');
                            }
                        })
                        .catch(function(error) {
                            Swal.fire('Error', 'Terjadi kesalahan saat mengirim campaign.', 'error');
                        });
                    }
                });
            });
        });

        // Delete Campaign
        document.querySelectorAll('.btn-delete-campaign').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.dataset.id;
                var name = this.dataset.name;

                Swal.fire({
                    title: 'Hapus Campaign?',
                    text: 'Campaign "' + name + '" akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var form = document.getElementById('deleteCampaignForm');
                        form.action = '/back/crm/email/campaigns/' + id + '/destroy';
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
