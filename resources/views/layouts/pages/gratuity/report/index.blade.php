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
        <!-- [ form-element ] start -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4>Recent Reports</h4>
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
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Srl</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>View</th>
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
