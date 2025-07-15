<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pensioner Report Details</title>
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
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .big-font {
            font-size: 24px;
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
                <th colspan="7">
                    <h1>{{ $website->organization }}</h1>
                    <h1>Gratuity Report: {{ $report->bill_no }}</h1>
                </th>
            </tr>
            <tr>
                <th>Name</th>
                <th>PPO No.</th>
                <th>Bank A/C No.</th>
                <th>IFSC</th>
                <th>Approved Amount</th>
                <th>Financial Year</th>
                <th>Ropa Year</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gratuityBills as $item)
                @php
                    $gratuityRopaYear = App\Models\GratuityRopaYear::where('id', $item->empDetails->ropa_year)->first();
                    $financialYear = App\Models\FinancialYear::where('id', $item->empDetails->financial_year)->first();
                @endphp
                <tr>
                    <td>{{ $item->empDetails->name }}</td>
                    <td>{{ $item->empDetails->ppo_number }}</td>
                    <td>{{ $item->empDetails->bank_ac_no ?? 'NA' }}</td>
                    <td>{{ $item->empDetails->ifsc ?? 'NA' }}</td>
                    <td>{{ $item->gratuity_amount }}</td>
                    <td>{{ $financialYear->year }}</td>
                    <td>{{ $gratuityRopaYear->year }}</td>
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
