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
                <span class="text-dark">Telegram Chats</span>
            </span>
        </div>

        {{-- Bot Switcher --}}
        @if($bots->count() > 1)
        <div class="card card-flush mb-5">
            <div class="card-body py-4">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-semibold text-gray-600">Bot:</span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-flex btn-light-primary fw-bold" data-bs-toggle="dropdown">
                            <i class="ki-duotone ki-message-text-2 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            {{ $selectedBot ? $selectedBot->name : 'Pilih Bot' }}
                            @if($selectedBot && $selectedBot->username)
                                <span class="text-muted ms-1">(@{{ $selectedBot->username }})</span>
                            @endif
                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-start">
                            @foreach($bots as $bot)
                                <a class="dropdown-item {{ $selectedBot && $selectedBot->id == $bot->id ? 'active' : '' }}"
                                   href="{{ route('back.crm.telegram.chats', ['bot_id' => $bot->id]) }}">
                                    <span class="fw-semibold">{{ $bot->name }}</span>
                                    @if($bot->username)
                                        <span class="text-muted ms-1 fs-8">(@{{ $bot->username }})</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card card-flush">
            <div class="card-header align-items-center py-5">
                <div class="card-title">
                    <i class="ki-duotone ki-message-text-2 fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Percakapan
                    @if($selectedBot)
                        <span class="text-muted fs-7 ms-2">({{ $selectedBot->name }})</span>
                    @endif
                </div>
            </div>
            <div class="card-body pt-0 px-0">
                @forelse($chats as $chat)
                    <a href="{{ route('back.crm.telegram.chats.show', $chat->id) }}"
                       class="d-flex align-items-center px-6 py-4 border-bottom border-gray-200 text-dark text-hover-primary chat-row"
                       style="transition: background 0.15s;">
                        {{-- Avatar --}}
                        <div class="symbol symbol-45px me-4">
                            <div class="symbol-label bg-light-primary text-primary fw-bold fs-5">
                                @php
                                    $name = $chat->display_name;
                                    $initials = '';
                                    $parts = explode(' ', $name);
                                    foreach(array_slice($parts, 0, 2) as $p) {
                                        $initials .= mb_strtoupper(mb_substr(ltrim($p, '@#'), 0, 1));
                                    }
                                @endphp
                                {{ $initials ?: '?' }}
                            </div>
                        </div>
                        {{-- Chat Info --}}
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-gray-800 fs-6 text-truncate">{{ $chat->display_name }}</span>
                                @if($chat->last_message_at)
                                    <span class="text-muted fs-8 ms-2 flex-shrink-0">{{ $chat->last_message_at->diffForHumans(null, true) }}</span>
                                @endif
                            </div>
                            <div class="text-muted fs-7 text-truncate">
                                @php
                                    $lastMsg = $chat->messages->first();
                                @endphp
                                @if($lastMsg)
                                    @if($lastMsg->direction === 'out')
                                        <i class="ki-duotone ki-check fs-7 text-success me-1"><span class="path1"></span><span class="path2"></span></i>
                                    @endif
                                    {{ Str::limit($lastMsg->text ?? '[' . $lastMsg->type . ']', 50) }}
                                @else
                                    <em>Belum ada pesan</em>
                                @endif
                            </div>
                        </div>
                        {{-- Chat type badge --}}
                        @if($chat->chat_type !== 'private')
                            <span class="badge badge-light-info ms-3 flex-shrink-0">{{ ucfirst($chat->chat_type) }}</span>
                        @endif
                    </a>
                @empty
                    <div class="text-center text-muted py-15 px-6">
                        <i class="ki-duotone ki-message-text-2 fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="fs-6">Belum ada percakapan.</div>
                        <div class="fs-7 text-gray-400 mt-1">Percakapan akan muncul ketika pengguna mengirim pesan ke bot.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .chat-row:hover {
            background-color: var(--bs-gray-100) !important;
        }
    </style>
@endsection
