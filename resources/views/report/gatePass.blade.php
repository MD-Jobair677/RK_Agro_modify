<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Challan / Gate Pass</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 20px 10px;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 20px 10px;
            box-sizing: border-box;
        }

        /* Header Section */
        .header-row {
            margin-bottom: 20px;
            width: 100%;
            white-space: nowrap;
        }

        .left-header {
            display: inline-block;
            width: 40%;
            vertical-align: top;
        }

        .center-header {
            display: inline-block;
            width: 20%;
            text-align: center;
            vertical-align: top;
            font-size: 18px;
            font-weight: bold;
            padding-top: 25px;
        }

        .right-header {
            display: inline-block;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }

        .company-tagline {
            font-size: 12px;
            margin: 5px 0 0 0;
        }

        .challan-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        /* Form Fields */
        .form-section {
            margin: 15px 0;
        }

        .form-row {
            margin-bottom: 8px;
            line-height: 22px;
        }

        .field-label {
            display: inline-block;
            font-weight: bold;
            min-width: 160px;
        }

        .underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 400px;
            /* height: 18px; */
            vertical-align: bottom;
        }

        /* Table - Only boxes */
        .table-section {
            margin: 20px 0 25px 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            height: 30px;
            vertical-align: middle;
            text-align: center;
        }

        /* Signatures */
        .signature-section {
            margin-top: 30px;
        }

        .signature-row {
            margin-bottom: 60px;
        }

        .customer-signature {
            display: inline-block;
            width: 45%;
            font-weight: bold;
        }

        .authorized-signature {
            display: inline-block;
            width: 45%;
            text-align: right;
            font-weight: bold;
        }

        /* Address */
        .address-section {
            text-align: center;
            font-size: 11px;
            line-height: 1.4;
        }
    </style>
</head>

<body>


    @php
        $copyTyps = [
            'customer' => 'Customer Copy',
            'office' => 'Office Copy',
            'gate_pass' => 'Gate Pass',
        ];
    @endphp


    @forelse (  $copyTyps as   $copyTyp )
        <div class="container">

            <!-- Header - 3 Columns -->
            <div class="header-row">
                <div class="left-header">
                    <img src="{{ public_path('assets/universal/images/logoFavicon/logo_dark.png') }}" alt="Logo"
                        width="200">
                </div>

                <div class="center-header">
                    {{ $PrintsDatas->print_uid ?? 'N/A' }}
                </div>

                <div class="right-header">
                    <div class="challan-title">CHALLAN / GATE PASS / {{  $copyTyp}}</div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="form-section">
                <div class="form-row">
                    <span class="field-label">DATE :</span>
                    <span class="underline">{{ $PrintsDatas->printed_at ?? 'N/A' }}</span>
                </div>

                <div class="form-row">
                    <span class="field-label">JOB NUMBER :</span>
                    <span class="underline">{{ $PrintsDatas->booking->booking_number ?? 'N/A' }}</span>
                </div>

                <div class="form-row">
                    <span class="field-label">NAME OF THE CUSTOMER :</span>
                    <span class="underline">{{ $PrintsDatas->customer->first_name ?? '' }}
                        {{ $PrintsDatas->customer->last_name ?? '' }}</span>
                </div>

                <div class="form-row">
                    <span class="field-label">ADDRESS :</span>
                    <span class="underline">{{ $PrintsDatas->customer->address ?? '' }}</span>
                </div>

                <div class="form-row">
                    <span class="field-label">PHONE :</span>
                    <span class="underline">{{ $PrintsDatas->customer->phone ?? '' }}</span>
                </div>

                <div class="form-row">
                    <span class="field-label">COW ID :</span>
                    <span class="underline">&nbsp;</span>
                </div>
            </div>

            <!-- Table Section -->
            <div class="table-section">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>COW NAME</th>
                            <th>TAG NUMBER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($PrintsDatas->cattles as $index => $cattle)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cattle->name }}</td>
                                <td>{{ $cattle->tag_number }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center;">No Cattle Assigned</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Signatures -->
            <div class="signature-section">
                <div class="signature-row">
                    <span class="customer-signature">CUSTOMER'S SIGNATURE</span>
                    <span class="authorized-signature">AUTHORISED SIGNATORY</span>
                </div>
            </div>

            <!-- Address -->
            <div class="address-section">
                2 No. Dhakeswari, Godenail, Narayanganj-1432, (Beside R.K Spinning Mills Ltd.)<br>
                Phone : +88 019 700 20 180
            </div>

        </div>
        <div style="page-break-after: always;"></div>

    @empty
        <h1>NO Print Found</h1>
    @endforelse



</body>

</html>
