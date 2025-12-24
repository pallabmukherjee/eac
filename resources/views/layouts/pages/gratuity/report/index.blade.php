@extends('layouts.main')

@section('title', 'Multi Form Layouts')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.gratuity.list') }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-1">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-primary me-3">
                                <i class="ti ti-users f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Total Employees</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $totalEmployees }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.gratuity.bill.index') }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-success me-3">
                                <i class="ti ti-git-pull-request f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Total Applications</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">{{ $totalApplications }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('superadmin.gratuity.bill.create') }}" class="card-link">
                <div class="card statistics-card-1 bg-brand-color-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-warning me-3">
                                <i class="ti ti-plus f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Create New Application</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">New</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="card statistics-card-1 bg-brand-color-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-white text-danger me-3">
                            <i class="ti ti-currency-dollar f-26"></i>
                        </div>
                        <div>
                            <p class="text-white mb-0">Total Gratuity Paid</p>
                            <div class="d-flex align-items-end">
                                <h3 class="text-white mb-0">Rs. {{ number_format($totalGratuityPaid, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <a href="{{ route('superadmin.gratuity.loan.index') }}" class="card-link">
                <div class="card statistics-card-1 bg-secondary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-white text-secondary me-3">
                                <i class="ti ti-building-bank f-26"></i>
                            </div>
                            <div>
                                <p class="text-white mb-0">Total Outstanding Loans</p>
                                <div class="d-flex align-items-end">
                                    <h3 class="text-white mb-0">Rs. {{ number_format($totalOutstandingLoans, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- [ Pending Applications ] start -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Latest Pending Applications</h5>
                    <a href="{{ route('superadmin.gratuity.bill.index') }}" class="btn btn-sm btn-link-primary">Show More</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Bill Details</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lastPending as $bill)
                                @php
                                    $amount = \App\Models\GratuityBillSummary::where('bill_id', $bill->bill_id)
                                        ->selectRaw('SUM(COALESCE(gratuity_amount, 0) + COALESCE(loan_amount, 0)) as total')
                                        ->first()->total;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-warning">
                                                    <i class="ti ti-file-text f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $bill->bill_no }}</h6>
                                                <small class="text-muted">ID: {{ $bill->bill_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($bill->created_at)->format('d M, Y') }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($bill->created_at)->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary f-14">Rs. {{ number_format($amount, 2) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">No pending applications</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Accepted Applications ] start -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5>Latest Accepted Applications</h5>
                    <a href="{{ route('superadmin.gratuity.bill.index', ['status' => 'approved']) }}" class="btn btn-sm btn-link-success">Show More</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Bill Details</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lastAccepted as $bill)
                                @php
                                    $amount = \App\Models\GratuityBillSummary::where('bill_id', $bill->bill_id)
                                        ->selectRaw('SUM(COALESCE(gratuity_amount, 0) + COALESCE(loan_amount, 0)) as total')
                                        ->first()->total;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-success">
                                                    <i class="ti ti-check f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $bill->bill_no }}</h6>
                                                <small class="text-muted">ID: {{ $bill->bill_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($bill->created_at)->format('d M, Y') }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($bill->created_at)->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary f-14">Rs. {{ number_format($amount, 2) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">No accepted applications</td>
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
