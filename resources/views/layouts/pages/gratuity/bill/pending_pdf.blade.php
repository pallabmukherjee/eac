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
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("helvetica");
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $pdf->page_text($pdf->get_width() - $width - 10, 25, $text, $font, $size);
        }
    </script>
    <table>
        <thead>
            <tr>
                <th colspan="9">
                    <h1>{{ $website->organization }}</h1>
                    <h1>Bill for Gratuity payment  {{$report->bill_no}}</h1>
                </th>
            </tr>
            <tr>
                <th>Payee Name Details<br>(Self/Spouse/Warration)</th>
                <th>Name</th>
                <th>PPO No.</th>
                <th>Bank A/C No.</th>
                <th>IFSC</th>
                <th>Approved Amount</th>
                <th>Financial Year</th>
                <th>Ropa Year</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @foreach($gratuityBills as $item)
                <tr>
                    <td>
                        @if($item->empDetails->alive_status == 1)
                            {{ $item->empDetails->name ?? 'NA' }}
                        @elseif($item->empDetails->alive_status == 2)
                            @if($item->empDetails->relation_died == 2)
                                {{ $item->empDetails->relation_name ?? 'NA' }}
                            @else
                                {{ $item->empDetails->warrant_name ?? 'NA' }}
                            @endif
                        @else
                            {{ 'N/A' }}
                        @endif
                    </td>
                    <td>{{ $item->empDetails->name }}</td>
                    <td>{{ $item->empDetails->ppo_number ?? 'NA' }}</td>
                    <td>{{ $item->empDetails->bank_ac_no ?? 'NA' }}</td>
                    <td>{{ $item->empDetails->ifsc ?? 'NA' }}</td>
                    <td>{{ $item->gratuity_amount }}</td>
                    <td>{{ $item->empDetails->financialYear->year ?? '' }}</td>
                    <td>{{ $item->empDetails->ropaYear->year ?? '' }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
                @php $totalAmount += $item->gratuity_amount; @endphp
            @endforeach
            <tr>
                <td colspan="5" class="text-right"><b>Total Amount:</b></td>
                <td colspan="4"><b>{{ $totalAmount }}</b></td>
            </tr>
        </tbody>
    </table>

    <br><br><br><br>
    <table>
        <tbody>
            <tr class="no-border">
                <td class="text-center">Dealing Assistant<br>{{ $website->organization }}</td>
                <td width="200px"></td>
                <td width="200px"></td>
                <td class="text-center">In-Charge, Pension Cell<br>{{ $website->organization }}</td>
            </tr>
        </tbody>
    </table>
    <br><br><br>
    <table>
        <tbody>
            <tr class="no-border">
                <td class="text-center">Accountant<br>{{ $website->organization }}</td>
                <td class="text-center">Finance Officer<br>{{ $website->organization }}</td>
                <td class="text-center">Executive Officer<br>{{ $website->organization }}</td>
                <td class="text-center">Chairman<br>{{ $website->organization }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>