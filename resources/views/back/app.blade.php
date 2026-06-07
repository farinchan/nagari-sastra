<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

@php
    $setting_web = \App\Models\SettingWebsite::first();
@endphp

<head>
    <base href="" />
    <title>{{ $setting_web->name }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ Str::limit(strip_tags($setting_web->about), 200, '...') }}" />
    <meta name="keywords"
        content="
            {{ $setting_web->name }}, Admin, OJS, Journal, jurnal, jurnal online, jurnal ilmiah, jurnal internasional, jurnal nasional, jurnal terakreditasi, jurnal terindeks scopus, jurnal terindeks sinta, jurnal terindeks google scholar, jurnal terindeks garuda, jurnal terindeks DOAJ, jurnal terindeks crossref, jurnal terindeks issn, jurnal terindeks e-issn, jurnal terindeks p-issn" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $setting_web->name }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ $setting_web->name }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="shortcut icon" href="{{ $setting_web->favicon }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('back/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('back/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('back/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('back/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="aside-enabled">
    @include('back/partials/theme-mode/_init')
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            @include('back/layout/aside/_base')
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('back/layout/header/_base')
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid " id="kt_content">
                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        @yield('content')
                    </div>
                    <!--end::Post-->
                </div>
                <!--end::Content-->
                @include('back/layout/_footer')
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    {{-- @include('back/partials/_drawers') --}}
    <!--end::Main-->
    @include('back/partials/_scrolltop')
    <!--begin::Modals-->
    {{-- @include('back/partials/modals/_invite-friends')
    @include('back/partials/modals/users-search/_main') --}}
    <!--end::Modals-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('back/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('back/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('back/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('back/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{ asset('back/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('back/js/custom/widgets.js') }}"></script>
    {{-- <script src="{{ asset("back/js/custom/apps/chat/chat.js")}}"></script>
    <script src="{{ asset("back/js/custom/utilities/modals/users-search.js")}}"></script> --}}
    @include('sweetalert::alert')

    <script>
        document.addEventListener('click', function(e) {
            // Cek apakah yang diklik itu <a> dengan class 'btn-loading'
            if (e.target.matches('a.btn-loading')) {

                Swal.fire({
                    title: 'Loading...',
                    text: 'Sedang memproses data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    </script>

    @yield('scripts')
    <!--end::Custom Javascript-->

    {{-- CRM Real-time Notifications (Reverb + Echo) --}}
    @if(auth()->check() && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('marketing')))
    <style>
        #crmToastBox{position:fixed;bottom:24px;right:24px;z-index:999999;display:flex;flex-direction:column-reverse;gap:10px;pointer-events:none;max-width:380px;width:100%}
        .crm-toast{pointer-events:auto;background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.15),0 0 0 1px rgba(0,0,0,.04);padding:0;overflow:hidden;animation:crmToastIn .4s cubic-bezier(.22,1,.36,1);cursor:pointer;transition:opacity .3s,transform .3s}
        .crm-toast.out{opacity:0;transform:translateX(120%)}
        .crm-toast-bar{height:3px;width:100%;animation:crmBar 8s linear forwards}
        .crm-toast-body{display:flex;align-items:flex-start;gap:12px;padding:14px 16px}
        .crm-toast-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .crm-toast-icon.tg{background:rgba(6,182,212,.12)}
        .crm-toast-icon.wc{background:rgba(99,102,241,.12)}
        .crm-toast-content{flex:1;overflow:hidden;min-width:0}
        .crm-toast-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:2px}
        .crm-toast-name{font-weight:600;font-size:13px;color:#1f2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .crm-toast-label{font-size:10px;font-weight:600;padding:2px 7px;border-radius:6px;flex-shrink:0;margin-left:8px}
        .crm-toast-label.tg{background:rgba(6,182,212,.12);color:#0891b2}
        .crm-toast-label.wc{background:rgba(99,102,241,.12);color:#6366f1}
        .crm-toast-msg{font-size:12.5px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.4}
        .crm-toast-time{font-size:11px;color:#9ca3af;margin-top:2px}
        .crm-toast-close{position:absolute;top:8px;right:10px;background:none;border:none;cursor:pointer;padding:2px;opacity:.35;transition:opacity .15s;font-size:16px;line-height:1;color:#6b7280}
        .crm-toast-close:hover{opacity:.8}
        @keyframes crmToastIn{from{opacity:0;transform:translateX(100%)}to{opacity:1;transform:translateX(0)}}
        @keyframes crmBar{from{width:100%}to{width:0}}
        @media(max-width:480px){#crmToastBox{right:12px;left:12px;max-width:100%}}
    </style>
    <div id="crmToastBox"></div>
    <audio id="crmNotifAudio" preload="auto" src="{{ asset('back/audio/notification.wav') }}"></audio>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script>
    (function(){
        var notifCount = 0;
        var maxItems = 20;
        var badge = document.getElementById('crmNotifBadge');
        var list = document.getElementById('crmNotifList');
        var empty = document.getElementById('crmNotifEmpty');
        var countEl = document.getElementById('crmNotifCount');
        var audio = document.getElementById('crmNotifAudio');
        var toastBox = document.getElementById('crmToastBox');

        // Initialize Echo with Reverb
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ config("reverb.apps.apps.0.key", env("REVERB_APP_KEY")) }}',
            wsHost: '{{ config("reverb.servers.reverb.host", env("REVERB_HOST", "localhost")) }}',
            wsPort: '{{ config("reverb.servers.reverb.port", env("REVERB_PORT", 8080)) }}',
            wssPort: '{{ config("reverb.servers.reverb.port", env("REVERB_PORT", 8080)) }}',
            forceTLS: {{ config("reverb.servers.reverb.options.tls.local_cert") ? 'true' : 'false' }},
            enabledTransports: ['ws', 'wss'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        function updateBadge() {
            if (notifCount > 0) {
                badge.textContent = notifCount > 9 ? '9+' : notifCount;
                badge.classList.remove('d-none');
                countEl.textContent = notifCount + ' notifikasi baru';
            } else {
                badge.classList.add('d-none');
                countEl.textContent = 'Tidak ada notifikasi baru';
            }
        }

        // ---- Floating toast ----
        function showToast(data) {
            var isTg = data.source === 'telegram';
            var label = isTg ? 'Telegram' : 'Webchat';
            var cls = isTg ? 'tg' : 'wc';
            var barColor = isTg ? '#06b6d4' : '#6366f1';

            var iconSvg = isTg
                ? '<i class="ki-duotone ki-send fs-2" style="color:#0891b2"><span class="path1"></span><span class="path2"></span></i>'
                : '<i class="ki-duotone ki-message-programming fs-2" style="color:#6366f1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>';

            var toast = document.createElement('div');
            toast.className = 'crm-toast';
            toast.innerHTML =
                '<div class="crm-toast-bar" style="background:' + barColor + '"></div>' +
                '<button type="button" class="crm-toast-close">&times;</button>' +
                '<div class="crm-toast-body">' +
                    '<div class="crm-toast-icon ' + cls + '">' + iconSvg + '</div>' +
                    '<div class="crm-toast-content">' +
                        '<div class="crm-toast-head">' +
                            '<span class="crm-toast-name">' + _esc(data.senderName) + '</span>' +
                            '<span class="crm-toast-label ' + cls + '">' + label + '</span>' +
                        '</div>' +
                        '<div class="crm-toast-msg">' + _esc(data.message) + '</div>' +
                        '<div class="crm-toast-time">' + data.time + ' · Baru saja</div>' +
                    '</div>' +
                '</div>';

            function dismiss() {
                toast.classList.add('out');
                setTimeout(function() { if (toast.parentNode) toast.parentNode.removeChild(toast); }, 350);
            }

            toast.querySelector('.crm-toast-close').addEventListener('click', function(e) {
                e.stopPropagation();
                dismiss();
            });

            toast.addEventListener('click', function() {
                window.location.href = data.url;
            });

            toastBox.appendChild(toast);

            // Keep max 4 toasts
            while (toastBox.children.length > 4) {
                toastBox.removeChild(toastBox.firstChild);
            }

            // Auto dismiss after 8s
            setTimeout(dismiss, 8000);
        }

        // ---- Dropdown list ----
        function addNotif(data) {
            if (empty) empty.style.display = 'none';

            var icon = data.source === 'telegram'
                ? '<i class="ki-duotone ki-send fs-2 text-info"><span class="path1"></span><span class="path2"></span></i>'
                : '<i class="ki-duotone ki-message-programming fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>';

            var label = data.source === 'telegram' ? 'Telegram' : 'Webchat';

            var item = document.createElement('a');
            item.href = data.url;
            item.className = 'd-flex align-items-start gap-3 px-4 py-3 border-bottom border-gray-100 text-decoration-none bg-hover-light-primary';
            item.innerHTML =
                '<div class="symbol symbol-35px mt-1">' +
                    '<div class="symbol-label bg-light-' + (data.source === 'telegram' ? 'info' : 'primary') + '">' + icon + '</div>' +
                '</div>' +
                '<div class="flex-grow-1 overflow-hidden">' +
                    '<div class="d-flex justify-content-between align-items-center">' +
                        '<span class="fw-semibold text-gray-800 fs-7 text-truncate">' + _esc(data.senderName) + '</span>' +
                        '<span class="badge badge-light-' + (data.source === 'telegram' ? 'info' : 'primary') + ' fs-9">' + label + '</span>' +
                    '</div>' +
                    '<div class="text-muted fs-8 text-truncate mt-1">' + _esc(data.message) + '</div>' +
                    '<div class="text-gray-400 fs-9 mt-1">' + data.time + '</div>' +
                '</div>';

            item.addEventListener('click', function() {
                notifCount = Math.max(0, notifCount - 1);
                updateBadge();
            });

            list.insertBefore(item, list.firstChild);

            while (list.children.length > maxItems + 1) {
                list.removeChild(list.lastChild);
            }

            notifCount++;
            updateBadge();

            // Floating toast
            showToast(data);

            // Play audio
            try {
                audio.currentTime = 0;
                audio.play().catch(function(){});
            } catch(e) {}

            // Browser notification
            if (Notification.permission === 'granted') {
                new Notification(label + ': ' + data.senderName, { body: data.message, icon: '{{ Storage::url("setting/logo.png") }}' });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission();
            }
        }

        function _esc(s) {
            var d = document.createElement('div');
            d.textContent = s;
            return d.innerHTML;
        }

        // Listen on private channel
        window.Echo.private('crm.notifications')
            .listen('.new.message', function(data) {
                addNotif(data);
            });

        // Request browser notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Reset badge when dropdown opened
        var btn = document.getElementById('crmNotifBtn');
        if (btn) {
            btn.addEventListener('click', function() {
                setTimeout(function() { notifCount = 0; updateBadge(); }, 3000);
            });
        }
    })();
    </script>
    @endif

    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
