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
                    <h4 style="margin: 2px 0 8px">Form No 97 [ Vide Rules 17 & 249 ]</h4>
                </td>
                <td style="border: none; padding:0">
                    <h3 style="margin: 0; text-decoration: underline;">Receipt Voucher</h3>
                </td>
                <td style="border: none; padding:0" width="17%">
                    <h5 style="margin: 0 0 5px">No.: {{ $receiptVoucher->bill_no }}</h5>
                    <h5 style="margin: 0">Date: {{ \Carbon\Carbon::parse($receiptVoucher->date)->format('d/m/Y') }}</h5>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center;">Received From: Chairman, Krishnagar Municipality. [00001] R.N. Thakur Road, P.O. Krishnagar, Dist: Nadia, Pin: 741101</td>
            </tr>
        </table>
    </div>



    <div style="margin-top: 30px;">
        <table class="details-table">
            @php
                $totalCash = 0;
                $totalCheque = 0;
                $totalOnline = 0;
            @endphp
            <thead>
                <tr>
                    <th>On Account of (Account Head)</th>
                    <th>Amount</th>
                    <th>Remarks (if any) </th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledgerItem as $item)
                @php
                    $totalCash += $item->cash_amount ?? 0;
                    $totalCheque += $item->cheque_amount ?? 0;
                    $totalOnline += $item->online_amount ?? 0;
                @endphp
                <tr>
                    <td>{{ $item->ledgerHead->ledgers_head_code }} {{ $item->ledgerHead->name }}</td>
                    <td>{{ $item->cash_amount + $item->cheque_amount + $item->online_amount }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="1" style="text-align:right;"><strong>TOTAL:</strong></td>
                    <td><strong>{{ $totalAmount }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <div style="padding: 10px">
            <label style="font-size: 14px">BUSES: <br>The Sum of {{ $totalAmountInWords }}</label><br><br>
            <label style="font-size: 14px">(in figures): Rs. {{ number_format($totalAmount, 2) }}  [Cash: {{ number_format($totalCash, 2) }} , Cheque: {{ number_format($totalCheque, 2) }} , Online: {{ number_format($totalOnline, 2) }}]</label>
        </div>

        <table class="details-table" style="margin-top: 50px">
            <tbody>
                <tr>
                    <td style="border: none; width:50%;">Cashier</td>
                    <td style="border: none; width:50%; text-align:right;">Vice Chairman/Auth.Signatory/E.0. </td>
                </tr>
            </tbody>
        </table>
    </div>


</body>
</html>
