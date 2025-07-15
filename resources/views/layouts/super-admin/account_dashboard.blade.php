@extends('layouts.main')

@section('title', 'Home')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h4>Transaction</h4>
                    <a href="{{ route('superadmin.account.paymentvoucher.index') }}" class="btn btn-primary">Payment</a>
                    <a href="{{ route('superadmin.account.receiptvoucher.index') }}" class="btn btn-primary">Receipt</a>
                    <a href="{{ route('superadmin.account.contravoucher.index') }}" class="btn btn-primary">Contra</a>
                    <a href="{{ route('superadmin.account.journalvoucher.index') }}" class="btn btn-primary">Journal</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-body">
                    <h4>Search and enquiry</h4>
                    <a href="{{ route('superadmin.account.paymentvoucher.list') }}" class="btn btn-primary">Payment</a>
                    <a href="{{ route('superadmin.account.receiptvoucher.list') }}" class="btn btn-primary">Receipt</a>
                    <a href="{{ route('superadmin.account.contravoucher.list') }}" class="btn btn-primary">Contra</a>
                    <a href="{{ route('superadmin.account.journalvoucher.list') }}" class="btn btn-primary">Journal</a>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="card mb-0">
                                <div class="card-body py-4 px-2">
                                    <a href="{{ route('superadmin.account.pendingaction.index') }}">
                                        <h1>{{ $deductCount }}</h1>
                                        <h6>Payment Deduction queue</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card mb-0">
                                <div class="card-body py-4 px-2">
                                    <a href="{{ route('superadmin.account.pendingaction.uncleared') }}">
                                        <h1>{{ $unclearedCheque }}</h1>
                                        <h6>Uncleared Cheque</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card mb-0">
                                <div class="card-body py-4 px-2">
                                    <a href="{{ route('superadmin.account.edit.index') }}">
                                        <h1>{{ $editRequest }}</h1>
                                        <h6>Edit Reqest</h6>
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
