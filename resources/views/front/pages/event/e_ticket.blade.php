<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $eventUser->event->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }
        .ticket-container {
            max-width: 700px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .ticket-header {
            background: #2a80b9;
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .ticket-header h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        .ticket-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .ticket-body {
            padding: 30px;
        }
        .ticket-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }
        .info-item {
            flex: 1 1 45%;
        }
        .info-item label {
            display: block;
            font-size: 0.75rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .info-item p {
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
        }
        .ticket-divider {
            border: none;
            border-top: 2px dashed #e0e0e0;
            margin: 25px 0;
        }
        .ticket-qr {
            text-align: center;
            padding: 20px;
        }
        .ticket-qr img {
            max-width: 180px;
        }
        .ticket-id {
            text-align: center;
            font-size: 0.85rem;
            color: #888;
            margin-top: 10px;
        }
        .ticket-footer {
            background: #f8f9fa;
            padding: 15px 30px;
            text-align: center;
            font-size: 0.8rem;
            color: #888;
        }
        .btn-print {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 30px;
            background: #2a80b9;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-print:hover { background: #236fa1; }
        .print-actions { text-align: center; margin-bottom: 20px; }
        @media print {
            body { background: #fff; padding: 0; }
            .print-actions { display: none; }
            .ticket-container { box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak E-Ticket</button>
        <a href="{{ route('event.show', $eventUser->event->slug) }}" class="btn-print" style="background: #6c757d;">← Kembali</a>
    </div>

    <div class="ticket-container">
        <div class="ticket-header">
            <h1>{{ $eventUser->event->name }}</h1>
            <p>E-Ticket Peserta</p>
        </div>

        <div class="ticket-body">
            <div class="ticket-info">
                <div class="info-item">
                    <label>Nama Peserta</label>
                    <p>{{ $eventUser->name }}</p>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <p>{{ $eventUser->email }}</p>
                </div>
                <div class="info-item">
                    <label>Waktu</label>
                    <p>{{ $eventUser->event->datetime ? \Carbon\Carbon::parse($eventUser->event->datetime)->format('d M Y, H:i') . ' WIB' : '-' }}</p>
                </div>
                <div class="info-item">
                    <label>Lokasi</label>
                    <p>{{ $eventUser->event->location ?? 'Online' }}</p>
                </div>
                @if($eventUser->phone)
                <div class="info-item">
                    <label>No. Telepon</label>
                    <p>{{ $eventUser->phone }}</p>
                </div>
                @endif
                <div class="info-item">
                    <label>Terdaftar</label>
                    <p>{{ $eventUser->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <hr class="ticket-divider">

            <div class="ticket-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('event.eticket', $eventUser->id)) }}"
                     alt="QR Code">
                <div class="ticket-id">ID: {{ $eventUser->id }}</div>
            </div>
        </div>

        <div class="ticket-footer">
            &copy; {{ date('Y') }} Nagari Sastra &mdash; E-Ticket ini adalah bukti pendaftaran yang sah
        </div>
    </div>

</body>
</html>
