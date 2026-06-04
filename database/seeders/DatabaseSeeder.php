<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\OutgoingMailCategory;
use App\Models\SettingBanner;
use App\Models\SettingWebsite;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'keuangan']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'humas']);
        Role::create(['name' => 'marketing']);

        OutgoingMailCategory::create([
            'name' => 'Letter of Acceptance (LoA)',
            'kode' => 'LoA',
            'description' => 'Kategori Letter of Acceptance (LoA) adalah kategori yang berisi dokumen resmi yang dikeluarkan untuk mengonfirmasi bahwa suatu karya ilmiah, artikel, atau penelitian telah diterima untuk dipublikasikan atau disajikan dalam suatu konferensi, jurnal, atau platform akademik lainnya.',
        ]);

        OutgoingMailCategory::create([
            'name' => 'Sertifikat Penulis Buku',
            'kode' => 'SRT-PB',
            'description' => 'Kategori sertifikat penulis buku adalah kategori yang berisi dokumen resmi yang diberikan kepada penulis buku sebagai pengakuan atas kontribusi mereka dalam menulis dan menerbitkan sebuah buku. Sertifikat ini biasanya mencantumkan nama penulis, judul buku, tanggal penerbitan, dan informasi relevan lainnya yang menunjukkan bahwa penulis tersebut telah berhasil menyelesaikan proses penulisan dan penerbitan buku.',
        ]);

        OutgoingMailCategory::create([
            'name' => 'Sertifikat Reviewer Jurnal',
            'kode' => 'SRT-RJ',
            'description' => 'Kategori sertifikat reviewer jurnal adalah kategori yang berisi dokumen resmi yang diberikan kepada reviewer jurnal sebagai pengakuan atas kontribusi mereka dalam meninjau dan mengevaluasi artikel ilmiah yang diajukan untuk diterbitkan. Sertifikat ini biasanya mencantumkan nama reviewer, judul artikel, tanggal peninjauan, dan informasi relevan lainnya yang menunjukkan bahwa reviewer tersebut telah berhasil menyelesaikan proses peninjauan artikel.',
        ]);

        User::create([
            'name' => 'Fajri - Developer',
            'email' => 'fajri@gariskode.com',
            'password' => bcrypt('password'),
        ])->assignRole('super-admin');

        SettingWebsite::create([
            'name' => 'Nagari Sastra - Publication, research, and Education',
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'email' => 'info@nagarisastra.org',
            'phone' => '089613390766',
            'address' => 'West Sumatra - Indonesia.',
            'latitude' => '-0.32177371869479526',
            'longitude' => '100.39795359131934',
            'about' => '<p><strong>Nagari Sastra - Publication, research, and Education</strong> adalah portal publikasi Independen yang berfokus pada penyebaran penelitian ilmiah, artikel akademik, dan sumber daya pendidikan. Kami berkomitmen untuk menyediakan platform yang mendukung pengembangan ilmu pengetahuan dan pendidikan di Indonesia.</p><p>Portal ini bertujuan untuk menjadi sumber terpercaya bagi para peneliti, akademisi, dan pelajar dalam mencari informasi ilmiah yang berkualitas. Dengan berbagai fitur unggulan, kami berharap dapat memberikan kontribusi positif bagi komunitas akademik dan masyarakat luas.</p>',
        ]);

        SettingBanner::create([
            'title' => 'Nagari Sastra - Publication, research, and Education',
            'subtitle' => 'Mendorong Pengembangan dan Penyebaran Penelitian Ilmiah yang Berkualitas di Indonesia',
            'image' => 'setting/banner/vC5qyP6SqARhMTDtFaUm.png',
            'url' => 'https://nagarisastra.org',
        ]);

        NewsCategory::create([
            'name' => 'Berita',
            'slug' => 'berita',
            'description' => 'Kategori berita adalah kategori yang berisi informasi terkini dan terbaru mengenai kegiatan, acara, dan informasi penting lainnya yang relevan dengan institusi atau organisasi.',
        ]);

        NewsCategory::create([
            'name' => 'Opini',
            'slug' => 'opini',
            'description' => 'Kategori opini adalah kategori yang berisi artikel atau tulisan yang berisi pandangan, pendapat, atau analisis dari penulis mengenai suatu topik atau isu tertentu.',
        ]);

        News::create([
            'title' => 'Peluncuran Program Penelitian Baru di Nagari Sastra',
            'slug' => 'peluncuran-program-penelitian-baru-di-nagari-sastra',
            'news_category_id' => 1,
            'thumbnail' => 'news/20240607090000_peluncuran-program-penelitian-baru-di-torkata-research.jpeg',
            'content' => '<p class="ql-align-justify">Nagari Sastra resmi meluncurkan program penelitian baru yang bertujuan untuk meningkatkan kolaborasi antara peneliti muda dan senior di Indonesia. Program ini diharapkan dapat menghasilkan penelitian berkualitas dan berdampak luas bagi masyarakat.</p><p class="ql-align-justify">Direktur Nagari Sastra menyampaikan bahwa program ini terbuka untuk semua bidang ilmu dan akan didukung penuh oleh fasilitas serta pendanaan dari institusi.</p>',
            'user_id' => 1,
            'status' => 'published',
            'meta_title' => 'Peluncuran Program Penelitian Baru di Nagari Sastra',
            'meta_description' => 'Nagari Sastra meluncurkan program penelitian baru untuk meningkatkan kolaborasi peneliti di Indonesia.',
            'meta_keywords' => 'penelitian, nagari sastra, kolaborasi, program baru',
        ]);

        News::create([
            'title' => 'Workshop Penulisan Artikel Ilmiah untuk Pemula',
            'slug' => 'workshop-penulisan-artikel-ilmiah-untuk-pemula',
            'news_category_id' => 2,
            'thumbnail' => 'news/20240607090001_workshop-penulisan-artikel-ilmiah-untuk-pemula.jpeg',
            'content' => '<p>Nagari Sastra mengadakan workshop penulisan artikel ilmiah yang ditujukan bagi peneliti dan mahasiswa pemula. Workshop ini menghadirkan pemateri berpengalaman dan memberikan tips praktis dalam menulis serta mempublikasikan artikel di jurnal bereputasi.</p>',
            'user_id' => 1,
            'status' => 'published',
            'meta_title' => 'Workshop Penulisan Artikel Ilmiah untuk Pemula',
            'meta_description' => 'Workshop penulisan artikel ilmiah untuk pemula diadakan oleh Nagari Sastra.',
            'meta_keywords' => 'workshop, artikel ilmiah, penulisan, nagari sastra',
        ]);

        // =============================================
        // FAQ SEEDER
        // =============================================

        // --- Umum ---
        Faq::create([
            'question' => 'Apa itu Nagari Sastra?',
            'answer' => 'Nagari Sastra adalah portal publikasi independen yang berfokus pada penyebaran penelitian ilmiah, artikel akademik, dan sumber daya pendidikan. Kami menyediakan layanan penerbitan buku, publikasi jurnal ilmiah, pendampingan penulisan, serta berbagai kegiatan akademik seperti seminar dan workshop untuk mendukung pengembangan ilmu pengetahuan di Indonesia.',
            'order' => 1,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Siapa saja yang dapat menggunakan layanan Nagari Sastra?',
            'answer' => 'Layanan kami terbuka untuk semua kalangan akademik, termasuk dosen, peneliti, mahasiswa (S1, S2, S3), guru, praktisi pendidikan, serta siapa saja yang memiliki minat dalam dunia penelitian dan publikasi ilmiah. Kami juga melayani instansi, lembaga penelitian, dan universitas yang membutuhkan kerjasama dalam bidang penerbitan dan publikasi.',
            'order' => 2,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Bagaimana cara mendaftar akun di Nagari Sastra?',
            'answer' => 'Anda dapat mendaftar akun secara gratis melalui halaman registrasi di website kami. Klik tombol "Daftar" pada bagian atas halaman, kemudian lengkapi formulir pendaftaran dengan data diri Anda seperti nama lengkap, email, dan password. Setelah mendaftar, Anda akan menerima email konfirmasi untuk mengaktifkan akun.',
            'order' => 3,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah layanan di Nagari Sastra berbayar?',
            'answer' => 'Nagari Sastra menyediakan layanan gratis dan berbayar. Akses ke informasi, berita, dan pengumuman di website bersifat gratis. Untuk layanan seperti penerbitan buku, publikasi jurnal, pendampingan penulisan, dan sertifikasi, terdapat biaya yang bervariasi tergantung jenis layanan. Informasi detail mengenai biaya dapat dilihat pada halaman masing-masing layanan atau menghubungi tim kami.',
            'order' => 4,
            'is_active' => true,
        ]);

        // --- Publikasi Jurnal ---
        Faq::create([
            'question' => 'Bagaimana cara mempublikasikan artikel di jurnal Nagari Sastra?',
            'answer' => "Untuk mempublikasikan artikel, Anda perlu:\n1. Mendaftar akun di website kami\n2. Memilih jurnal yang sesuai dengan bidang penelitian Anda\n3. Mengirimkan naskah melalui sistem OJS (Open Journal System) yang tersedia\n4. Naskah akan melalui proses review oleh reviewer yang kompeten di bidangnya\n5. Setelah diterima, Anda akan menerima Letter of Acceptance (LoA) dan artikel akan dipublikasikan sesuai jadwal penerbitan jurnal",
            'order' => 5,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Berapa lama proses review artikel jurnal?',
            'answer' => "Proses review artikel biasanya memakan waktu 2-4 minggu tergantung pada ketersediaan reviewer dan kompleksitas artikel. Setelah review selesai, penulis akan menerima notifikasi berupa hasil review yang mencakup:\n- Diterima tanpa revisi\n- Diterima dengan revisi minor\n- Diterima dengan revisi mayor\n- Ditolak\n\nJika diperlukan revisi, penulis diberikan waktu tambahan untuk memperbaiki naskah sesuai masukan reviewer.",
            'order' => 6,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah jurnal di Nagari Sastra sudah terindeks?',
            'answer' => 'Ya, jurnal-jurnal yang dikelola oleh Nagari Sastra telah terindeks di berbagai database ilmiah nasional maupun internasional. Kami terus berupaya meningkatkan kualitas dan akreditasi jurnal agar dapat terindeks di database yang lebih luas. Informasi detail mengenai indeksasi masing-masing jurnal dapat dilihat pada halaman jurnal terkait.',
            'order' => 7,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apa itu Letter of Acceptance (LoA) dan bagaimana cara mendapatkannya?',
            'answer' => 'Letter of Acceptance (LoA) adalah dokumen resmi yang menyatakan bahwa artikel atau karya ilmiah Anda telah diterima untuk dipublikasikan. LoA diterbitkan setelah artikel melewati proses review dan dinyatakan layak terbit. Dokumen ini dapat digunakan sebagai bukti publikasi untuk keperluan akademik seperti kenaikan jabatan fungsional, syarat kelulusan, atau pelaporan penelitian.',
            'order' => 8,
            'is_active' => true,
        ]);

        // --- Penerbitan Buku ---
        Faq::create([
            'question' => 'Bagaimana proses penerbitan buku di Nagari Sastra?',
            'answer' => "Proses penerbitan buku meliputi beberapa tahapan:\n1. Pengajuan naskah oleh penulis\n2. Review dan evaluasi naskah oleh tim editor\n3. Proses editing dan layout\n4. Desain sampul buku\n5. Pengurusan ISBN\n6. Cetak dan distribusi\n\nSeluruh proses biasanya memakan waktu 4-8 minggu tergantung pada kondisi naskah dan jumlah revisi yang diperlukan. Penulis akan mendapatkan sertifikat penulis buku setelah buku resmi diterbitkan.",
            'order' => 9,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah buku yang diterbitkan mendapatkan ISBN?',
            'answer' => 'Ya, setiap buku yang diterbitkan melalui Nagari Sastra akan mendapatkan ISBN (International Standard Book Number) resmi dari Perpustakaan Nasional Republik Indonesia. ISBN merupakan kode identifikasi unik yang berlaku secara internasional untuk setiap judul buku yang diterbitkan.',
            'order' => 10,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah bisa menerbitkan buku kolaborasi atau book chapter?',
            'answer' => "Ya, Nagari Sastra menerima penerbitan buku kolaborasi (book chapter). Dalam format ini, beberapa penulis masing-masing menulis satu bab dan digabungkan menjadi satu buku utuh. Setiap penulis bab akan mendapatkan:\n- Sertifikat penulis buku\n- Nama tercantum di buku sebagai kontributor\n- ISBN yang berlaku untuk seluruh buku\n\nFormat book chapter sangat cocok bagi dosen dan peneliti yang ingin menambah poin publikasi.",
            'order' => 11,
            'is_active' => true,
        ]);

        // --- Event & Workshop ---
        Faq::create([
            'question' => 'Bagaimana cara mendaftar event atau workshop?',
            'answer' => "Untuk mendaftar event atau workshop:\n1. Login ke akun Nagari Sastra Anda\n2. Buka halaman Event dan pilih kegiatan yang diminati\n3. Klik tombol \"Daftar Sekarang\" dan lengkapi formulir pendaftaran\n4. Anda akan menerima konfirmasi pendaftaran dan e-ticket melalui email serta dashboard akun Anda\n\nBeberapa event bersifat terbuka (gratis) dan beberapa memerlukan pembayaran sesuai ketentuan.",
            'order' => 12,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah peserta event mendapatkan sertifikat?',
            'answer' => 'Ya, setiap peserta yang mengikuti event, seminar, atau workshop yang diselenggarakan oleh Nagari Sastra akan mendapatkan e-sertifikat sebagai bukti keikutsertaan. Sertifikat dapat diunduh melalui dashboard akun setelah kegiatan selesai dan kehadiran telah diverifikasi oleh panitia.',
            'order' => 13,
            'is_active' => true,
        ]);

        // --- Pendampingan & Layanan ---
        Faq::create([
            'question' => 'Apakah Nagari Sastra menyediakan layanan pendampingan penulisan?',
            'answer' => "Ya, kami menyediakan layanan pendampingan penulisan artikel ilmiah dan buku. Layanan ini mencakup:\n- Konsultasi topik dan judul penelitian\n- Bimbingan metodologi penelitian\n- Pendampingan penulisan draft artikel\n- Proofreading dan editing naskah\n- Panduan submit ke jurnal target\n- Pendampingan revisi artikel berdasarkan masukan reviewer\n\nLayanan pendampingan dilakukan oleh mentor berpengalaman yang ahli di bidangnya masing-masing.",
            'order' => 14,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah tersedia layanan penerjemahan artikel ke bahasa Inggris?',
            'answer' => 'Ya, kami menyediakan layanan penerjemahan dan proofreading artikel ilmiah dari Bahasa Indonesia ke Bahasa Inggris, maupun sebaliknya. Penerjemahan dilakukan oleh translator berpengalaman di bidang akademik untuk memastikan terminologi dan gaya penulisan sesuai standar jurnal internasional.',
            'order' => 15,
            'is_active' => true,
        ]);

        // --- Teknis & Kontak ---
        Faq::create([
            'question' => 'Bagaimana cara menghubungi tim Nagari Sastra?',
            'answer' => "Anda dapat menghubungi tim kami melalui beberapa cara:\n- Email: info@nagarisastra.org\n- WhatsApp: 089613390766\n- Formulir kontak di halaman \"Hubungi Kami\" pada website\n- Live chat yang tersedia di website kami\n\nTim kami siap melayani pada hari kerja (Senin-Jumat) pukul 08.00-17.00 WIB. Untuk pertanyaan di luar jam kerja, Anda tetap dapat mengirimkan pesan dan kami akan merespons pada hari kerja berikutnya.",
            'order' => 16,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah data dan informasi pribadi saya aman di Nagari Sastra?',
            'answer' => 'Keamanan data pengguna adalah prioritas utama kami. Seluruh data pribadi Anda dilindungi dengan enkripsi dan disimpan di server yang aman. Kami tidak akan membagikan data pribadi Anda kepada pihak ketiga tanpa persetujuan Anda. Untuk informasi lebih lanjut mengenai perlindungan data, silakan baca halaman Kebijakan Privasi kami.',
            'order' => 17,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apa saja metode pembayaran yang tersedia?',
            'answer' => "Nagari Sastra menerima pembayaran melalui beberapa metode:\n- Transfer bank (BCA, BNI, BRI, Mandiri, BSI)\n- E-wallet (GoPay, OVO, Dana, ShopeePay)\n- QRIS (scan QR universal)\n\nSetelah melakukan pembayaran, silakan konfirmasi melalui WhatsApp atau email dengan melampirkan bukti transfer. Tim keuangan kami akan memverifikasi pembayaran dalam waktu 1x24 jam kerja.",
            'order' => 18,
            'is_active' => true,
        ]);

    }
}
