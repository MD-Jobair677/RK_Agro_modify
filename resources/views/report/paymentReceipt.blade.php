<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Cash Receipt</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;

            padding: 0;
            color: #000;
        }

        .receipt {
            width: 100%;
            margin: auto;
            border: 1px solid #000;
            padding: 10px;
            margin-top: 15px;
            /* height:45%; */
        }

        /* HEADER */
        .header-table {
            width: 100%;
            margin-bottom: 5px;
        }

        .header-left {
            width: 70%;

            font-size: 22px;
            font-weight: bold;
        }

        .header-left small {
            display: block;
            font-size: 11px;
            font-weight: normal;
        }

        .header-right {
            width: 30%;

            text-align: right;
            vertical-align: top;

        }

        .receipt-no {
            font-size: 18px;
            font-weight: bold;
            font-size: 20px;
        }

        .receipt-title {
            font-size: 13px;
            font-weight: bold;
        }


        .bar {
            height: 14px;
            background: #cfcfcf;
            margin-bottom: 10px;
        }


        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .content-table td {
            padding: 5px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 160px;
            font-size: 10px

        }

        .line {
            border-bottom: 1px solid #000;
        }


        .footer {
            font-size: 12px;
        }

        .sign-row {
            width: 100%;
            margin-top: 40px;
        }

        .sign-row td {
            width: 50%;
            text-align: center;
            padding-top: 10px;
        }

        .address {
            text-align: center;
            font-size: 11px;
            line-height: 1.3;
            margin-top: 10px;
        }
    </style>
</head>

<body>


    @php
        $copyTyps = [
            'customer' => 'Customer Copy',
            'office' => 'Office Copy',
            'account' => 'Account Copy',
        ];
    @endphp

    @forelse ($copyTyps as $key => $copyType)
        <div class="receipt">

            <table class="header-table">
                <tr>
                    <td class="header-left">

                        <img src="{{ public_path('assets/universal/images/logoFavicon/logo_dark.png') }}" alt="Logo"
                            width="200">

                    </td>
                    <td class="header-right">
                        <div class="receipt-no">#{{ $paymentReceiptsData->payment_uid }}</div>
                        <div class="receipt-title">CASH RECEIPT ({{ $copyType }})</div>
                    </td>
                </tr>
            </table>

            <div class="bar"></div>

            <table class="content-table">
                <tr>
                    <td class="label">DATE :</td>
                    <td class="line">{{ $paymentReceiptsData->printed_at }}</td>
                    <td class="label">JOB NUMBER :</td>
                    <td class="line">{{ $paymentReceiptsData->booking->booking_number }}</td>
                </tr>


                <tr>
                    <td class="label">SALE PRICE :</td>
                    <td class="line" colspan="3">{{ $paymentReceiptsData->booking->sale_price ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="label">AMOUNT RECEIVED :</td>
                    <td class="line" colspan="3">{{ $paymentReceiptsData->receipt_tk ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">TOTAL  AMOUNT RECEIVED :</td>
                    <td class="line" colspan="3">{{ $total_received ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">DUE PRICE :</td>
                    <td class="line" colspan="3">
                        {{ number_format($paymentReceiptsData->booking->due_price, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="label"> RECEIPT IN WORD :</td>
                    <td class="line" colspan="3">{{ $inword ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="label">FROM :</td>
                    <td class="line" colspan="3">
                        {{ optional($paymentReceiptsData->booking->customer)->first_name ?? 'N/A' }}
                        {{ optional($paymentReceiptsData->booking->customer)->last_name ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">ADDRESS :</td>
                    <td class="line" colspan="3">
                        {{ optional($paymentReceiptsData->booking->delivery_location)->district_city ?? 'N/A' }}
                        {{ optional($paymentReceiptsData->booking->delivery_location)->area ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">PHONE :</td>
                    <td class="line" colspan="3">
                        {{ optional($paymentReceiptsData->booking->customer)->phone ?? 'N/A' }}
                    </td>
                </tr>

                <tr>
                    <td class="label">FOR :</td>
                    <td class="line" colspan="3">
                        {{ $paymentReceiptsData->comment?? 'N/A' }}
                    </td>
                </tr>
            </table>

            <table class="footer sign-row">
                <tr>
                    <td>
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 5px;"></div>
                        CUSTOMER'S SIGNATURE
                    </td>
                    <td>
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 5px;"></div>
                        AUTHORISED SIGNATORY
                    </td>
                </tr>
            </table>


            <div class="address">
                2 No. Dhakeswari, Godenail, Narayanganj-1432, (Beside R.K Spinning Mills Ltd.)<br>
                Phone : +88 019 700 20 180
            </div>

        </div>

    @empty
        <h1>Cash Receipt not found</h1>
    @endforelse



</body>

</html>
