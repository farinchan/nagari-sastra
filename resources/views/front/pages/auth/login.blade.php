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

                                <!-- Register Link -->
                                <div class="text-center mt-20">
                                    <p class="p-sm grey-color">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}" class="theme-color txt-500">Daftar sekarang</a>
                                    </p>
                                </div>

                            </form>
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
</style>
@endsection
