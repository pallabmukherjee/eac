<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Voucher - PDF</title>
    <link rel="icon" href="{{ URL::asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            padding: 10px;
        }


        th {
            border-bottom: 1px solid #000;
        }
        td{
            border-bottom: 1px dotted #dbe0e5;
        }
        th {
            padding:0px;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 5px;
        }
        td {
            padding: 5px;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            font-family: Arial, sans-serif;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 0
        }
        p {
            font-family: Arial, sans-serif;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 5px 0 10px;
        }
    </style>
</head>
<body>
    <div style="margin-top: 30px;">
        <table class="details-table">
            <tr>
                <td style="border: none; padding:0">
                    <h4 style="margin: 0">{{ $website->organization ?? "Please update your organization name from website setting" }}</h4>
                    <h4 style="margin: 2px 0 8px">Form 98 [Vide Rules 17A, 22B & 230]</h4>
                </td>
                <td style="border: none; padding:0">
                    <h3 style="margin: 0; text-decoration: underline;">Payment Voucher</h3>
                </td>
                <td style="border: none; padding:0" width="17%">
                    <h5 style="margin: 0 0 5px">No.: {{ $paymentVoucher->bill_no }}</h5>
                    <h5 style="margin: 0">Date: {{ \Carbon\Carbon::parse($paymentVoucher->date)->format('d/m/Y') }}</h5>
                </td>
            </tr>
        </table>
    </div>



    <div style="margin-top: 30px;">
        <table class="details-table">
            <tr>
                <td style="border: none; padding:3px 0" width="12%"><strong>Paid by:</strong></td>
                <td style="border: none; padding:3px 0">{{ $paymentVoucher->schemeFund->ledgers_head_code ?? 'N/A' }} ({{ $paymentVoucher->schemeFund->name ?? 'N/A' }})</td>
            </tr>
            <tr>
                <td style="border: none; padding:3px 0" width="12%"><strong>Pay to:</strong></td>
                <td style="border: none; padding:3px 0">{{ $paymentVoucher->beneficiary->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border: none; padding:3px 0" width="12%"><strong>Bank:</strong></td>
                @php
                    $relatedLedger = \App\Models\DetailedHead::where('ledgers_head_code', $paymentVoucher->bank)->first();
                @endphp
                <td style="border: none; padding:3px 0">{{ $paymentVoucher->bank }} ({{ $relatedLedger->name }})</td>
            </tr>
            <tr>
                <td style="border: none; padding:3px 0" width="12%"><strong>Bill Ref.:</strong></td>
                <td style="border: none; padding:3px 0">{{ $paymentVoucher->reference_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="border: none; padding:3px 0" width="12%"><strong>Ref. Date:</strong></td>
                <td style="border: none; padding:3px 0">{{ \Carbon\Carbon::parse($paymentVoucher->reference_date)->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px;">
        <table class="details-table">
            <thead>
                <tr>
                    <th>Srl</th>
                    <th>Account Head</th>
                    <th>Payment</th>
                    <th>Deduction</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledgerItem as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>( {{ $item->ledger }} ) {{ $item->ledgername->name }}</td>
                    <td>{{ $item->pay_deduct == 1 ? $item->amount : '' }}</td>
                    <td>{{ $item->pay_deduct == 2 ? $item->amount : '' }}</td>
                    <td></td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align:right;"><strong>Total:</strong></td>
                    <td><strong>{{ $pay }}</strong></td>
                    <td><strong>{{ $deduct }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;"><strong>Net Payable:</strong></td>
                    <td><strong>{{ $totalAmount }}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="margin-top: 30px; padding:10px">
        <label><strong>Narration:</strong> {{ $paymentVoucher->narration }}</label>
        <h5>{{ $totalAmountInWords }}</h5>
    </div>
</body>
</html>
