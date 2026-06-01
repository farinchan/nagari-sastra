# Nagari Sastra

**Nagari Sastra Group** — Platform web app dan website resmi yang dibangun dengan Laravel. Aplikasi ini mencakup website publik, panel administrasi, dan sistem CRM terintegrasi.

## Fitur Utama

### 🌐 Website Publik
- **Homepage** — Landing page website Nagari Sastra
- **Berita (News)** — Artikel berita dengan kategori dan komentar
- **Buku (Book)** — Katalog buku dengan preview dan detail
- **Jurnal (Journal)** — Publikasi jurnal ilmiah
- **Event** — Pendaftaran event, e-ticket, dan presensi
- **Pengumuman** — Halaman pengumuman resmi
- **Profil** — Halaman profil organisasi
- **Tim** — Halaman editor dan reviewer
- **Kontak** — Formulir kontak
- **Payment** — Sistem pembayaran
- **Auth** — Login, register, lupa password, dan login Google

### 🛠️ Panel Administrasi (Back Office)
- **Dashboard** — Ringkasan statistik
- **Manajemen Berita** — CRUD berita dan kategori
- **Manajemen Buku** — CRUD buku dan kategori
- **Manajemen Jurnal** — CRUD jurnal
- **Manajemen Event** — CRUD event, peserta, dan presensi
- **Pengumuman** — CRUD pengumuman
- **Sambutan** — Halaman sambutan
- **Menu Profil** — Manajemen halaman profil
- **Surat Masuk & Keluar** — Manajemen surat-menyurat
- **Keuangan (Finance)** — Manajemen keuangan
- **Master Data** — Data jurnal, user, dan pengaturan lainnya
- **Pengaturan** — Konfigurasi website

### 📬 CRM (Customer Relationship Management)
- **Email** — Kirim & terima email dengan multi-akun SMTP/IMAP, campaign bulk email (via queue)
- **Webchat** — Widget chat embed untuk website, real-time messaging dengan gambar
- **Telegram** — Integrasi bot Telegram, kirim & terima pesan dan gambar
- **Notifikasi Real-time** — Notifikasi pesan masuk via Laravel Reverb (WebSocket)
- **WhatsApp** — Integrasi WhatsApp

### 👥 Role Pengguna
- Super Admin
- Marketing
- User (member)

---

## Persyaratan

- PHP >= 8.2 (ext: pdo_mysql, mbstring, openssl, curl, gd, imap)
- Composer
- MySQL / MariaDB
- Node.js & NPM (opsional, untuk build asset)

---

## Setup Development (Lokal)

### 1. Clone & Install Dependencies

```bash
git clone <repo-url> nagari-sastra
cd nagari-sastra
composer install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` sesuai konfigurasi lokal:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nagari_sastra
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=957250
REVERB_APP_KEY=your_reverb_key
REVERB_APP_SECRET=your_reverb_secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### 3. Database

```bash
php artisan migrate --seed
```

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Jalankan Server Development

Buka **3 terminal** terpisah:

**Terminal 1 — Web Server:**
```bash
php artisan serve
```
Akses di `http://localhost:8000`

**Terminal 2 — Queue Worker (untuk pengiriman email):**
```bash
php artisan queue:work --queue=emails,default
```

**Terminal 3 — Reverb WebSocket (untuk notifikasi real-time CRM):**
```bash
php artisan reverb:start
```

> **Catatan:** Terminal 2 dan 3 hanya diperlukan jika menggunakan fitur CRM (email, webchat, telegram).

### Login Default

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `superadmin@nagarisastra.com` | `password` |

---

## Setup Production (Server)

### 1. Persyaratan Server

- PHP >= 8.2 (ext: pdo_mysql, mbstring, openssl, curl, gd, imap)
- Composer
- MySQL / MariaDB
- Nginx atau Apache
- Supervisor (untuk queue & reverb)
- SSL Certificate (disarankan)

### 2. Clone & Install

```bash
cd /var/www
git clone <repo-url> nagari-sastra
cd nagari-sastra
composer install --optimize-autoloader --no-dev
```

### 3. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nagari_sastra
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

QUEUE_CONNECTION=database

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=957250
REVERB_APP_KEY=your_reverb_key
REVERB_APP_SECRET=your_reverb_secret
REVERB_HOST=0.0.0.0
REVERB_PORT=8080
REVERB_SCHEME=https
```

### 4. Database & Storage

```bash
php artisan migrate --seed --force
php artisan storage:link
```

### 5. Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. Permission

```bash
chown -R www-data:www-data /var/www/nagari-sastra
chmod -R 755 /var/www/nagari-sastra
chmod -R 775 storage bootstrap/cache
```

### 7. Nginx Config

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/nagari-sastra/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 8. Supervisor — Queue Worker

Buat file `/etc/supervisor/conf.d/nagari-queue.conf`:

```ini
[program:nagari-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/nagari-sastra/artisan queue:work --queue=emails,default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/nagari-sastra/storage/logs/queue.log
stopwaitsecs=3600
```

### 9. Supervisor — Reverb WebSocket

Buat file `/etc/supervisor/conf.d/nagari-reverb.conf`:

```ini
[program:nagari-reverb]
process_name=%(program_name)s
command=php /var/www/nagari-sastra/artisan reverb:start --host=0.0.0.0 --port=8080
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/nagari-sastra/storage/logs/reverb.log
```

### 10. Start Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start nagari-queue:*
sudo supervisorctl start nagari-reverb
```

### 11. Cron (Laravel Scheduler)

Tambahkan ke crontab (`crontab -e`):

```
* * * * * cd /var/www/nagari-sastra && php artisan schedule:run >> /dev/null 2>&1
```

---

## Perintah Berguna

```bash
# Setelah deploy: restart queue & clear cache
php artisan queue:restart
php artisan optimize:clear
php artisan optimize

# Monitor & retry queue gagal
php artisan queue:failed
php artisan queue:retry all

# Clear semua cache
php artisan optimize:clear
```

---

## Tech Stack

- **Framework:** Laravel 12
- **Database:** MySQL
- **Real-time:** Laravel Reverb (WebSocket)
- **Queue:** Laravel Queue (database driver)
- **Auth:** Laravel Auth + Google OAuth
- **Template Admin:** Metronic (KT Theme)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
