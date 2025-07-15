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
            page-break-inside: avoid;
            break-inside: avoid;
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
            font-size: 15px;
            padding: 5px;
        }
        th strong{
            font-size: 21px;
        }
        td {
            padding: 5px;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 16px;
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
        h3{
            margin: 0;
            padding: 10px
        }
        h4{
            font-size: 17px;
        }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>
                <h1>Form 81 [Vide Rules 239 & 260]</h1>
                <h1>Name of Urban Local Body : {{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                <p>BALANCE SHEET AS ON 31 March {{ $selectedYear }}</p>
            </th>
        </tr>
    </thead>
</table>
<h3>SOURCES OF FUNDS</h3>
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Reserves and Surplus</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts1, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts1, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>
<!-- Table for B-4 -->
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Grants,Contribution for Specific purposes</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts2, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts2, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- Table for B-5, B-6 -->
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Loans</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp

            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts3, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts3, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

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
<table>
    <tbody>
        <tr>
            <td class="text-right" width="500px"><h4>Total:</h4></td>
            <td><h4><strong>{{ number_format($sumMaxPrevious, 2) }}</strong></h4></td>
            <td><h4><strong>{{ number_format($sumMaxCurrent, 2) }}</strong></h4></td>
        </tr>
    </tbody>
</table>
<br>

<h3>APPLICATION OF FUNDS</h3>

<!-- Table for B-11 -->
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Fixed Assets</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp

            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts4, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts4, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- Table for B-12, B-13 -->
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Investments</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts5, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts5, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- Table for B-14, B-15, B-16, B-17, B-18, B-7, B-8, B-9, B-10 -->
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Working Capital</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts6, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts6, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Other Assets</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts7, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts7, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

<!-- Table for B-20 -->
<table>
    <thead>
        <tr>
            <th colspan="5"><strong>Misc.Expenditure(to the extent not written off)</strong></th>
        </tr>
        <tr>
            <th width="60px">Code No.</th>
            <th width="360px">Description of Item</th>
            <th width="100px">Schedule Reference No</th>
            <th width="100px">Previous Year Amount</th>
            <th width="100px">Current Year Amount</th>
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
            @endphp
            <tr>
                <td>{{ $item['major_head']->code }}</td>
                <td>{{ $item['major_head']->name }}</td>
                <td>{{ $item['major_head']->schedule_reference_no }}</td>
                <td>{{ number_format(max($item['yearly_data'][$previousYear]['closing_debit'], $item['yearly_data'][$previousYear]['closing_credit']), 2) }}</td>
                <td>{{ number_format(max($item['yearly_data'][$selectedYear]['closing_debit'], $item['yearly_data'][$selectedYear]['closing_credit']), 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right">Total: </td>
            <td><strong>{{ number_format($sumMaxPreviousYearAmounts8, 2) }}</strong></td>
            <td> <strong>{{ number_format($sumMaxCurrentYearAmounts8, 2) }}</strong></td>
        </tr>
    </tfoot>
</table>

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

<table>
    <tbody>
        <tr>
            <td class="text-right" width="500px"><h4>Total: </h4></td>
            <td><h4><strong>{{ number_format($sumMaxPrevious, 2) }}</strong></h4></td>
            <td><h4><strong>{{ number_format($sumMaxCurrent, 2) }}</strong></h4></td>
        </tr>
    </tbody>
</table>
</body>
</html>
