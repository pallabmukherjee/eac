@extends('layouts.main')

@section('title', 'Account Dashboard')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h4 class="m-b-10">Account Dashboard</h4>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Account</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.paymentvoucher.index') }}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-wallet me-2"></i>Payment</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.receiptvoucher.index') }}" class="btn btn-success d-flex align-items-center"><i class="ti ti-receipt me-2"></i>Receipt</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.contravoucher.index') }}" class="btn btn-warning d-flex align-items-center"><i class="ti ti-arrows-left-right me-2"></i>Contra</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.journalvoucher.index') }}" class="btn btn-info d-flex align-items-center"><i class="ti ti-book me-2"></i>Journal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Search & Enquiry</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.paymentvoucher.list') }}" class="btn btn-primary d-flex align-items-center"><i class="ti ti-search me-2"></i>Payment</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.receiptvoucher.list') }}" class="btn btn-success d-flex align-items-center"><i class="ti ti-search me-2"></i>Receipt</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.contravoucher.list') }}" class="btn btn-warning d-flex align-items-center"><i class="ti ti-search me-2"></i>Contra</a>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('superadmin.account.journalvoucher.list') }}" class="btn btn-info d-flex align-items-center"><i class="ti ti-search me-2"></i>Journal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Pending Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="card bg-light-danger mb-0">
                                <div class="card-body py-4 px-2">
                                    <a href="{{ route('superadmin.account.pendingaction.index') }}">
                                        <i class="ti ti-clock text-danger" style="font-size: 36px;"></i>
                                        <h1 class="mt-2">{{ $deductCount }}</h1>
                                        <h6>Payment Deduction Queue</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-light-warning mb-0">
                                <div class="card-body py-4 px-2">
                                    <a href="{{ route('superadmin.account.pendingaction.uncleared') }}">
                                        <i class="ti ti-file-invoice text-warning" style="font-size: 36px;"></i>
                                        <h1 class="mt-2">{{ $unclearedCheque }}</h1>
                                        <h6>Uncleared Cheque</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-light-info mb-0">
                                <div class="card-body py-4 px-2">
                                    <a href="{{ route('superadmin.account.edit.index') }}">
                                        <i class="ti ti-edit-circle text-info" style="font-size: 36px;"></i>
                                        <h1 class="mt-2">{{ $editRequest }}</h1>
                                        <h6>Edit Request</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')

@endsection
