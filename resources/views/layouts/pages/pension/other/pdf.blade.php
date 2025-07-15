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
            font-size: 11px;
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
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .big-font {
            font-size: 22px;
            font-weight: bold;
        }
        .no-border td{
            border: none;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="14">
                    <h1>{{ $website->organization }}</h1>
                    <h1>Pensioner Other Bill Report Of {{ \Carbon\Carbon::parse($report->created_at)->format('F-Y') }}</h1>
                </th>
            </tr>
            <tr>
                <th>Name Of <br>Pensioners</th>
                <th>Type Of <br>Pension</th>
                <th>Pensioner In Case Of Family Pension</th>
                <th>PPO Number</th>
                <th>PPO Date</th>
                <th>Date Of Retairment</th>
                <th>Date Of Death</th>
                <th>5 years Complet Date</th>
                <th>Basic pension</th>
                <th>D/R</th>
                <th>M/A</th>
                <th>Other</th>
                <th>Total</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pensionersReport as $item)
                <tr>
                    <td>{{ $item->pensionerDetails->pensioner_name }}</td>
                    <td>
                        @if ($item->pensionerDetails->pension_type == 1)
                            Self
                        @else
                            Family member
                        @endif
                    </td>
                    <td width="100px">{{ $item->pensionerDetails->family_name }}</td>
                    <td>{{ $item->pensionerDetails->ppo_number }}</td>
                    <td>NA</td>
                    <td>{{ \Carbon\Carbon::parse($item->pensionerDetails->retirement_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pensionerDetails->death_date)->format('d/m/Y') ?? "Alive" }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pensionerDetails->five_year_date)->format('d/m/Y') }}</td>
                    <td>{{ $item->pensionerDetails->basic_pension }}</td>
                    <td>{{ ($item->pensionerDetails->basic_pension * $item->pensionerDetails->dr_percentage) / 100 }}</td>
                    <td>{{ $item->pensionerDetails->medical_allowance }}</td>
                    <td>{{ $item->pensionerDetails->other_allowance }}</td>
                    <td>{{ $item->gross }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br><br><br>
    <table>
        <tbody>
            <tr class="no-border">
                <td class="text-center">Dealink Assistant<br>{{ $website->organization }}</td>
                <td width="200px"></td>
                <td width="200px"></td>
                <td class="text-center">In-Charge<br>{{ $website->organization }}</td>
            </tr>
        </tbody>
    </table>
    <br><br><br>
    <table>
        <tbody>
            <tr class="no-border">
                <td class="text-center">Accountant<br>{{ $website->organization }}</td>
                <td class="text-center">F.O<br>{{ $website->organization }}</td>
                <td class="text-center">E.O<br>{{ $website->organization }}</td>
                <td class="text-center">Chairman<br>{{ $website->organization }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
