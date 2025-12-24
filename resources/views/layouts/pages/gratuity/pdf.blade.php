<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gratuity Data List</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2 class="text-center">Gratuity Data List</h2>
    <table>
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Employee Code</th>
                <th>Name</th>
                <th>Relation Name</th>
                <th>PPO Number</th>
                <th>PPO Amount</th>
                <th>Sanctioned Amount</th>
                <th>Retirement Date</th>
                <th>PPO Receive Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gratuities as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->employee_code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->relation_name }}</td>
                <td>{{ $item->ppo_number }}</td>
                <td class="text-right">{{ number_format($item->ppo_amount, 2) }}</td>
                <td class="text-right">{{ number_format($item->sanctioned_amount, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($item->retirement_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->ppo_receive_date)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
