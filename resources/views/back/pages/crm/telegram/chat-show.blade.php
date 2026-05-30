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
                <a href="{{ route('back.crm.telegram.chats', ['bot_id' => $chat->telegram_bot_id]) }}" class="text-muted">Telegram Chats</a>
                <i class="ki-duotone ki-right fs-7 mx-1"></i>
                <span class="text-dark">{{ $chat->display_name }}</span>
            </span>
        </div>

        <div class="card card-flush">
            {{-- Chat Header --}}
            <div class="card-header align-items-center py-4 border-bottom">
                <div class="d-flex align-items-center">
                    <a href="{{ route('back.crm.telegram.chats', ['bot_id' => $chat->telegram_bot_id]) }}" class="btn btn-sm btn-icon btn-light me-3">
                        <i class="ki-duotone ki-arrow-left fs-3"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                    <div class="symbol symbol-40px me-3">
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
                    <div>
                        <div class="fw-bold text-gray-800 fs-6">{{ $chat->display_name }}</div>
                        <div class="d-flex align-items-center gap-2">
                            @if($chat->username)
                                <span class="text-muted fs-8">@{{ $chat->username }}</span>
                            @endif
                            @php
                                $typeBadge = match($chat->chat_type) {
                                    'private' => 'badge-light-info',
                                    'group' => 'badge-light-warning',
                                    'supergroup' => 'badge-light-success',
                                    'channel' => 'badge-light-primary',
                                    default => 'badge-light-secondary',
                                };
                            @endphp
                            <span class="badge {{ $typeBadge }} fs-9">{{ ucfirst($chat->chat_type) }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">via <strong>{{ $chat->bot->name }}</strong></span>
                </div>
            </div>

            {{-- Message Area --}}
            <div class="card-body" style="background-color: #f5f8fa;">
                <div id="chatMessages" style="max-height: 500px; overflow-y: auto; padding: 10px 0;">
                    @php
                        $messages = $chat->messages->sortBy('created_at');
                    @endphp

                    @forelse($messages as $msg)
                        @if($msg->direction === 'in')
                            {{-- Incoming --}}
                            <div class="d-flex justify-content-start mb-4">
                                <div style="max-width: 75%;">
                                    <div class="bg-white rounded-3 p-3 shadow-sm">
                                        @if($msg->type !== 'text')
                                            <div class="mb-1">
                                                @switch($msg->type)
                                                    @case('photo')
                                                        <span class="badge badge-light-info fs-9"><i class="ki-duotone ki-picture fs-7"><span class="path1"></span><span class="path2"></span></i> Foto</span>
                                                        @break
                                                    @case('document')
                                                        <span class="badge badge-light-warning fs-9"><i class="ki-duotone ki-file fs-7"><span class="path1"></span><span class="path2"></span></i> {{ $msg->file_name ?? 'Dokumen' }}</span>
                                                        @break
                                                    @case('sticker')
                                                        <span class="badge badge-light-success fs-9">🎭 Sticker</span>
                                                        @break
                                                    @case('video')
                                                        <span class="badge badge-light-primary fs-9"><i class="ki-duotone ki-movie fs-7"><span class="path1"></span><span class="path2"></span></i> Video</span>
                                                        @break
                                                    @case('voice')
                                                        <span class="badge badge-light-danger fs-9"><i class="ki-duotone ki-microphone fs-7"><span class="path1"></span><span class="path2"></span></i> Voice</span>
                                                        @break
                                                    @case('location')
                                                        <span class="badge badge-light-success fs-9"><i class="ki-duotone ki-geolocation fs-7"><span class="path1"></span><span class="path2"></span></i> Lokasi</span>
                                                        @break
                                                    @case('contact')
                                                        <span class="badge badge-light-info fs-9"><i class="ki-duotone ki-profile-circle fs-7"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Kontak</span>
                                                        @break
                                                @endswitch
                                            </div>
                                        @endif
                                        @if($msg->text)
                                            <div class="text-gray-800 fs-7">{!! nl2br(e($msg->text)) !!}</div>
                                        @endif
                                    </div>
                                    <div class="text-muted fs-9 mt-1 ms-1">
                                        {{ $msg->sent_at ? $msg->sent_at->format('d M H:i') : $msg->created_at->format('d M H:i') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Outgoing --}}
                            <div class="d-flex justify-content-end mb-4">
                                <div style="max-width: 75%;">
                                    <div class="bg-primary rounded-3 p-3">
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
                            <i class="ki-duotone ki-message-text-2 fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="fs-7">Belum ada pesan dalam percakapan ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Reply Form --}}
            <div class="card-footer py-4 border-top">
                <form action="{{ route('back.crm.telegram.send-message') }}" method="POST">
                    @csrf
                    <input type="hidden" name="chat_id" value="{{ $chat->chat_id }}">
                    <input type="hidden" name="bot_id" value="{{ $chat->telegram_bot_id }}">
                    <div class="d-flex gap-2 align-items-end">
                        <textarea name="text" class="form-control form-control-solid flex-grow-1" rows="2" placeholder="Ketik pesan..." required></textarea>
                        <button type="submit" class="btn btn-primary btn-icon" style="min-height: 50px; min-width: 50px;">
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
    // Auto-scroll to bottom
    var chatDiv = document.getElementById('chatMessages');
    if (chatDiv) {
        chatDiv.scrollTop = chatDiv.scrollHeight;
    }
</script>
@endsection
