<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contra Summary Report</title>
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
                    <p>Detail of Contra Voucher for {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
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
            @foreach ($data as $voucherId => $vouchers)
                <tr>
                    <td colspan="4"><strong style="padding-right: 30px">Vch.Date: {{ \Carbon\Carbon::parse($vouchers->first()->date)->format('d/m/Y') }} </strong> <strong>Vch.No: {{ $vouchers->first()->bill_no }}</strong></td>
                </tr>
                @php
                    $counter = 1;
                @endphp
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td width="50px">{{ $counter }}</td>
                        <td width="450px">{{ $voucher->from_bank }} <span style="padding-left: 10px">{{ $voucher->fromBank->name }}</span></td>
                        <td>{{ $voucher->cash_amount + $voucher->cash_amount + $voucher->online_amount }}</td>
                        <td></td>
                    </tr>
                    @php $counter++; @endphp

                    <tr>
                        <td width="50px">{{ $counter }}</td>
                        <td width="450px">{{ $voucher->to_bank }} <span style="padding-left: 10px">{{ $voucher->toBank->name }}</span></td>
                        <td></td>
                        <td>{{ $voucher->cash_amount + $voucher->cash_amount + $voucher->online_amount }}</td>
                    </tr>
                    @php $counter++; @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
