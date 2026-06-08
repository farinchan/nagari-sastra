@extends('front.app')

@section('content')

    <!-- LOGIN PAGE
    ============================================= -->
    <section id="login-1" class="wide-60 login-section division">
        <div class="container">
            <div class="row d-flex align-items-center">

                <!-- LOGIN TEXT -->
                <div class="col-lg-6">
                    <div class="login-txt pc-25 wow fadeInLeft">

                        <h3 class="h3-md">Selamat Datang Kembali</h3>
                        <p class="p-lg grey-color mt-15 mb-30">
                            Masuk ke akun Anda untuk mengakses dashboard, mengelola publikasi, dan melihat progres karya ilmiah Anda.
                        </p>

                        <!-- ADVANTAGES -->
                        <div class="login-advantages">
                            <div class="d-flex align-items-start mb-20">
                                <div class="ico-25 theme-color mr-15 mt-1"><span class="flaticon-like"></span></div>
                                <div>
                                    <h5 class="h5-xs mb-1">Kelola Publikasi</h5>
                                    <p class="p-md grey-color">Pantau status artikel dan buku yang sedang dalam proses review</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-20">
                                <div class="ico-25 theme-color mr-15 mt-1"><span class="flaticon-analytics"></span></div>
                                <div>
                                    <h5 class="h5-xs mb-1">Dashboard Analitik</h5>
                                    <p class="p-md grey-color">Lihat statistik dan progres publikasi Anda secara real-time</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="ico-25 theme-color mr-15 mt-1"><span class="flaticon-certificate"></span></div>
                                <div>
                                    <h5 class="h5-xs mb-1">Unduh Sertifikat</h5>
                                    <p class="p-md grey-color">Akses sertifikat publikasi dan kegiatan akademik Anda</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- LOGIN FORM -->
                <div class="col-lg-6">
                    <div class="login-form-holder pc-25 wow fadeInRight">

                        <div class="round-form-holder bg-white">
                            <div class="round-form-title text-center mb-30">
                                <h5 class="h5-md">Masuk ke Akun</h5>
                                <p class="p-sm grey-color">Gunakan email atau username Anda</p>
                            </div>

                            <form action="{{ route('login') }}" method="POST" class="contact-form">
                                @csrf

                                <!-- Email/Username -->
                                <div class="form-group mb-20">
                                    <label for="login" class="control-label">Email atau Username</label>
                                    <input type="text" id="login" name="login"
                                        class="form-control @error('login') is-invalid @enderror"
                                        placeholder="Masukkan email atau username"
                                        value="{{ old('login') }}" required>
                                    @error('login')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group mb-15">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="d-flex justify-content-between align-items-center mb-25">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label p-sm" for="remember">Ingat saya</label>
                                    </div>
                                    <a href="#" class="p-sm theme-color">Lupa password?</a>
                                </div>

                                <!-- Submit -->
                                <button type="submit" class="btn btn-theme tra-white-hover btn-block mb-20">
                                    Masuk
                                </button>

                            </form>

                            <!-- Divider -->
                            <div style="display: flex; align-items: center; margin: 20px 0;">
                                <hr style="flex: 1; border: none; border-top: 1px solid #e0e0e0;">
                                <span style="padding: 0 15px; font-size: 13px; color: #999;">atau</span>
                                <hr style="flex: 1; border: none; border-top: 1px solid #e0e0e0;">
                            </div>

                            <!-- Google Login -->
                            <a href="{{ route('google.redirect') }}"
                               style="display: flex; align-items: center; justify-content: center; width: 100%; height: 50px; font-size: 15px; font-weight: 600; color: #333; background: #fff; border: 2px solid #e0e0e0; border-radius: 6px; text-decoration: none; transition: all 0.3s ease;"
                               onmouseover="this.style.borderColor='#4285F4'; this.style.boxShadow='0 2px 8px rgba(66,133,244,0.2)';"
                               onmouseout="this.style.borderColor='#e0e0e0'; this.style.boxShadow='none';">
                                <svg width="20" height="20" viewBox="0 0 24 24" style="margin-right: 10px;">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                Masuk dengan Google
                            </a>

                            <!-- Register Link -->
                            <div style="text-align: center; margin-top: 20px;">
                                <p class="p-sm grey-color">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}" class="theme-color txt-500">Daftar sekarang</a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('styles')
<style>
    .round-form-holder {
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 40px 35px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .round-form-title h5 {
        font-weight: 700;
        color: #222;
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
    .contact-form .btn-block {
        width: 100%;
        height: 52px;
        font-size: 15px;
        font-weight: 600;
    }
    .login-advantages .ico-25 {
        min-width: 25px;
    }
    .invalid-feedback {
        display: block;
        font-size: 13px;
        color: #dc3545;
        margin-top: 5px;
    }
    @media (max-width: 991px) {
        .login-txt { margin-bottom: 40px; }
        .round-form-holder { padding: 30px 25px; }
    }
    .btn-google-login {
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 100%;
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
</style>
@endsection
