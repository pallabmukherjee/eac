@extends('layouts.main')

@section('title', 'Home')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        @if ($userRole && ($userRole->payment == 1 || $userRole->receipt || $userRole->contra || $userRole->journal))
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('admin.account') }}">
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
        @endif

        @if ($userRole && $userRole->leave == 1)
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
        @endif


        @if ($userRole && $userRole->pension == 1)
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
        @endif

        @if ($userRole && $userRole->gratuity == 1)
        <div class="col-sm-3">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.gratuity.index') }}">
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
