@extends('front.app')

@section('content')

    <!-- REGISTER PAGE
    ============================================= -->
    <section id="register-1" class="wide-60 register-section division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-50">
                        <h3 class="h3-md">Daftar Akun Baru</h3>
                        <p class="p-lg grey-color">
                            Bergabunglah dengan Nagari Sastra untuk mengakses fitur lengkap penelitian dan publikasi ilmiah
                        </p>
                    </div>
                </div>
            </div>

            <!-- REGISTER FORM -->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="register-form-box wow fadeInUp">

                        <form action="{{ route('register.post') }}" method="POST" class="contact-form">
                            @csrf

                            <!-- DATA PRIBADI -->
                            <div class="form-section-title mb-25">
                                <h5 class="h5-sm"><span class="flaticon-user mr-10 theme-color"></span> Data Pribadi</h5>
                            </div>

                            <div class="row">
                                <!-- Nama Lengkap -->
                                <div class="col-md-6">
                                    <div class="form-group mb-20">
                                        <label for="name" class="control-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Masukkan nama lengkap"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Username -->
                                <div class="col-md-6">
                                    <div class="form-group mb-20">
                                        <label for="username" class="control-label">Username</label>
                                        <input type="text" id="username" name="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            placeholder="Masukkan username (opsional)"
                                            value="{{ old('username') }}">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group mb-20">
                                        <label for="email" class="control-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Masukkan alamat email"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group mb-20">
                                        <label for="phone" class="control-label">Nomor Telepon</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="Masukkan nomor telepon"
                                            value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group mb-20">
                                        <label for="password" class="control-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Minimal 8 karakter" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group mb-20">
                                        <label for="password_confirmation" class="control-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control"
                                            placeholder="Ulangi password" required>
                                    </div>
                                </div>
                            </div>

                            <!-- DIVIDER -->
                            <hr class="my-30">

                            <!-- INFORMASI AKADEMIK -->
                            <div class="form-section-title mb-25">
                                <h5 class="h5-sm"><span class="flaticon-certificate mr-10 theme-color"></span> Informasi Akademik <span class="p-sm grey-color">(Opsional)</span></h5>
                                <p class="p-sm grey-color mt-5">Informasi ini membantu verifikasi kredibilitas akademik Anda</p>
                            </div>

                            <div class="row">
                                <!-- SINTA ID -->
                                <div class="col-md-4">
                                    <div class="form-group mb-20">
                                        <label for="sinta_id" class="control-label">SINTA ID</label>
                                        <input type="text" id="sinta_id" name="sinta_id"
                                            class="form-control @error('sinta_id') is-invalid @enderror"
                                            placeholder="ID SINTA Anda"
                                            value="{{ old('sinta_id') }}">
                                        @error('sinta_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Scopus ID -->
                                <div class="col-md-4">
                                    <div class="form-group mb-20">
                                        <label for="scopus_id" class="control-label">Scopus ID</label>
                                        <input type="text" id="scopus_id" name="scopus_id"
                                            class="form-control @error('scopus_id') is-invalid @enderror"
                                            placeholder="ID Scopus Anda"
                                            value="{{ old('scopus_id') }}">
                                        @error('scopus_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Google Scholar -->
                                <div class="col-md-4">
                                    <div class="form-group mb-20">
                                        <label for="google_scholar" class="control-label">Google Scholar</label>
                                        <input type="url" id="google_scholar" name="google_scholar"
                                            class="form-control @error('google_scholar') is-invalid @enderror"
                                            placeholder="Link Google Scholar"
                                            value="{{ old('google_scholar') }}">
                                        @error('google_scholar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- TERMS -->
                            <div class="form-group mt-15 mb-25">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                        class="custom-control-input @error('agree_terms') is-invalid @enderror"
                                        id="agree_terms" name="agree_terms"
                                        {{ old('agree_terms') ? 'checked' : '' }} required>
                                    <label class="custom-control-label p-sm" for="agree_terms">
                                        Saya menyetujui <a href="{{ route('page.terms') }}" target="_blank" class="theme-color">Syarat dan Ketentuan</a>
                                        serta <a href="{{ route('page.privacy') }}" target="_blank" class="theme-color">Kebijakan Privasi</a> <span class="text-danger">*</span>
                                    </label>
                                    @error('agree_terms')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Honeypot (hidden from humans) -->
                            <div style="position: absolute; left: -9999px;" aria-hidden="true">
                                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="form-group mb-25">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SUBMIT -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-theme tra-white-hover submit-btn mb-15">
                                    Daftar Sekarang
                                </button>
                            </div>

                        </form>

                        <!-- Divider -->
                        <div style="display: flex; align-items: center; margin: 20px 0;">
                            <hr style="flex: 1; border: none; border-top: 1px solid #e0e0e0;">
                            <span style="padding: 0 15px; font-size: 13px; color: #999;">atau</span>
                            <hr style="flex: 1; border: none; border-top: 1px solid #e0e0e0;">
                        </div>

                        <!-- Google Register -->
                        <div style="text-align: center;">
                            <a href="{{ route('google.redirect') }}"
                               style="display: inline-flex; align-items: center; justify-content: center; min-width: 280px; height: 50px; font-size: 15px; font-weight: 600; color: #333; background: #fff; border: 2px solid #e0e0e0; border-radius: 6px; text-decoration: none; transition: all 0.3s ease;"
                               onmouseover="this.style.borderColor='#4285F4'; this.style.boxShadow='0 2px 8px rgba(66,133,244,0.2)';"
                               onmouseout="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';">
                                <svg width="20" height="20" viewBox="0 0 24 24" style="margin-right: 10px;">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Daftar dengan Google
                            </a>
                        </div>

                        <!-- LOGIN LINK -->
                        <div style="text-align: center; margin-top: 20px;">
                            <p class="p-sm grey-color">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="theme-color txt-500">Masuk sekarang</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- BENEFITS SECTION
    ============================================= -->
    <section id="register-benefits" class="bg-lightgrey wide-60 features-section division">
        <div class="container">

            <!-- SECTION TITLE -->
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="section-title text-center mb-60">
                        <h3 class="h3-md">Keuntungan Bergabung</h3>
                        <p class="p-xl">Dapatkan akses ke berbagai fitur unggulan untuk mendukung karir akademik dan penelitian Anda</p>
                    </div>
                </div>
            </div>

            <!-- BENEFITS ROW -->
            <div class="row">

                <!-- BENEFIT #1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="fbox-ico">
                            <div class="ico-50 theme-color"><span class="flaticon-pen"></span></div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Publikasi Artikel</h5>
                            <p class="p-md grey-color">Submit dan publikasikan artikel penelitian melalui sistem OJS terintegrasi</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="0.6s">
                        <div class="fbox-ico">
                            <div class="ico-50 theme-color"><span class="flaticon-analytics"></span></div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Dashboard Analitik</h5>
                            <p class="p-md grey-color">Pantau progres publikasi dan statistik dampak penelitian Anda</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="0.8s">
                        <div class="fbox-ico">
                            <div class="ico-50 theme-color"><span class="flaticon-team"></span></div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Jaringan Peneliti</h5>
                            <p class="p-md grey-color">Bergabung dengan komunitas peneliti dan berkolaborasi dengan sesama akademisi</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="1.0s">
                        <div class="fbox-ico">
                            <div class="ico-50 theme-color"><span class="flaticon-calendar"></span></div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Event & Webinar</h5>
                            <p class="p-md grey-color">Akses eksklusif ke seminar, workshop, dan konferensi akademik</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="1.2s">
                        <div class="fbox-ico">
                            <div class="ico-50 theme-color"><span class="flaticon-certificate"></span></div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Sertifikasi</h5>
                            <p class="p-md grey-color">Dapatkan sertifikat digital untuk setiap publikasi dan kegiatan akademik</p>
                        </div>
                    </div>
                </div>

                <!-- BENEFIT #6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="fbox-2 pr-25 mb-40 wow fadeInUp" data-wow-delay="1.4s">
                        <div class="fbox-ico">
                            <div class="ico-50 theme-color"><span class="flaticon-support"></span></div>
                        </div>
                        <div class="fbox-2-txt">
                            <h5 class="h5-md">Dukungan 24/7</h5>
                            <p class="p-md grey-color">Tim support profesional siap membantu proses publikasi Anda</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('styles')
<style>
    .register-form-box {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 45px 40px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .form-section-title {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 12px;
    }
    .form-section-title h5 {
        font-weight: 700;
        color: #222;
        margin-bottom: 0;
    }
    .contact-form .control-label {
        font-weight: 500;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }
    .contact-form .form-control {
        margin-bottom: 0;
    }
    .contact-form .form-group {
        margin-bottom: 0;
    }
    .contact-form .submit-btn {
        min-width: 250px;
        height: 52px;
        font-size: 15px;
        font-weight: 600;
    }
    .my-30 {
        margin-top: 30px !important;
        margin-bottom: 30px !important;
    }
    .invalid-feedback {
        display: block;
        font-size: 13px;
        color: #dc3545;
        margin-top: 5px;
    }
    .fbox-ico {
        margin-bottom: 15px;
    }
    @media (max-width: 768px) {
        .register-form-box {
            padding: 30px 20px;
        }
    }
    .btn-google-login {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 250px;
        height: 52px;
        font-size: 15px;
        font-weight: 600;
        color: #333 !important;
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-google-login:hover {
        background: #f8f9fa;
        border-color: #4285F4;
        box-shadow: 0 2px 8px rgba(66, 133, 244, 0.2);
        color: #333 !important;
    }
    .my-20 {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
    }
</style>
@endsection
