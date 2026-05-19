@extends('front.app')
@section('seo')
    <title>{{ $meta['description'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Nagari Sastra">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
    <link rel="canonical" href="{{ route('home') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')
    <!-- REGISTER SECTION
           ============================================= -->
    <section id="register-section" class="wide-60 register-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="register-wrapper">

                        <!-- REGISTER FORM TITLE -->
                        <div class="section-title text-center mb-50">
                            <h3 class="h3-md">Daftar Akun Baru</h3>
                            <p class="p-lg grey-color">Bergabunglah dengan Nagari Sastra untuk mengakses fitur lengkap penelitian dan publikasi ilmiah</p>
                        </div>

                        <!-- REGISTER FORM -->
                        <div class="register-form-wrapper">
                            <form action="{{ route('register.post') }}" method="POST" class="register-form">
                                @csrf

                                <div class="row">
                                    <!-- Nama Lengkap -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                id="name"
                                                name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Masukkan nama lengkap Anda"
                                                value="{{ old('name') }}"
                                                required
                                            >
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username" class="col-form-label">Username</label>
                                            <input
                                                type="text"
                                                id="username"
                                                name="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                placeholder="Masukkan username (opsional)"
                                                value="{{ old('username') }}"
                                            >
                                            @error('username')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label">Email <span class="text-danger">*</span></label>
                                            <input
                                                type="email"
                                                id="email"
                                                name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Masukkan alamat email"
                                                value="{{ old('email') }}"
                                                required
                                            >
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="col-form-label">Nomor Telepon</label>
                                            <input
                                                type="tel"
                                                id="phone"
                                                name="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="Masukkan nomor telepon"
                                                value="{{ old('phone') }}"
                                            >
                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="col-form-label">Password <span class="text-danger">*</span></label>
                                            <input
                                                type="password"
                                                id="password"
                                                name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Masukkan password (min. 6 karakter)"
                                                required
                                            >
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                            <input
                                                type="password"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                class="form-control"
                                                placeholder="Konfirmasi password Anda"
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Information Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="h5-sm mb-3 theme-color">Informasi Akademik (Opsional)</h5>
                                        <p class="text-muted small mb-3">Informasi ini akan membantu kami memverifikasi kredibilitas akademik Anda</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- SINTA ID -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sinta_id" class="col-form-label">SINTA ID</label>
                                            <input
                                                type="text"
                                                id="sinta_id"
                                                name="sinta_id"
                                                class="form-control @error('sinta_id') is-invalid @enderror"
                                                placeholder="ID SINTA Anda"
                                                value="{{ old('sinta_id') }}"
                                            >
                                            @error('sinta_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Scopus ID -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="scopus_id" class="col-form-label">Scopus ID</label>
                                            <input
                                                type="text"
                                                id="scopus_id"
                                                name="scopus_id"
                                                class="form-control @error('scopus_id') is-invalid @enderror"
                                                placeholder="ID Scopus Anda"
                                                value="{{ old('scopus_id') }}"
                                            >
                                            @error('scopus_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Google Scholar -->
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="google_scholar" class="col-form-label">Google Scholar</label>
                                            <input
                                                type="url"
                                                id="google_scholar"
                                                name="google_scholar"
                                                class="form-control @error('google_scholar') is-invalid @enderror"
                                                placeholder="Link Google Scholar Anda"
                                                value="{{ old('google_scholar') }}"
                                            >
                                            @error('google_scholar')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input
                                            type="checkbox"
                                            class="custom-control-input @error('agree_terms') is-invalid @enderror"
                                            id="agree_terms"
                                            name="agree_terms"
                                            {{ old('agree_terms') ? 'checked' : '' }}
                                            required
                                        >
                                        <label class="custom-control-label" for="agree_terms">
                                            Saya menyetujui <a href="{{ route('terms.service') }}" target="_blank" class="theme-color">Syarat dan Ketentuan</a>
                                            serta <a href="{{ route('privacy.policy') }}" target="_blank" class="theme-color">Kebijakan Privasi</a> <span class="text-danger">*</span>
                                        </label>
                                        @error('agree_terms')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-theme btn-block btn-lg mb-3">
                                        Daftar Sekarang
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="text-center mb-3">
                                    <hr class="my-4">
                                    <span class="text-muted">atau</span>
                                </div>

                                <!-- Login Link -->
                                <div class="text-center">
                                    <p class="text-muted">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="theme-color font-weight-bold">Masuk sekarang</a>
                                    </p>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BENEFITS SECTION -->
    <section id="register-benefits" class="bg-lightgrey wide-60 features-section division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">
                        <h3 class="h3-md">Keuntungan Bergabung dengan Nagari Sastra</h3>
                        <p class="p-xl">Dapatkan akses ke berbagai fitur unggulan untuk mendukung karir akademik dan penelitian Anda</p>
                    </div>
                </div>
            </div>

            <!-- BENEFITS ROW -->
            <div class="row">

                <!-- BENEFIT #1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="fbox-2-ico ico-50">
                            <div class="shape-ico color-theme">
                                <span class="flaticon-document"></span>
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="gradient1" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#41b883;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#359568;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#gradient1)" d="M57.6,-59.7C72.7,-43.6,81.5,-21.8,79.7,-1.1C77.9,19.6,65.5,39.2,50.4,54.6C35.3,70,17.6,81.2,-2.9,84.1C-23.4,87,-46.8,81.6,-61.9,66.2C-77,50.8,-83.8,25.4,-81.9,2.1C-80,-21.2,-69.3,-42.4,-54.2,-58.5C-39.1,-74.6,-19.5,-85.6,2.1,-87.7C23.7,-89.8,47.4,-82.9,57.6,-59.7Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Publikasi Artikel Ilmiah</h5>
                            <p class="p-md grey-color">Submit dan publikasikan artikel penelitian Anda melalui sistem OJS terintegrasi dengan proses review yang profesional</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="fbox-2-ico ico-50">
                            <div class="shape-ico color-theme">
                                <span class="flaticon-analytics"></span>
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="gradient2" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#41b883;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#359568;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#gradient2)" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,79.6,-45.2C87.4,-31.3,90,-13.6,87.5,3.4C85,20.4,77.4,40.8,64.8,56.5C52.2,72.2,34.6,83.2,15.1,88.1C-4.4,93,-25.8,91.8,-43.7,83.9C-61.6,76,-76,61.4,-84.1,44.2C-92.2,27,-94,7.2,-89.7,-10.7C-85.4,-28.6,-75,-44.6,-61.2,-51.8C-47.4,-59,-30.2,-57.4,-14.7,-65.1C0.8,-72.8,14.6,-89.8,44.7,-76.4Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Dashboard Analitik</h5>
                            <p class="p-md grey-color">Pantau progres publikasi, statistik sitasi, dan dampak penelitian Anda dengan dashboard analitik komprehensif</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="0.8s">
                        <div class="fbox-2-ico ico-50">
                            <div class="shape-ico color-theme">
                                <span class="flaticon-team"></span>
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="gradient3" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#41b883;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#359568;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#gradient3)" d="M39.4,-65.1C49.7,-58.2,55.7,-43.4,63.1,-29.8C70.5,-16.2,79.3,-3.8,79.8,8.9C80.3,21.6,72.5,34.6,62.4,45.4C52.3,56.2,40,64.8,26.1,69.9C12.2,75,-3.3,76.6,-18.1,73.4C-32.9,70.2,-47,62.2,-58.4,51.4C-69.8,40.6,-78.5,27,-81.3,12.2C-84.1,-2.6,-81,-18.6,-74.2,-32.4C-67.4,-46.2,-56.9,-57.8,-44.2,-63.9C-31.5,-70,-17.8,-70.6,-2.9,-66.2C12,-61.8,24,-52.4,39.4,-65.1Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Jaringan Peneliti</h5>
                            <p class="p-md grey-color">Bergabung dengan komunitas peneliti global, berkolaborasi, dan berbagi pengetahuan dengan sesama akademisi</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="1.0s">
                        <div class="fbox-2-ico ico-50">
                            <div class="shape-ico color-theme">
                                <span class="flaticon-calendar"></span>
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="gradient4" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#41b883;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#359568;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#gradient4)" d="M35.2,-60.8C45.4,-54.2,53.1,-43.4,58.6,-31.2C64.1,-19,67.4,-5.4,65.8,7.4C64.2,20.2,57.7,32.2,48.5,41.8C39.3,51.4,27.4,58.6,14.2,62.1C1,65.6,-13.5,65.4,-26.8,61.8C-40.1,58.2,-52.2,51.2,-60.1,41.2C-68,31.2,-71.7,18.2,-71.9,5C-72.1,-8.2,-68.8,-21.6,-61.9,-32.4C-55,-43.2,-44.5,-51.4,-33.2,-57.6C-21.9,-63.8,-9.8,-67.9,2.8,-72.5C15.4,-77.1,25,-61.4,35.2,-60.8Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Event & Webinar</h5>
                            <p class="p-md grey-color">Akses eksklusif ke seminar, workshop, dan konferensi akademik untuk mengembangkan wawasan penelitian</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="1.2s">
                        <div class="fbox-2-ico ico-50">
                            <div class="shape-ico color-theme">
                                <span class="flaticon-certificate"></span>
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="gradient5" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#41b883;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#359568;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#gradient5)" d="M42.3,-69.5C54.8,-62.1,65.2,-50.3,71.1,-36.2C77,-22.1,78.4,-6.7,75.9,7.8C73.4,22.3,67,35.9,57.8,46.8C48.6,57.7,36.6,65.9,23.4,70.2C10.2,74.5,-4.2,75,-17.8,71.7C-31.4,68.4,-44.2,61.3,-54.3,51.2C-64.4,41.1,-71.8,28,-74.6,14.2C-77.4,0.4,-75.6,-14.1,-69.9,-26.4C-64.2,-38.7,-54.6,-48.8,-43.2,-56.6C-31.8,-64.4,-18.6,-70,-4.6,-67.8C9.4,-65.6,29.8,-55.6,42.3,-69.5Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Sertifikasi</h5>
                            <p class="p-md grey-color">Dapatkan sertifikat digital untuk setiap publikasi dan partisipasi dalam kegiatan akademik</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="1.4s">
                        <div class="fbox-2-ico ico-50">
                            <div class="shape-ico color-theme">
                                <span class="flaticon-support"></span>
                                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <linearGradient id="gradient6" x1="0%" y1="0%" x2="0%" y2="100%">
                                            <stop offset="0%" style="stop-color:#41b883;stop-opacity:1" />
                                            <stop offset="100%" style="stop-color:#359568;stop-opacity:1" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#gradient6)" d="M48.1,-78.3C61.7,-71.1,71.8,-56.7,76.8,-40.8C81.8,-24.9,81.7,-7.5,78.2,8.7C74.7,24.9,67.8,40,57.9,52.4C48,64.8,35.1,74.5,20.8,78.9C6.5,83.3,-9.2,82.4,-23.5,77.8C-37.8,73.2,-50.7,64.9,-60.8,53.8C-70.9,42.7,-78.2,28.8,-80.3,14.2C-82.4,-0.4,-79.3,-15.7,-72.4,-28.4C-65.5,-41.1,-54.8,-51.2,-42.6,-58.8C-30.4,-66.4,-16.8,-71.5,-1.7,-68.6C13.4,-65.7,34.5,-54.8,48.1,-78.3Z" transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Dukungan 24/7</h5>
                            <p class="p-md grey-color">Tim support profesional siap membantu Anda dalam proses publikasi dan penggunaan platform</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('styles')
<style>
    .register-section {
        padding-top: 80px;
        padding-bottom: 80px;
    }

    .register-wrapper {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        padding: 50px 40px;
        border: 1px solid #e9ecef;
    }

    .register-form .form-control {
        height: 50px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 14px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .register-form .form-control:focus {
        border-color: #41b883;
        box-shadow: 0 0 0 0.2rem rgba(65, 184, 131, 0.25);
        background-color: #ffffff;
    }

    .register-form .form-control.is-invalid {
        border-color: #dc3545;
    }

    .register-form .col-form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .register-form .btn-theme {
        height: 50px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .register-form .custom-control-label {
        font-size: 14px;
        color: #6c757d;
        line-height: 1.5;
    }

    .register-form .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #41b883;
        border-color: #41b883;
    }

    .register-form .text-danger {
        color: #dc3545 !important;
    }

    .register-form .theme-color {
        color: #41b883 !important;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .register-form .theme-color:hover {
        color: #359568 !important;
        text-decoration: underline;
    }

    .register-form .invalid-feedback {
        display: block;
        font-size: 14px;
        margin-top: 5px;
    }

    .register-form hr {
        border-top: 1px solid #dee2e6;
        margin: 1rem 0;
    }

    .register-form .text-muted {
        color: #6c757d !important;
        background: #ffffff;
        padding: 0 15px;
        position: relative;
        top: -12px;
    }

    .register-form .btn-block {
        width: 100%;
        display: block;
    }

    .register-form .h5-sm {
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .register-wrapper {
            padding: 30px 25px;
            margin: 15px;
            border-radius: 10px;
        }

        .register-section {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .register-form .form-control {
            height: 45px;
            font-size: 14px;
        }

        .register-form .btn-theme {
            height: 45px;
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .register-wrapper {
            padding: 25px 20px;
            margin: 10px;
        }
    }
</style>
@endsection
