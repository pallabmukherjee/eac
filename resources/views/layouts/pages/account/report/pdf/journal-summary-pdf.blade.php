<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Summary Report</title>
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
    <table>
        <thead>
            <tr>
                <th colspan="4">
                    <h1>{{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>Detail of Journal Voucher for {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                </th>
            </tr>
            <tr>
                <th>Srl.</th>
                <th>Account Head Code & Description</th>
                <th>DEBIT</th>
                <th>CREDIT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $voucher)
                <tr>
                    <td colspan="4"><strong style="padding-right: 30px">Vch.Date: {{ \Carbon\Carbon::parse($voucher->date)->format('d/m/Y') }} </strong> <strong>Vch.No: {{ $voucher->bill_no }}</strong></td>
                </tr>
                @foreach ($voucher->ledgerHeads as $ledgerHead)
                    <tr>
                        <td width="50px">{{ $loop->iteration }}</td>
                        <td width="450px">{{ $ledgerHead->ledger_head }} <span style="padding-left: 10px">{{ $ledgerHead->ledgerHead->name }}</span></td>
                        <td>
                            @if ($ledgerHead->crdr == 2)
                                {{ $ledgerHead->amount }}
                            @else

                            @endif
                        </td>
                        <td>
                            @if ($ledgerHead->crdr == 1)
                                {{ $ledgerHead->amount }}
                            @else

                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
