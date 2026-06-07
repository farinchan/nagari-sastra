@extends('front.app')

@section('content')

    <!-- ACCOUNT PROFILE
    ============================================= -->
    <section id="account-profile" class="wide-60 blog-page-section division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">
                        <h3 class="h3-md">Profil Saya</h3>
                        <p class="p-xl grey-color">Kelola informasi profil dan keamanan akun Anda</p>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- SIDEBAR / PROFILE CARD -->
                <div class="col-lg-4">
                    <div class="mb-40">

                        <!-- Profile Card -->
                        <div class="bg-white text-center p-4"
                             style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eee;">

                            <!-- Avatar -->
                            <div class="mb-20" style="position: relative; display: inline-block;">
                                <img src="{{ $me->getPhoto() }}" alt="{{ $me->name }}"
                                     class="profile-avatar-sidebar"
                                     style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #f0f0f0;">
                                @if ($me->isOnline())
                                    <span style="position: absolute; bottom: 8px; right: 8px; width: 16px; height: 16px; background: #22c55e; border-radius: 50%; border: 3px solid #fff;"></span>
                                @endif
                            </div>

                            <!-- Name & Role -->
                            <h5 class="h5-md mb-5">{{ $me->name }}</h5>
                            <p class="p-sm grey-color mb-10">
                                <span class="flaticon-envelope mr-1"></span> {{ $me->email }}
                            </p>
                            @if ($me->getRoleNames()->count() > 0)
                                <div class="mb-15">
                                    @foreach ($me->getRoleNames() as $role)
                                        <span class="badge"
                                              style="background: #eef2ff; color: #4f46e5; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            {{ ucfirst($role) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Quick Stats -->
                            <div style="border-top: 1px solid #f0f0f0; padding-top: 15px; margin-top: 10px;">
                                <div class="row text-center">
                                    <div class="col-6" style="border-right: 1px solid #f0f0f0;">
                                        <h6 class="h6-lg mb-0 theme-color">{{ $events->total() }}</h6>
                                        <small class="grey-color">Event Diikuti</small>
                                    </div>
                                    <div class="col-6">
                                        <p class="p-sm mb-0 {{ $me->isOnline() ? 'txt-500' : 'grey-color' }}"
                                           style="{{ $me->isOnline() ? 'color: #22c55e;' : '' }}">
                                            {{ $me->lastSeenFormatted() }}
                                        </p>
                                        <small class="grey-color">Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Info -->
                        <div class="bg-white mt-20 p-4"
                             style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eee;">
                            <h6 class="h6-xl mb-15">Informasi Akun</h6>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #f5f5f5;">
                                    <span class="grey-color"><span class="flaticon-user mr-2"></span>Username</span>
                                    <span class="txt-500">{{ $me->username ?: '-' }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #f5f5f5;">
                                    <span class="grey-color"><span class="flaticon-phone-call mr-2"></span>Telepon</span>
                                    <span class="txt-500">{{ $me->phone ?: '-' }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #f5f5f5;">
                                    <span class="grey-color"><span class="flaticon-user mr-2"></span>Gender</span>
                                    <span class="txt-500">{{ $me->gender ? ucfirst($me->gender) : '-' }}</span>
                                </li>
                                <li class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #f5f5f5;">
                                    <span class="grey-color"><span class="flaticon-calendar mr-2"></span>Bergabung</span>
                                    <span class="txt-500">{{ $me->created_at->format('d M Y') }}</span>
                                </li>
                                @if ($me->sinta_id)
                                    <li class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #f5f5f5;">
                                        <span class="grey-color">SINTA ID</span>
                                        <a href="https://sinta.kemdikbud.go.id/authors/profile/{{ $me->sinta_id }}"
                                           target="_blank" rel="noopener noreferrer" class="theme-color">{{ $me->sinta_id }}</a>
                                    </li>
                                @endif
                                @if ($me->scopus_id)
                                    <li class="d-flex justify-content-between py-2" style="border-bottom: 1px solid #f5f5f5;">
                                        <span class="grey-color">Scopus ID</span>
                                        <a href="https://www.scopus.com/authid/detail.uri?authorId={{ $me->scopus_id }}"
                                           target="_blank" rel="noopener noreferrer" class="theme-color">{{ $me->scopus_id }}</a>
                                    </li>
                                @endif
                                @if ($me->google_scholar)
                                    <li class="d-flex justify-content-between py-2">
                                        <span class="grey-color">Google Scholar</span>
                                        <a href="{{ $me->google_scholar }}"
                                           target="_blank" rel="noopener noreferrer" class="theme-color">Lihat Profil</a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                    </div>
                </div>
                <!-- END SIDEBAR -->


                <!-- MAIN CONTENT -->
                <div class="col-lg-8">

                    <!-- TABS -->
                    <div class="bg-white p-4"
                         style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #eee;">

                        <ul class="nav nav-tabs" id="profileTabs" role="tablist"
                            style="border-bottom: 2px solid #f0f0f0; margin-bottom: 25px;">
                            <li class="nav-item">
                                <a class="nav-link active" id="edit-tab" data-toggle="tab" href="#editProfile"
                                   role="tab" style="font-weight: 600; font-size: 15px; padding: 10px 20px;">
                                    <span class="flaticon-settings mr-2"></span>Edit Profil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="password-tab" data-toggle="tab" href="#changePassword"
                                   role="tab" style="font-weight: 600; font-size: 15px; padding: 10px 20px;">
                                    <span class="flaticon-key mr-2"></span>Ubah Password
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="events-tab" data-toggle="tab" href="#myEvents"
                                   role="tab" style="font-weight: 600; font-size: 15px; padding: 10px 20px;">
                                    <span class="flaticon-calendar mr-2"></span>Riwayat Event
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="profileTabsContent">

                            <!-- ===== TAB 1: EDIT PROFILE ===== -->
                            <div class="tab-pane fade show active" id="editProfile" role="tabpanel">

                                <!-- Photo Upload (Separate / AJAX) -->
                                <div class="text-center mb-30" style="border-bottom: 1px solid #f0f0f0; padding-bottom: 25px;">
                                    <div style="position: relative; display: inline-block;">
                                        <img id="photoPreview" src="{{ $me->getPhoto() }}" alt="{{ $me->name }}"
                                             class="profile-avatar-edit"
                                             style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #eee; transition: opacity 0.3s;">
                                        <label for="photoInput" id="photoLabel"
                                               style="position: absolute; bottom: 0; right: 0; background: #4f46e5; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff; font-size: 14px; transition: background 0.2s;">
                                            <span class="flaticon-image" id="photoIcon"></span>
                                        </label>
                                        <input type="file" id="photoInput"
                                               accept="image/jpeg,image/png,image/jpg,image/gif"
                                               style="display: none;">
                                        <!-- Loading spinner overlay -->
                                        <div id="photoLoading" style="display: none; position: absolute; top: 0; left: 0; width: 100px; height: 100px; border-radius: 50%; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center;">
                                            <div style="width: 24px; height: 24px; border: 3px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                                        </div>
                                    </div>
                                    <p class="p-sm grey-color mt-10 mb-0">Klik ikon untuk ganti foto — JPG, PNG, GIF (Maks 2MB)</p>
                                    <p id="photoMessage" class="p-sm mt-5 mb-0" style="display: none;"></p>
                                </div>

                                <!-- Profile Form -->
                                <form action="{{ route('account.profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Name -->
                                        <div class="col-md-6 mb-20">
                                            <label class="p-sm txt-500 mb-5">Nama Lengkap *</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $me->name) }}" required>
                                            @error('name')
                                                <span class="text-danger p-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6 mb-20">
                                            <label class="p-sm txt-500 mb-5">Email *</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email', $me->email) }}" required>
                                            @error('email')
                                                <span class="text-danger p-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6 mb-20">
                                            <label class="p-sm txt-500 mb-5">No. Telepon</label>
                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', $me->phone) }}" placeholder="08xxxxxxxxxx">
                                            @error('phone')
                                                <span class="text-danger p-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-md-6 mb-20">
                                            <label class="p-sm txt-500 mb-5">Jenis Kelamin *</label>
                                            <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="laki-laki" {{ old('gender', $me->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="perempuan" {{ old('gender', $me->gender) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                            @error('gender')
                                                <span class="text-danger p-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Academic Links -->
                                    <div class="mt-10 mb-20" style="border-top: 1px solid #f0f0f0; padding-top: 20px;">
                                        <h6 class="h6-sm mb-15">
                                            <span class="flaticon-worldwide mr-2"></span>Link Akademik
                                        </h6>
                                        <div class="row">
                                            <!-- SINTA ID -->
                                            <div class="col-md-4 mb-20">
                                                <label class="p-sm txt-500 mb-5">SINTA ID</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background: #f8f9fa; border-color: #ddd; font-size: 13px;">
                                                            <img src="https://is3.cloudhost.id/cms-unri/ee.ft.unri.ac.id/laravel-grapesjs/media/Sinta.png" alt="SINTA" style="width: 16px; height: 16px;" onerror="this.style.display='none'">
                                                        </span>
                                                    </div>
                                                    <input type="text" name="sinta_id"
                                                           class="form-control @error('sinta_id') is-invalid @enderror"
                                                           value="{{ old('sinta_id', $me->sinta_id) }}"
                                                           placeholder="cth: 6012345">
                                                </div>
                                                <small class="grey-color">ID numerik dari profil SINTA</small>
                                                @error('sinta_id')
                                                    <span class="text-danger p-sm d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Scopus ID -->
                                            <div class="col-md-4 mb-20">
                                                <label class="p-sm txt-500 mb-5">Scopus Author ID</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background: #f8f9fa; border-color: #ddd; font-size: 13px;">
                                                            <img src="https://rcbe.eng.ui.ac.id/wp-content/uploads/174/2024/05/scopus.png" alt="Scopus" style="width: 16px; height: 16px;" onerror="this.style.display='none'">
                                                        </span>
                                                    </div>
                                                    <input type="text" name="scopus_id"
                                                           class="form-control @error('scopus_id') is-invalid @enderror"
                                                           value="{{ old('scopus_id', $me->scopus_id) }}"
                                                           placeholder="cth: 57200123456">
                                                </div>
                                                <small class="grey-color">Author ID dari Scopus</small>
                                                @error('scopus_id')
                                                    <span class="text-danger p-sm d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Google Scholar -->
                                            <div class="col-md-4 mb-20">
                                                <label class="p-sm txt-500 mb-5">Google Scholar</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background: #f8f9fa; border-color: #ddd; font-size: 13px;">
                                                            <img src="https://scholar.google.com/favicon.ico" alt="Scholar" style="width: 16px; height: 16px;" onerror="this.style.display='none'">
                                                        </span>
                                                    </div>
                                                    <input type="url" name="google_scholar"
                                                           class="form-control @error('google_scholar') is-invalid @enderror"
                                                           value="{{ old('google_scholar', $me->google_scholar) }}"
                                                           placeholder="https://scholar.google.com/...">
                                                </div>
                                                <small class="grey-color">URL lengkap profil Google Scholar</small>
                                                @error('google_scholar')
                                                    <span class="text-danger p-sm d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="text-right mt-10">
                                        <button type="submit" class="btn btn-theme tra-grey-hover">
                                            <span class="flaticon-check mr-2"></span>Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- ===== TAB 2: CHANGE PASSWORD ===== -->
                            <div class="tab-pane fade" id="changePassword" role="tabpanel">
                                <form action="{{ route('account.password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-12 mb-20">
                                            <label class="p-sm txt-500 mb-5">Password Saat Ini *</label>
                                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                                                   placeholder="Masukkan password saat ini" required>
                                            @error('current_password')
                                                <span class="text-danger p-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-20">
                                            <label class="p-sm txt-500 mb-5">Password Baru *</label>
                                            <input type="password" name="new_password"
                                                   class="form-control @error('new_password') is-invalid @enderror"
                                                   placeholder="Minimal 8 karakter" required>
                                            @error('new_password')
                                                <span class="text-danger p-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-20">
                                            <label class="p-sm txt-500 mb-5">Konfirmasi Password Baru *</label>
                                            <input type="password" name="new_password_confirmation" class="form-control"
                                                   placeholder="Ulangi password baru" required>
                                        </div>
                                    </div>

                                    <div class="text-right mt-10">
                                        <button type="submit" class="btn btn-theme tra-grey-hover">
                                            <span class="flaticon-lock mr-2"></span>Ubah Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- ===== TAB 3: EVENT HISTORY ===== -->
                            <div class="tab-pane fade" id="myEvents" role="tabpanel">

                                @if ($events->isEmpty())
                                    <div class="text-center py-4">
                                        <div style="border: 2px dashed #ddd; border-radius: 8px; padding: 40px 20px; max-width: 400px; margin: 0 auto;">
                                            <div class="ico-55 mb-15 grey-color"><span class="flaticon-calendar"></span></div>
                                            <h6 class="h6-sm">Belum Ada Event</h6>
                                            <p class="p-sm grey-color mb-15">Anda belum mengikuti event apapun.</p>
                                            <a href="{{ route('event.index') }}" class="btn btn-sm btn-theme tra-grey-hover">
                                                Lihat Event
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <!-- Event List -->
                                    <div class="table-responsive">
                                        <table class="table" style="margin-bottom: 0;">
                                            <thead>
                                                <tr style="background: #f8f9fa;">
                                                    <th class="p-sm txt-500" style="border-top: none; padding: 12px 15px;">Event</th>
                                                    <th class="p-sm txt-500 text-center" style="border-top: none; padding: 12px 15px; width: 120px;">Status</th>
                                                    <th class="p-sm txt-500 text-center" style="border-top: none; padding: 12px 15px; width: 130px;">Tanggal Daftar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($events as $eventUser)
                                                    <tr>
                                                        <td style="padding: 15px; vertical-align: middle;">
                                                            @if ($eventUser->event)
                                                                <div class="d-flex align-items-center">
                                                                    <div class="mr-15" style="width: 55px; height: 55px; flex-shrink: 0; overflow: hidden; border-radius: 8px;">
                                                                        <img src="{{ $eventUser->event->getThumbnail() }}"
                                                                             alt="{{ $eventUser->event->name }}"
                                                                             style="width: 100%; height: 100%; object-fit: cover;">
                                                                    </div>
                                                                    <div>
                                                                        <a href="{{ route('event.show', $eventUser->event->slug) }}"
                                                                           class="txt-500" style="color: #333; font-size: 14px;">
                                                                            {{ Str::limit($eventUser->event->name, 50) }}
                                                                        </a>
                                                                        <p class="p-sm grey-color mb-0">
                                                                            {{ $eventUser->event->type ?? 'Event' }}
                                                                            @if($eventUser->event->start_date)
                                                                                · {{ \Carbon\Carbon::parse($eventUser->event->start_date)->format('d M Y') }}
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="grey-color p-sm">Event dihapus</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center" style="padding: 15px; vertical-align: middle;">
                                                            @php
                                                                $statusColor = match($eventUser->status ?? 'registered') {
                                                                    'approved', 'hadir' => '#22c55e',
                                                                    'pending', 'registered' => '#f59e0b',
                                                                    'rejected', 'ditolak' => '#ef4444',
                                                                    default => '#6b7280',
                                                                };
                                                            @endphp
                                                            <span style="background: {{ $statusColor }}15; color: {{ $statusColor }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                                                {{ ucfirst($eventUser->status ?? 'Terdaftar') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center grey-color p-sm" style="padding: 15px; vertical-align: middle;">
                                                            {{ $eventUser->created_at->format('d M Y') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    @if ($events->hasPages())
                                        <div class="mt-20 text-center">
                                            {{ $events->links() }}
                                        </div>
                                    @endif
                                @endif

                            </div>

                        </div>
                        <!-- END TAB CONTENT -->

                    </div>
                </div>
                <!-- END MAIN CONTENT -->

            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        #photoLabel:hover {
            background: #4338ca !important;
            transform: scale(1.1);
        }
    </style>
    <script>
        document.getElementById('photoInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const preview = document.getElementById('photoPreview');
            const loading = document.getElementById('photoLoading');
            const message = document.getElementById('photoMessage');

            // Validate size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showMessage('Ukuran gambar maksimal 2MB', false);
                e.target.value = '';
                return;
            }

            // Validate type
            if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(file.type)) {
                showMessage('Format harus: JPG, PNG, atau GIF', false);
                e.target.value = '';
                return;
            }

            // Show preview immediately
            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(file);

            // Show loading state
            preview.style.opacity = '0.5';
            loading.style.display = 'flex';
            message.style.display = 'none';

            // Upload via AJAX
            const formData = new FormData();
            formData.append('photo', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("account.profile.photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                preview.style.opacity = '1';
                loading.style.display = 'none';

                if (data.success) {
                    // Update all avatars on page
                    preview.src = data.photo_url;
                    document.querySelectorAll('.profile-avatar-sidebar').forEach(img => {
                        img.src = data.photo_url;
                    });
                    showMessage(data.message, true);
                } else {
                    showMessage(data.message || 'Gagal upload foto', false);
                }
            })
            .catch(error => {
                preview.style.opacity = '1';
                loading.style.display = 'none';
                showMessage('Terjadi kesalahan, coba lagi', false);
            });

            e.target.value = '';
        });

        function showMessage(text, isSuccess) {
            const msg = document.getElementById('photoMessage');
            msg.textContent = text;
            msg.style.display = 'block';
            msg.style.color = isSuccess ? '#22c55e' : '#ef4444';
            msg.style.fontWeight = '500';
            setTimeout(() => { msg.style.display = 'none'; }, 4000);
        }
    </script>
@endsection
