@extends('layouts.main')

@section('title', 'Multi Form Layouts')

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
                            <h4>Pensioner Report Details</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.report.pdf', $report->report_id) }}" class="btn btn-primary" target="_blank">View PDF</a>
                            <a href="{{ route('superadmin.pension.report.csv', $report->report_id) }}" class="btn btn-primary" target="_blank">CSV Download</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Srl</th>
                                        <th>pensioner_name</th>
                                        <th>Aadhaar No</th>
                                        <th>PPO</th>
                                        <th>Employee Code</th>
                                        <th>Gross</th>
                                        <th>Arrear</th>
                                        <th>Overdrawn</th>
                                        <th>Net Pension</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pensionersReport as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->pensionerDetails->pensioner_name }}</td>
                                            <td>{{ $item->pensionerDetails->aadhar_number }}</td>
                                            <td>{{ $item->pensionerDetails->ppo_number }}</td>
                                            <td>{{ $item->pensionerDetails->employee_code }}</td>
                                            <td>{{ $item->gross }}</td>
                                            <td>{{ $item->arrear }}</td>
                                            <td>{{ $item->overdrawn }}</td>
                                            <td>{{ $item->net_pension }}</td>
                                            <td>{{ $item->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Srl</th>
                                        <th>pensioner_name</th>
                                        <th>Aadhaar No</th>
                                        <th>PPO</th>
                                        <th>Employee Code</th>
                                        <th>Gross</th>
                                        <th>Arrear</th>
                                        <th>Overdrawn</th>
                                        <th>Net Pension</th>
                                        <th>Remarks</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection
