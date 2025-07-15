<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Head wise Receipt Summary</title>
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
        .big-font {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="4">
                    <h1>Form 88 [Vide Rules 239 & 260]</h1>
                    <h1>Name of Urban Local Body : {{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>INCOME AND EXPENDITURE STATEMENT FOR THE YEAR {{ $selectedYear }}</p>
                </th>
            </tr>
            <tr>
                <th>Code Number</th>
                <th>Item/Head of Account</th>
                <th>Previous Year Amount</th>
                <th>Current Year Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="1"></td>
                <td colspan="3" style="font-size: 24px; font-weight: bold;">INCOME</td>
            </tr>
            @php
                $totalOpeningCredit = 0;
                $totalClosingCredit = 0;
            @endphp

            @foreach($groupedIncomeBalances as $key => $groupData)
                <tr>
                    @php
                        $majorHead = \App\Models\MajorHead::where('code', $key)->first();
                    @endphp
                    <td>{{ $key }}</td>
                    <td>{{ $majorHead->name }}</td>
                    <td>{{ number_format($groupData['sum_opening_credit'], 2) }}</td>
                    <td>{{ number_format($groupData['sum_closing_credit'], 2) }}</td>
                </tr>

                @php
                    $totalOpeningCredit += $groupData['sum_opening_credit'];
                    $totalClosingCredit += $groupData['sum_closing_credit'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>A</strong></td>
                <td><strong>Total - INCOME</strong></td>
                <td><strong>{{ number_format($totalOpeningCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalClosingCredit, 2) }}</strong></td>

            </tr>
        </tfoot>
    </table>



<br>
<br>



    <table>
        <thead>
            <tr>
                <th>Code Number</th>
                <th>Item/Head of Account</th>
                <th>Previous Year Amount</th>
                <th>Current Year Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="1"></td>
                <td colspan="3" style="font-size: 24px; font-weight: bold;">EXPENDITURE</td>
            </tr>
            @php
                $totalExpenditureOpeningCredit = 0;
                $totalExpenditureClosingCredit = 0;
            @endphp

            @foreach($groupedExpenditureBalances as $key => $groupData)
                <tr>
                    @php
                        $majorHead = \App\Models\MajorHead::where('code', $key)->first();
                    @endphp
                    <td>{{ $key }}</td>
                    <td>{{ $majorHead->name }}</td>
                    <td>{{ number_format($groupData['sum_Expenditure_opening_credit'], 2) }}</td>
                    <td>{{ number_format($groupData['sum_Expenditure_closing_credit'], 2) }}</td>

                </tr>

                @php
                    $totalExpenditureOpeningCredit += $groupData['sum_Expenditure_opening_credit'];
                    $totalExpenditureClosingCredit += $groupData['sum_Expenditure_closing_credit'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td><strong>B</strong></td>
                <td><strong>Total - EXPENDITURE</strong></td>
                <td><strong>{{ number_format($totalExpenditureOpeningCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalExpenditureClosingCredit, 2) }}</strong></td>

            </tr>
        </tfoot>
    </table>


    <br>
<br>


    <table>
        <tbody>
            <tr>
                <td colspan="1"><strong>A-B</strong></td>
                <td colspan="1"><strong>Gross surplus/(deficit) of income over expenditure</strong></td>
                <td><strong>{{ number_format($totalOpeningCredit - $totalExpenditureOpeningCredit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalClosingCredit - $totalExpenditureClosingCredit, 2) }}</strong></td>

            </tr>
            <tr>
                <td colspan="2"></td>
                <td>SURPLUS</td>
                <td>DEFICIT</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
