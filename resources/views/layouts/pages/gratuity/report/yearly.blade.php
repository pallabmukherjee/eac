@extends('layouts.main')

@section('title', 'Yearly Total Paid Report')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Yearly Total Paid Report</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Year</th>
                                    <th class="py-3 text-end">Total Gratuity</th>
                                    <th class="py-3 text-end">Total Loan</th>
                                    <th class="py-3 text-end">Total Paid Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                <tr>
                                    <td><span class="fw-bold">{{ $report->year }}</span></td>
                                    <td class="text-end">Rs. {{ number_format($report->total_gratuity, 2) }}</td>
                                    <td class="text-end">Rs. {{ number_format($report->total_loan, 2) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary f-14">Rs. {{ number_format($report->total_paid, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
