<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
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

        .signature img {
            display: block;
            margin: 8px 0;
        }
    </style>
</head>

<body style="line-height: 1.3;">
    <div style="position: absolute; top: -33; left: -34; width: 113%; height: 109%; z-index: -1;">
        <img src="{{ public_path('ext_images/template.png') }}" style="width: 100%; height: 100%; object-fit: cover;"
            alt="">
    </div>
    <h2 style="margin-top: 125px; font-weight: bold; text-decoration: underline;">LETTER OF ACCEPTANCE</h2>
    <p style="top: 175px; left: 50%; transform: translateX(-50%); position: absolute; font-size: 14px; text-align: center; width: 100%;">
        NO: {{ $number }}
    </p>

    <div class="content">
        <p>
            To:<br>
            <b>{{ $name }}</b><br>
            {{ $affiliation }}
        </p>

        <p style="margin-bottom: 0">Dear Sir/Madam,</p>

        <p style="margin-top: 2">
            Thank you for your cooperation in performing all the changes requested by the reviewers. At the same time,
            we gladly inform you that your paper has been <strong>accepted</strong> for publication.
        </p>

        <table class="detail-table">
            <tr>
                <td class="detail-label">Authors</td>
                <td style="width: 12px;">:</td>
                <td>{{ $authors_string }}</td>
            </tr>
            <tr>
                <td class="detail-label">Article Title</td>
                <td>:</td>
                <td><strong>{{ $title }}</strong></td>
            </tr>
            <tr>
                <td class="detail-label">Journal</td>
                <td>:</td>
                <td><strong>{{ $journal }}</strong></td>
            </tr>
            <tr>
                <td class="detail-label">Edition</td>
                <td>:</td>
                <td><strong>{{ $edition }}</strong></td>
            </tr>
        </table>

        <p>
            This letter serves as official confirmation that your manuscript has been accepted for publication in
            <strong>{{ $journal }}</strong>. Your article will be published in Edition {{ $edition }}.
        </p>

        <p>
            Your article can be accessed at the following link:
            <a href="{{ $article_url }}">{{ $article_url }}</a>
        </p>

        <p>
            Thank you for making Journal {{ $journal }} a vehicle for your research interests.
        </p>

        <table style="width: 100%; margin-top: 30px;">
            <tr>
                <td style="vertical-align: bottom; width: 50%;">
                    <img style="width: 120px;" src="{{ $journal_thumbnail }}" alt="">
                </td>
                <td style="vertical-align: top; text-align: left; width: 50%;">
                    <p style="margin: 0;">Padang, {{ $date }}</p>
                    <p style="margin: 0;">Editor in Chief,</p>
                    @if($chief_editor_signature)
                        <img style="height: 110px; display: block; margin: 8px 0;" src="{{ $chief_editor_signature }}" alt="">
                    @else
                        <div style="height: 110px;"></div>
                    @endif
                    <p style="margin: 0;"><strong>{{ $chief_editor }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
