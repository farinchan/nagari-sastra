<?php

namespace Database\Seeders;

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

    }
}
