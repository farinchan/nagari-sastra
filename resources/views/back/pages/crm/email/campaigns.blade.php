@extends('back.app')

@section('content')
    <div id="kt_content_container" class=" container-xxl ">
        <div class="card card-flush">
            <div class="card-header pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-dark">Email Campaigns</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Kelola dan kirim email campaign</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('back.crm.email.campaigns.create') }}" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-plus fs-4"></i> Buat Campaign
                    </a>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>Nama Campaign</th>
                                <th>Akun Pengirim</th>
                                <th>Grup Target</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Progress</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaigns as $campaign)
                            <tr>
                                <td class="fw-bold text-gray-800">{{ $campaign->name }}</td>
                                <td>
                                    @if($campaign->emailAccount)
                                        <span class="text-muted fs-7">{{ $campaign->emailAccount->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($campaign->group)
                                        <span class="badge badge-light-primary">{{ $campaign->group->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ Str::limit($campaign->subject, 40) }}</td>
                                <td>
                                    @switch($campaign->status)
                                        @case('draft')
                                            <span class="badge badge-light-secondary">Draft</span>
                                            @break
                                        @case('sending')
                                            <span class="badge badge-light-warning">Mengirim</span>
                                            @break
                                        @case('sent')
                                            <span class="badge badge-light-success">Terkirim</span>
                                            @break
                                        @case('failed')
                                            <span class="badge badge-light-danger">Gagal</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <span class="text-muted fs-7">{{ $campaign->sent_count }}/{{ $campaign->total_recipients }}</span>
                                </td>
                                <td class="text-muted fs-7">{{ $campaign->created_at->format('d M Y H:i') }}</td>
                                <td class="text-end">
                                    @if($campaign->status === 'draft')
                                    <button type="button" class="btn btn-sm btn-light-success me-1 btn-send-campaign"
                                        data-id="{{ $campaign->id }}"
                                        data-name="{{ $campaign->name }}"
                                        title="Kirim Campaign">
                                        <i class="ki-duotone ki-send fs-5"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-light-danger btn-delete-campaign" data-id="{{ $campaign->id }}" data-name="{{ $campaign->name }}" title="Hapus">
                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-10">
                                    <i class="ki-duotone ki-rocket fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
                                    <p class="mb-0">Belum ada campaign. Buat campaign pertama Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Delete Form-->
    <form id="deleteCampaignForm" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
<script>
    // Send Campaign
    document.querySelectorAll('.btn-send-campaign').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.dataset.id;
            var name = this.dataset.name;
            var button = this;

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
                    // Show loading
                    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
                    button.disabled = true;

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
                            button.innerHTML = '<i class="ki-duotone ki-send fs-5"><span class="path1"></span><span class="path2"></span></i>';
                            button.disabled = false;
                        }
                    })
                    .catch(function(error) {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengirim campaign.', 'error');
                        button.innerHTML = '<i class="ki-duotone ki-send fs-5"><span class="path1"></span><span class="path2"></span></i>';
                        button.disabled = false;
                    });
                }
            });
        });
    });

    // Delete Campaign
    document.querySelectorAll('.btn-delete-campaign').forEach(function(btn) {
        btn.addEventListener('click', function() {
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
