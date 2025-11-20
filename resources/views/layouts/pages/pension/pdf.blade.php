<!DOCTYPE html>
<html>
<head>
    <title>Pensioners List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Pensioners List</h2>
    <table>
        <thead>
            <tr>
                <th>PPO Code</th>
                <th>Pensioner Name</th>
                <th>Aadhar No</th>
                <th>Phone No</th>
                <th>Type Of Pension</th>
                <th>Life Certificate</th>
                <th>Date of Retirement</th>
                <th>Alive Status</th>
                <th>5 Years Completed</th>
                <th>5 Years Completed Status</th>
                <th>80 Years Completed</th>
                <th>80 Years Completed Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pensioners as $pensioner)
                <tr>
                    <td>{{ $pensioner->ppo_number }}</td>
                    <td>{{ $pensioner->pensioner_name }}</td>
                    <td>{{ $pensioner->aadhar_number }}</td>
                    <td>{{ $pensioner->phone_no }}</td>
                    <td>{{ $pensioner->pension_type == 1 ? 'Self' : 'Family member' }}</td>
                    <td>{{ $pensioner->life_certificate == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pensioner->retirement_date)->format('d/m/Y') }}</td>
                    <td>{{ $pensioner->alive_status == 1 ? 'Alive' : 'Dead' }}</td>
                    @php
                        $fiveYearDate = \Carbon\Carbon::parse($pensioner->five_year_date);
                    @endphp
                    <td>{{ $fiveYearDate->format('d/m/Y') }}</td>
                    <td>{{ $fiveYearDate->isPast() ? 'Yes' : 'No' }}</td>
                    @if($pensioner->dob)
                        @php
                            $eightyYearDate = \Carbon\Carbon::parse($pensioner->dob)->addYears(80);
                        @endphp
                        <td>{{ $eightyYearDate->format('d/m/Y') }}</td>
                        <td>{{ $eightyYearDate->isPast() ? 'Yes' : 'No' }}</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
