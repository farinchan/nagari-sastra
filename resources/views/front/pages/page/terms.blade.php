@extends('front.app')

@section('content')

    <!-- SYARAT & KETENTUAN
    ============================================= -->
    <section id="terms" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="posts-wrapper">

                        <h3 class="h3-md mb-30">Syarat & Ketentuan</h3>
                        <p class="p-md grey-color mb-40">Terakhir diperbarui: {{ date('d F Y') }}</p>

                        <!-- 1 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">1. Penerimaan Syarat</h5>
                            <div class="post-txt">
                                <p>Dengan mengakses dan menggunakan situs web {{ $setting_web->name }} ("Situs"), Anda menyetujui dan terikat oleh syarat dan ketentuan berikut. Jika Anda tidak menyetujui salah satu dari ketentuan ini, harap untuk tidak menggunakan Situs ini.</p>
                            </div>
                        </div>

                        <!-- 2 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">2. Penggunaan Layanan</h5>
                            <div class="post-txt">
                                <p>Situs ini menyediakan layanan penerbitan jurnal ilmiah, buku, berita, dan kegiatan akademik. Pengguna diharapkan untuk:</p>
                                <ul class="txt-list mb-15">
                                    <li>Menggunakan layanan sesuai dengan peraturan dan hukum yang berlaku di Republik Indonesia.</li>
                                    <li>Tidak menyalahgunakan layanan untuk tujuan yang melanggar hukum atau tidak etis.</li>
                                    <li>Menjaga kerahasiaan informasi akun yang dimiliki.</li>
                                    <li>Tidak mengunggah konten yang bersifat menyinggung, diskriminatif, atau melanggar hak cipta pihak lain.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 3 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">3. Akun Pengguna</h5>
                            <div class="post-txt">
                                <p>Untuk mengakses beberapa fitur, Anda mungkin perlu membuat akun. Anda bertanggung jawab untuk:</p>
                                <ul class="txt-list mb-15">
                                    <li>Memberikan informasi yang akurat dan lengkap saat registrasi.</li>
                                    <li>Menjaga keamanan kata sandi akun Anda.</li>
                                    <li>Semua aktivitas yang terjadi melalui akun Anda.</li>
                                </ul>
                                <p>Kami berhak menonaktifkan akun yang melanggar ketentuan ini tanpa pemberitahuan sebelumnya.</p>
                            </div>
                        </div>

                        <!-- 4 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">4. Hak Kekayaan Intelektual</h5>
                            <div class="post-txt">
                                <p>Seluruh konten yang tersedia di Situs ini, termasuk namun tidak terbatas pada teks, grafis, logo, ikon, gambar, klip audio, unduhan digital, dan kompilasi data, merupakan properti {{ $setting_web->name }} atau penyedia kontennya dan dilindungi oleh undang-undang hak cipta Indonesia dan internasional.</p>
                                <p>Artikel jurnal yang diterbitkan melalui platform ini tunduk pada lisensi yang ditentukan oleh masing-masing jurnal.</p>
                            </div>
                        </div>

                        <!-- 5 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">5. Penerbitan dan Submisi</h5>
                            <div class="post-txt">
                                <p>Penulis yang mengirimkan naskah melalui platform ini menyatakan bahwa:</p>
                                <ul class="txt-list mb-15">
                                    <li>Naskah tersebut merupakan karya asli dan belum diterbitkan di tempat lain.</li>
                                    <li>Semua penulis yang tercantum telah menyetujui pengajuan naskah.</li>
                                    <li>Tidak ada konflik kepentingan yang berkaitan dengan naskah yang diajukan.</li>
                                    <li>Penulis bersedia mengikuti proses review yang ditetapkan oleh jurnal terkait.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 6 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">6. Pembatasan Tanggung Jawab</h5>
                            <div class="post-txt">
                                <p>{{ $setting_web->name }} tidak bertanggung jawab atas:</p>
                                <ul class="txt-list mb-15">
                                    <li>Kerugian langsung maupun tidak langsung yang timbul dari penggunaan Situs ini.</li>
                                    <li>Gangguan layanan yang disebabkan oleh faktor di luar kendali kami.</li>
                                    <li>Keakuratan konten yang disediakan oleh pihak ketiga atau pengguna.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 7 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">7. Perubahan Ketentuan</h5>
                            <div class="post-txt">
                                <p>Kami berhak untuk mengubah atau memperbarui syarat dan ketentuan ini kapan saja tanpa pemberitahuan sebelumnya. Perubahan akan berlaku segera setelah dipublikasikan di Situs ini. Pengguna disarankan untuk meninjau halaman ini secara berkala.</p>
                            </div>
                        </div>

                        <!-- 8 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">8. Hukum yang Berlaku</h5>
                            <div class="post-txt">
                                <p>Syarat dan ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia. Segala sengketa yang timbul akan diselesaikan melalui musyawarah mufakat atau melalui pengadilan yang berwenang di Indonesia.</p>
                            </div>
                        </div>

                        <!-- 9 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">9. Kontak</h5>
                            <div class="post-txt">
                                <p>Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi kami melalui halaman <a href="{{ route('contact.index') }}" class="theme-color">Kontak</a>.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
