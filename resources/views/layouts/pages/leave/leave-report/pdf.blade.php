<!DOCTYPE html>
<html>
<head>
    <title>Leave Reports</title>
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
            padding:0px;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 5px;
        }
        td {
            padding: 5px;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 10px;
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
    <h1 style="margin-top: 10px">Leave Reports for {{ $month }}</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Emp ID</th>
                <th>Emp Name</th>
                <th>Emp Designation</th>
                <th>CL</th>
                <th>CL Enjoyed</th>
                <th>CL Date</th>
                <th>CL In Hand</th>
                <th>EL</th>
                <th>EL Enjoyed</th>
                <th>EL Date</th>
                <th>EL In Hand</th>
                <th>ML</th>
                <th>ML Enjoyed</th>
                <th>ML Date</th>
                <th>ML In Hand</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaveReports as $report)
                <tr>
                    @php
                        $employeeType = \App\Models\EmployeeType::where('id', $report->emp_designation)->first();
                    @endphp
                    <td>{{ $report->emp_id }}</td>
                    <td>{{ $report->emp_name }}</td>
                    <td>{{ $employeeType->employee_type }}</td>
                    <td>{{ $report->cl }}</td>
                    <td>{{ $report->cl_enjoyed }}</td>
                    <td>{{ $report ->cl_date }}</td>
                    <td>{{ $report->cl_in_hand }}</td>
                    <td>{{ $report->el }}</td>
                    <td>{{ $report->el_enjoyed }}</td>
                    <td>{{ $report->el_date }}</td>
                    <td>{{ $report->el_in_hand }}</td>
                    <td>{{ $report->ml }}</td>
                    <td>{{ $report->ml_enjoyed }}</td>
                    <td>{{ $report->ml_date }}</td>
                    <td>{{ $report->ml_in_hand }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
