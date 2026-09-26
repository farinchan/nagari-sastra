@extends('front.app')

@php
    $title = '404 - Halaman Tidak Ditemukan | ' . ($setting_web->name ?? 'Nagari Sastra');
@endphp

@section('content')

    <!-- 404 ERROR PAGE SECTION
    ============================================= -->
    <section id="page-404" class="wide-80 division text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-7">

                    <!-- 404 DIGITS -->
                    <div class="error-404-title mb-20">
                        <h1 class="font-weight-bold theme-color" style="font-size: 6rem; line-height: 1; letter-spacing: 2px;">404</h1>
                    </div>

                    <!-- ERROR MESSAGE -->
                    <h2 class="h3-md mb-20">Halaman Tidak Ditemukan</h2>
                    <p class="p-lg grey-color mb-35">
                        Mohon maaf, halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL yang Anda masukkan salah.
                    </p>

                    <!-- QUICK NAVIGATION BUTTONS -->
                    <div class="btns-group mb-40" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px;">
                        <a href="{{ route('home') }}" class="btn btn-md btn-theme tra-grey-hover">
                            <span class="flaticon-home mr-1"></span> Kembali ke Beranda
                        </a>
                        <a href="{{ route('book.index') }}" class="btn btn-md btn-tra-grey theme-hover">
                            Katalog Buku
                        </a>
                        <a href="{{ route('journal.index') }}" class="btn btn-md btn-tra-grey theme-hover">
                            Direktori Jurnal
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-md btn-tra-grey theme-hover">
                            Hubungi Kami
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
