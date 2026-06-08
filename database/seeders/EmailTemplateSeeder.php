<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::truncate();

        $templates = [
            [
                'name' => 'Newsletter Modern',
                'body_html' => '
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9;padding:20px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                <tr>
                    <td style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:30px 40px;text-align:center;">
                        <h1 style="color:#ffffff;margin:0;font-family:Arial,sans-serif;font-size:28px;font-weight:700;letter-spacing:-0.5px;">📰 Newsletter</h1>
                        <p style="color:rgba(255,255,255,0.85);margin:8px 0 0;font-family:Arial,sans-serif;font-size:14px;">Berita & update terbaru untuk Anda</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 40px;">
                        <h2 style="color:#333;font-family:Arial,sans-serif;font-size:20px;margin:0 0 15px;">Halo! 👋</h2>
                        <p style="color:#555;font-family:Arial,sans-serif;font-size:15px;line-height:1.7;margin:0 0 20px;">
                            Terima kasih telah berlangganan newsletter kami. Berikut adalah update terbaru yang kami siapkan khusus untuk Anda.
                        </p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                            <tr>
                                <td style="background-color:#f8f9ff;border-left:4px solid #667eea;padding:15px 20px;border-radius:0 6px 6px 0;">
                                    <h3 style="color:#333;font-family:Arial,sans-serif;font-size:16px;margin:0 0 8px;">📌 Highlight Minggu Ini</h3>
                                    <p style="color:#666;font-family:Arial,sans-serif;font-size:14px;line-height:1.6;margin:0;">
                                        Tulis konten highlight Anda di sini. Bisa berupa berita, artikel, atau pengumuman penting.
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="0" cellspacing="0" style="margin:25px 0;">
                            <tr>
                                <td style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:6px;padding:12px 30px;">
                                    <a href="#" style="color:#ffffff;text-decoration:none;font-family:Arial,sans-serif;font-size:15px;font-weight:600;">Baca Selengkapnya →</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#f8f9fa;padding:25px 40px;border-top:1px solid #eee;text-align:center;">
                        <p style="color:#999;font-family:Arial,sans-serif;font-size:12px;margin:0 0 8px;">
                            Anda menerima email ini karena berlangganan newsletter kami.
                        </p>
                        <p style="color:#999;font-family:Arial,sans-serif;font-size:12px;margin:0 0 8px;">
                            <a href="#" style="color:#667eea;text-decoration:underline;">Berhenti Berlangganan</a> &nbsp;|&nbsp;
                            <a href="#" style="color:#667eea;text-decoration:underline;">Kelola Preferensi</a>
                        </p>
                        <p style="color:#bbb;font-family:Arial,sans-serif;font-size:11px;margin:10px 0 0;">
                            &copy; ' . date('Y') . ' Nama Perusahaan. All rights reserved.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>',
                'is_active' => true,
            ],
            [
                'name' => 'Promo & Marketing',
                'body_html' => '
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#fef3e2;padding:20px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                <tr>
                    <td style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);padding:35px 40px;text-align:center;">
                        <h1 style="color:#ffffff;margin:0;font-family:Arial,sans-serif;font-size:32px;font-weight:800;">🔥 PROMO SPESIAL</h1>
                        <p style="color:rgba(255,255,255,0.9);margin:10px 0 0;font-family:Arial,sans-serif;font-size:16px;">Penawaran terbatas — jangan sampai terlewat!</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:35px 40px;">
                        <div style="text-align:center;margin:0 0 25px;">
                            <span style="background-color:#fff3cd;color:#856404;font-family:Arial,sans-serif;font-size:13px;font-weight:600;padding:6px 16px;border-radius:20px;display:inline-block;">⏰ Berlaku sampai akhir bulan ini</span>
                        </div>
                        <h2 style="color:#333;font-family:Arial,sans-serif;font-size:22px;text-align:center;margin:0 0 15px;">Diskon Hingga <span style="color:#f5576c;">50%</span></h2>
                        <p style="color:#555;font-family:Arial,sans-serif;font-size:15px;line-height:1.7;text-align:center;margin:0 0 25px;">
                            Nikmati potongan harga spesial untuk semua produk pilihan kami. Gunakan kode promo di bawah saat checkout.
                        </p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;">
                            <tr>
                                <td align="center">
                                    <table cellpadding="0" cellspacing="0" style="border:2px dashed #f5576c;border-radius:8px;padding:15px 30px;background-color:#fff5f6;">
                                        <tr>
                                            <td style="text-align:center;">
                                                <p style="color:#999;font-family:Arial,sans-serif;font-size:12px;margin:0 0 5px;text-transform:uppercase;letter-spacing:1px;">Kode Promo</p>
                                                <p style="color:#f5576c;font-family:monospace;font-size:28px;font-weight:800;margin:0;letter-spacing:3px;">DISKON50</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="0" cellspacing="0" style="margin:25px auto;" align="center">
                            <tr>
                                <td style="background:linear-gradient(135deg,#f093fb,#f5576c);border-radius:8px;padding:14px 40px;">
                                    <a href="#" style="color:#ffffff;text-decoration:none;font-family:Arial,sans-serif;font-size:16px;font-weight:700;">Belanja Sekarang 🛒</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#fafafa;padding:25px 40px;border-top:1px solid #eee;text-align:center;">
                        <p style="color:#999;font-family:Arial,sans-serif;font-size:12px;margin:0 0 5px;">*Syarat dan ketentuan berlaku. Promo tidak dapat digabungkan.</p>
                        <p style="color:#bbb;font-family:Arial,sans-serif;font-size:11px;margin:8px 0 0;">
                            <a href="#" style="color:#f5576c;text-decoration:underline;">Berhenti Berlangganan</a>
                        </p>
                        <p style="color:#ccc;font-family:Arial,sans-serif;font-size:11px;margin:8px 0 0;">&copy; ' . date('Y') . ' Nama Perusahaan. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>',
                'is_active' => true,
            ],
            [
                'name' => 'Notifikasi Sistem',
                'body_html' => '
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#eef2f7;padding:20px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06);">
                <tr>
                    <td style="background-color:#1e293b;padding:20px 40px;">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td><h1 style="color:#ffffff;margin:0;font-family:Arial,sans-serif;font-size:20px;font-weight:600;">🔔 Notifikasi</h1></td>
                                <td style="text-align:right;"><span style="color:rgba(255,255,255,0.5);font-family:Arial,sans-serif;font-size:12px;">Pesan otomatis</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 40px;">
                        <h2 style="color:#1e293b;font-family:Arial,sans-serif;font-size:18px;margin:0 0 15px;">Informasi Penting</h2>
                        <p style="color:#555;font-family:Arial,sans-serif;font-size:14px;line-height:1.7;margin:0 0 20px;">
                            Kami ingin menginformasikan bahwa ada update penting terkait akun Anda. Silakan cek detail di bawah ini.
                        </p>
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin:20px 0;">
                            <tr>
                                <td style="padding:20px;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;"><span style="color:#64748b;font-family:Arial,sans-serif;font-size:13px;">Status</span></td>
                                            <td style="padding:8px 0;border-bottom:1px solid #e2e8f0;text-align:right;"><span style="background-color:#dcfce7;color:#166534;font-family:Arial,sans-serif;font-size:12px;font-weight:600;padding:3px 10px;border-radius:4px;">Aktif</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:8px 0;"><span style="color:#64748b;font-family:Arial,sans-serif;font-size:13px;">Tanggal</span></td>
                                            <td style="padding:8px 0;text-align:right;"><span style="color:#334155;font-family:Arial,sans-serif;font-size:13px;font-weight:600;">' . date('d M Y') . '</span></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table cellpadding="0" cellspacing="0" style="margin:20px 0;">
                            <tr>
                                <td style="background-color:#1e293b;border-radius:6px;padding:11px 28px;">
                                    <a href="#" style="color:#ffffff;text-decoration:none;font-family:Arial,sans-serif;font-size:14px;font-weight:600;">Lihat Detail →</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#f8fafc;padding:20px 40px;border-top:1px solid #e2e8f0;text-align:center;">
                        <p style="color:#94a3b8;font-family:Arial,sans-serif;font-size:11px;margin:0;">
                            Email ini dikirim secara otomatis. Jika butuh bantuan, hubungi
                            <a href="mailto:support@example.com" style="color:#3b82f6;text-decoration:none;">support@example.com</a>
                        </p>
                        <p style="color:#cbd5e1;font-family:Arial,sans-serif;font-size:11px;margin:8px 0 0;">&copy; ' . date('Y') . ' Nama Perusahaan</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>',
                'is_active' => true,
            ],
            [
                'name' => 'Minimal Clean',
                'body_html' => '
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#ffffff;padding:15px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:20px 0;border-bottom:2px solid #111;">
                        <h1 style="color:#111;margin:0;font-family:Georgia,serif;font-size:24px;font-weight:400;letter-spacing:2px;text-transform:uppercase;">Nama Brand</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px 0;">
                        <p style="color:#333;font-family:Georgia,serif;font-size:16px;line-height:1.8;margin:0 0 20px;">Halo,</p>
                        <p style="color:#333;font-family:Georgia,serif;font-size:16px;line-height:1.8;margin:0 0 20px;">
                            Tulis isi pesan Anda di sini. Template ini dirancang untuk komunikasi yang clean dan profesional — cocok untuk segala keperluan.
                        </p>
                        <p style="color:#333;font-family:Georgia,serif;font-size:16px;line-height:1.8;margin:0 0 5px;">Salam hangat,</p>
                        <p style="color:#333;font-family:Georgia,serif;font-size:16px;line-height:1.8;margin:0;font-style:italic;">Tim Anda</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:25px 0;border-top:1px solid #ddd;text-align:center;">
                        <p style="color:#999;font-family:Arial,sans-serif;font-size:11px;margin:0;">
                            <a href="#" style="color:#999;text-decoration:underline;">Berhenti Berlangganan</a>
                        </p>
                        <p style="color:#ccc;font-family:Arial,sans-serif;font-size:11px;margin:6px 0 0;">&copy; ' . date('Y') . ' Nama Brand</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
}
