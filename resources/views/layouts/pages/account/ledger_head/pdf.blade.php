<!DOCTYPE html>
<html>
<head>
    <title>Trial Balance</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <h2>Trial Balance</h2>
    <div class="header-info">
        Generated on: {{ date('d-m-Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Head of Account</th>
                <th>LH Code</th>
                <th>DH Code</th>
                <th>Opening Debit</th>
                <th>Opening Credit</th>
                <th>Debit Amount</th>
                <th>Credit Amount</th>
                <th>Closing Debit</th>
                <th>Closing Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailedHeads as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->ledgers_head_code ?? 'N/A' }}</td>
                <td>{{ $item->code }}</td>
                <td class="text-end">{{ number_format($item->opening_debit, 2) }}</td>
                <td class="text-end">{{ number_format($item->opening_credit, 2) }}</td>
                <td class="text-end">{{ number_format($item->debit_amount, 2) }}</td>
                <td class="text-end">{{ number_format($item->credit_amount, 2) }}</td>
                <td class="text-end">{{ number_format($item->closing_debit, 2) }}</td>
                <td class="text-end">{{ number_format($item->closing_credit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
