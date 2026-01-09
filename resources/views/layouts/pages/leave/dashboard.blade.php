@extends('layouts.main')

@section('title', 'Leave Dashboard')

@section('css')
    <!-- map-vector css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/jsvectormap.min.css') }}">
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.leave.employee.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-1 text-white me-3">
                                <i class="ti ti-users f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Employee List</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.leave.report.index') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-2 text-white me-3">
                                <i class="ph-duotone ph-notebook f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Leave Reports</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <a href="{{ route('superadmin.leave.type.list') }}">
                        <div class="d-flex align-items-center">
                            <div class="avtar bg-brand-color-3 text-white me-3">
                                <i class="ph-duotone ph-list-bullets f-26"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-end">
                                    <h4 class="mb-0 f-w-500">Leave Types</h4>
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
