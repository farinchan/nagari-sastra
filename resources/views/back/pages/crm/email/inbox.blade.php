@extends('back.app')
@section('content')
    <div id="kt_content_container" class=" container-xxl ">

        @if(isset($no_account) && $no_account)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="ki-duotone ki-information-5 fs-2x text-warning me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <div>
                    <strong>Belum ada akun email.</strong> Silakan konfigurasi akun email terlebih dahulu.
                    <a href="{{ route('back.crm.email.accounts') }}" class="fw-bold">Konfigurasi Akun Email</a>
                </div>
            </div>
        @else
            <div class="row">
                {{-- Sidebar --}}
                <div class="col-md-3">
                    <div class="card card-flush mb-5">
                        <div class="card-body">
                            <a href="{{ route('back.crm.email.compose', ['account_id' => $selectedAccount->id ?? '']) }}" class="btn btn-primary w-100 mb-5">
                                <i class="ki-duotone ki-pencil fs-4"><span class="path1"></span><span class="path2"></span></i> Tulis Email
                            </a>

                            {{-- Account Switcher --}}
                            <div class="mb-5">
                                <label class="form-label fw-bold text-muted fs-7">Akun Email</label>
                                <select class="form-select form-select-solid form-select-sm" id="accountSwitcher">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}" {{ $selectedAccount && $selectedAccount->id == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->name }} ({{ $acc->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Last sync info --}}
                            @if($selectedAccount && $selectedAccount->last_synced_at)
                                <div class="text-muted fs-8 mb-3">
                                    <i class="ki-duotone ki-time fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    Sync terakhir: {{ $selectedAccount->last_synced_at->diffForHumans() }}
                                </div>
                            @endif

                            {{-- Folders --}}
                            <div class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary">
                                @php
                                    $fixedFolders = [
                                        ['label' => 'Inbox', 'key' => 'INBOX', 'icon' => 'ki-sms'],
                                        ['label' => 'Sent Mail', 'key' => 'SENT', 'icon' => 'ki-send'],
                                        ['label' => 'Starred', 'key' => 'STARRED', 'icon' => 'ki-star'],
                                        ['label' => 'Spam', 'key' => 'SPAM', 'icon' => 'ki-shield-cross'],
                                        ['label' => 'Trash', 'key' => 'TRASH', 'icon' => 'ki-trash'],
                                    ];

                                    // Map keys to actual IMAP paths from saved folder list
                                    $imapFolders = $selectedAccount->imap_folders ?? [];
                                    $folderMap = [];
                                    foreach ($imapFolders as $f) {
                                        $lower = strtolower($f['name']);
                                        if (in_array($lower, ['inbox'])) $folderMap['INBOX'] = $f['path'];
                                        if (in_array($lower, ['sent', 'sent mail', 'sent items', 'terkirim'])) $folderMap['SENT'] = $f['path'];
                                        if (str_contains($lower, 'sent')) $folderMap['SENT'] = $folderMap['SENT'] ?? $f['path'];
                                        if (in_array($lower, ['starred', 'flagged'])) $folderMap['STARRED'] = $f['path'];
                                        if (str_contains($lower, 'star') || str_contains($lower, 'flag')) $folderMap['STARRED'] = $folderMap['STARRED'] ?? $f['path'];
                                        if (in_array($lower, ['spam', 'junk', 'junk email'])) $folderMap['SPAM'] = $f['path'];
                                        if (str_contains($lower, 'spam') || str_contains($lower, 'junk')) $folderMap['SPAM'] = $folderMap['SPAM'] ?? $f['path'];
                                        if (in_array($lower, ['trash', 'deleted items', 'deleted'])) $folderMap['TRASH'] = $f['path'];
                                        if (str_contains($lower, 'trash') || str_contains($lower, 'delete')) $folderMap['TRASH'] = $folderMap['TRASH'] ?? $f['path'];
                                    }
                                    // Defaults
                                    $folderMap['INBOX'] = $folderMap['INBOX'] ?? 'INBOX';
                                    $folderMap['SENT'] = $folderMap['SENT'] ?? 'Sent';
                                    $folderMap['STARRED'] = $folderMap['STARRED'] ?? 'Starred';
                                    $folderMap['SPAM'] = $folderMap['SPAM'] ?? 'Spam';
                                    $folderMap['TRASH'] = $folderMap['TRASH'] ?? 'Trash';
                                @endphp

                                @foreach($fixedFolders as $ff)
                                    @php $imapPath = $folderMap[$ff['key']]; @endphp
                                    <div class="menu-item mb-1">
                                        <a class="menu-link {{ ($folder ?? 'INBOX') === $imapPath ? 'active' : '' }}"
                                           href="{{ route('back.crm.email.inbox', ['account_id' => $selectedAccount->id ?? '', 'folder' => $imapPath]) }}">
                                            <span class="menu-icon">
                                                <i class="ki-duotone {{ $ff['icon'] }} fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                            </span>
                                            <span class="menu-title">{{ $ff['label'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="col-md-9">
                    <div class="card card-flush">
                        <div class="card-header align-items-center py-4 gap-2">
                            <div class="card-title">
                                <i class="ki-duotone ki-sms fs-2 me-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                {{ $folder ?? 'INBOX' }}
                                @if($selectedAccount)
                                    <span class="text-muted fs-7 ms-2">- {{ $selectedAccount->email }}</span>
                                @endif
                            </div>
                            <div class="card-toolbar gap-2">
                                <button type="button" class="btn btn-sm btn-light-success" id="btnSync">
                                    <i class="ki-duotone ki-arrows-circle fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    <span id="syncText">Sync Email</span>
                                    <span id="syncSpinner" class="spinner-border spinner-border-sm d-none ms-1"></span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            {{-- Sync result alert --}}
                            <div id="syncAlert" class="d-none mb-3"></div>

                            @if($error)
                                <div class="alert alert-danger d-flex align-items-center">
                                    <i class="ki-duotone ki-information-5 fs-2x text-danger me-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <div>
                                        <strong>Error.</strong><br>
                                        <small class="text-muted">{{ $error }}</small>
                                    </div>
                                </div>
                            @elseif($emails->isEmpty())
                                <div class="text-center py-10">
                                    <i class="ki-duotone ki-sms fs-3x text-gray-300 mb-3"><span class="path1"></span><span class="path2"></span></i>
                                    <p class="text-muted">Belum ada email. Klik <strong>Sync Email</strong> untuk mengambil email dari server.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-row-bordered align-middle gs-0 gy-3">
                                        <tbody>
                                            @foreach($emails as $email)
                                                <tr class="cursor-pointer {{ !$email->is_seen ? 'bg-light-primary' : '' }}" onclick="window.location='{{ route('back.crm.email.show', ['uid' => $email->uid, 'account_id' => $selectedAccount->id, 'folder' => $folder ?? 'INBOX']) }}'">
                                                    <td style="width: 30px;">
                                                        <div class="form-check form-check-sm form-check-custom">
                                                            <input class="form-check-input" type="checkbox" onclick="event.stopPropagation();">
                                                        </div>
                                                    </td>
                                                    <td style="width: 30px;">
                                                        @if($email->has_attachment)
                                                            <i class="ki-duotone ki-paper-clip fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                                                        @endif
                                                    </td>
                                                    <td style="width: 220px;" class="{{ !$email->is_seen ? 'fw-bold' : '' }}">
                                                        {{ Str::limit($email->from_name ?: $email->from_email, 30) }}
                                                    </td>
                                                    <td class="{{ !$email->is_seen ? 'fw-bold' : '' }}">
                                                        {{ Str::limit($email->subject, 55) }}
                                                    </td>
                                                    <td class="text-end text-muted fs-7" style="width: 130px;">
                                                        {{ $email->email_date ? $email->email_date->format('d M Y H:i') : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-muted fs-7 mt-3">Menampilkan {{ $emails->count() }} email</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        // Account switcher
        document.getElementById('accountSwitcher')?.addEventListener('change', function() {
            window.location = '{{ route("back.crm.email.inbox") }}?account_id=' + this.value;
        });

        // Sync button
        document.getElementById('btnSync')?.addEventListener('click', function() {
            const btn = this;
            const syncText = document.getElementById('syncText');
            const syncSpinner = document.getElementById('syncSpinner');
            const syncAlert = document.getElementById('syncAlert');

            btn.disabled = true;
            syncText.textContent = 'Menyinkronkan...';
            syncSpinner.classList.remove('d-none');
            syncAlert.classList.add('d-none');

            fetch('{{ route("back.crm.email.sync") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    account_id: '{{ $selectedAccount->id ?? '' }}',
                    folder: '{{ $folder ?? "INBOX" }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                syncText.textContent = 'Sync Email';
                syncSpinner.classList.add('d-none');

                if (data.success) {
                    syncAlert.className = 'alert alert-success mb-3';
                    syncAlert.innerHTML = '<i class="ki-duotone ki-check-circle fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>' + data.message;
                    syncAlert.classList.remove('d-none');

                    if (data.synced > 0) {
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } else {
                    syncAlert.className = 'alert alert-danger mb-3';
                    syncAlert.innerHTML = '<i class="ki-duotone ki-cross-circle fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>' + (data.error || 'Gagal sinkronisasi.');
                    syncAlert.classList.remove('d-none');
                }
            })
            .catch(error => {
                btn.disabled = false;
                syncText.textContent = 'Sync Email';
                syncSpinner.classList.add('d-none');

                syncAlert.className = 'alert alert-danger mb-3';
                syncAlert.innerHTML = 'Terjadi kesalahan: ' + error.message;
                syncAlert.classList.remove('d-none');
            });
        });
    </script>
@endsection
