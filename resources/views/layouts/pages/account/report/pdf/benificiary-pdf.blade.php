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
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th colspan="5">
                    <h1>{{ $website->organization ?? "Please update your organization name from website setting" }}</h1>
                    <p>Partywise Payment Summary for {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
                </th>
            </tr>
            <tr>
                <th>Party Code & Name</th>
                <th>Tot Vch</th>
                <th>Payment</th>
                <th>Deduction</th>
                <th>Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Initialize overall total sums
                $overallTotalReceipt = 0;
                $overallTotalDeduction = 0;
                $totalUniqueVoucherIds = collect();
                $overallNetAmount = 0;
            @endphp

            @foreach($groupedData['grouped_payments'] as $payee => $voucherIds)
                @php
                    // Initialize payee total sums
                    $payeeTotalReceipt = 0;
                    $payeeTotalDeduction = 0;
                    $uniqueVoucherIds = $voucherIds->unique(); // Get unique voucher IDs
                    $totalUniqueVoucherIds = $totalUniqueVoucherIds->merge($uniqueVoucherIds);
                    $beneficiary = \App\Models\Beneficiary::find($payee);
                @endphp
                <tr>
                    @php
                        $totalItems = 0;
                    @endphp
                    @foreach($voucherIds as $voucherId)
                        @php
                            $ledgerReports = \App\Models\LedgerReport::where('voucher_id', $voucherId)->get();
                            $ledgerCount = $ledgerReports->count();
                            $totalItems += $ledgerCount;
                            foreach ($ledgerReports as $report) {
                                if ($report->pay_deduct == 1) {
                                    $payeeTotalReceipt += $report->amount;
                                    $overallTotalReceipt += $report->amount;
                                } elseif ($report->pay_deduct == 2) {
                                    $payeeTotalDeduction += $report->amount;
                                    $overallTotalDeduction += $report->amount;
                                }
                            }
                        @endphp
                    @endforeach
                    <td><span style="margin-right: 10px">{{ $beneficiary ? $beneficiary->party_code : '' }}</span> {{ $beneficiary ? $beneficiary->name : 'Unknown Beneficiary' }}</td>
                    <td>{{ $uniqueVoucherIds->count() }}</td>
                    <td>{{ number_format($payeeTotalReceipt, 2) }}</td>
                    <td>{{ number_format($payeeTotalDeduction, 2) }}</td>
                    <td>
                        @php
                            $netAmount = $payeeTotalReceipt - $payeeTotalDeduction; // Calculate net amount for the payee
                            $overallNetAmount += $netAmount; // Accumulate to overall net amount
                        @endphp
                        {{ number_format($netAmount, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="1" style="text-align: right;">Total</th>
                <th>{{ $totalUniqueVoucherIds->count() }}</th>
                <th>{{ number_format($overallTotalReceipt, 2) }}</th>
                <th>{{ number_format($overallTotalDeduction, 2) }}</th>
                <th>{{ number_format($overallNetAmount, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
