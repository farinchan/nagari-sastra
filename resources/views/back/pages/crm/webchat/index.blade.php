@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">

        {{-- Widget Switcher --}}
        @if($widgets->count() > 0)
        <div class="card card-flush mb-5">
            <div class="card-body py-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-semibold text-gray-600">Widget:</span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-flex btn-light-primary fw-bold" data-bs-toggle="dropdown">
                                <i class="ki-duotone ki-message-programming fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                {{ $selectedWidget ? $selectedWidget->name : 'Semua Widget' }}
                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-start">
                                <a class="dropdown-item {{ !$selectedWidget ? 'active' : '' }}"
                                   href="{{ route('back.crm.webchat.index') }}">
                                    <span class="fw-semibold">Semua Widget</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                @foreach($widgets as $w)
                                    <a class="dropdown-item {{ $selectedWidget && $selectedWidget->id == $w->id ? 'active' : '' }}"
                                       href="{{ route('back.crm.webchat.index', ['widget_id' => $w->id]) }}">
                                        <span class="fw-semibold">{{ $w->name }}</span>
                                        @if(!$w->is_active)
                                            <span class="badge badge-light-secondary ms-1 fs-9">Nonaktif</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <span class="badge badge-light-info fs-7">{{ $conversations->total() }} percakapan</span>
                </div>
            </div>
        </div>
        @endif

        <div class="card card-flush">
            <div class="card-header align-items-center py-5">
                <div class="card-title">
                    <i class="ki-duotone ki-message-programming fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    Webchat — Percakapan
                    @if($selectedWidget)
                        <span class="text-muted fs-7 ms-2">({{ $selectedWidget->name }})</span>
                    @endif
                </div>
            </div>
            <div class="card-body pt-0 px-0">
                @forelse($conversations as $conv)
                    <a href="{{ route('back.crm.webchat.show', $conv->id) }}"
                       class="d-flex align-items-center px-6 py-4 border-bottom border-gray-200 text-dark text-hover-primary chat-row"
                       style="transition: background 0.15s;">
                        {{-- Avatar --}}
                        <div class="symbol symbol-45px me-4">
                            <div class="symbol-label {{ $conv->unread_count > 0 ? 'bg-primary text-white' : 'bg-light-primary text-primary' }} fw-bold fs-5">
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
                        {{-- Chat Info --}}
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-gray-800 fs-6 text-truncate">
                                    {{ $conv->display_name }}
                                    @if($conv->visitor_email)
                                        <span class="text-muted fs-8 ms-1">({{ $conv->visitor_email }})</span>
                                    @endif
                                </span>
                                <div class="d-flex align-items-center gap-2 flex-shrink-0 ms-2">
                                    @if($conv->widget)
                                        <span class="badge badge-light-primary fs-9">{{ $conv->widget->name }}</span>
                                    @endif
                                    @if($conv->status === 'closed')
                                        <span class="badge badge-light-secondary fs-9">Ditutup</span>
                                    @endif
                                    @if($conv->unread_count > 0)
                                        <span class="badge badge-primary badge-circle fs-9">{{ $conv->unread_count }}</span>
                                    @endif
                                    @if($conv->last_message_at)
                                        <span class="text-muted fs-8">{{ $conv->last_message_at->diffForHumans(null, true) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-muted fs-7 text-truncate">
                                @php
                                    $lastMsg = $conv->messages->first();
                                @endphp
                                @if($lastMsg)
                                    @if($lastMsg->sender === 'admin')
                                        <i class="ki-duotone ki-check fs-7 text-success me-1"><span class="path1"></span><span class="path2"></span></i>
                                    @endif
                                    {{ Str::limit($lastMsg->message, 60) }}
                                @else
                                    <em>Belum ada pesan</em>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-muted py-15 px-6">
                        <i class="ki-duotone ki-message-programming fs-3x text-gray-300 mb-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        <div class="fs-6">Belum ada percakapan webchat.</div>
                        <div class="fs-7 text-gray-400 mt-1">Percakapan akan muncul ketika pengunjung mengirim pesan melalui widget chat di website.</div>
                    </div>
                @endforelse
            </div>
            @if($conversations->hasPages())
                <div class="card-footer d-flex justify-content-center">
                    {{ $conversations->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .chat-row:hover {
            background-color: var(--bs-gray-100) !important;
        }
    </style>
@endsection
