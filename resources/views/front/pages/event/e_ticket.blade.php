<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $eventUser->event->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #e8ecf1;
            padding: 30px 15px;
            min-height: 100vh;
        }

        .print-actions {
            text-align: center;
            margin-bottom: 25px;
        }
        .btn-action {
            display: inline-block;
            padding: 12px 28px;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            margin: 0 5px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-print { background: #3A2195; }
        .btn-back { background: #6c757d; }

        /* ===== TICKET ===== */
        .ticket {
            max-width: 820px;
            margin: 0 auto;
            display: flex;
            background: #fff;
            border-radius: 16px;
            overflow: visible;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            position: relative;
        }

        /* --- LEFT: Main Ticket --- */
        .ticket-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ticket-header {
            background: #5bc0de;
            color: #fff;
            padding: 28px 30px;
            position: relative;
        }
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: repeating-linear-gradient(90deg, transparent, transparent 8px, #fff 8px, #fff 12px);
            opacity: 0.2;
        }
        .ticket-event-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.8;
            margin-bottom: 6px;
        }
        .ticket-event-name {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.3;
        }

        .ticket-body {
            padding: 25px 30px;
            flex: 1;
        }
        .ticket-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 25px;
        }
        .ticket-field label {
            display: block;
            font-size: 0.65rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 3px;
        }
        .ticket-field p {
            font-size: 0.88rem;
            font-weight: 600;
            color: #222;
            word-break: break-word;
        }
        .ticket-field.full {
            grid-column: 1 / -1;
        }

        .ticket-bottom {
            background: #f7f8fa;
            padding: 12px 30px;
            font-size: 0.72rem;
            color: #999;
            border-top: 1px solid #eee;
        }

        /* --- PERFORATION (tear line) --- */
        .ticket-perforation {
            width: 32px;
            position: relative;
            flex-shrink: 0;
        }
        .ticket-perforation::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            border-left: 2px dashed #d0d0d0;
        }
        /* Notch holes top and bottom */
        .ticket-perforation .notch-top,
        .ticket-perforation .notch-bottom {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 12px;
            background: #e8ecf1;
            border-radius: 0 0 12px 12px;
        }
        .ticket-perforation .notch-top {
            top: -1px;
            border-radius: 0 0 12px 12px;
        }
        .ticket-perforation .notch-bottom {
            bottom: -1px;
            border-radius: 12px 12px 0 0;
        }
        /* Scissors icon */
        .ticket-perforation .scissors {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(90deg);
            font-size: 14px;
            color: #bbb;
        }

        /* --- RIGHT: Stub --- */
        .ticket-stub {
            width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px 15px;
            background: #fafbfc;
            flex-shrink: 0;
        }
        .ticket-stub .qr-code {
            width: 130px;
            height: 130px;
            margin-bottom: 12px;
        }
        .ticket-stub .qr-code img {
            width: 100%;
            height: 100%;
        }
        .ticket-stub .stub-id {
            font-family: 'Space Mono', monospace;
            font-size: 0.6rem;
            color: #999;
            letter-spacing: 1px;
            text-align: center;
            word-break: break-all;
            margin-bottom: 10px;
        }
        .ticket-stub .stub-label {
            font-size: 0.6rem;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .ticket-stub .stub-event {
            font-size: 0.7rem;
            font-weight: 700;
            color: #2a80b9;
            text-align: center;
            margin-top: 5px;
            line-height: 1.3;
        }

        /* ===== PRINT ===== */
        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm;
            }
            body {
                background: #fff !important;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-actions { display: none !important; }
            .ticket {
                box-shadow: none;
                max-width: 100%;
                border: 1px solid #ddd;
            }
            .ticket-perforation .notch-top,
            .ticket-perforation .notch-bottom {
                background: #fff;
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .ticket {
                flex-direction: column;
                border-radius: 12px;
            }
            .ticket-perforation {
                width: 100%;
                height: 24px;
            }
            .ticket-perforation::before {
                top: 50%;
                bottom: auto;
                left: 0;
                right: 0;
                width: 100%;
                height: 0;
                border-left: none;
                border-top: 2px dashed #d0d0d0;
                transform: none;
            }
            .ticket-perforation .notch-top,
            .ticket-perforation .notch-bottom {
                display: none;
            }
            .ticket-perforation .scissors {
                transform: translate(-50%, -50%) rotate(0deg);
            }
            .ticket-stub {
                width: 100%;
                padding: 20px;
                flex-direction: row;
                gap: 20px;
            }
            .ticket-stub .qr-code {
                width: 90px;
                height: 90px;
                margin-bottom: 0;
            }
            .ticket-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="print-actions">
        <button class="btn-action btn-print" onclick="window.print()">🖨️ Cetak E-Ticket</button>
        <a href="{{ route('event.show', $eventUser->event->slug) }}" class="btn-action btn-back">← Kembali ke Event</a>
    </div>

    <div class="ticket">

        <!-- MAIN TICKET -->
        <div class="ticket-main">
            <div class="ticket-header">
                <div class="ticket-event-label">E-Ticket Peserta</div>
                <div class="ticket-event-name">{{ $eventUser->event->name }}</div>
            </div>

            <div class="ticket-body">
                <div class="ticket-grid">
                    <div class="ticket-field">
                        <label>Nama Peserta</label>
                        <p>{{ $eventUser->name }}</p>
                    </div>
                    <div class="ticket-field">
                        <label>Email</label>
                        <p>{{ $eventUser->email }}</p>
                    </div>
                    <div class="ticket-field">
                        <label>Waktu Kegiatan</label>
                        <p>{{ $eventUser->event->datetime ?: '-' }}</p>
                    </div>
                    <div class="ticket-field">
                        <label>Lokasi</label>
                        <p>{{ $eventUser->event->location ?? 'Online' }}</p>
                    </div>
                    @if($eventUser->phone)
                    <div class="ticket-field">
                        <label>No. Telepon</label>
                        <p>{{ $eventUser->phone }}</p>
                    </div>
                    @endif
                    <div class="ticket-field">
                        <label>Tanggal Registrasi</label>
                        <p>{{ $eventUser->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="ticket-bottom">
                &copy; {{ date('Y') }} Nagari Sastra &mdash; Tunjukkan e-ticket ini kepada panitia saat registrasi ulang di lokasi kegiatan.
            </div>
        </div>

        <!-- PERFORATION (tear line) -->
        <div class="ticket-perforation">
            <div class="notch-top"></div>
            <div class="scissors">✂</div>
            <div class="notch-bottom"></div>
        </div>

        <!-- STUB (tear-off section) -->
        <div class="ticket-stub">
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $eventUser->id}}"
                     alt="QR Code">
            </div>
            <div class="stub-id">{{ strtoupper(substr($eventUser->id, 0, 8)) }}</div>
            <div class="stub-label">Scan untuk verifikasi</div>
            <div class="stub-event">{{ Str::limit($eventUser->event->name, 30) }}</div>
        </div>

    </div>

</body>
</html>
