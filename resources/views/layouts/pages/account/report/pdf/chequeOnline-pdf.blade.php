<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheque Book Register (Payment)</title>
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
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="6">
                    <h1>{{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>Cheque Book Register (Payment) for {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                </th>
            </tr>
            <tr>
                <th>Cheque No</th>
                <th>Cheque Date</th>
                <th>Issued to ( Party Code & Name )</th>
                <th>Vch No</th>
                <th>Vch Date</th>
                <th>Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedData['grouped_payments'] as $payment)
                <tr>
                    <td>{{ $payment->voucherBank->cheque_no ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->voucherBank->date ?? 'N/A')->format('d/m/Y') }}</td>
                    <td>{{ $payment->beneficiary->name }}</td>
                    <td>{{ $payment->bill_no }}</td>
                    <td>{{ $payment->date }}</td>
                    <td>{{ $payment->netAmount }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total Net Amount:</strong></td>
                <td><strong>{{ $groupedData['totalNetAmount'] }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
