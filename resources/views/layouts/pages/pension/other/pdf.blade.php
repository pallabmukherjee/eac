<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pensioner Other Bill Details</title>
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
        .bordered-table,
        .bordered-table th,
        .bordered-table td {
            border: 1px solid #000;
        }
        th {
            padding: 5px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        p {
            text-align: center;
            font-size: 13px;
            font-weight: 400;
            margin: 5px 0 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .no-border td {
            border: none;
        }
    </style>
</head>
<body>
    <table class="bordered-table">
        <thead>
            <tr>
                <th colspan="8">
                    <h1>{{ $website->organization }}</h1>
                    <h1>Pensioner Other Bill Report Of {{ \Carbon\Carbon::parse($report->created_at)->format('F-Y') }}</h1>
                    @if($report->details)
                        <p>{{ $report->details }}</p>
                    @endif
                    <p>Voucher No _______________ Voucher Date _____________</p>
                </th>
            </tr>
            <tr>
                <th>Srl</th>
                <th>Name Of Pensioners</th>
                <th>Type Of Pension</th>
                <th>Pensioner In Case Of Family member</th>
                <th>PPO No</th>
                <th>Bank A/C</th>
                <th>IFSC</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total_amount = 0; @endphp
            @foreach($pensionersReport as $item)
                @php $total_amount += $item->amount; @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->pensionerDetails->pensioner_name }}</td>
                    <td>
                        @if ($item->pensionerDetails->pension_type == 1)
                            Self
                        @else
                            Family member
                        @endif
                    </td>
                    <td>{{ $item->pensionerDetails->family_name }}</td>
                    <td>{{ $item->pensionerDetails->ppo_number }}</td>
                    <td>{{ $item->pensionerDetails->savings_account_number }}</td>
                    <td>{{ $item->pensionerDetails->ifsc_code }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="7" class="text-right">Total</th>
                <th class="text-right">{{ number_format($total_amount, 2) }}</th>
            </tr>
        </tbody>
    </table>

    <br><br><br><br>
    <table>
        <tbody>
            <tr class="no-border">
                <td><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td>
                <td class="text-center">Dealing Assistant<br>{{ $website->organization }}</td>
                <td width="200px"></td>
                <td width="200px"></td>
                <td class="text-center">In-Charge, Pension cell<br>{{ $website->organization }}</td>
            </tr>
        </tbody>
    </table>
    <br><br><br>
    <table>
        <tbody>
            <tr class="no-border">
                <td class="text-center">Accountant<br>{{ $website->organization }}</td>
                <td class="text-center">Finance Officer<br>{{ $website->organization }}</td>
                <td class="text-center">Executive Officer.O<br>{{ $website->organization }}</td>
                <td class="text-center">Chairman<br>{{ $website->organization }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>