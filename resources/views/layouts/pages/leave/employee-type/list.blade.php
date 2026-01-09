@extends('layouts.main')

@section('title', 'Employee Type')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/style.css') }}">
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
                    <h4>Employee Type</h4>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <a href="{{ route('superadmin.leave.type.add') }}" class="btn btn-primary me-2">
                        <i class="ti ti-plus me-1"></i> Add New
                    </a>
                    <a href="#" class="btn btn-secondary me-2">
                        <i class="ti ti-file-download me-1"></i> Download PDF
                    </a>
                    <a href="#" class="btn btn-success">
                        <i class="ti ti-file-type-csv me-1"></i> Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="employee-type-data" class="table table-hover table-borderless align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Employee Type</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employeeTypes as $employeeType)
                        <tr>
                            <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                            <td>{{ $employeeType->employee_type }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('superadmin.leave.type.edit', $employeeType->id) }}" class="btn btn-icon btn-link-primary avtar-xs" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <form action="{{ route('superadmin.leave.type.destroy', $employeeType->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-link-danger avtar-xs" style="border: none; background: none;" data-bs-toggle="tooltip" title="Delete">
                                            <i class="ti ti-trash f-20"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var table = $('#employee-type-data').DataTable();
    </script>
@endsection
