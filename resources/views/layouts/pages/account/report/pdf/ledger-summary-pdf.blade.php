<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEDGER FOR THE PERIOD Report</title>
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
                    <h1>Form 96 [Vide Rule 8(4), Rules 239 & 256]</h1>
                    <h1>Name of ULB : {{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>LEDGER FOR THE PERIOD {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                </th>
            </tr>
            <tr>
                @php
                    use App\Models\DetailedHead;
                    $detailedHead = DetailedHead::where('ledgers_head_code', $ledgerCode)->first();
                @endphp
                <th colspan="2"><strong>Ledger Name: {{ $detailedHead->name }}</strong></th>
                <th colspan="3"><strong>Ledger Code: {{ $ledgerCode }}</strong></th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Vch Type</th>
                <th>Vch No</th>
                <th>Debit</th>
                <th>Credit</th>
            </tr>
        </thead>
        @php
            $debit = 0; // Sum for paytype == 2
            $credit = 0; // Sum for paytype == 1
        @endphp
        <tbody>
            @foreach($combinedReports as $report)
            @php
                if ($report['paytype'] == 2) {
                    $debit += $report['amount'];
                }
                if ($report['paytype'] == 1) {
                    $credit += $report['amount'];
                }
            @endphp
            <tr>
                <td>{{ $report['date'] ?? 'N/A' }}</td> <!-- Date -->
                <td>{{ $report['type'] }}</td>
                <td>{{ $report['bill_no'] }}</td>
                <td>
                    @if ($report['paytype'] == 2)
                    {{ $report['amount'] }}
                    @endif
                </td>
                <td>
                    @if ($report['paytype'] == 1)
                    {{ $report['amount'] }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Transaction:</th>
                <th>{{ number_format($debit, 2) }}</th>
                <th>{{ number_format($credit, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</table>
</body>
</html>
