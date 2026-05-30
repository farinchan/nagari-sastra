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

        .header {
            margin-top: 105px;
            text-align: center;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            text-decoration: underline;
        }

        .invoice-meta {
            margin: 18px auto 0;
            width: 100%;
            max-width: 520px;
            font-size: 14px;
        }

        .invoice-meta td {
            padding: 2px 0;
            vertical-align: top;
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

        .notice {
            margin-top: 18px;
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
            margin: 0px 0 0 -70px;
        }
    </style>
</head>

<body style="line-height: 1.3;">
    <div style="position: absolute; top: -33; left: -34; width: 113%; height: 109%; z-index: -1;">
        <img src="{{ public_path('ext_images/template.png') }}" style="width: 100%; height: 100%; object-fit: cover;"
            alt="">
    </div>
    <h2 style="margin-top: 125px; font-weight: bold; text-decoration: underline;">INVOICE</h2>
    <p style="top: 175px; left: 50%; transform: translateX(-50%); position: absolute; font-size: 14px; text-align: center; width: 100%;">
        NO: {{ $number }}
    </p>


    <div class="content">
        <p>
            To:<br>
            <b>{{ $name }}</b><br>
            {{ $affiliation }}
        </p>

        <p style="margin-bottom: 0">Dear Sir/Mam,</p>

        <p style="margin-top: 2">
            We hope this letter finds you well. Please find below the invoice details for the manuscript submission and
            publication administration fee associated with your article.
        </p>

        <p>To the author of the following manuscript,</p>

        <table class="detail-table">
            <tr>
                <td class="detail-label">Author Name</td>
                <td style="width: 12px;">:</td>
                <td>{{ $authorship }}</td>
            </tr>
            <tr>
                <td class="detail-label">Article Title</td>
                <td>:</td>
                <td><strong>{{ $title }}</strong></td>
            </tr>
            <tr>
                <td class="detail-label">Invoice Amount</td>
                <td>:</td>
                <td><strong>{{ $payment_percent . '%' }} - @money($payment_amount)</strong></td>
            </tr>
            <tr>
                <td class="detail-label">Payment Deadline</td>
                <td>:</td>
                <td><strong>{{ $payment_due_date }}</strong></td>
            </tr>
        </table>

        <p>
            This invoice is issued for the article submission and publication administration fee related to
            <strong>{{ $journal }}</strong>. Please complete the payment before the deadline stated above.
        </p>

        <p class="notice">
            Please complete the payment directly through the payment link below:
            <a href="{{ route('payment.show', ['invoice_number' => str_replace('/', '-', $number)]) }}" target="_blank">
                {{ route('payment.show', ['invoice_number' => str_replace('/', '-', $number)]) }}
            </a>.
            If you need assistance, please contact Mr. Fajri Rinaldi Chan via WhatsApp at +62 822-8835-8026.
        </p>

        <div class="signature">
            <p>Padang, {{ $date }}</p>
            <p>Direktur,</p>
            <img style="height: 160px;" src="{{ public_path('ext_images/ttd.png') }}" alt="">
            <p><strong>Fajri Rinaldi Chan, S.Pd., M.Kom</strong><br>
                {{-- <small>Scopus ID. 57216153330</small> --}}
            </p>
        </div>
    </div>

    {{-- <img style="position: absolute; bottom: -20; right: 10;  width: 100px;" src="{{ $journal_thumbnail }}"
        alt=""> --}}

</body>

</html>
