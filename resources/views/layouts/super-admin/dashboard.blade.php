@extends('layouts.main')

@section('title', 'Home')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')
    <style>
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            font-size: 3rem;
            font-weight: bold;
            color: #000000;
            pointer-events: none;
            text-align: center;
            z-index: -1;
            user-select: none;
        }
        .watermark img {
            width: 100%;
            /* height: 120px; */
            display: block;
            margin: 0 auto;
        }
    </style>

    <div class="watermark">
        @if (isset($websiteSetting->logo))
            <img src="{{ asset('storage/' . $websiteSetting->logo) }}" alt="Logo">
        @endif
        @if (isset($websiteSetting->organization))
            <br>
            {{ $websiteSetting->organization }}
        @endif
    </div>


    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.account.account') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-1 text-white me-3">
                                <i class="ti ti-building-bank f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Accounts</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.leave.employee.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-2 text-white me-3">
                                <i class="ph-duotone ph-airplane-tilt f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Leave</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.pension.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-3 text-white me-3">
                                <i class="ph-duotone ph-chalkboard-teacher f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Pension</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.gratuity.report.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-4 text-white me-3">
                                <i class="ti ti-clipboard-list f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Gratuity</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
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
