@extends('layouts.main')

@section('title', 'Employee-wise Gratuity Report')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Employee-wise Gratuity Report</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('superadmin.gratuity.report.employee.wise') }}" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Select Employee</label>
                                <select name="emp_id" class="form-control select2">
                                    <option value="">Search Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $selectedEmployee == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->employee_code }} - {{ $emp->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Fetch Report</button>
                            </div>
                        </div>
                    </form>

                    @if($selectedEmployee)
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Bill No.</th>
                                    <th class="py-3">Prayer Details</th>
                                    <th class="py-3 text-end">Gratuity</th>
                                    <th class="py-3 text-end">Loan Paid</th>
                                    <th class="py-3 text-end">Total</th>
                                    <th class="py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                <tr>
                                    <td><span class="fw-bold">{{ $report->bill_no }}</span></td>
                                    <td>
                                        No: {{ $report->prayer_no }}<br>
                                        Date: {{ $report->prayer_date }}
                                    </td>
                                    <td class="text-end">Rs. {{ number_format($report->gratuity_amount, 2) }}</td>
                                    <td class="text-end">Rs. {{ number_format($report->loan_amount, 2) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary">Rs. {{ number_format($report->total_amount, 2) }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($report->created_at)->format('d M, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No payment history found for this employee.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Search for an employee',
            width: '100%'
        });
    });
</script>
@endsection
