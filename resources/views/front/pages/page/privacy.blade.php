@extends('front.app')
@section('seo')
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="author" content="Nagari Sastra">

    <meta property="og:title" content="{{ $meta['title'] }}">
    <meta property="og:description" content="{{ $meta['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('page.privacy') }}">
    <link rel="canonical" href="{{ route('page.privacy') }}">
    <meta property="og:image" content="{{ Storage::url($meta['favicon']) }}">
@endsection
@section('content')

    <!-- KEBIJAKAN PRIVASI
    ============================================= -->
    <section id="privacy" class="wide-60 blog-page-section division">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="posts-wrapper">

                        <h3 class="h3-md mb-30">Kebijakan Privasi</h3>
                        <p class="p-md grey-color mb-40">Terakhir diperbarui: {{ date('d F Y') }}</p>

                        <!-- 1 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">1. Pendahuluan</h5>
                            <div class="post-txt">
                                <p>{{ $setting_web->name }} ("kami") berkomitmen untuk melindungi privasi pengguna. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.</p>
                            </div>
                        </div>

                        <!-- 2 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">2. Informasi yang Kami Kumpulkan</h5>
                            <div class="post-txt">
                                <p>Kami dapat mengumpulkan jenis informasi berikut:</p>
                                <ul class="txt-list mb-15">
                                    <li><strong>Informasi Pribadi:</strong> Nama, alamat email, nomor telepon, afiliasi institusi yang Anda berikan saat registrasi atau pengajuan naskah.</li>
                                    <li><strong>Informasi Akun:</strong> Username, kata sandi (terenkripsi), dan preferensi akun.</li>
                                    <li><strong>Informasi Penggunaan:</strong> Data tentang bagaimana Anda menggunakan Situs, termasuk halaman yang dikunjungi, waktu akses, dan fitur yang digunakan.</li>
                                    <li><strong>Informasi Teknis:</strong> Alamat IP, jenis browser, sistem operasi, dan informasi perangkat.</li>
                                    <li><strong>Informasi Berlangganan:</strong> Alamat email yang didaftarkan melalui newsletter.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 3 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">3. Penggunaan Informasi</h5>
                            <div class="post-txt">
                                <p>Informasi yang kami kumpulkan digunakan untuk:</p>
                                <ul class="txt-list mb-15">
                                    <li>Menyediakan, mengoperasikan, dan memelihara layanan kami.</li>
                                    <li>Memproses pengajuan naskah, pendaftaran kegiatan, dan pembelian buku.</li>
                                    <li>Mengirimkan notifikasi terkait akun dan layanan Anda.</li>
                                    <li>Mengirimkan informasi terbaru melalui newsletter (jika Anda berlangganan).</li>
                                    <li>Meningkatkan kualitas layanan dan pengalaman pengguna.</li>
                                    <li>Memenuhi kewajiban hukum yang berlaku.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 4 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">4. Penyimpanan dan Keamanan Data</h5>
                            <div class="post-txt">
                                <p>Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi informasi pribadi Anda, termasuk:</p>
                                <ul class="txt-list mb-15">
                                    <li>Enkripsi kata sandi menggunakan algoritma hashing yang kuat.</li>
                                    <li>Penggunaan protokol HTTPS untuk transmisi data.</li>
                                    <li>Pembatasan akses ke data pribadi hanya kepada personel yang berwenang.</li>
                                    <li>Pencadangan data secara berkala.</li>
                                </ul>
                                <p>Meskipun demikian, tidak ada metode transmisi melalui internet yang 100% aman. Kami tidak dapat menjamin keamanan absolut atas informasi Anda.</p>
                            </div>
                        </div>

                        <!-- 5 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">5. Berbagi Informasi</h5>
                            <div class="post-txt">
                                <p>Kami tidak menjual, memperdagangkan, atau menyewakan informasi pribadi Anda kepada pihak ketiga. Informasi dapat dibagikan dalam kondisi berikut:</p>
                                <ul class="txt-list mb-15">
                                    <li><strong>Proses Editorial:</strong> Informasi penulis dibagikan kepada reviewer dan editor dalam proses peer-review.</li>
                                    <li><strong>Kepatuhan Hukum:</strong> Jika diwajibkan oleh hukum atau proses hukum yang berlaku.</li>
                                    <li><strong>Penyedia Layanan:</strong> Pihak ketiga yang membantu operasional kami (hosting, email) terikat oleh kewajiban kerahasiaan.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 6 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">6. Cookie</h5>
                            <div class="post-txt">
                                <p>Situs kami menggunakan cookie untuk:</p>
                                <ul class="txt-list mb-15">
                                    <li>Mempertahankan sesi login Anda.</li>
                                    <li>Mengingat preferensi pengguna.</li>
                                    <li>Menganalisis penggunaan Situs untuk peningkatan layanan.</li>
                                </ul>
                                <p>Anda dapat mengatur browser untuk menolak cookie, namun beberapa fitur Situs mungkin tidak berfungsi dengan baik.</p>
                            </div>
                        </div>

                        <!-- 7 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">7. Hak Pengguna</h5>
                            <div class="post-txt">
                                <p>Anda memiliki hak untuk:</p>
                                <ul class="txt-list mb-15">
                                    <li><strong>Mengakses</strong> informasi pribadi yang kami simpan tentang Anda.</li>
                                    <li><strong>Memperbaiki</strong> informasi yang tidak akurat atau tidak lengkap.</li>
                                    <li><strong>Menghapus</strong> akun dan informasi pribadi Anda (dengan keterbatasan tertentu terkait arsip publikasi).</li>
                                    <li><strong>Berhenti berlangganan</strong> dari newsletter kapan saja.</li>
                                </ul>
                                <p>Untuk menggunakan hak-hak ini, silakan hubungi kami melalui halaman <a href="{{ route('contact.index') }}" class="theme-color">Kontak</a>.</p>
                            </div>
                        </div>

                        <!-- 8 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">8. Tautan Pihak Ketiga</h5>
                            <div class="post-txt">
                                <p>Situs kami dapat berisi tautan ke situs web pihak ketiga. Kami tidak bertanggung jawab atas praktik privasi atau konten dari situs web tersebut. Kami menyarankan Anda untuk membaca kebijakan privasi setiap situs yang Anda kunjungi.</p>
                            </div>
                        </div>

                        <!-- 9 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">9. Perubahan Kebijakan</h5>
                            <div class="post-txt">
                                <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan dipublikasikan di halaman ini dengan tanggal pembaruan terbaru. Pengguna disarankan untuk meninjau kebijakan ini secara berkala.</p>
                            </div>
                        </div>

                        <!-- 10 -->
                        <div class="mb-35">
                            <h5 class="h5-md mb-15">10. Kontak</h5>
                            <div class="post-txt">
                                <p>Jika Anda memiliki pertanyaan atau keluhan mengenai Kebijakan Privasi ini, silakan hubungi kami melalui halaman <a href="{{ route('contact.index') }}" class="theme-color">Kontak</a>.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
