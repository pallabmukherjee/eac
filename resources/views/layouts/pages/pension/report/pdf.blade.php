<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pensioner Report Details</title>
    <style>
        body,
        html {
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
            padding: 0px;
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
            font-size: 13px;
            font-weight: 400;
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

        .no-border td {
            border: none;
        }
    </style>
</head>

<body>
    <table class="bordered-table">
        <thead>
            <tr>
                <th colspan="16">
                    <h1>{{ $website->organization }}</h1>
                    <h1>Pensioner Report of {{ \Carbon\Carbon::parse($report->created_at)->format('F-Y') }}</h1>
                    <p>Voucher No _______________ Voucher Date _____________</p>
                </th>
            </tr>
            <tr>
                <th>Sl. No</th>
                <th>Name Of Pensioners</th>
                <th>Aadhaar No</th>
                <th>Type Of Pension</th>
                <th>Pensioner In Case Of Family member</th>
                <th>PPO Number</th>
                <th>PPO Date</th>
                <th>Bank AC No.</th>
                <th>IFSC CODE</th>
                <th>Date of Retirement</th>
                <th>Basic pension</th>
                <th>D/R</th>
                <th>M/A</th>
                <th>Other</th>
                <th>Total</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_basic_pension = 0;
                $total_dr = 0;
                $total_medical = 0;
                $total_other = 0;
                $total_gross = 0;
            @endphp
            @foreach ($pensionersReport as $item)
                @php
                    $aadhar = $item->pensionerDetails->aadhar_number;
                    $masked = str_repeat('*', strlen($aadhar) - 4) . substr($aadhar, -4);

                    $basic_pension = ceil($item->pensionerDetails->basic_pension);
                    $dr_raw = ($item->pensionerDetails->basic_pension * $item->pensionerDetails->dr_percentage) / 100;
                    $dr = ($dr_raw - floor($dr_raw)) >= 0.01 ? ceil($dr_raw) : floor($dr_raw);
                    $medical = ceil($item->pensionerDetails->medical_allowance);
                    $other = ceil($item->pensionerDetails->other_allowance);
                    $gross = $item->net_pension;

                    $total_basic_pension += $basic_pension;
                    $total_dr += $dr;
                    $total_medical += $medical;
                    $total_other += $other;
                    $total_gross += $gross;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->pensionerDetails->pensioner_name }}</td>
                    <td>{{ $masked }}</td>
                    <td>
                        @if ($item->pensionerDetails->pension_type == 1)
                            Self
                        @else
                            Family member
                        @endif
                    </td>
                    <td width="100px">{{ $item->pensionerDetails->family_name }}</td>
                    <td>{{ $item->pensionerDetails->ppo_number }}</td>
                    <td>
                        @if ($item->pensionerDetails->ppo_date)
                            {{ \Carbon\Carbon::parse($item->pensionerDetails->ppo_date)->format('d/m/Y') }}
                        @else
                            NA
                        @endif
                    </td>
                    <td>{{ $item->pensionerDetails->savings_account_number }}</td>
                    <td>{{ $item->pensionerDetails->ifsc_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pensionerDetails->retirement_date)->format('d/m/Y') ?? 'Alive' }}</td>
                    <td>{{ number_format($basic_pension, 2) }}</td>
                    <td>{{ number_format($dr, 2) }}</td>
                    <td>{{ number_format($medical, 2) }}</td>
                    <td>{{ number_format($other, 2) }}</td>
                    <td>{{ number_format($gross, 2) }}</td>
                    <td>{{ $item->remarks }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="10" class="text-right">Total</th>
                <th>{{ number_format($total_basic_pension, 2) }}</th>
                <th>{{ number_format($total_dr, 2) }}</th>
                <th>{{ number_format($total_medical, 2) }}</th>
                <th>{{ number_format($total_other, 2) }}</th>
                <th>{{ number_format($total_gross, 2) }}</th>
                <th></th>
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