<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Voucher </title>
    <link rel="icon" href="{{ URL::asset('assets/images/favicon.svg') }}" type="image/x-icon">
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
    <div style="margin-top: 30px;">
        <table class="details-table">
            <tr>
                <td style="border: none; padding:0">
                    <h4 style="margin: 0">{{ $website->organization ?? "Please update your organization name from website setting" }}</h4>
                    <h4 style="margin: 2px 0 8px">Form 100 [Vide Rule 256]</h4>
                </td>
                <td style="border: none; padding:0">
                    <h3 style="margin: 0; text-decoration: underline;">Journal Voucher </h3>
                </td>
                <td style="border: none; padding:0" width="17%">
                    <h5 style="margin: 0 0 5px">No.: {{ $journalVoucher->bill_no }}</h5>
                    <h5 style="margin: 0">Date: {{ \Carbon\Carbon::parse($journalVoucher->date)->format('d/m/Y') }}</h5>
                </td>
            </tr>
        </table>
    </div>



    <div style="margin-top: 10px;">
        <table class="details-table">
            @php
                $totalCash = 0;
                $totalCheque = 0;
                $totalOnline = 0;
            @endphp
            <thead>
                <tr>
                    <th>Srl</th>
                    <th>Particulrs</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledgerItem as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->ledgerHead->ledgers_head_code }} {{ $item->ledgerHead->name }}</td>
                    <td>
                        @if ($item->crdr == 2)
                            {{ $item->amount}}
                        @endif
                    </td>
                    <td>
                        @if ($item->crdr == 1)
                            {{ $item->amount }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;"><strong>TOTAL:</strong></td>
                    <td><strong>{{ $totalDebitAmount  }}</strong></td>
                    <td><strong>{{ $totalCreditAmount  }}</strong></td>
                </tr>
            </tfoot>
        </table>
        <div style="padding: 10px">
            {{-- <label style="font-size: 14px">BUSES: <br>The Sum of {{ $totalAmountInWords }}</label><br><br> --}}
            <label style="font-size: 14px">Narration: {{ $journalVoucher->narration }}</label>
        </div>

        <table class="details-table" style="margin-top: 100px">
            <tbody>
                <tr>
                    <td style="border: none; width:50%;">Prepared by</td>
                    <td style="border: none; width:50%; text-align:right;">Checked by</td>
                </tr>
            </tbody>
        </table>
    </div>


</body>
</html>
