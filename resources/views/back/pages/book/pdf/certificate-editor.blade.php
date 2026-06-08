<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Editor</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { font-family: 'Times New Roman', serif; margin: 0; padding: 0; }
        .certificate { width: 100%; height: 100%; position: relative; text-align: center; padding: 60px 80px; box-sizing: border-box; }
        .certificate .border-outer { border: 3px solid #1a3c6e; padding: 20px; height: calc(100% - 120px); }
        .certificate .border-inner { border: 1px solid #1a3c6e; padding: 40px; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .certificate h1 { font-size: 36px; color: #1a3c6e; margin-bottom: 5px; letter-spacing: 3px; text-transform: uppercase; }
        .certificate h2 { font-size: 18px; color: #666; font-weight: normal; margin-bottom: 30px; }
        .certificate .recipient { font-size: 28px; font-weight: bold; color: #1a3c6e; border-bottom: 2px solid #1a3c6e; padding-bottom: 5px; margin-bottom: 15px; display: inline-block; }
        .certificate .description { font-size: 14px; color: #333; line-height: 1.8; max-width: 600px; margin: 0 auto 30px; }
        .certificate .book-title { font-size: 16px; font-weight: bold; color: #1a3c6e; font-style: italic; }
        .certificate .footer { margin-top: 40px; font-size: 12px; color: #666; }
        .certificate .date { font-size: 13px; color: #333; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer">
            <div class="border-inner">
                <h1>Sertifikat</h1>
                <h2>Editor Buku</h2>
                <p style="font-size: 12px; color: #888; margin-bottom: 20px;">No: {{ $nomor_surat ?? '' }}</p>
                <p style="font-size: 14px; color: #666;">Diberikan kepada:</p>
                <div class="recipient">{{ $editor->display_name_with_title ?? $editor->display_name }}</div>
                @if($editor->affiliation)
                    <p style="font-size: 13px; color: #888; margin-top: 5px;">{{ $editor->affiliation }}</p>
                @endif
                <div class="description">
                    Sebagai editor dalam penerbitan buku berjudul:
                    <br>
                    <span class="book-title">"{{ $book->title }}"</span>
                    <br><br>
                    @if($book->isbn) ISBN: {{ $book->isbn }} <br> @endif
                    Penerbit: {{ $book->publisher ?? '-' }} | Tahun: {{ $book->publish_year ?? '-' }}
                </div>
                <div class="date">{{ $date }}</div>
                <div class="footer">
                    {{ $setting_web->name ?? 'Torkata Research' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
