@extends('layouts.main')

@section('title', 'Pension Bill Details')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ form-element ] start -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4>Bill Details: {{ \Carbon\Carbon::create()->month($report->month)->format('F') }} {{ $report->year }}</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.report.pdf', $report->report_id) }}" class="btn btn-primary me-2" target="_blank">
                                <i class="ti ti-file-type-pdf me-1"></i> View PDF
                            </a>
                            <a href="{{ route('superadmin.pension.report.csv', $report->report_id) }}" class="btn btn-success" target="_blank">
                                <i class="ti ti-file-type-csv me-1"></i> CSV Download
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="report-details" class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">Pensioner Details</th>
                                    <th class="py-3">PPO Number</th>
                                    <th class="py-3 text-end">Gross Amount</th>
                                    <th class="py-3 text-end">Arrear</th>
                                    <th class="py-3 text-end">Overdrawn</th>
                                    <th class="py-3 text-end">Net Pension</th>
                                    <th class="py-3">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pensionersReport as $item)
                                <tr>
                                    <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ti ti-user f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $item->pensionerDetails->pensioner_name }}</h6>
                                                <small class="text-muted">Code: {{ $item->pensionerDetails->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $item->pensionerDetails->ppo_number }}</span>
                                            <small class="text-muted">Aadhaar: {{ $item->pensionerDetails->aadhar_number }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($item->gross, 2) }}</td>
                                    <td class="text-end text-success">{{ number_format($item->arrear, 2) }}</td>
                                    <td class="text-end text-danger">{{ number_format($item->overdrawn, 2) }}</td>
                                    <td class="text-end fw-bold text-primary">Rs. {{ number_format($item->net_pension, 2) }}</td>
                                    <td>
                                        @if($item->remarks)
                                            <span class="badge bg-light-secondary text-secondary" style="white-space: normal;">{{ $item->remarks }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var table = $('#report-details').DataTable({
            "order": [[0, 'asc']]
        });
    </script>
@endsection