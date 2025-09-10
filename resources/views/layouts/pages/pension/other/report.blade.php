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
                            <h4>Pensioner Other Bill Reports</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.other.create') }}" class="btn btn-primary">Other Bill Genarate</a>
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
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pensionerReport as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y') }}</td>
                                            <td>{{ $item->details ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('superadmin.pension.other.show', $item->bill_id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-eye f-20"></i></a>
                                                <a href="{{ route('superadmin.pension.other.edit', $item->bill_id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Srl</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Details</th>
                                        <th>Action</th>
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
