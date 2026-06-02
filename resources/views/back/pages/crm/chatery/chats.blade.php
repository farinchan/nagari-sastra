@extends('back.app')
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card card-flush" style="min-height: 75vh;">
            <div class="card-body p-0">
                <div class="d-flex cy-split" style="height: 75vh;">

                    {{-- LEFT: Chat List --}}
                    <div class="border-end cy-left" id="cyLeftPanel" style="display: flex; flex-direction: column;">
                        {{-- Header --}}
                        <div class="px-5 py-4 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="fw-bold text-gray-800 mb-0 fs-5">
                                    <i class="ki-duotone ki-message-notif fs-3 me-2 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    Chatery Whatsapp
                                </h4>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-light" id="cyReloadChatsBtn" title="Reload Chat">
                                        <i class="ki-duotone ki-arrows-circle fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                    <a href="{{ route('back.crm.chatery.index') }}" class="btn btn-sm btn-icon btn-light" title="Kelola Session">
                                        <i class="ki-duotone ki-setting-2 fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </a>
                                </div>
                            </div>
                            @if(count($sessions) > 1)
                            <div class="mt-3">
                                <select class="form-select form-select-sm form-select-solid" id="sessionFilter">
                                    @foreach($sessions as $s)
                                        <option value="{{ $s->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        {{-- Chat List Container --}}
                        <div id="cyChatList" style="overflow-y: auto; flex: 1;">
                            <div id="cyChatListLoading" class="text-center py-10">
                                <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                                <div class="text-muted fs-8 mt-2">Memuat percakapan...</div>
                            </div>
                            <div id="cyChatListEmpty" class="text-center text-muted py-15 px-6 d-none">
                                <i class="ki-duotone ki-message-notif fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                <div class="fs-7">Belum ada percakapan</div>
                            </div>
                            <div id="cyChatListItems"></div>
                        </div>
                    </div>

                    {{-- RIGHT: Chat Messages --}}
                    <div class="flex-grow-1 d-flex flex-column cy-right" id="cyRightPanel">
                        {{-- Empty State --}}
                        <div id="cyEmptyState" class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                            <i class="ki-duotone ki-message-notif fs-4x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            <div class="fs-6 fw-semibold mb-1">WhatsApp Unofficial</div>
                            <div class="fs-7 text-gray-400">Pilih percakapan untuk melihat pesan</div>
                        </div>

                        {{-- Chat Area (hidden by default) --}}
                        <div id="cyChatArea" class="d-none d-flex flex-column" style="height: 100%;">
                            {{-- Chat Header --}}
                            <div class="px-5 py-3 border-bottom d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-sm btn-icon btn-light d-md-none" id="cyBackBtn">
                                        <i class="ki-duotone ki-left fs-3"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                    <div class="symbol symbol-35px" id="cyChatAvatar"></div>
                                    <div>
                                        <div class="fw-bold text-gray-800 fs-6" id="cyChatName"></div>
                                        <div class="text-muted fs-8" id="cyChatPhone"></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-icon btn-light" id="cyReloadMsgsBtn" title="Reload Pesan">
                                    <i class="ki-duotone ki-arrows-circle fs-4" id="cyReloadMsgsIcon"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="spinner-border spinner-border-sm d-none" id="cyReloadMsgsSpinner"></span>
                                </button>
                            </div>

                            {{-- Messages --}}
                            <div id="cyMessageBody" class="flex-grow-1 px-5 py-4" style="overflow-y: auto; background: #f5f5f5;">
                                <div id="cyMsgLoadMore" class="text-center py-3 d-none">
                                    <button type="button" class="btn btn-sm btn-light" id="cyLoadOlderBtn">
                                        <span class="spinner-border spinner-border-sm d-none me-1" id="cyLoadOlderSpinner"></span>
                                        Muat pesan lama
                                    </button>
                                </div>
                                <div id="cyMsgLoading" class="text-center py-10">
                                    <div class="spinner-border spinner-border-sm text-success"></div>
                                    <div class="text-muted fs-8 mt-2">Memuat pesan...</div>
                                </div>
                                <div id="cyMsgList"></div>
                            </div>

                            {{-- Reply Form --}}
                            <div class="px-5 py-3 border-top bg-white">
                                <div class="d-flex gap-2 align-items-end">
                                    <div class="flex-grow-1">
                                        <textarea id="cyMsgInput" class="form-control form-control-solid" rows="1" placeholder="Ketik pesan..." style="resize: none; min-height: 38px; max-height: 76px;"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success btn-icon flex-shrink-0" id="cySendBtn" style="width: 38px; height: 38px;">
                                        <i class="ki-duotone ki-send fs-4" id="cySendIcon"><span class="path1"></span><span class="path2"></span></i>
                                        <span class="spinner-border spinner-border-sm d-none" id="cySendSpinner"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Image Lightbox --}}
    <div class="modal fade" id="cyLightboxModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="text-end mb-2">
                    <button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i></button>
                </div>
                <img id="cyLightboxImg" src="" class="rounded-3" style="max-height: 80vh; object-fit: contain; width: 100%;">
            </div>
        </div>
    </div>

    <style>
        .cy-chat-row { transition: background 0.15s; cursor: pointer; text-decoration: none !important; }
        .cy-chat-row:hover { background-color: var(--bs-gray-100) !important; }
        .cy-chat-row.active { background-color: #e8f5e9 !important; }
        .cy-left { width: 360px; min-width: 360px; }
        .cy-msg-in .cy-bubble { background: #fff; border-radius: 4px 12px 12px 12px; }
        .cy-msg-out .cy-bubble { background: var(--bs-success); border-radius: 12px 4px 12px 12px; color: #fff; }
        .cy-msg-out .cy-bubble a { color: #d4edda; }
        .cy-msg-out .cy-bubble .cy-media-badge { background: rgba(255,255,255,0.2) !important; color: #fff !important; }
        .cy-media-img { max-width: 260px; max-height: 220px; display: block; cursor: pointer; transition: opacity 0.2s; }
        .cy-media-img:hover { opacity: 0.85; }
        .cy-media-video { max-width: 280px; max-height: 220px; border-radius: 8px; }
        .cy-media-audio { width: 250px; height: 36px; }
        .cy-media-doc { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 8px; text-decoration: none; }
        .cy-msg-in .cy-media-doc { background: #f0f0f0; color: #333; }
        .cy-msg-out .cy-media-doc { background: rgba(255,255,255,0.15); color: #fff; }
        .cy-quoted { border-left: 3px solid var(--bs-info); padding-left: 8px; margin-bottom: 6px; font-size: 12px; opacity: 0.8; }
        .symbol-label img, img.symbol-label { border-radius: 50%; width: 100%; height: 100%; object-fit: cover; }
        .cy-reload-spin { animation: cy-spin 0.8s linear infinite; }
        @keyframes cy-spin { to { transform: rotate(360deg); } }
        @media (max-width: 768px) {
            .cy-split { height: calc(100vh - 120px) !important; }
            .cy-left { width: 100%; min-width: 100%; }
            .cy-left.cy-hide-mobile { display: none !important; }
            .cy-right.cy-show-mobile { display: flex !important; }
        }
    </style>
@endsection

@section('scripts')
<script>
(function() {
    const CSRF = '{{ csrf_token() }}';
    const URL_API_CHATS = '{{ route("back.crm.chatery.api.chats") }}';
    const URL_API_MSGS = '{{ route("back.crm.chatery.api.messages") }}';
    const URL_SEND = '{{ route("back.crm.chatery.chats.send") }}';
    const SESSIONS = @json($sessions);
    const SESSION_MAP = {};
    SESSIONS.forEach(s => { SESSION_MAP[s.id] = s.api_url ? s.api_url.replace(/\/+$/, '') : ''; });

    let S = {
        sessionId: SESSIONS.length ? SESSIONS[0].id : null,
        chats: [],
        activeChatId: null,
        activeChat: null,
        msgCursor: null,
        msgHasMore: false,
        knownMsgIds: new Set(),
        loadingChats: false,
        loadingMsgs: false,
        sending: false,
        refreshTimer: null,
    };

    // DOM refs
    const $ = id => document.getElementById(id);
    const $left = $('cyLeftPanel'), $chatItems = $('cyChatListItems'),
          $chatLoading = $('cyChatListLoading'), $chatEmpty = $('cyChatListEmpty'),
          $empty = $('cyEmptyState'), $area = $('cyChatArea'),
          $chatName = $('cyChatName'), $chatPhone = $('cyChatPhone'), $chatAvatar = $('cyChatAvatar'),
          $msgBody = $('cyMessageBody'), $msgList = $('cyMsgList'),
          $msgLoading = $('cyMsgLoading'), $msgLoadMore = $('cyMsgLoadMore'),
          $loadOlderBtn = $('cyLoadOlderBtn'), $loadOlderSpin = $('cyLoadOlderSpinner'),
          $input = $('cyMsgInput'), $sendBtn = $('cySendBtn'),
          $sendIcon = $('cySendIcon'), $sendSpin = $('cySendSpinner'),
          $reloadChats = $('cyReloadChatsBtn'), $reloadMsgs = $('cyReloadMsgsBtn'),
          $reloadMsgsIcon = $('cyReloadMsgsIcon'), $reloadMsgsSpin = $('cyReloadMsgsSpinner'),
          $sessionFilter = $('sessionFilter'), $backBtn = $('cyBackBtn');

    // ==========================================
    // HELPERS
    // ==========================================
    function esc(s) { if (!s) return ''; const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
    function nl2br(s) { return esc(s).replace(/\n/g, '<br>'); }

    function timeAgo(ts) {
        const d = Math.floor(Date.now()/1000) - ts;
        if (d < 60) return 'baru';
        if (d < 3600) return Math.floor(d/60) + ' mnt';
        if (d < 86400) return Math.floor(d/3600) + ' jam';
        if (d < 604800) return Math.floor(d/86400) + ' hari';
        const dt = new Date(ts*1000);
        return dt.getDate() + '/' + (dt.getMonth()+1);
    }

    function fmtTime(ts) {
        const d = new Date(ts*1000);
        const M = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return d.getDate() + ' ' + M[d.getMonth()] + ' ' + String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
    }

    function initials(n) { return (n||'').split(' ').slice(0,2).map(p=>p.charAt(0).toUpperCase()).join('') || '?'; }

    function mediaUrl(url) {
        if (!url) return null;
        if (url.startsWith('http://') || url.startsWith('https://')) return url;
        const base = SESSION_MAP[S.sessionId] || '';
        return base + (url.startsWith('/') ? '' : '/') + url;
    }

    async function api(url, p) {
        const r = await fetch(url + '?' + new URLSearchParams(p), { headers: { Accept: 'application/json' } });
        return r.json();
    }

    async function post(url, d) {
        const r = await fetch(url, {
            method: 'POST', headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(d),
        });
        return r.json();
    }

    function openLightbox(src) {
        $('cyLightboxImg').src = src;
        new bootstrap.Modal($('cyLightboxModal')).show();
    }

    // ==========================================
    // RENDER CHAT LIST
    // ==========================================
    function renderChats() {
        if (!S.chats.length) { $chatItems.innerHTML = ''; $chatEmpty.classList.remove('d-none'); return; }
        $chatEmpty.classList.add('d-none');
        let h = '';
        S.chats.forEach(c => {
            const act = S.activeChatId === c.id;
            const name = esc(c.name || c.phone || c.id);
            const msg = esc((c.lastMessage||'').substring(0,35));
            const time = c.lastMessageTimestamp ? timeAgo(c.lastMessageTimestamp) : '';
            const ur = c.unreadCount || 0;
            const avCls = ur > 0 ? 'bg-success text-white' : 'bg-light-success text-success';
            const av = c.profilePicture
                ? '<img src="'+esc(c.profilePicture)+'" class="symbol-label" style="object-fit:cover">'
                : '<div class="symbol-label '+avCls+' fw-bold fs-6">'+initials(c.name||c.phone)+'</div>';

            h += '<div class="d-flex align-items-center px-5 py-3 border-bottom border-gray-100 cy-chat-row'+(act?' active':'')+'" data-id="'+esc(c.id)+'">'
                +'<div class="symbol symbol-40px me-3">'+av+'</div>'
                +'<div class="d-flex flex-column flex-grow-1 overflow-hidden">'
                +'<div class="d-flex justify-content-between align-items-center">'
                +'<span class="fw-semibold text-gray-800 fs-7 text-truncate">'+name+'</span>'
                +'<div class="d-flex align-items-center gap-1 flex-shrink-0 ms-2">'
                +(c.isGroup?'<span class="badge badge-light-info fs-9">Grup</span>':'')
                +(ur>0?'<span class="badge badge-success badge-circle badge-sm">'+ur+'</span>':'')
                +'</div></div>'
                +'<div class="d-flex justify-content-between align-items-center mt-1">'
                +'<span class="text-muted fs-8 text-truncate me-2">'+(msg||'<em>Belum ada pesan</em>')+'</span>'
                +(time?'<span class="text-muted fs-9 flex-shrink-0">'+time+'</span>':'')
                +'</div></div></div>';
        });
        $chatItems.innerHTML = h;
        $chatItems.querySelectorAll('.cy-chat-row').forEach(el => {
            el.addEventListener('click', () => openChat(el.dataset.id));
        });
    }

    // ==========================================
    // RENDER MESSAGE
    // ==========================================
    function renderMsg(msg) {
        const out = msg.fromMe;
        const cls = out ? 'cy-msg-out' : 'cy-msg-in';
        const align = out ? 'justify-content-end' : 'justify-content-start';
        const time = msg.timestamp ? fmtTime(msg.timestamp) : '';
        let body = '';

        // Group sender
        if (!out && msg.isGroup && msg.senderName) {
            body += '<div class="text-primary fs-9 fw-bold mb-1">'+esc(msg.senderName)+'</div>';
        }

        // Quoted
        if (msg.quotedMessage) {
            const qt = msg.quotedMessage.content || msg.quotedMessage.caption || '[Media]';
            body += '<div class="cy-quoted">'+esc(qt.substring(0,100))+'</div>';
        }

        // Media
        const t = msg.type || 'text';
        if (t === 'image') {
            const imgSrc = mediaUrl(msg.mediaUrl);
            if (imgSrc) {
                body += '<img src="'+esc(imgSrc)+'" class="cy-media-img rounded-2 mb-2" loading="lazy" onclick="window._cyLightbox(this.src)">';
            } else {
                body += '<div class="cy-media-badge badge badge-light-success fs-9 mb-1">📷 Gambar</div>';
            }
        } else if (t === 'video') {
            const vidSrc = mediaUrl(msg.mediaUrl);
            if (vidSrc) {
                body += '<video src="'+esc(vidSrc)+'" class="cy-media-video mb-2" controls preload="metadata"></video>';
            } else {
                body += '<div class="cy-media-badge badge badge-light-info fs-9 mb-1">🎬 Video</div>';
            }
        } else if (t === 'audio' || t === 'ptt') {
            const audSrc = mediaUrl(msg.mediaUrl);
            if (audSrc) {
                body += '<audio src="'+esc(audSrc)+'" class="cy-media-audio mb-2" controls preload="metadata"></audio>';
            } else {
                body += '<div class="cy-media-badge badge badge-light-danger fs-9 mb-1">🎤 Audio</div>';
            }
        } else if (t === 'document') {
            const fn = msg.filename || 'Dokumen';
            const docSrc = mediaUrl(msg.mediaUrl);
            if (docSrc) {
                body += '<a href="'+esc(docSrc)+'" target="_blank" class="cy-media-doc mb-2">'
                    +'<i class="ki-duotone ki-file fs-2"><span class="path1"></span><span class="path2"></span></i>'
                    +'<div><div class="fs-8 fw-semibold">'+esc(fn)+'</div>'
                    +(msg.mimetype?'<div class="fs-9 opacity-75">'+esc(msg.mimetype)+'</div>':'')
                    +'</div></a>';
            } else {
                body += '<div class="cy-media-badge badge badge-light-warning fs-9 mb-1">📄 '+esc(fn)+'</div>';
            }
        } else if (t === 'sticker') {
            const stkSrc = mediaUrl(msg.mediaUrl);
            if (stkSrc) {
                body += '<img src="'+esc(stkSrc)+'" class="rounded-2 mb-2" style="max-width:120px;max-height:120px;" loading="lazy">';
            } else {
                body += '<div class="cy-media-badge badge badge-light fs-9 mb-1">🎨 Sticker</div>';
            }
        } else if (t === 'location') {
            body += '<div class="cy-media-badge badge badge-light-success fs-9 mb-1">📍 Lokasi</div>';
        } else if (t === 'contact' || t === 'vcard') {
            body += '<div class="cy-media-badge badge badge-light-primary fs-9 mb-1">👤 Kontak</div>';
        }

        // Text content
        if (msg.content) body += '<div class="fs-7">'+nl2br(msg.content)+'</div>';
        if (msg.caption) body += '<div class="fs-7 mt-1">'+nl2br(msg.caption)+'</div>';

        // Empty body fallback
        if (!body.trim()) body = '<div class="fs-8 text-muted">['+esc(t)+']</div>';

        // Time
        let tm = '<div class="text-muted fs-9 mt-1 '+(out?'text-end me-1':'ms-1')+'">';
        if (out) tm += '<i class="ki-duotone ki-check fs-9 text-success"><span class="path1"></span><span class="path2"></span></i> ';
        tm += time + '</div>';

        return '<div class="d-flex '+align+' mb-3 '+cls+'" data-msg-id="'+esc(msg.id||'')+'">'
            +'<div style="max-width:75%;"><div class="cy-bubble p-3 shadow-sm">'+body+'</div>'+tm+'</div></div>';
    }

    // ==========================================
    // LOAD CHATS
    // ==========================================
    async function loadChats(silent) {
        if (!S.sessionId || S.loadingChats) return;
        S.loadingChats = true;
        if (!silent) { $chatLoading.classList.remove('d-none'); $chatEmpty.classList.add('d-none'); $chatItems.innerHTML = ''; }
        $reloadChats.querySelector('i')?.classList.add('cy-reload-spin');

        try {
            const r = await api(URL_API_CHATS, { session_id: S.sessionId });
            S.chats = (r.success && r.data && r.data.chats) ? r.data.chats : [];
        } catch(e) { S.chats = []; }

        $chatLoading.classList.add('d-none');
        $reloadChats.querySelector('i')?.classList.remove('cy-reload-spin');
        renderChats();
        S.loadingChats = false;
    }

    // ==========================================
    // OPEN CHAT
    // ==========================================
    async function openChat(chatId) {
        S.activeChatId = chatId;
        S.activeChat = S.chats.find(c => c.id === chatId) || { id: chatId, name: chatId };
        S.msgCursor = null; S.msgHasMore = false; S.knownMsgIds = new Set();

        renderChats();
        $empty.classList.add('d-none');
        $area.classList.remove('d-none');
        $msgList.innerHTML = '';
        $msgLoadMore.classList.add('d-none');
        $msgLoading.classList.remove('d-none');
        $left.classList.add('cy-hide-mobile');

        // Header
        const c = S.activeChat;
        $chatName.textContent = c.name || c.phone || c.id;
        $chatPhone.textContent = c.phone ? '+'+c.phone : (c.isGroup ? 'Grup' : '');
        $chatAvatar.innerHTML = c.profilePicture
            ? '<img src="'+esc(c.profilePicture)+'" class="symbol-label" style="object-fit:cover">'
            : '<div class="symbol-label bg-light-success text-success fw-bold fs-6">'+initials(c.name||c.phone)+'</div>';

        await loadMessages(false);
        $input.focus();
        startRefresh();
    }

    // ==========================================
    // LOAD MESSAGES
    // ==========================================
    async function loadMessages(older) {
        if (!S.sessionId || !S.activeChatId || S.loadingMsgs) return;
        S.loadingMsgs = true;
        if (older) $loadOlderSpin.classList.remove('d-none');

        try {
            const p = { session_id: S.sessionId, chat_id: S.activeChatId, limit: 50 };
            if (older && S.msgCursor) p.cursor = S.msgCursor;
            const r = await api(URL_API_MSGS, p);

            if (r.success && r.data && r.data.messages) {
                S.msgCursor = r.data.cursor || null;
                S.msgHasMore = r.data.hasMore || false;
                const msgs = r.data.messages.slice().reverse(); // oldest first

                if (older) {
                    const prev = $msgBody.scrollHeight;
                    const fresh = msgs.filter(m => m.id && !S.knownMsgIds.has(m.id));
                    fresh.forEach(m => S.knownMsgIds.add(m.id));
                    $msgList.insertAdjacentHTML('afterbegin', fresh.map(renderMsg).join(''));
                    $msgBody.scrollTop = $msgBody.scrollHeight - prev;
                } else {
                    msgs.forEach(m => { if(m.id) S.knownMsgIds.add(m.id); });
                    $msgList.innerHTML = msgs.map(renderMsg).join('');
                    $msgBody.scrollTop = $msgBody.scrollHeight;
                }
                $msgLoadMore.classList.toggle('d-none', !S.msgHasMore);
            }
        } catch(e) { console.error('loadMessages:', e); }

        $msgLoading.classList.add('d-none');
        $loadOlderSpin.classList.add('d-none');
        S.loadingMsgs = false;
    }

    // ==========================================
    // RELOAD MESSAGES (manual / auto)
    // ==========================================
    async function reloadMessages(showSpin) {
        if (!S.sessionId || !S.activeChatId || S.loadingMsgs || S.sending) return;
        if (showSpin) { $reloadMsgsIcon.classList.add('d-none'); $reloadMsgsSpin.classList.remove('d-none'); }

        try {
            const r = await api(URL_API_MSGS, { session_id: S.sessionId, chat_id: S.activeChatId, limit: 20 });
            if (r.success && r.data && r.data.messages) {
                const msgs = r.data.messages.slice().reverse();
                const fresh = msgs.filter(m => m.id && !S.knownMsgIds.has(m.id));
                if (fresh.length) {
                    fresh.forEach(m => S.knownMsgIds.add(m.id));
                    const atBottom = ($msgBody.scrollHeight - $msgBody.scrollTop - $msgBody.clientHeight) < 80;
                    $msgList.insertAdjacentHTML('beforeend', fresh.map(renderMsg).join(''));
                    if (atBottom) $msgBody.scrollTop = $msgBody.scrollHeight;
                }
            }
        } catch(e) {}

        if (showSpin) { $reloadMsgsIcon.classList.remove('d-none'); $reloadMsgsSpin.classList.add('d-none'); }
    }

    // ==========================================
    // SEND MESSAGE
    // ==========================================
    async function sendMessage() {
        const txt = $input.value.trim();
        if (!txt || S.sending || !S.activeChatId) return;
        S.sending = true;
        $sendIcon.classList.add('d-none'); $sendSpin.classList.remove('d-none'); $sendBtn.disabled = true;

        try {
            const r = await post(URL_SEND, { session_id: S.sessionId, chat_id: S.activeChatId, message: txt });
            if (r.success !== false) {
                $input.value = ''; $input.style.height = '38px';
                const now = Math.floor(Date.now()/1000);
                const fakeId = 'local_'+now+'_'+Math.random().toString(36).substr(2,5);
                S.knownMsgIds.add(fakeId);
                $msgList.insertAdjacentHTML('beforeend', renderMsg({ id: fakeId, fromMe: true, type: 'text', content: txt, timestamp: now, isGroup: false }));
                $msgBody.scrollTop = $msgBody.scrollHeight;
            } else {
                alert(r.message || 'Gagal mengirim pesan');
            }
        } catch(e) { alert('Gagal mengirim pesan'); }

        S.sending = false;
        $sendIcon.classList.remove('d-none'); $sendSpin.classList.add('d-none'); $sendBtn.disabled = false;
        $input.focus();
    }

    // ==========================================
    // AUTO-REFRESH (10 detik)
    // ==========================================
    function startRefresh() {
        stopRefresh();
        S.refreshTimer = setInterval(() => {
            reloadMessages(false);
        }, 10000);
    }

    function stopRefresh() {
        if (S.refreshTimer) { clearInterval(S.refreshTimer); S.refreshTimer = null; }
    }

    // ==========================================
    // EVENTS
    // ==========================================
    $sendBtn.addEventListener('click', sendMessage);
    $input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
    $input.addEventListener('input', function() { this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 76) + 'px'; });

    $backBtn.addEventListener('click', () => {
        S.activeChatId = null; S.activeChat = null; stopRefresh();
        $left.classList.remove('cy-hide-mobile'); $area.classList.add('d-none'); $empty.classList.remove('d-none');
        renderChats();
    });

    $loadOlderBtn.addEventListener('click', () => loadMessages(true));

    // Scroll lazy load
    $msgBody.addEventListener('scroll', function() {
        if (this.scrollTop < 50 && S.msgHasMore && !S.loadingMsgs) loadMessages(true);
    });

    // Reload buttons
    $reloadChats.addEventListener('click', () => loadChats(false));
    $reloadMsgs.addEventListener('click', () => reloadMessages(true));

    // Session filter
    if ($sessionFilter) {
        $sessionFilter.addEventListener('change', function() {
            S.sessionId = parseInt(this.value);
            S.activeChatId = null; S.activeChat = null; stopRefresh();
            $area.classList.add('d-none'); $empty.classList.remove('d-none');
            loadChats(false);
        });
    }

    // Lightbox global
    window._cyLightbox = openLightbox;

    // INIT
    if (S.sessionId) loadChats(false);
})();
</script>
@endsection
