@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush" style="min-height: 75vh;">
            <div class="card-body p-0">
                <div class="d-flex wc-split" style="height: 75vh;">

                    {{-- LEFT: Conversation List --}}
                    <div class="border-end wc-left {{ isset($activeConversation) ? 'wc-hide-mobile' : '' }}" style="display: flex; flex-direction: column;">
                        {{-- Header --}}
                        <div class="px-5 py-4 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="fw-bold text-gray-800 mb-0 fs-5">
                                    <i class="ki-duotone ki-message-programming fs-3 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    Webchat
                                </h4>
                                <a href="{{ route('back.crm.webchat.widgets') }}" class="btn btn-sm btn-icon btn-light" title="Widget Settings">
                                    <i class="ki-duotone ki-setting-2 fs-4"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                            </div>
                            @if($widgets->count() > 0)
                            <div class="mt-3">
                                <select class="form-select form-select-sm form-select-solid" id="widgetFilter">
                                    <option value="">Semua Widget</option>
                                    @foreach($widgets as $w)
                                        <option value="{{ $w->id }}" {{ $selectedWidget && $selectedWidget->id == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="d-flex gap-1 mt-3 px-1">
                                <a href="{{ route('back.crm.webchat.index', array_merge(request()->only('widget_id'), [])) }}" class="btn btn-sm {{ !$selectedStatus ? 'btn-primary' : 'btn-light' }} flex-fill">Semua</a>
                                <a href="{{ route('back.crm.webchat.index', array_merge(request()->only('widget_id'), ['status' => 'active'])) }}" class="btn btn-sm {{ $selectedStatus === 'active' ? 'btn-success' : 'btn-light' }} flex-fill">Aktif</a>
                                <a href="{{ route('back.crm.webchat.index', array_merge(request()->only('widget_id'), ['status' => 'closed'])) }}" class="btn btn-sm {{ $selectedStatus === 'closed' ? 'btn-secondary' : 'btn-light' }} flex-fill">Diakhiri</a>
                            </div>
                        </div>
                        {{-- Chat List --}}
                        <div style="overflow-y: auto; flex: 1;">
                            @forelse($conversations as $conv)
                                <a href="{{ route('back.crm.webchat.index', array_merge(request()->only(['widget_id', 'status']), ['chat_id' => $conv->id])) }}"
                                   class="d-flex align-items-center px-5 py-3 border-bottom border-gray-100 text-dark text-hover-primary wc-chat-row {{ isset($activeConversation) && $activeConversation->id == $conv->id ? 'bg-light-primary' : '' }} {{ $conv->status === 'closed' ? 'wc-closed' : '' }}"
                                   style="transition: background 0.15s;">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label {{ $conv->status === 'closed' ? 'bg-light-secondary text-gray-500' : ($conv->unread_count > 0 ? 'bg-primary text-white' : 'bg-light-primary text-primary') }} fw-bold fs-6">
                                            @php
                                                $name = $conv->display_name;
                                                $initials = '';
                                                $parts = explode(' ', $name);
                                                foreach(array_slice($parts, 0, 2) as $p) {
                                                    $initials .= mb_strtoupper(mb_substr(ltrim($p, '#'), 0, 1));
                                                }
                                            @endphp
                                            {{ $initials ?: '?' }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold {{ $conv->status === 'closed' ? 'text-gray-500' : 'text-gray-800' }} fs-7 text-truncate">{{ $conv->display_name }}</span>
                                            <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">
                                                @if($conv->status === 'closed')
                                                    <span class="badge badge-light-secondary fs-9">Diakhiri</span>
                                                @elseif($conv->unread_count > 0)
                                                    <span class="badge badge-primary badge-circle badge-sm">{{ $conv->unread_count }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <span class="text-muted fs-8 text-truncate me-2">
                                                @php $lastMsg = $conv->messages->first(); @endphp
                                                @if($lastMsg)
                                                    @if($lastMsg->sender === 'admin') <i class="ki-duotone ki-check fs-8 text-success"><span class="path1"></span><span class="path2"></span></i> @endif
                                                    {{ Str::limit($lastMsg->message ?: ($lastMsg->image ? '📷 Gambar' : ''), 35) }}
                                                @else
                                                    <em>Belum ada pesan</em>
                                                @endif
                                            </span>
                                            @if($conv->last_message_at)
                                                <span class="text-muted fs-9 flex-shrink-0">{{ $conv->last_message_at->diffForHumans(null, true) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-muted py-15 px-6">
                                    <i class="ki-duotone ki-message-programming fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    <div class="fs-7">Belum ada percakapan</div>
                                </div>
                            @endforelse
                            @if($conversations->hasPages())
                                <div class="px-4 py-3">
                                    {{ $conversations->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- RIGHT: Messages --}}
                    <div class="flex-grow-1 d-flex flex-column wc-right {{ !isset($activeConversation) ? 'wc-hide-mobile' : '' }}" style="min-width: 0;">
                        @if(isset($activeConversation))
                            {{-- Chat Header --}}
                            <div class="px-5 py-3 border-bottom d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('back.crm.webchat.index', ['widget_id' => request('widget_id')]) }}" class="btn btn-sm btn-icon btn-light me-2 wc-back-btn d-none">
                                        <i class="ki-duotone ki-arrow-left fs-3"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <div class="symbol symbol-35px me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                            @php
                                                $aName = $activeConversation->display_name;
                                                $aInitials = '';
                                                $aParts = explode(' ', $aName);
                                                foreach(array_slice($aParts, 0, 2) as $p) {
                                                    $aInitials .= mb_strtoupper(mb_substr(ltrim($p, '#'), 0, 1));
                                                }
                                            @endphp
                                            {{ $aInitials ?: '?' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-gray-800 fs-6">{{ $activeConversation->display_name }}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($activeConversation->visitor_email)
                                                <span class="text-muted fs-9">{{ $activeConversation->visitor_email }}</span>
                                            @endif
                                            @if($activeConversation->widget)
                                                <span class="badge badge-light-primary fs-9">{{ $activeConversation->widget->name }}</span>
                                            @endif
                                            @php $statusBadge = $activeConversation->status === 'active' ? 'badge-light-success' : 'badge-light-secondary'; @endphp
                                            <span class="badge {{ $statusBadge }} fs-9">{{ ucfirst($activeConversation->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($activeConversation->status === 'active')
                                        <form action="{{ route('back.crm.webchat.close', $activeConversation->id) }}" method="POST">@csrf<button type="submit" class="btn btn-sm btn-icon btn-light-warning" title="Tutup"><i class="ki-duotone ki-cross-circle fs-4"><span class="path1"></span><span class="path2"></span></i></button></form>
                                    @endif
                                    <form action="{{ route('back.crm.webchat.destroy', $activeConversation->id) }}" method="POST" onsubmit="return confirm('Hapus percakapan?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus"><i class="ki-duotone ki-trash fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button></form>
                                </div>
                            </div>

                            {{-- Messages --}}
                            <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 20px; background: #f5f8fa;">
                                @forelse($activeConversation->messagesAsc as $msg)
                                    @if($msg->sender === 'visitor')
                                        <div class="d-flex justify-content-start mb-4">
                                            <div style="max-width: 70%;">
                                                <div class="bg-white rounded-3 p-3 shadow-sm">
                                                    @if($msg->image)
                                                        <a href="{{ asset('storage/' . $msg->image) }}" target="_blank"><img src="{{ asset('storage/' . $msg->image) }}" class="rounded-2 mb-2" style="max-width: 220px; max-height: 160px; display: block;" alt=""></a>
                                                    @endif
                                                    @if($msg->message)
                                                        <div class="text-gray-800 fs-7">{!! nl2br(e($msg->message)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-muted fs-9 mt-1 ms-1">{{ $msg->created_at->format('d M H:i') }}</div>
                                            </div>
                                        </div>
                                    @elseif($msg->sender === 'system')
                                        <div class="d-flex justify-content-center mb-4">
                                            <div class="bg-light rounded-3 px-4 py-2">
                                                <span class="text-muted fs-8 fst-italic">{{ $msg->message }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-end mb-4">
                                            <div style="max-width: 70%;">
                                                <div class="bg-primary rounded-3 p-3">
                                                    @if($msg->image)
                                                        <a href="{{ asset('storage/' . $msg->image) }}" target="_blank"><img src="{{ asset('storage/' . $msg->image) }}" class="rounded-2 mb-2" style="max-width: 220px; max-height: 160px; display: block;" alt=""></a>
                                                    @endif
                                                    @if($msg->message)
                                                        <div class="text-white fs-7">{!! nl2br(e($msg->message)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-muted fs-9 mt-1 text-end me-1">
                                                    <i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                    @if($msg->adminUser) <span class="text-gray-500">{{ $msg->adminUser->name }}</span> · @endif
                                                    {{ $msg->created_at->format('d M H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-muted py-10">
                                        <div class="fs-7">Belum ada pesan.</div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Image Preview --}}
                            <div id="imagePreview" class="px-4 py-2 border-top d-none" style="background: #f0f2f5;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative">
                                        <img id="previewImg" src="" style="height: 50px; border-radius: 6px; object-fit: cover;" alt="">
                                        <button type="button" class="btn btn-icon btn-sm btn-active-light-danger position-absolute" style="top: -6px; right: -6px; width: 18px; height: 18px;" id="clearImage">
                                            <i class="ki-duotone ki-cross fs-8"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>
                                    <span class="text-muted fs-8" id="imageName"></span>
                                </div>
                            </div>

                            {{-- Reply --}}
                            <div class="px-5 py-3 border-top">
                                <form id="replyForm">
                                    @csrf
                                    <div class="d-flex gap-2 align-items-end">
                                        <input type="file" id="imageInput" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">
                                        <button type="button" class="btn btn-icon btn-light" style="min-height: 44px; min-width: 44px;" id="attachBtn" title="Gambar">
                                            <i class="ki-duotone ki-picture fs-3"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <textarea id="replyMessage" name="message" class="form-control form-control-solid flex-grow-1" rows="1" placeholder="Ketik balasan..."></textarea>
                                        <button type="submit" class="btn btn-primary btn-icon" style="min-height: 44px; min-width: 44px;" id="sendBtn">
                                            <i class="ki-duotone ki-send fs-3"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            {{-- Empty state --}}
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                <i class="ki-duotone ki-message-programming fs-4x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                <div class="fs-5 fw-semibold text-gray-400">Pilih percakapan</div>
                                <div class="fs-7 text-gray-400 mt-1">Pilih percakapan dari daftar di sebelah kiri</div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .wc-chat-row:hover { background-color: var(--bs-gray-100) !important; }
        .wc-chat-row.bg-light-primary { background-color: #f1f3ff !important; }
        .wc-chat-row.wc-closed { opacity: 0.65; }
        .wc-left { width: 360px; min-width: 360px; }
        @media (max-width: 768px) {
            .wc-split { height: calc(100vh - 120px) !important; }
            .wc-left { width: 100%; min-width: 100%; }
            .wc-hide-mobile { display: none !important; }
            .wc-back-btn { display: inline-flex !important; }
        }
    </style>
@endsection

@section('scripts')
<script>
@if(isset($activeConversation))
var chatDiv = document.getElementById('chatMessages');
var lastId = {{ $activeConversation->messagesAsc->last() ? $activeConversation->messagesAsc->last()->id : 0 }};
var conversationId = {{ $activeConversation->id }};
var selectedFile = null;

function scrollToBottom() { if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight; }
scrollToBottom();

document.getElementById('attachBtn').addEventListener('click', function() { document.getElementById('imageInput').click(); });
document.getElementById('imageInput').addEventListener('change', function(e) {
    var file = e.target.files[0]; if (!file) return;
    if (file.size > 5*1024*1024) { this.value=''; return; }
    selectedFile = file;
    var reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('previewImg').src = ev.target.result;
        document.getElementById('imageName').textContent = file.name;
        document.getElementById('imagePreview').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});
document.getElementById('clearImage').addEventListener('click', function() {
    selectedFile = null; document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').classList.add('d-none');
});

function appendMessage(msg) {
    var imgHtml = '';
    if (msg.image) imgHtml = '<a href="'+msg.image+'" target="_blank"><img src="'+msg.image+'" class="rounded-2 mb-2" style="max-width:220px;max-height:160px;display:block;" alt=""></a>';
    var textHtml = msg.message ? msg.message.replace(/\n/g, '<br>') : '';
    var html = '';
    if (msg.sender === 'visitor') {
        html = '<div class="d-flex justify-content-start mb-4"><div style="max-width:70%"><div class="bg-white rounded-3 p-3 shadow-sm">'+imgHtml+(textHtml?'<div class="text-gray-800 fs-7">'+textHtml+'</div>':'')+'</div><div class="text-muted fs-9 mt-1 ms-1">'+msg.time+'</div></div></div>';
    } else {
        html = '<div class="d-flex justify-content-end mb-4"><div style="max-width:70%"><div class="bg-primary rounded-3 p-3">'+imgHtml+(textHtml?'<div class="text-white fs-7">'+textHtml+'</div>':'')+'</div><div class="text-muted fs-9 mt-1 text-end me-1"><i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i> '+(msg.admin_name?'<span class="text-gray-500">'+msg.admin_name+'</span> · ':'')+msg.time+'</div></div></div>';
    }
    chatDiv.insertAdjacentHTML('beforeend', html); scrollToBottom();
}

setInterval(function() {
    fetch('{{ route("back.crm.webchat.fetch", $activeConversation->id) }}?last_id='+lastId)
        .then(r => r.json()).then(data => {
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(function(msg) { if (msg.id > lastId) { appendMessage(msg); lastId = msg.id; } });
            }
        });
}, 3000);

document.getElementById('replyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var msgInput = document.getElementById('replyMessage');
    var msg = msgInput.value.trim();
    if (!msg && !selectedFile) return;
    var btn = document.getElementById('sendBtn');
    btn.disabled = true;
    if (selectedFile) {
        var fd = new FormData();
        fd.append('image', selectedFile); fd.append('message', msg);
        fetch('{{ route("back.crm.webchat.reply-ajax", $activeConversation->id) }}', { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:fd })
        .then(r=>r.json()).then(data=>{ if(data.success){appendMessage(data.message);lastId=data.message.id;msgInput.value='';selectedFile=null;document.getElementById('imageInput').value='';document.getElementById('imagePreview').classList.add('d-none');} btn.disabled=false; }).catch(()=>{btn.disabled=false;});
    } else {
        fetch('{{ route("back.crm.webchat.reply-ajax", $activeConversation->id) }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:JSON.stringify({message:msg}) })
        .then(r=>r.json()).then(data=>{ if(data.success){appendMessage(data.message);lastId=data.message.id;msgInput.value='';} btn.disabled=false; }).catch(()=>{btn.disabled=false;});
    }
});
document.getElementById('replyMessage').addEventListener('keydown', function(e) { if (e.key==='Enter'&&!e.shiftKey) { e.preventDefault(); document.getElementById('replyForm').dispatchEvent(new Event('submit')); } });
@endif

document.getElementById('widgetFilter')?.addEventListener('change', function() {
    var v = this.value;
    var status = '{{ $selectedStatus }}';
    var url = '{{ route("back.crm.webchat.index") }}';
    var params = [];
    if (v) params.push('widget_id='+v);
    if (status) params.push('status='+status);
    window.location.href = params.length ? url+'?'+params.join('&') : url;
});
</script>
@endsection
