@extends('layouts.main')

@section('title', 'Home')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        @if ($userRole && $userRole->payment == 1)
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.account.paymentvoucher.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-1 text-white me-3">
                                <i class="fas fa-rupee-sign  f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Payment</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif


        @if ($userRole && $userRole->receipt == 1)
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.account.receiptvoucher.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-2 text-white me-3">
                                <i class="fas fa-rupee-sign  f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Receipt</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if ($userRole && $userRole->contra == 1)
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.account.contravoucher.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-3 text-white me-3">
                                <i class="fas fa-rupee-sign  f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Contra</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if ($userRole && $userRole->journal == 1)
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.account.journalvoucher.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-4 text-white me-3">
                                <i class="fas fa-rupee-sign  f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Journal</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if ($userRole && $userRole->report == 1)
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.account.ledgerhead.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-4 text-white me-3">
                                <i class="fas fa-rupee-sign  f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Report</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    <script src="{{ URL::asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/world.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/world-merc.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/dashboard-default.js') }}"></script>
    <!-- [Page Specific JS] end -->
@endsection
