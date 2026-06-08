@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush" style="min-height: 75vh;">
            <div class="card-body p-0">
                <div class="d-flex tg-split" style="height: 75vh;">

                    {{-- LEFT: Chat List --}}
                    <div class="border-end tg-left {{ isset($activeChat) ? 'tg-hide-mobile' : '' }}" style="display: flex; flex-direction: column;">
                        {{-- Header --}}
                        <div class="px-5 py-4 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="fw-bold text-gray-800 mb-0 fs-5">
                                    <i class="ki-duotone ki-message-text-2 fs-3 me-2 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    Telegram
                                </h4>
                                <a href="{{ route('back.crm.telegram.bots') }}" class="btn btn-sm btn-icon btn-light" title="Bot Settings">
                                    <i class="ki-duotone ki-setting-2 fs-4"><span class="path1"></span><span class="path2"></span></i>
                                </a>
                            </div>
                            @if($bots->count() > 1)
                            <div class="mt-3">
                                <select class="form-select form-select-sm form-select-solid" id="botFilter">
                                    @foreach($bots as $bot)
                                        <option value="{{ $bot->id }}" {{ $selectedBot && $selectedBot->id == $bot->id ? 'selected' : '' }}>{{ $bot->name }} @if($bot->username) (@{{ $bot->username }}) @endif</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            @if($selectedBot)
                                <div class="mt-2">
                                    <span class="text-muted fs-8">Bot: <strong>{{ $selectedBot->name }}</strong></span>
                                </div>
                            @endif
                        </div>
                        {{-- Chat List --}}
                        <div style="overflow-y: auto; flex: 1;">
                            @forelse($chats as $chat)
                                <a href="{{ route('back.crm.telegram.chats', ['bot_id' => $selectedBot->id ?? '', 'chat_id' => $chat->id]) }}"
                                   class="d-flex align-items-center px-5 py-3 border-bottom border-gray-100 text-dark text-hover-primary tg-chat-row {{ isset($activeChat) && $activeChat->id == $chat->id ? 'bg-light-info' : '' }}"
                                   style="transition: background 0.15s;">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-info text-info fw-bold fs-6">
                                            @php
                                                $cname = $chat->display_name;
                                                $cinitials = '';
                                                $cparts = explode(' ', $cname);
                                                foreach(array_slice($cparts, 0, 2) as $p) {
                                                    $cinitials .= mb_strtoupper(mb_substr(ltrim($p, '@#'), 0, 1));
                                                }
                                            @endphp
                                            {{ $cinitials ?: '?' }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold text-gray-800 fs-7 text-truncate">{{ $chat->display_name }}</span>
                                            @if($chat->chat_type !== 'private')
                                                <span class="badge badge-light-info fs-9 ms-2 flex-shrink-0">{{ ucfirst($chat->chat_type) }}</span>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <span class="text-muted fs-8 text-truncate me-2">
                                                @php $lastMsg = $chat->messages->first(); @endphp
                                                @if($lastMsg)
                                                    @if($lastMsg->direction === 'out') <i class="ki-duotone ki-check fs-8 text-success"><span class="path1"></span><span class="path2"></span></i> @endif
                                                    {{ Str::limit($lastMsg->text ?? '[' . $lastMsg->type . ']', 35) }}
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
                                    <i class="ki-duotone ki-message-text-2 fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <div class="fs-7">Belum ada percakapan</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- RIGHT: Messages --}}
                    <div class="flex-grow-1 d-flex flex-column tg-right {{ !isset($activeChat) ? 'tg-hide-mobile' : '' }}" style="min-width: 0;">
                        @if(isset($activeChat))
                            {{-- Chat Header --}}
                            <div class="px-5 py-3 border-bottom d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('back.crm.telegram.chats', ['bot_id' => $selectedBot->id ?? '']) }}" class="btn btn-sm btn-icon btn-light me-2 tg-back-btn d-none">
                                        <i class="ki-duotone ki-arrow-left fs-3"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                    <div class="symbol symbol-35px me-3">
                                        <div class="symbol-label bg-light-info text-info fw-bold fs-6">
                                            @php
                                                $aName = $activeChat->display_name;
                                                $aInitials = '';
                                                $aParts = explode(' ', $aName);
                                                foreach(array_slice($aParts, 0, 2) as $p) {
                                                    $aInitials .= mb_strtoupper(mb_substr(ltrim($p, '@#'), 0, 1));
                                                }
                                            @endphp
                                            {{ $aInitials ?: '?' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-gray-800 fs-6">{{ $activeChat->display_name }}</div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($activeChat->username)
                                                <span class="text-muted fs-9">@{{ $activeChat->username }}</span>
                                            @endif
                                            @php
                                                $typeBadge = match($activeChat->chat_type) {
                                                    'private' => 'badge-light-info',
                                                    'group' => 'badge-light-warning',
                                                    'supergroup' => 'badge-light-success',
                                                    'channel' => 'badge-light-primary',
                                                    default => 'badge-light-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $typeBadge }} fs-9">{{ ucfirst($activeChat->chat_type) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-muted fs-8">via <strong>{{ $activeChat->bot->name }}</strong></span>
                            </div>

                            {{-- Messages --}}
                            <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 20px; background: #f5f8fa;">
                                @php $messages = $activeChat->messages->sortBy('created_at'); @endphp
                                @forelse($messages as $msg)
                                    @if($msg->direction === 'in')
                                        <div class="d-flex justify-content-start mb-4">
                                            <div style="max-width: 70%;">
                                                <div class="bg-white rounded-3 p-3 shadow-sm">
                                                    @if($msg->type === 'photo' && $msg->file_id)
                                                        <a href="{{ route('back.crm.telegram.file-proxy', [$activeChat->telegram_bot_id, $msg->file_id]) }}" target="_blank">
                                                            <img src="{{ route('back.crm.telegram.file-proxy', [$activeChat->telegram_bot_id, $msg->file_id]) }}" class="rounded-2 mb-2" style="max-width: 240px; max-height: 200px; display: block;" alt="Foto" loading="lazy">
                                                        </a>
                                                    @elseif($msg->type !== 'text')
                                                        <div class="mb-1">
                                                            @switch($msg->type)
                                                                @case('photo') <span class="badge badge-light-info fs-9">📷 Foto</span> @break
                                                                @case('document') <span class="badge badge-light-warning fs-9">📄 {{ $msg->file_name ?? 'Dokumen' }}</span> @break
                                                                @case('sticker') <span class="badge badge-light-success fs-9">🎭 Sticker</span> @break
                                                                @case('video') <span class="badge badge-light-primary fs-9">🎬 Video</span> @break
                                                                @case('voice') <span class="badge badge-light-danger fs-9">🎤 Voice</span> @break
                                                                @case('location') <span class="badge badge-light-success fs-9">📍 Lokasi</span> @break
                                                                @case('contact') <span class="badge badge-light-info fs-9">👤 Kontak</span> @break
                                                            @endswitch
                                                        </div>
                                                    @endif
                                                    @if($msg->text)
                                                        <div class="text-gray-800 fs-7">{!! nl2br(e($msg->text)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-muted fs-9 mt-1 ms-1">{{ $msg->sent_at ? $msg->sent_at->format('d M H:i') : $msg->created_at->format('d M H:i') }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-end mb-4">
                                            <div style="max-width: 70%;">
                                                <div class="bg-primary rounded-3 p-3">
                                                    @if($msg->type === 'photo' && $msg->file_id)
                                                        <a href="{{ route('back.crm.telegram.file-proxy', [$activeChat->telegram_bot_id, $msg->file_id]) }}" target="_blank">
                                                            <img src="{{ route('back.crm.telegram.file-proxy', [$activeChat->telegram_bot_id, $msg->file_id]) }}" class="rounded-2 mb-2" style="max-width: 240px; max-height: 200px; display: block;" alt="Foto" loading="lazy">
                                                        </a>
                                                    @endif
                                                    @if($msg->text)
                                                        <div class="text-white fs-7">{!! nl2br(e($msg->text)) !!}</div>
                                                    @endif
                                                </div>
                                                <div class="text-muted fs-9 mt-1 text-end me-1">
                                                    <i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i>
                                                    {{ $msg->sent_at ? $msg->sent_at->format('d M H:i') : $msg->created_at->format('d M H:i') }}
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
                            <div id="tgImagePreview" class="px-4 py-2 border-top d-none" style="background: #f0f2f5;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="position-relative">
                                        <img id="tgPreviewImg" src="" style="height: 50px; border-radius: 6px; object-fit: cover;" alt="">
                                        <button type="button" class="btn btn-icon btn-sm btn-active-light-danger position-absolute" style="top: -6px; right: -6px; width: 18px; height: 18px;" id="tgClearImage">
                                            <i class="ki-duotone ki-cross fs-8"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>
                                    <span class="text-muted fs-8" id="tgImageName"></span>
                                </div>
                            </div>

                            {{-- Reply --}}
                            <div class="px-5 py-3 border-top">
                                <form action="{{ route('back.crm.telegram.send-message') }}" method="POST" enctype="multipart/form-data" id="tgReplyForm">
                                    @csrf
                                    <input type="hidden" name="chat_id" value="{{ $activeChat->chat_id }}">
                                    <input type="hidden" name="bot_id" value="{{ $activeChat->telegram_bot_id }}">
                                    <input type="file" id="tgPhotoInput" name="photo" accept="image/jpeg,image/png,image/gif,image/webp" class="d-none">
                                    <div class="d-flex gap-2 align-items-end">
                                        <button type="button" class="btn btn-icon btn-light" style="min-height: 44px; min-width: 44px;" id="tgAttachBtn" title="Kirim Gambar">
                                            <i class="ki-duotone ki-picture fs-3"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                        <textarea name="text" class="form-control form-control-solid flex-grow-1" rows="1" placeholder="Ketik pesan..."></textarea>
                                        <button type="submit" class="btn btn-primary btn-icon" style="min-height: 44px; min-width: 44px;">
                                            <i class="ki-duotone ki-send fs-3"><span class="path1"></span><span class="path2"></span></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                                <i class="ki-duotone ki-message-text-2 fs-4x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
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
        .tg-chat-row:hover { background-color: var(--bs-gray-100) !important; }
        .tg-chat-row.bg-light-info { background-color: #f0f9ff !important; }
        .tg-left { width: 360px; min-width: 360px; }
        @media (max-width: 768px) {
            .tg-split { height: calc(100vh - 120px) !important; }
            .tg-left { width: 100%; min-width: 100%; }
            .tg-hide-mobile { display: none !important; }
            .tg-back-btn { display: inline-flex !important; }
        }
    </style>
@endsection

@section('scripts')
<script>
@if(isset($activeChat))
var chatDiv = document.getElementById('chatMessages');
var tgReplyForm = document.getElementById('tgReplyForm');
var tgTextarea = tgReplyForm.querySelector('textarea[name="text"]');
var tgPhotoInput = document.getElementById('tgPhotoInput');
var tgSendBtn = tgReplyForm.querySelector('button[type="submit"]');
var tgLastMsgId = {{ $activeChat->messages->max('id') ?? 0 }};
var tgChatDbId = {{ $activeChat->id }};
var tgFetchUrl = '{{ route("back.crm.telegram.fetch-messages", $activeChat->id) }}';
var tgSendUrl = '{{ route("back.crm.telegram.send-message") }}';
var tgCsrfToken = '{{ csrf_token() }}';
var tgPollingTimer = null;

// Scroll to bottom
function tgScrollBottom() {
    if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
}
tgScrollBottom();

// Build message bubble HTML
function tgBuildMsgHtml(msg) {
    var isOut = msg.direction === 'out';
    var html = '';

    if (isOut) {
        html += '<div class="d-flex justify-content-end mb-4">';
        html += '<div style="max-width: 70%;">';
        html += '<div class="bg-primary rounded-3 p-3">';
    } else {
        html += '<div class="d-flex justify-content-start mb-4">';
        html += '<div style="max-width: 70%;">';
        html += '<div class="bg-white rounded-3 p-3 shadow-sm">';
    }

    // Photo
    if (msg.type === 'photo' && msg.photo_url) {
        html += '<a href="' + msg.photo_url + '" target="_blank">';
        html += '<img src="' + msg.photo_url + '" class="rounded-2 mb-2" style="max-width: 240px; max-height: 200px; display: block;" alt="Foto" loading="lazy">';
        html += '</a>';
    } else if (msg.type !== 'text') {
        var badges = {photo:'📷 Foto', document:'📄 '+(msg.file_name||'Dokumen'), sticker:'🎭 Sticker', video:'🎬 Video', voice:'🎤 Voice', location:'📍 Lokasi', contact:'👤 Kontak'};
        html += '<div class="mb-1"><span class="badge badge-light-info fs-9">' + (badges[msg.type] || msg.type) + '</span></div>';
    }

    // Text
    if (msg.text) {
        var escaped = msg.text.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
        var textClass = isOut ? 'text-white' : 'text-gray-800';
        html += '<div class="' + textClass + ' fs-7">' + escaped + '</div>';
    }

    html += '</div>'; // close bg

    // Time
    if (isOut) {
        html += '<div class="text-muted fs-9 mt-1 text-end me-1">';
        html += '<i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i> ';
        html += msg.time + '</div>';
    } else {
        html += '<div class="text-muted fs-9 mt-1 ms-1">' + msg.time + '</div>';
    }

    html += '</div></div>';
    return html;
}

// Append message to chat
function tgAppendMsg(msg) {
    if (!chatDiv) return;
    chatDiv.insertAdjacentHTML('beforeend', tgBuildMsgHtml(msg));
    if (msg.id && msg.id > tgLastMsgId) tgLastMsgId = msg.id;
    tgScrollBottom();
}

// Image picker
document.getElementById('tgAttachBtn').addEventListener('click', function() {
    tgPhotoInput.click();
});
tgPhotoInput.addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { alert('Maksimal 5MB'); this.value = ''; return; }
    var reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('tgPreviewImg').src = ev.target.result;
        document.getElementById('tgImageName').textContent = file.name;
        document.getElementById('tgImagePreview').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});
document.getElementById('tgClearImage').addEventListener('click', function() {
    tgPhotoInput.value = '';
    document.getElementById('tgImagePreview').classList.add('d-none');
});

// AJAX Send
tgReplyForm.addEventListener('submit', function(e) {
    e.preventDefault();

    var text = tgTextarea.value.trim();
    var hasFile = tgPhotoInput.files.length > 0;

    if (!text && !hasFile) {
        alert('Kirim pesan teks atau gambar.');
        return;
    }

    // Disable button
    tgSendBtn.disabled = true;
    var origIcon = tgSendBtn.innerHTML;
    tgSendBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    var formData = new FormData(tgReplyForm);

    fetch(tgSendUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': tgCsrfToken,
            'Accept': 'application/json'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success && data.data) {
            tgAppendMsg(data.data);

            // Reset form
            tgTextarea.value = '';
            tgPhotoInput.value = '';
            document.getElementById('tgImagePreview').classList.add('d-none');
        } else {
            alert(data.message || 'Gagal mengirim pesan.');
        }
    })
    .catch(function(err) {
        console.error('Send error:', err);
        alert('Terjadi kesalahan saat mengirim.');
    })
    .finally(function() {
        tgSendBtn.disabled = false;
        tgSendBtn.innerHTML = origIcon;
        tgTextarea.focus();
    });
});

// Enter to send (Shift+Enter for newline)
tgTextarea.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        tgReplyForm.dispatchEvent(new Event('submit'));
    }
});

// Polling: fetch new messages every 5 seconds
function tgPollMessages() {
    fetch(tgFetchUrl + '?last_id=' + tgLastMsgId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success && data.messages && data.messages.length > 0) {
            data.messages.forEach(function(msg) {
                // Only append if not already shown
                if (msg.id > tgLastMsgId) {
                    tgAppendMsg(msg);
                }
            });
        }
    })
    .catch(function(err) {
        console.error('Poll error:', err);
    });
}

tgPollingTimer = setInterval(tgPollMessages, 5000);

// Clean up on page leave
window.addEventListener('beforeunload', function() {
    if (tgPollingTimer) clearInterval(tgPollingTimer);
});
@endif

document.getElementById('botFilter')?.addEventListener('change', function() {
    window.location.href = '{{ route("back.crm.telegram.chats") }}?bot_id='+this.value;
});
</script>
@endsection
