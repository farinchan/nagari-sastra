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

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 18px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #333;
            padding: 8px 10px;
            font-size: 13px;
        }

        .items-table th {
            background-color: #f0f0f0;
            font-weight: 700;
            text-align: center;
        }

        .items-table td.text-right {
            text-align: right;
        }

        .items-table td.text-center {
            text-align: center;
        }

        .items-table tfoot td {
            font-weight: 700;
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
        <img src="{{ public_path('ext_images/template_letter.png') }}" style="width: 100%; height: 100%; object-fit: cover;"
            alt="">
    </div>
    <h2 style="margin-top: 125px; font-weight: bold; text-decoration: underline;">INVOICE</h2>
    <p style="top: 175px; left: 50%; transform: translateX(-50%); position: absolute; font-size: 14px; text-align: center; width: 100%;">
        NO: {{ $number }}
    </p>

    <div class="content">
        <p>
            To:<br>
            <b>{{ $kepada }}</b><br>
            {{ $kepada_detail }}
        </p>

        <p style="margin-bottom: 0">Dear Sir/Madam,</p>

        <p style="margin-top: 2">
            We hope this letter finds you well. Please find below the invoice details for your payment.
        </p>

        @if($items && count($items) > 0)
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Item</th>
                    <th style="width: 50px;">Qty</th>
                    <th style="width: 130px;">Amount</th>
                    <th style="width: 130px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item['name'] ?? '-' }}</strong>
                        @if(!empty($item['detail']))
                            <br><small>{{ $item['detail'] }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item['qty'] ?? 1 }}</td>
                    <td class="text-right">@money($item['amount'] ?? 0)</td>
                    <td class="text-right">@money(($item['qty'] ?? 1) * ($item['amount'] ?? 0))</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">Total</td>
                    <td class="text-right">@money($payment_amount)</td>
                </tr>
            </tfoot>
        </table>
        @endif

        <table class="detail-table">
            <tr>
                <td class="detail-label">Invoice Amount</td>
                <td style="width: 12px;">:</td>
                <td><strong>{{ $payment_percent }}% - @money($payment_amount)</strong></td>
            </tr>
            @if($payment_due_date)
            <tr>
                <td class="detail-label">Payment Deadline</td>
                <td>:</td>
                <td><strong>{{ $payment_due_date }}</strong></td>
            </tr>
            @endif
        </table>

        @if($keterangan)
        <p>
            <strong>Notes:</strong> {{ $keterangan }}
        </p>
        @endif

        @if($payment_link)
        <p>
            Please complete the payment directly through the payment link below:
            <a href="{{ $payment_link }}" target="_blank">{{ $payment_link }}</a>.
        </p>
        @endif

        <div class="signature">
            <p>Padang, {{ $date }}</p>
            <p>Direktur,</p>
            <img style="height: 160px;" src="{{ public_path('ext_images/ttd.png') }}" alt="">
            <p style="margin: -20px 0 0 0;"><strong>Fajri Rinaldi Chan, S.Pd., M.Kom</strong></p>
        </div>
    </div>

</body>

</html>
