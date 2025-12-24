@extends('layouts.main')

@section('title', 'Pension Dashboard')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.pension.export') }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-1">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-primary me-3">
                                <i class="ti ti-users f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Total Active Pensioners</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $totalPensioners }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.pension.export', ['type' => 'dead']) }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-danger me-3">
                                <i class="ti ti-user-x f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Total Dead</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $totalDead }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.pension.export', ['type' => '5years']) }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-success me-3">
                                <i class="ti ti-calendar-time f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">5 Years Completed</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $total5YearsCompleted }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.pension.export', ['type' => '80years']) }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-warning me-3">
                                <i class="ti ti-calendar-stats f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">80 Years Completed</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $total80YearsCompleted }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.pension.report.index') }}" class="card-link">
                <div class="card statistics-card-1 bg-secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-secondary me-3">
                                <i class="ti ti-file-analytics f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Total Bills</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $totalReportsGenerated }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.pension.export', ['type' => 'life_cert_yes']) }}" class="card-link">
                <div class="card statistics-card-1 bg-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-info me-3">
                                <i class="ti ti-certificate f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Life Certificate (Yes)</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $totalLifeCertificateYes }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- [ Recent Reports ] start -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Recent Pension Bills</h5>
                    <a href="{{ route('superadmin.pension.report.index') }}" class="btn btn-sm btn-link-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Bill Details</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestReports as $report)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ti ti-calendar-stats f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ \Carbon\Carbon::create()->month($report->month)->format('F') }} {{ $report->year }}</h6>
                                                <small class="text-muted">ID: {{ $report->report_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary f-14 fw-bold">Rs. {{ number_format($report->total_amount, 2) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="fw-bold">{{ $report->created_at->format('d M, Y') }}</span>
                                            <small class="text-muted">{{ $report->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3">No bills found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Recent Other Bills ] start -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Recent Other Bills</h5>
                    <a href="{{ route('superadmin.pension.other.index') }}" class="btn btn-sm btn-link-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Bill Details</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-end">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestOtherBills as $bill)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-info">
                                                    <i class="ti ti-file-text f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ \Carbon\Carbon::parse($bill->created_at)->format('F Y') }}</h6>
                                                <small class="text-muted">Bill #{{ $bill->bill_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary f-14 fw-bold">Rs. {{ number_format($bill->total_amount, 2) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex flex-column align-items-end">
                                            <span class="fw-bold">{{ $bill->created_at->format('d M, Y') }}</span>
                                            <small class="text-muted">{{ $bill->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3">No other bills found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection