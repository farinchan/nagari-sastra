<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $meta['title'] }}</title>
    <meta name="description" content="{{ $meta['description'] }}">
    <meta name="keywords" content="{{ $meta['keywords'] }}">
    <meta name="robots" content="noindex,nofollow">
    <style>
        :root {
            --preview-header-height: 72px;
            --page-max-width: 840px;
        }

        html,
        body {
            margin: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #dfe4ea;
            font-family: Arial, sans-serif;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--preview-header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 10px 16px;
            background: #ffffff;
            color: #0f172a;
            border-bottom: 1px solid #cbd5e1;
            z-index: 30;
            box-sizing: border-box;
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.04);
        }

        .topbar h1 {
            margin: 0;
            font-size: 16px;
            line-height: 1.25;
            font-weight: 700;
        }

        .topbar p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #475569;
        }

        .topbar .actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
            align-items: center;
        }

        .topbar a {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .topbar .back {
            background: #f8fafc;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }

        .topbar .open {
            background: #2563eb;
            color: #fff;
        }

        .viewer {
            position: fixed;
            top: var(--preview-header-height);
            left: 0;
            right: 0;
            bottom: 0;
            box-sizing: border-box;
            background: #d9dde3;
        }

        .pdf-frame {
            width: 100%;
            height: 100%;
            border: 0;
            background: #ffffff;
        }

        .fallback {
            width: min(var(--page-max-width), 100%);
            margin: 0 auto;
        }

        .fallback {
            color: #0f172a;
            padding: 24px 0;
            text-align: center;
        }

        .fallback a {
            color: #2563eb;
        }

        @media (max-width: 767.98px) {
            :root {
                --preview-header-height: 126px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                justify-content: center;
                height: auto;
                min-height: var(--preview-header-height);
                padding: 10px 12px;
            }

            .topbar h1 {
                font-size: 15px;
            }

            .topbar p {
                font-size: 11px;
            }

            .topbar .actions {
                width: 100%;
                flex-direction: column;
            }

            .topbar a {
                width: 100%;
                text-align: center;
                box-sizing: border-box;
            }

        }

        @media (max-width: 480px) {
            :root {
                --preview-header-height: 148px;
            }

            .topbar {
                padding: 12px 14px;
            }

            .topbar h1 {
                font-size: 14px;
            }

            .topbar .actions {
                gap: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="topbar">
        <div>
            <h1>{{ $book->title }}</h1>
            <p>{{ $book->authorString ?: $book->author ?: '-' }} | {{ $book->publisher ?: '-' }}</p>
        </div>
        <div class="actions">
            @if ($preview_url)
                <a href="{{ $preview_url }}" target="_blank" class="open">Buka PDF</a>
            @endif
            <a href="{{ route('book.show', $book->slug) }}" class="back">Kembali</a>
        </div>
    </div>

    @if ($preview_url)
        <div class="viewer">
            <iframe
                class="pdf-frame"
                src="{{ $preview_url }}#toolbar=1&navpanes=0&scrollbar=1"
                title="{{ $book->title }}"
                loading="lazy"
            ></iframe>
        </div>
    @else
        <div class="fallback">
            <div>
                <h1 style="margin: 0 0 12px; font-size: 24px;">Preview file tidak tersedia</h1>
                <p style="margin: 0 0 16px;">Buku ini belum memiliki file PDF untuk ditampilkan.</p>
                <a href="{{ route('book.show', $book->slug) }}">Kembali ke detail buku</a>
            </div>
        </div>
    @endif

</body>

</html>
