<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Letter of Acceptance - {{ $title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', Arial, sans-serif;
            line-height: 1.45;
            padding: 30px;
        }

        h2 {
            text-align: center;
        }

        .content {
            margin-top: 28px;
            font-size: 15px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 18px;
        }

        .detail-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .detail-label {
            width: 180px;
            color: #333;
        }

        .signature {
            margin-top: 30px;
            margin-left: auto;
            width: 290px;
            text-align: left;
        }

        .signature p {
            margin: 0;
            text-align: left;
        }
    </style>
</head>

<body style="line-height: 1.35;">
    <div style="position: absolute; top: -33; left: -34; width: 113%; height: 109%; z-index: -1;">
        <img src="{{ public_path('ext_images/template.png') }}" style="width: 100%; height: 100%; object-fit: cover;"
            alt="">
    </div>
    <h2 style="margin-top: 110px; font-weight: bold; text-decoration: underline;">LETTER OF ACCEPTANCE</h2>
    <p style="top: 158px; left: 50%; transform: translateX(-50%); position: absolute; font-size: 14px; text-align: center; width: 100%;">
        NO: {{ $number }}
    </p>

    <div class="content">
        <p style="margin-bottom: 0">Dear Author(s),</p>

        <p style="margin-top: 4px;">
            Thank you for submitting your book manuscript to our editorial board. We are pleased to inform you that following the editorial review and evaluation process, your book manuscript has been officially <strong>ACCEPTED</strong> for publication.
        </p>

        <table class="detail-table">
            <tr>
                <td class="detail-label">Author(s)</td>
                <td style="width: 12px;">:</td>
                <td>{{ $authors_string }}</td>
            </tr>
            <tr>
                <td class="detail-label">Book Title</td>
                <td style="width: 12px;">:</td>
                <td><strong>{{ $title }}</strong></td>
            </tr>
            <tr>
                <td class="detail-label">Category / Field</td>
                <td style="width: 12px;">:</td>
                <td>{{ $category }}</td>
            </tr>
            @if($isbn && $isbn != '-')
            <tr>
                <td class="detail-label">ISBN</td>
                <td style="width: 12px;">:</td>
                <td>{{ $isbn }}</td>
            </tr>
            @endif
            @if($qrcbn && $qrcbn != '-')
            <tr>
                <td class="detail-label">QRCBN</td>
                <td style="width: 12px;">:</td>
                <td>{{ $qrcbn }}</td>
            </tr>
            @endif
            @if(!empty($editors))
            <tr>
                <td class="detail-label">Editor(s)</td>
                <td style="width: 12px;">:</td>
                <td>{{ $editors }}</td>
            </tr>
            @endif
            <tr>
                <td class="detail-label">Publisher</td>
                <td style="width: 12px;">:</td>
                <td>{{ $publisher }}</td>
            </tr>
            <tr>
                <td class="detail-label">Publication Year</td>
                <td style="width: 12px;">:</td>
                <td>{{ $publish_year }}</td>
            </tr>
        </table>

        <p>
            This letter serves as official confirmation of acceptance for publication by <strong>{{ $publisher }}</strong>. The publishing team will proceed with the final editing, proofreading, layout typesetting, and book cataloging.
        </p>

        <p>
            The book catalog and publication page can be accessed at the following link:
            <br>
            <a href="{{ $book_url }}" style="color: #1a3c6e;">{{ $book_url }}</a>
        </p>

        <p>
            Thank you for entrusting your academic and literary work to <strong>{{ $publisher }}</strong>.
        </p>

        <table style="width: 100%; margin-top: 25px;">
            <tr>
                <td style="vertical-align: bottom; width: 45%;">
                    @if($book_thumbnail)
                        <img style="width: 110px; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);" src="{{ $book_thumbnail }}" alt="Cover Buku">
                    @endif
                </td>
                <td style="vertical-align: top; text-align: left; width: 55%;">
                    <p style="margin: 0;">Padang, {{ $date }}</p>
                    <p style="margin: 0;">Direktur,</p>
                    @if(!empty($director_signature))
                        <img style="height: 160px; display: block; margin: 4px 0 -20px -70px;" src="{{ $director_signature }}" alt="Tanda Tangan Direktur">
                    @else
                        <div style="height: 80px;"></div>
                    @endif
                    <p style="margin: 0;"><strong>{{ $director_name ?? 'Fajri Rinaldi Chan, S.Pd., M.Kom' }}</strong></p>
                    <p style="margin: 0; font-size: 13px; color: #555;">{{ $publisher }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
