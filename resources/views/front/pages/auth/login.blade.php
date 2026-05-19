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
    <!-- LOGIN SECTION
               ============================================= -->
    <section id="login-section" class="wide-60 login-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="login-wrapper">

                        <!-- LOGIN FORM TITLE -->
                        <div class="section-title text-center mb-50">
                            <h3 class="h3-md">Masuk ke Akun Anda</h3>
                            <p class="p-lg grey-color">Masuk untuk mengakses fitur lengkap Nagari Sastra
                            </p>
                        </div>

                        <!-- LOGIN FORM -->
                        <div class="login-form-wrapper">
                            <form action="{{ route('login') }}" method="POST" class="login-form">
                                @csrf

                                <!-- Email/Username Input -->
                                <div class="form-group mb-3">
                                    <label for="login" class="form-label">Email atau Username</label>
                                    <input type="text" id="login" name="login"
                                        class="form-control @error('login') is-invalid @enderror"
                                        placeholder="Masukkan email atau username Anda" value="{{ old('login') }}"
                                        required>
                                    @error('login')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password Input -->
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password Anda" required>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="form-group d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Ingat saya
                                        </label>
                                    </div>
                                    <a href="#" class="forgot-password-link theme-color">Lupa password?</a>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-theme  btn-lg w-100 mb-3">
                                        Masuk
                                    </button>
                                </div>

                                <!-- Divider -->
                                <div class="text-center mb-3">
                                    <p class="grey-color">atau</p>
                                </div>

                                <!-- Register Link -->
                                <div class="text-center">
                                    <p class="p-md grey-color">
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
