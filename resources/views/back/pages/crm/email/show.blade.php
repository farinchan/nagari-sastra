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
                <a href="{{ route('back.crm.email.inbox', ['account_id' => $selectedAccount->id]) }}" class="text-muted">Inbox</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <span class="text-dark">{{ Str::limit($subject, 50) }}</span>
            </span>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <a href="{{ route('back.crm.email.inbox', ['account_id' => $selectedAccount->id]) }}" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i> Kembali ke Inbox
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('back.crm.email.compose', ['account_id' => $selectedAccount->id, 'to' => $from_email, 'subject' => 'Re: ' . $subject]) }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-4"><span class="path1"></span><span class="path2"></span></i> Reply
                </a>
                <form action="{{ route('back.crm.email.delete') }}" method="POST" id="deleteEmailForm">
                    @csrf
                    <input type="hidden" name="account_id" value="{{ $selectedAccount->id }}">
                    <input type="hidden" name="uid" value="{{ $uid }}">
                    <button type="button" class="btn btn-sm btn-light-danger" id="btnDeleteEmail">
                        <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- Email Header --}}
        <div class="card card-flush mb-5">
            <div class="card-body">
                <h3 class="fw-bold mb-4">{{ $subject }}</h3>
                <div class="d-flex flex-wrap gap-5">
                    <div>
                        <span class="text-muted fs-7">Dari:</span>
                        <span class="fw-semibold">{{ $from_name }} &lt;{{ $from_email }}&gt;</span>
                    </div>
                    <div>
                        <span class="text-muted fs-7">Kepada:</span>
                        <span class="fw-semibold">{{ implode(', ', $to_list) }}</span>
                    </div>
                    @if(!empty($cc_list))
                        <div>
                            <span class="text-muted fs-7">CC:</span>
                            <span class="fw-semibold">{{ implode(', ', $cc_list) }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-muted fs-7">Tanggal:</span>
                        <span class="fw-semibold">{{ $date }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Email Body --}}
        <div class="card card-flush mb-5">
            <div class="card-body p-0">
                <iframe id="email-body" class="w-100 border-0" srcdoc="{!! htmlspecialchars($body_html) !!}" style="min-height: 400px;"></iframe>
            </div>
        </div>

        {{-- Attachments --}}
        @if(!empty($attachments))
            <div class="card card-flush">
                <div class="card-header">
                    <div class="card-title">
                        <i class="ki-duotone ki-paper-clip fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                        Lampiran ({{ count($attachments) }})
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($attachments as $attachment)
                            <div class="border border-dashed border-gray-300 rounded p-3">
                                <i class="ki-duotone ki-file fs-3 me-2"><span class="path1"></span><span class="path2"></span></i>
                                <span class="fw-semibold">{{ $attachment['filename'] }}</span>
                                @if($attachment['size'])
                                    <span class="text-muted fs-7 ms-2">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-resize iframe
        var iframe = document.getElementById('email-body');
        iframe.addEventListener('load', function() {
            try {
                var height = iframe.contentDocument.body.scrollHeight + 40;
                iframe.style.height = Math.max(height, 400) + 'px';
            } catch(e) {
                iframe.style.height = '600px';
            }
        });

        // Delete email
        document.getElementById('btnDeleteEmail')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Email?',
                text: 'Email yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteEmailForm').submit();
                }
            });
        });
    </script>
@endsection
