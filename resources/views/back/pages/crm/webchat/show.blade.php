@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">

        <div class="card card-flush">
            {{-- Chat Header --}}
            <div class="card-header align-items-center py-4 border-bottom">
                <div class="d-flex align-items-center">
                    <a href="{{ route('back.crm.webchat.index') }}" class="btn btn-sm btn-icon btn-light me-3">
                        <i class="ki-duotone ki-arrow-left fs-3"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <div class="symbol symbol-40px me-3">
                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-5">
                            @php
                                $name = $conversation->display_name;
                                $initials = '';
                                $parts = explode(' ', $name);
                                foreach(array_slice($parts, 0, 2) as $p) {
                                    $initials .= mb_strtoupper(mb_substr(ltrim($p, '#'), 0, 1));
                                }
                            @endphp
                            {{ $initials ?: '?' }}
                        </div>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-800 fs-6">{{ $conversation->display_name }}</div>
                        <div class="d-flex align-items-center gap-2">
                            @if($conversation->visitor_email)
                                <span class="text-muted fs-8">{{ $conversation->visitor_email }}</span>
                            @endif
                            @if($conversation->widget)
                                <span class="badge badge-light-primary fs-9">{{ $conversation->widget->name }}</span>
                            @endif
                            @php
                                $statusBadge = $conversation->status === 'active' ? 'badge-light-success' : 'badge-light-secondary';
                            @endphp
                            <span class="badge {{ $statusBadge }} fs-9">{{ ucfirst($conversation->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-toolbar d-flex gap-2">
                    @if($conversation->ip_address)
                        <span class="text-muted fs-9">IP: {{ $conversation->ip_address }}</span>
                    @endif
                    @if($conversation->status === 'active')
                        <form action="{{ route('back.crm.webchat.close', $conversation->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light-warning" title="Tutup Percakapan">
                                <i class="ki-duotone ki-cross-circle fs-4"><span class="path1"></span><span class="path2"></span></i> Tutup
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('back.crm.webchat.destroy', $conversation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus percakapan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light-danger" title="Hapus Percakapan">
                            <i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Message Area --}}
            <div class="card-body" style="background-color: #f5f8fa;">
                <div id="chatMessages" style="max-height: 500px; overflow-y: auto; padding: 10px 0;">
                    @forelse($conversation->messagesAsc as $msg)
                        @if($msg->sender === 'visitor')
                            {{-- Incoming --}}
                            <div class="d-flex justify-content-start mb-4">
                                <div style="max-width: 75%;">
                                    <div class="bg-white rounded-3 p-3 shadow-sm">
                                        @if($msg->image)
                                            <a href="{{ asset('storage/' . $msg->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $msg->image) }}" class="rounded-2 mb-2" style="max-width: 240px; max-height: 180px; cursor: pointer; display: block;" alt="Image">
                                            </a>
                                        @endif
                                        @if($msg->message)
                                            <div class="text-gray-800 fs-7">{!! nl2br(e($msg->message)) !!}</div>
                                        @endif
                                    </div>
                                    <div class="text-muted fs-9 mt-1 ms-1">
                                        {{ $msg->created_at->format('d M H:i') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Outgoing (Admin) --}}
                            <div class="d-flex justify-content-end mb-4">
                                <div style="max-width: 75%;">
                                    <div class="bg-primary rounded-3 p-3">
                                        @if($msg->image)
                                            <a href="{{ asset('storage/' . $msg->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $msg->image) }}" class="rounded-2 mb-2" style="max-width: 240px; max-height: 180px; cursor: pointer; display: block;" alt="Image">
                                            </a>
                                        @endif
                                        @if($msg->message)
                                            <div class="text-white fs-7">{!! nl2br(e($msg->message)) !!}</div>
                                        @endif
                                    </div>
                                    <div class="text-muted fs-9 mt-1 text-end me-1">
                                        <i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i>
                                        @if($msg->adminUser)
                                            <span class="text-gray-500">{{ $msg->adminUser->name }}</span> ·
                                        @endif
                                        {{ $msg->created_at->format('d M H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-muted py-10">
                            <i class="ki-duotone ki-message-programming fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            <div class="fs-7">Belum ada pesan dalam percakapan ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Image Preview --}}
            <div id="imagePreview" class="px-4 py-2 border-top d-none" style="background: #f0f2f5;">
                <div class="d-flex align-items-center gap-2">
                    <div class="position-relative">
                        <img id="previewImg" src="" style="height: 60px; border-radius: 6px; object-fit: cover;" alt="">
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-danger position-absolute" style="top: -6px; right: -6px; width: 20px; height: 20px;" id="clearImage">
                            <i class="ki-duotone ki-cross fs-7"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                    <span class="text-muted fs-8" id="imageName"></span>
                </div>
            </div>

            {{-- Reply Form --}}
            <div class="card-footer py-4 border-top">
                <form id="replyForm">
                    @csrf
                    <div class="d-flex gap-2 align-items-end">
                        <input type="file" id="imageInput" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">
                        <button type="button" class="btn btn-icon btn-light" style="min-height: 50px; min-width: 50px;" id="attachBtn" title="Kirim Gambar">
                            <i class="ki-duotone ki-picture fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                        <textarea id="replyMessage" name="message" class="form-control form-control-solid flex-grow-1" rows="2" placeholder="Ketik balasan..."></textarea>
                        <button type="submit" class="btn btn-primary btn-icon" style="min-height: 50px; min-width: 50px;" id="sendBtn">
                            <i class="ki-duotone ki-send fs-2"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var chatDiv = document.getElementById('chatMessages');
    var lastId = {{ $conversation->messagesAsc->last() ? $conversation->messagesAsc->last()->id : 0 }};
    var conversationId = {{ $conversation->id }};
    var selectedFile = null;

    // Auto-scroll to bottom
    function scrollToBottom() {
        if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
    }
    scrollToBottom();

    // Image attach
    document.getElementById('attachBtn').addEventListener('click', function () {
        document.getElementById('imageInput').click();
    });

    document.getElementById('imageInput').addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire('Error', 'Ukuran gambar maksimal 5MB.', 'error');
            this.value = '';
            return;
        }
        selectedFile = file;
        var reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('imageName').textContent = file.name;
            document.getElementById('imagePreview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('clearImage').addEventListener('click', function () {
        selectedFile = null;
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreview').classList.add('d-none');
    });

    // Append message bubble
    function appendMessage(msg) {
        var imgHtml = '';
        if (msg.image) {
            imgHtml = '<a href="' + msg.image + '" target="_blank"><img src="' + msg.image + '" class="rounded-2 mb-2" style="max-width:240px;max-height:180px;cursor:pointer;display:block;" alt="Image"></a>';
        }
        var textHtml = msg.message ? msg.message.replace(/\n/g, '<br>') : '';

        var html = '';
        if (msg.sender === 'visitor') {
            html = '<div class="d-flex justify-content-start mb-4">' +
                '<div style="max-width: 75%;">' +
                '<div class="bg-white rounded-3 p-3 shadow-sm">' +
                imgHtml +
                (textHtml ? '<div class="text-gray-800 fs-7">' + textHtml + '</div>' : '') +
                '</div>' +
                '<div class="text-muted fs-9 mt-1 ms-1">' + msg.time + '</div>' +
                '</div></div>';
        } else {
            html = '<div class="d-flex justify-content-end mb-4">' +
                '<div style="max-width: 75%;">' +
                '<div class="bg-primary rounded-3 p-3">' +
                imgHtml +
                (textHtml ? '<div class="text-white fs-7">' + textHtml + '</div>' : '') +
                '</div>' +
                '<div class="text-muted fs-9 mt-1 text-end me-1">' +
                '<i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i> ' +
                (msg.admin_name ? '<span class="text-gray-500">' + msg.admin_name + '</span> · ' : '') +
                msg.time +
                '</div></div></div>';
        }
        chatDiv.insertAdjacentHTML('beforeend', html);
        scrollToBottom();
    }

    // Poll for new messages every 3 seconds
    setInterval(function () {
        fetch('{{ route("back.crm.webchat.fetch", $conversation->id) }}?last_id=' + lastId)
            .then(r => r.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    data.messages.forEach(function (msg) {
                        if (msg.id > lastId) {
                            appendMessage(msg);
                            lastId = msg.id;
                        }
                    });
                }
            });
    }, 3000);

    // AJAX reply (with optional image via FormData)
    document.getElementById('replyForm').addEventListener('submit', function (e) {
        e.preventDefault();
        var msgInput = document.getElementById('replyMessage');
        var msg = msgInput.value.trim();
        if (!msg && !selectedFile) return;

        var btn = document.getElementById('sendBtn');
        btn.disabled = true;

        if (selectedFile) {
            // FormData upload
            var fd = new FormData();
            fd.append('image', selectedFile);
            fd.append('message', msg);

            fetch('{{ route("back.crm.webchat.reply-ajax", $conversation->id) }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    appendMessage(data.message);
                    lastId = data.message.id;
                    msgInput.value = '';
                    selectedFile = null;
                    document.getElementById('imageInput').value = '';
                    document.getElementById('imagePreview').classList.add('d-none');
                }
                btn.disabled = false;
            })
            .catch(() => { btn.disabled = false; });
        } else {
            // Text-only (JSON)
            fetch('{{ route("back.crm.webchat.reply-ajax", $conversation->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message: msg }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    appendMessage(data.message);
                    lastId = data.message.id;
                    msgInput.value = '';
                }
                btn.disabled = false;
            })
            .catch(() => { btn.disabled = false; });
        }
    });

    // Send on Enter (Shift+Enter for new line)
    document.getElementById('replyMessage').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('replyForm').dispatchEvent(new Event('submit'));
        }
    });
</script>
@endsection
