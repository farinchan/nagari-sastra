@extends('back.app')

@section('title', 'Bulk Message - Chatery')

@section('toolbar')
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Bulk Message</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('back.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">CRM</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">Bulk Message</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- Bulk Message Form Card --}}
            <div class="card card-flush mb-6">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <i class="ki-duotone ki-send fs-2 me-2 text-success">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        Kirim Bulk Message
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form id="bulkForm" enctype="multipart/form-data">
                        <div class="row">
                            {{-- Session Selector --}}
                            <div class="col-md-6 mb-5">
                                <label class="form-label required">Session</label>
                                <select name="session_id" id="sessionSelect" class="form-select form-select-solid" required>
                                    <option value="">-- Pilih Session --</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}">
                                            {{ $session->name }} ({{ $session->phone_number ?? $session->session_id }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted">Pilih session WhatsApp yang sudah terkoneksi.</div>
                            </div>

                            {{-- Delay --}}
                            <div class="col-md-6 mb-5">
                                <label class="form-label required">Delay (milidetik)</label>
                                <input type="number" name="delay" id="delayInput" class="form-control form-control-solid"
                                       value="1000" min="500" step="100" required>
                                <div class="form-text text-muted">Jeda antar pengiriman pesan. Minimum 500ms.</div>
                            </div>
                        </div>

                        {{-- Group Selector --}}
                        <div class="mb-5">
                            <label class="form-label">Grup Kontak</label>
                            <div class="d-flex gap-3 align-items-start">
                                <select id="groupSelect" class="form-select form-select-solid flex-grow-1">
                                    <option value="">-- Pilih Grup (Opsional) --</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">
                                            {{ $group->name }} ({{ $group->contacts_count }} kontak)
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" id="loadGroupBtn" class="btn btn-light-primary btn-sm flex-shrink-0" style="height: 42px;">
                                    <i class="ki-duotone ki-download fs-4"><span class="path1"></span><span class="path2"></span></i> Muat
                                </button>
                            </div>
                            <div class="form-text text-muted">Pilih grup untuk mengisi nomor otomatis. Nomor akan ditambahkan ke daftar di bawah.</div>
                        </div>

                        {{-- Phone Numbers --}}
                        <div class="mb-5">
                            <label class="form-label required">Nomor Tujuan</label>
                            <textarea name="numbers" id="numbersInput" class="form-control form-control-solid" rows="6"
                                      placeholder="Masukkan nomor tujuan, satu per baris&#10;628123456789&#10;628987654321&#10;628111222333"
                                      required></textarea>
                            <div class="form-text text-muted">
                                Format: <code>628xxx</code> — satu nomor per baris. Baris kosong akan diabaikan.
                            </div>
                        </div>

                        {{-- Message --}}
                        <div class="mb-5">
                            <label class="form-label required">Pesan</label>
                            <textarea name="message" id="messageInput" class="form-control form-control-solid" rows="5"
                                      placeholder="Tulis pesan yang akan dikirim ke semua nomor di atas..."
                                      required></textarea>
                        </div>

                        {{-- Image Upload --}}
                        <div class="mb-5">
                            <label class="form-label">Gambar (Opsional)</label>
                            <input type="file" name="image" id="imageInput" class="form-control form-control-solid"
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                            <div class="form-text text-muted">Format: JPEG, PNG, GIF, WEBP. Maksimal 5MB.</div>
                            {{-- Image Preview --}}
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="d-flex align-items-center gap-3">
                                    <img id="imagePreviewImg" src="" alt="Preview"
                                         class="rounded border shadow-sm" style="max-height: 150px; max-width: 250px; object-fit: cover;">
                                    <button type="button" id="removeImageBtn" class="btn btn-sm btn-light-danger">
                                        <i class="ki-duotone ki-trash fs-5">
                                            <span class="path1"></span><span class="path2"></span>
                                            <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                        </i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" id="submitBtn" class="btn btn-success">
                                <i class="ki-duotone ki-send fs-4 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                <span id="submitBtnText">Kirim Bulk Message</span>
                                <span id="submitBtnSpinner" class="spinner-border spinner-border-sm ms-2" role="status" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Results Card --}}
            <div id="resultsCard" class="card card-flush" style="display: none;">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <i class="ki-duotone ki-chart-simple fs-2 me-2 text-primary">
                            <span class="path1"></span><span class="path2"></span>
                            <span class="path3"></span><span class="path4"></span>
                        </i>
                        Hasil Pengiriman
                    </div>
                </div>
                <div class="card-body pt-0">
                    {{-- Summary Stats --}}
                    <div class="row g-4 mb-6">
                        <div class="col-md-4">
                            <div class="border border-dashed border-gray-300 rounded p-4 text-center">
                                <div class="fs-4 fw-bold text-gray-800" id="statTotal">0</div>
                                <div class="fs-7 text-muted fw-semibold">
                                    <i class="ki-duotone ki-people fs-5 me-1 text-gray-500">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                    Total Nomor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border border-dashed border-gray-300 rounded p-4 text-center">
                                <div class="fs-4 fw-bold text-success" id="statSuccess">0</div>
                                <div class="fs-7 text-muted fw-semibold">
                                    <i class="ki-duotone ki-check-circle fs-5 me-1 text-success">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Berhasil
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border border-dashed border-gray-300 rounded p-4 text-center">
                                <div class="fs-4 fw-bold text-danger" id="statFailed">0</div>
                                <div class="fs-7 text-muted fw-semibold">
                                    <i class="ki-duotone ki-cross-circle fs-5 me-1 text-danger">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    Gagal
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Log --}}
                    <label class="form-label fw-bold mb-3">Detail Log</label>
                    <div id="resultLog" class="border border-gray-300 rounded bg-light p-4"
                         style="max-height: 350px; overflow-y: auto; font-size: 13px;">
                        <div class="text-muted text-center py-5">Belum ada hasil.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('bulkForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const submitBtnSpinner = document.getElementById('submitBtnSpinner');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewImg = document.getElementById('imagePreviewImg');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const resultsCard = document.getElementById('resultsCard');
        const resultLog = document.getElementById('resultLog');
        const statTotal = document.getElementById('statTotal');
        const statSuccess = document.getElementById('statSuccess');
        const statFailed = document.getElementById('statFailed');

        // ── Image preview ──────────────────────────────────────────
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) {
                imagePreview.style.display = 'none';
                return;
            }

            // Validate size (5 MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 5MB.');
                this.value = '';
                imagePreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreviewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        removeImageBtn.addEventListener('click', function () {
            imageInput.value = '';
            imagePreview.style.display = 'none';
            imagePreviewImg.src = '';
        });

        // ── Load group phones ────────────────────────────────────────
        document.getElementById('loadGroupBtn').addEventListener('click', function () {
            const groupId = document.getElementById('groupSelect').value;
            if (!groupId) {
                alert('Pilih grup kontak terlebih dahulu.');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            fetch("{{ url('back/crm/chatery/groups') }}/" + groupId + "/phones")
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.phones.length > 0) {
                        const textarea = document.getElementById('numbersInput');
                        const existing = textarea.value.trim();
                        const newPhones = data.phones.join('\n');
                        textarea.value = existing ? existing + '\n' + newPhones : newPhones;
                        alert(data.phones.length + ' nomor berhasil dimuat dari grup.');
                    } else {
                        alert('Grup ini belum memiliki kontak.');
                    }
                })
                .catch(() => alert('Gagal memuat kontak grup.'))
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = '<i class="ki-duotone ki-download fs-4"><span class="path1"></span><span class="path2"></span></i> Muat';
                });
        });

        // ── Form submit ────────────────────────────────────────────
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Basic validation
            const sessionId = document.getElementById('sessionSelect').value;
            const numbers = document.getElementById('numbersInput').value.trim();
            const message = document.getElementById('messageInput').value.trim();
            const delay = parseInt(document.getElementById('delayInput').value, 10);

            if (!sessionId) {
                alert('Pilih session terlebih dahulu.');
                return;
            }
            if (!numbers) {
                alert('Masukkan minimal satu nomor tujuan.');
                return;
            }
            if (!message) {
                alert('Pesan tidak boleh kosong.');
                return;
            }
            if (isNaN(delay) || delay < 500) {
                alert('Delay minimal 500 milidetik.');
                return;
            }

            // Count numbers for confirmation
            const numberLines = numbers.split('\n').filter(n => n.trim() !== '');
            if (!confirm('Kirim pesan ke ' + numberLines.length + ' nomor? Proses ini tidak bisa dibatalkan.')) {
                return;
            }

            // Build FormData
            const formData = new FormData(form);

            // Disable button
            submitBtn.disabled = true;
            submitBtnText.textContent = 'Mengirim...';
            submitBtnSpinner.style.display = 'inline-block';

            // Show results card & reset
            resultsCard.style.display = 'block';
            statTotal.textContent = numberLines.length;
            statSuccess.textContent = '0';
            statFailed.textContent = '0';
            resultLog.innerHTML = '<div class="d-flex align-items-center text-muted"><span class="spinner-border spinner-border-sm me-2"></span> Memproses pengiriman...</div>';

            // Scroll to results
            resultsCard.scrollIntoView({ behavior: 'smooth', block: 'start' });

            fetch("{{ route('back.crm.chatery.bulk.send') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && Array.isArray(data.results)) {
                    let successCount = 0;
                    let failedCount = 0;
                    let logHtml = '';

                    data.results.forEach(function (item, index) {
                        if (item.success) {
                            successCount++;
                            logHtml += '<div class="d-flex align-items-center py-1">' +
                                '<span class="badge badge-light-success me-2" style="min-width: 60px;">Berhasil</span>' +
                                '<code class="me-2">' + escapeHtml(item.to) + '</code>' +
                                '<span class="text-muted fs-8">— ' + escapeHtml(item.message || 'sent') + '</span>' +
                                '</div>';
                        } else {
                            failedCount++;
                            logHtml += '<div class="d-flex align-items-center py-1">' +
                                '<span class="badge badge-light-danger me-2" style="min-width: 60px;">Gagal</span>' +
                                '<code class="me-2">' + escapeHtml(item.to) + '</code>' +
                                '<span class="text-muted fs-8">— ' + escapeHtml(item.message || 'failed') + '</span>' +
                                '</div>';
                        }
                    });

                    statTotal.textContent = data.results.length;
                    statSuccess.textContent = successCount;
                    statFailed.textContent = failedCount;
                    resultLog.innerHTML = logHtml || '<div class="text-muted text-center py-3">Tidak ada data.</div>';
                } else {
                    resultLog.innerHTML = '<div class="text-danger py-2">' +
                        '<i class="ki-duotone ki-information-3 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> ' +
                        escapeHtml(data.message || 'Terjadi kesalahan saat mengirim pesan.') +
                        '</div>';
                }
            })
            .catch(err => {
                let errorMsg = 'Terjadi kesalahan.';
                if (err && err.message) {
                    errorMsg = err.message;
                } else if (err && err.errors) {
                    errorMsg = Object.values(err.errors).flat().join(', ');
                }
                resultLog.innerHTML = '<div class="text-danger py-2">' +
                    '<i class="ki-duotone ki-information-3 fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> ' +
                    escapeHtml(errorMsg) +
                    '</div>';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtnText.textContent = 'Kirim Bulk Message';
                submitBtnSpinner.style.display = 'none';
            });
        });

        // ── Utility ────────────────────────────────────────────────
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }
    });
</script>
@endsection
