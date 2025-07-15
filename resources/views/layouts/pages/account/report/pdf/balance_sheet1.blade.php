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
                    <h1>Form 81 [Vide Rules 239 & 260]</h1>
                    <h1>Name of Urban Local Body : {{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>BALANCE SHEET AS ON 31 March {{ $selectedYear }}</p>
                </th>
            </tr>
        </thead>
    </table>
    <br><br>
    <h4>SOURCES OF FUNDS</h4>
    <table>
        <thead>
            <tr>
                <th colspan="13"><strong>Reserves and Surplus</strong></th>
            </tr>
            <tr>
                <th>Code No.</th>
                <th>Description of Item</th>
                <th>Schedule Reference No</th>
                <th>Previous Year Amount</th>
                <th>Opening Debit</th>
                <th>Opening Credit</th>
                <th>Closing Debit</th>
                <th>Closing Credit</th>
                <th>Major Head Code</th>
                <th>Schedule Reference No</th>
                <th>Current Year Amount</th>
                <th>Opening Debit</th>
                <th>Opening Credit</th>
                <th>Closing Debit</th>
                <th>Closing Credit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalClosingCreditPreviousYear1 = 0;
                $totalClosingDebitPreviousYear1 = 0;
                $totalClosingCreditCurrentYear1 = 0;
                $totalClosingDebitCurrentYear1 = 0;
                $sumMaxPreviousYearAmounts1 = 0;
                $sumMaxCurrentYearAmounts1 = 0;
            @endphp

            @foreach($summedBalances1 as $item)
                @php
                    $totalClosingCreditPreviousYear1 += $item['yearly_data'][$previousYear]['closing_credit'];
                    $totalClosingDebitPreviousYear1 += $item['yearly_data'][$previousYear]['closing_debit'];
                    $totalClosingCreditCurrentYear1 += $item['yearly_data'][$selectedYear]['closing_credit'];
                    $totalClosingDebitCurrentYear1 += $item['yearly_data'][$selectedYear]['closing_debit'];

                    $maxPrev1 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent1 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts1 += $maxPrev1;
                    $sumMaxCurrentYearAmounts1 += $maxCurrent1;

                    $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
                @endphp
                <tr>
                    <td>{{ $item['major_head']->code }}</td>
                    <td>{{ $majorHead->name }}</td>
                    <td>{{ $item['major_head']->schedule_reference_no }}</td>
                    <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                    <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                    <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                    <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                    <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                    <td>{{ $item['major_head']->code }}</td>
                    <td>{{ $item['major_head']->schedule_reference_no }}</td>
                    <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                    <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                    <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                    <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                    <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
                <td><strong>{{ $totalClosingDebitPreviousYear1 }}</strong></td>
                <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear1 }}</strong></td>
                <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
                <td><strong>{{ $totalClosingDebitCurrentYear1 }}</strong></td>
                <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear1 }}</strong></td>
            </tr>

            <tr>
                <td colspan="5"></td>
                <td colspan="6">{{ max($totalClosingDebitPreviousYear1, $totalClosingCreditPreviousYear1) }}</td>
                <td>{{ max($totalClosingDebitCurrentYear1, $totalClosingCreditCurrentYear1) }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts1, 2) }}</strong></td>
                <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts1, 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <br><br><br>
<!-- Table for B-4 -->
<table>
    <thead>
        <tr>
            <th colspan="12">Grants,Contribution for Specific purposes</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear2 = 0;
            $totalClosingDebitPreviousYear2 = 0;
            $totalClosingCreditCurrentYear2 = 0;
            $totalClosingDebitCurrentYear2 = 0;
                $sumMaxPreviousYearAmounts2 = 0;
                $sumMaxCurrentYearAmounts2 = 0;
        @endphp

        @foreach($summedBalances2 as $item)
            @php
                $totalClosingCreditPreviousYear2 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear2 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear2 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear2 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev2 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent2 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts2 += $maxPrev2;
                    $sumMaxCurrentYearAmounts2 += $maxCurrent2;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear2 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear2 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear2 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear2 }}</strong></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear2, $totalClosingCreditPreviousYear2) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear2, $totalClosingCreditCurrentYear2) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts2, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts2, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<br><br><br>


<!-- Table for B-5, B-6 -->
<table>
    <thead>
        <tr>
            <th colspan="12">Loans</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear3 = 0;
            $totalClosingDebitPreviousYear3 = 0;
            $totalClosingCreditCurrentYear3 = 0;
            $totalClosingDebitCurrentYear3 = 0;
                $sumMaxPreviousYearAmounts3 = 0;
                $sumMaxCurrentYearAmounts3 = 0;
        @endphp

        @foreach($summedBalances3 as $item)
            @php
                $totalClosingCreditPreviousYear3 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear3 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear3 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear3 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev3 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent3 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts3 += $maxPrev3;
                    $sumMaxCurrentYearAmounts3 += $maxCurrent3;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp

            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear3 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear3 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear3 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear3 }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear3, $totalClosingCreditPreviousYear3) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear3, $totalClosingCreditCurrentYear3) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts3, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts3, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<br><br><br>
@php
    // Previous Year max of debit/credit
    $max1 = $sumMaxPreviousYearAmounts1;
    $max2 = $sumMaxPreviousYearAmounts2;
    $max3 = $sumMaxPreviousYearAmounts3;
    $sumMaxPrevious = $max1 + $max2 + $max3;

    // Current Year max of debit/credit
    $cmax1 = $sumMaxCurrentYearAmounts1;
    $cmax2 = $sumMaxCurrentYearAmounts2;
    $cmax3 = $sumMaxCurrentYearAmounts3;
    $sumMaxCurrent = $cmax1 + $cmax2 + $cmax3;
@endphp
Previous Year Max Total: {{ $max1 }} + {{ $max2 }} + {{ $max3 }} = <strong>{{ $sumMaxPrevious }}</strong> <br>
Current Year Max Total: {{ $cmax1 }} + {{ $cmax2 }} + {{ $cmax3 }} = <strong>{{ $sumMaxCurrent }}</strong> <br>
<br><br><br>

<h4>APPLICATION OF FUNDS</h4>

<!-- Table for B-11 -->
<table>
    <thead>
        <tr>
            <th colspan="12">Fixed Assets</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear4 = 0;
            $totalClosingDebitPreviousYear4 = 0;
            $totalClosingCreditCurrentYear4 = 0;
            $totalClosingDebitCurrentYear4 = 0;
                $sumMaxPreviousYearAmounts4 = 0;
                $sumMaxCurrentYearAmounts4 = 0;
        @endphp

        @foreach($summedBalances4 as $item)
            @php
                $totalClosingCreditPreviousYear4 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear4 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear4 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear4 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev4 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent4 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts4 += $maxPrev4;
                    $sumMaxCurrentYearAmounts4 += $maxCurrent4;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp

            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear4 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear4 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear4 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear4 }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear4, $totalClosingCreditPreviousYear4) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear4, $totalClosingCreditCurrentYear4) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts4, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts4, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<br><br><br>

<!-- Table for B-12, B-13 -->
<table>
    <thead>
        <tr>
            <th colspan="12">Investments</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear5 = 0;
            $totalClosingDebitPreviousYear5 = 0;
            $totalClosingCreditCurrentYear5 = 0;
            $totalClosingDebitCurrentYear5 = 0;
                $sumMaxPreviousYearAmounts5 = 0;
                $sumMaxCurrentYearAmounts5 = 0;
        @endphp

        @foreach($summedBalances5 as $item)
            @php
                $totalClosingCreditPreviousYear5 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear5 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear5 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear5 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev5 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent5 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts5 += $maxPrev5;
                    $sumMaxCurrentYearAmounts5 += $maxCurrent5;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear5 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear5 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear5 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear5 }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear5, $totalClosingCreditPreviousYear5) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear5, $totalClosingCreditCurrentYear5) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts5, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts5, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<br><br><br>

<!-- Table for B-14, B-15, B-16, B-17, B-18, B-7, B-8, B-9, B-10 -->
<table>
    <thead>
        <tr>
            <th colspan="12">Working Capital</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear6 = 0;
            $totalClosingDebitPreviousYear6 = 0;
            $totalClosingCreditCurrentYear6 = 0;
            $totalClosingDebitCurrentYear6 = 0;
                $sumMaxPreviousYearAmounts6 = 0;
                $sumMaxCurrentYearAmounts6 = 0;
        @endphp

        @foreach($summedBalances6 as $item)
            @php
                $totalClosingCreditPreviousYear6 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear6 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear6 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear6 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev6 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent6 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts6 += $maxPrev6;
                    $sumMaxCurrentYearAmounts6 += $maxCurrent6;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear6 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear6 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear6 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear6 }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear6, $totalClosingCreditPreviousYear6) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear6, $totalClosingCreditCurrentYear6) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts6, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts6, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>

<br><br><br>

<table>
    <thead>
        <tr>
            <th colspan="12">Other Assets</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear7 = 0;
            $totalClosingDebitPreviousYear7 = 0;
            $totalClosingCreditCurrentYear7 = 0;
            $totalClosingDebitCurrentYear7 = 0;
                $sumMaxPreviousYearAmounts7 = 0;
                $sumMaxCurrentYearAmounts7 = 0;
        @endphp

        @foreach($summedBalances7 as $item)
            @php
                $totalClosingCreditPreviousYear7 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear7 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear7 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear7 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev7 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent7 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts7 += $maxPrev7;
                    $sumMaxCurrentYearAmounts7 += $maxCurrent7;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear7 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear7 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear7 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear7 }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear7, $totalClosingCreditPreviousYear7) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear7, $totalClosingCreditCurrentYear7) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts7, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts7, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>

<br><br><br>

<!-- Table for B-20 -->
<table>
    <thead>
        <tr>
            <th colspan="12">Misc.Expenditure(to the extent not written off)</th>
        </tr>
        <tr>
            <th>Major Head Code</th>
            <th>Description of Item</th>
            <th>Schedule Reference No</th>
            <th>Previous Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
            <th>Major Head Code</th>
            <th>Schedule Reference No</th>
            <th>Current Year Amount</th>
            <th>Opening Debit</th>
            <th>Opening Credit</th>
            <th>Closing Debit</th>
            <th>Closing Credit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalClosingCreditPreviousYear8 = 0;
            $totalClosingDebitPreviousYear8 = 0;
            $totalClosingCreditCurrentYear8 = 0;
            $totalClosingDebitCurrentYear8 = 0;
                $sumMaxPreviousYearAmounts8 = 0;
                $sumMaxCurrentYearAmounts8 = 0;
        @endphp

        @foreach($summedBalances8 as $item)
            @php
                $totalClosingCreditPreviousYear8 += $item['yearly_data'][$previousYear]['closing_credit'];
                $totalClosingDebitPreviousYear8 += $item['yearly_data'][$previousYear]['closing_debit'];
                $totalClosingCreditCurrentYear8 += $item['yearly_data'][$selectedYear]['closing_credit'];
                $totalClosingDebitCurrentYear8 += $item['yearly_data'][$selectedYear]['closing_debit'];

                $maxPrev8 = max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']);
                    $maxCurrent8 = max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']);
                    $sumMaxPreviousYearAmounts8 += $maxPrev8;
                    $sumMaxCurrentYearAmounts8 += $maxCurrent8;

                $majorHead = \App\Models\MajorHead::where('code', $item['major_head']->code)->first();
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $majorHead->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$previousYear]['closing_credit'] }}</td>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['opening_credit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_debit'] }}</td>
                <td>{{ $item['yearly_data'][$selectedYear]['closing_credit'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right;">Total Closing Debit (Previous Year): </td>
            <td><strong>{{ $totalClosingDebitPreviousYear8 }}</strong></td>
            <td>Total Closing Credit (Previous Year): <strong>{{ $totalClosingCreditPreviousYear8 }}</strong></td>
            <td colspan="4" style="text-align: right;">Total Closing Debit (Current Year): </td>
            <td><strong>{{ $totalClosingDebitCurrentYear8 }}</strong></td>
            <td>Total Closing Credit (Current Year): <strong>{{ $totalClosingCreditCurrentYear8 }}</strong></td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td colspan="6">{{ max($totalClosingDebitPreviousYear8, $totalClosingCreditPreviousYear8) }}</td>
            <td>{{ max($totalClosingDebitCurrentYear8, $totalClosingCreditCurrentYear8) }}</td>
            <td></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Previous Year): <strong>{{ number_format($sumMaxPreviousYearAmounts8, 2) }}</strong></td>
            <td colspan="6">Total of Max(Closing Debit, Credit) (Current Year): <strong>{{ number_format($sumMaxCurrentYearAmounts8, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<br><br><br>
@php
    // Previous Year max of debit/credit
    $max1 = $sumMaxPreviousYearAmounts4;
    $max2 = $sumMaxPreviousYearAmounts5;
    $max3 = $sumMaxPreviousYearAmounts6;
    $max4 = $sumMaxPreviousYearAmounts7;
    $max5 = $sumMaxPreviousYearAmounts8;
    $sumMaxPrevious = $max1 + $max2 + $max3 + $max4 + $max5;

    // Current Year max of debit/credit
    $cmax1 = $sumMaxCurrentYearAmounts4;
    $cmax2 = $sumMaxCurrentYearAmounts5;
    $cmax3 = $sumMaxCurrentYearAmounts6;
    $cmax4 = $sumMaxCurrentYearAmounts7;
    $cmax5 = $sumMaxCurrentYearAmounts8;
    $sumMaxCurrent = $cmax1 + $cmax2 + $cmax3 + $cmax4 + $cmax5;
@endphp

Previous Year Max Total: {{ $max1 }} + {{ $max2 }} + {{ $max3 }} + {{ $max4 }} + {{ $max5 }} = <strong>{{ $sumMaxPrevious }}</strong> <br>
Current Year Max Total: {{ $cmax1 }} + {{ $cmax2 }} + {{ $cmax3 }} + {{ $cmax4 }}  + {{ $cmax5 }}= <strong>{{ $sumMaxCurrent }}</strong> <br>
<br><br><br>
</body>
</html>
