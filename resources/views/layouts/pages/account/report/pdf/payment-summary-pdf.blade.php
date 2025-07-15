<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Head wise Payment Summary</title>
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
                <th colspan="5">
                    <h1>{{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>Account Head wise Payment Summary for {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                </th>
            </tr>
            <tr>
                <th>Account Head</th>
                <th>Account Description</th>
                <th>Tot No</th>
                <th>Payment</th>
                <th>Deduction</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupedData as $item)
                <tr>
                    <td>{{ $item['ledger_head'] }}</td>
                    <td>
                        {{ $item['ledger_name'] }}<br>
                        {{ $item['major_head_name'] }}
                    </td>
                    <td>{{ $item['count'] }}</td>
                    <td>{{ $item['payment_amount'] != 0 ? number_format($item['payment_amount'], 2) : '' }}</td>
                    <td>{{ $item['deduction_amount'] != 0 ? number_format($item['deduction_amount'], 2) : '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Total:</th>
                <th>{{ $groupedData->sum('count') }}</th>
                <th>{{ number_format($groupedData->sum('payment_amount'), 2, '.', '') }}</th>
                <th>{{ number_format($groupedData->sum('deduction_amount'), 2, '.', '') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
