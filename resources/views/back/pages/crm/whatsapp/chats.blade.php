@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush" style="min-height: 75vh;">
            <div class="card-body p-0">
                <div class="d-flex wa-split" style="height: 75vh;">

                    {{-- LEFT: Chat List --}}
                    <div class="border-end wa-left {{ isset($activeChat) ? 'wa-hide-mobile' : '' }}" style="display: flex; flex-direction: column;">
                        {{-- Header --}}
                        <div class="px-5 py-4 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="fw-bold text-gray-800 mb-0 fs-5">
                                    <i class="ki-duotone ki-whatsapp fs-3 me-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                                    WhatsApp
                                </h4>
                                <a href="{{ route('back.crm.whatsapp.accounts') }}" class="btn btn-sm btn-icon btn-light" title="Account Settings">
                                    <i class="ki-duotone ki-setting-2 fs-4"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                            </div>
                            @if($accounts->count() > 1)
                            <div class="mt-3">
                                <select class="form-select form-select-sm form-select-solid" id="accountFilter">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}" {{ $selectedAccount && $selectedAccount->id == $acc->id ? 'selected' : '' }}>{{ $acc->name }} ({{ $acc->phone_number }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        {{-- Chat List --}}
                        <div style="overflow-y: auto; flex: 1;">
                            @forelse($chats as $chat)
                                <a href="{{ route('back.crm.whatsapp.chats', ['account_id' => $selectedAccount->id ?? '', 'chat_id' => $chat->id]) }}"
                                   class="d-flex align-items-center px-5 py-3 border-bottom border-gray-100 text-dark text-hover-primary wa-chat-row {{ isset($activeChat) && $activeChat->id == $chat->id ? 'bg-light-success' : '' }}"
                                   style="transition: background 0.15s;">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label {{ $chat->unread_count > 0 ? 'bg-success text-white' : 'bg-light-success text-success' }} fw-bold fs-6">
                                            @php
                                                $name = $chat->display_name;
                                                $initials = '';
                                                $parts = explode(' ', $name);
                                                foreach(array_slice($parts, 0, 2) as $p) {
                                                    $initials .= mb_strtoupper(mb_substr(ltrim($p, '+'), 0, 1));
                                                }
                                            @endphp
                                            {{ $initials ?: '?' }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold text-gray-800 fs-7 text-truncate">{{ $chat->display_name }}</span>
                                            <div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">
                                                @if($chat->unread_count > 0)
                                                    <span class="badge badge-success badge-circle badge-sm">{{ $chat->unread_count }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <span class="text-muted fs-8 text-truncate me-2">
                                                @php $lastMsg = $chat->messages->first(); @endphp
                                                @if($lastMsg)
                                                    @if($lastMsg->direction === 'out') <i class="ki-duotone ki-check fs-8 text-success"><span class="path1"></span><span class="path2"></span></i> @endif
                                                    @if($lastMsg->type === 'image') 📷 Gambar
                                                    @elseif($lastMsg->type === 'document') 📄 Dokumen
                                                    @elseif($lastMsg->type === 'video') 🎬 Video
                                                    @elseif($lastMsg->type === 'audio') 🎤 Audio
                                                    @elseif($lastMsg->type === 'template') 📋 Template
                                                    @else {{ Str::limit($lastMsg->body ?: $lastMsg->caption ?: '', 35) }}
                                                    @endif
                                                @else
                                                    <em>Belum ada pesan</em>
                                                @endif
                                            </span>
                                            @if($chat->last_message_at)
                                                <span class="text-muted fs-9 flex-shrink-0">{{ $chat->last_message_at->diffForHumans(null, true) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-muted py-15 px-6">
                                    <i class="ki-duotone ki-whatsapp fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span></i>
                                    <div class="fs-7">Belum ada percakapan masuk</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- RIGHT: Chat Messages --}}
                    <div class="flex-grow-1 d-flex flex-column {{ isset($activeChat) ? '' : 'd-none d-md-flex' }} wa-right">
                        @if(isset($activeChat))
                            {{-- Chat Header --}}
                            <div class="px-5 py-3 border-bottom d-flex align-items-center gap-3">
                                <a href="{{ route('back.crm.whatsapp.chats', ['account_id' => $selectedAccount->id ?? '']) }}" class="btn btn-sm btn-icon btn-light d-md-none wa-back-btn">
                                    <i class="ki-duotone ki-left fs-3"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                                <div class="symbol symbol-35px">
                                    <div class="symbol-label bg-light-success text-success fw-bold fs-6">
                                        @php
                                            $chatName = $activeChat->display_name;
                                            $chatInitials = '';
                                            $chatParts = explode(' ', $chatName);
                                            foreach(array_slice($chatParts, 0, 2) as $p) {
                                                $chatInitials .= mb_strtoupper(mb_substr(ltrim($p, '+'), 0, 1));
                                            }
                                        @endphp
                                        {{ $chatInitials ?: '?' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold text-gray-800 fs-6">{{ $activeChat->display_name }}</div>
                                    <div class="text-muted fs-8">+{{ $activeChat->wa_id }}</div>
                                </div>
                            </div>

                            {{-- Messages --}}
                            <div id="waMessageBody" class="flex-grow-1 px-5 py-4" style="overflow-y: auto; background: #f5f5f5;">
                                @forelse($messages as $msg)
                                    @if($msg->direction === 'in')
                                        <div class="d-flex justify-content-start mb-4">
                                            <div style="max-width: 70%;">
                                                <div class="bg-white rounded-3 p-3 shadow-sm">
                                                    @if(in_array($msg->type, ['image', 'video', 'document', 'audio', 'sticker']) && $msg->media_id)
                                                        @if($msg->type === 'image')
                                                            <a href="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" target="_blank">
                                                                <img src="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" class="rounded-2 mb-2" style="max-width: 240px; max-height: 200px; display: block;" alt="Foto" loading="lazy">
                                                            </a>
                                                        @elseif($msg->type === 'video')
                                                            <a href="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" target="_blank" class="btn btn-sm btn-light-primary mb-2">
                                                                <i class="ki-duotone ki-to-right fs-3"><span class="path1"></span><span class="path2"></span></i> 🎬 Video
                                                            </a>
                                                        @elseif($msg->type === 'document')
                                                            <a href="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" target="_blank" class="btn btn-sm btn-light-warning mb-2">
                                                                📄 {{ $msg->file_name ?: 'Dokumen' }}
                                                            </a>
                                                        @elseif($msg->type === 'audio')
                                                            <a href="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" target="_blank" class="btn btn-sm btn-light-danger mb-2">
                                                                🎤 Audio
                                                            </a>
                                                        @elseif($msg->type === 'sticker')
                                                            <a href="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" target="_blank">
                                                                <img src="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" class="rounded-2 mb-2" style="max-width: 120px; display: block;" alt="Sticker" loading="lazy">
                                                            </a>
                                                        @endif
                                                    @elseif($msg->type === 'location')
                                                        <span class="badge badge-light-success fs-9 mb-1">📍 Lokasi</span>
                                                    @endif
                                                    @if($msg->body)
                                                        <div class="text-gray-800 fs-7">{!! nl2br(e($msg->body)) !!}</div>
                                                    @endif
                                                    @if($msg->caption)
                                                        <div class="text-gray-800 fs-7 mt-1">{!! nl2br(e($msg->caption)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-muted fs-9 mt-1 ms-1">{{ $msg->sent_at ? $msg->sent_at->format('d M H:i') : $msg->created_at->format('d M H:i') }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-end mb-4">
                                            <div style="max-width: 70%;">
                                                <div class="bg-success rounded-3 p-3">
                                                    @if($msg->type === 'image' && $msg->media_id)
                                                        <a href="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" target="_blank">
                                                            <img src="{{ route('back.crm.whatsapp.media-proxy', [$activeChat->whatsapp_account_id, $msg->media_id]) }}" class="rounded-2 mb-2" style="max-width: 240px; max-height: 200px; display: block;" alt="Foto" loading="lazy">
                                                        </a>
                                                    @elseif($msg->type === 'template')
                                                        <span class="badge badge-light fs-9 mb-1">📋 Template: {{ $msg->body }}</span>
                                                    @endif
                                                    @if($msg->body && $msg->type !== 'template')
                                                        <div class="text-white fs-7">{!! nl2br(e($msg->body)) !!}</div>
                                                    @endif
                                                    @if($msg->caption)
                                                        <div class="text-white fs-7 mt-1">{!! nl2br(e($msg->caption)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-muted fs-9 mt-1 text-end me-1">
                                                    @if($msg->status === 'read')
                                                        <i class="ki-duotone ki-double-check fs-9 text-info"><span class="path1"></span><span class="path2"></span></i>
                                                    @elseif($msg->status === 'delivered')
                                                        <i class="ki-duotone ki-double-check fs-9 text-muted"><span class="path1"></span><span class="path2"></span></i>
                                                    @else
                                                        <i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                    @endif
                                                    {{ $msg->sent_at ? $msg->sent_at->format('d M H:i') : $msg->created_at->format('d M H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-muted py-10">
                                        <div class="fs-7">Belum ada pesan</div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Reply Form --}}
                            <div class="px-5 py-3 border-top bg-white">
                                <form action="{{ route('back.crm.whatsapp.send-message') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-end">
                                    @csrf
                                    <input type="hidden" name="chat_id" value="{{ $activeChat->id }}">
                                    {{-- Attach Image --}}
                                    <input type="file" name="image" id="waImageInput" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">
                                    <button type="button" class="btn btn-sm btn-icon btn-light flex-shrink-0" id="waAttachBtn" title="Kirim Gambar">
                                        <i class="ki-duotone ki-picture fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                    <div class="flex-grow-1 position-relative">
                                        {{-- Image Preview --}}
                                        <div id="waImagePreview" class="d-none mb-2 position-relative" style="display: inline-block;">
                                            <img id="waPreviewImg" src="" class="rounded" style="max-height: 80px; max-width: 150px;">
                                            <button type="button" id="waClearImg" class="btn btn-icon btn-sm btn-danger position-absolute" style="top: -8px; right: -8px; width: 20px; height: 20px; padding: 0;">
                                                <i class="ki-duotone ki-cross fs-8"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        </div>
                                        <textarea name="message" id="waMessage" class="form-control form-control-solid" rows="1" placeholder="Ketik pesan..." style="resize: none; min-height: 38px; max-height: 76px;"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success btn-icon flex-shrink-0">
                                        <i class="ki-duotone ki-send fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                <i class="ki-duotone ki-whatsapp fs-4x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span></i>
                                <div class="fs-6 fw-semibold mb-1">WhatsApp Business CRM</div>
                                <div class="fs-7 text-gray-400">Pilih percakapan untuk melihat pesan</div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .wa-chat-row:hover { background-color: var(--bs-gray-100) !important; }
        .wa-chat-row.bg-light-success { background-color: #e8f5e9 !important; }
        .wa-left { width: 360px; min-width: 360px; }
        @media (max-width: 768px) {
            .wa-split { height: calc(100vh - 120px) !important; }
            .wa-left { width: 100%; min-width: 100%; }
            .wa-hide-mobile { display: none !important; }
            .wa-right { width: 100%; }
        }
    </style>
@endsection

@section('scripts')
<script>
// Scroll to bottom
var msgBody = document.getElementById('waMessageBody');
if (msgBody) msgBody.scrollTop = msgBody.scrollHeight;

// Auto-resize textarea
var waMsg = document.getElementById('waMessage');
if (waMsg) {
    waMsg.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 76) + 'px';
    });
    waMsg.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            this.closest('form').submit();
        }
    });
}

// Image attachment
var waAttachBtn = document.getElementById('waAttachBtn');
var waImageInput = document.getElementById('waImageInput');
var waImagePreview = document.getElementById('waImagePreview');
var waPreviewImg = document.getElementById('waPreviewImg');
var waClearImg = document.getElementById('waClearImg');

if (waAttachBtn) {
    waAttachBtn.addEventListener('click', function() { waImageInput.click(); });
    waImageInput.addEventListener('change', function() {
        var f = this.files[0];
        if (!f) return;
        if (f.size > 5 * 1024 * 1024) { this.value = ''; return; }
        var r = new FileReader();
        r.onload = function(ev) {
            waPreviewImg.src = ev.target.result;
            waImagePreview.classList.remove('d-none');
        };
        r.readAsDataURL(f);
    });
    waClearImg.addEventListener('click', function() {
        waImageInput.value = '';
        waImagePreview.classList.add('d-none');
        waPreviewImg.src = '';
    });
}

// Account filter
document.getElementById('accountFilter')?.addEventListener('change', function() {
    window.location.href = '{{ route("back.crm.whatsapp.chats") }}?account_id=' + this.value;
});
</script>
@endsection
