@extends('layouts.main')

@section('title', 'Export Bootstrap Table')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/flatpickr.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                          <h4>{{ $title }}</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                          <a href="{{ route('superadmin.leave.employee.add') }}" class="btn btn-primary">Add Employee Information</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Bill No.</th>
                                    <th>Employee ID</th>
                                    <th>CL - In Hand</th>
                                    <th>EL - In Hand</th>
                                    <th>ML - In Hand</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employeeLeaves as $employeeLeave)
                                <tr>
                                    <td>{{ $employeeLeave->emp_number }}</td>
                                    <td>{{ $employeeLeave->emp_name }}</td>
                                    <td>{{ $employeeLeave->employeeType->employee_type ?? 'N/A' }}</td>
                                    <td>{{ $employeeLeave->bill_no ?? "N/A" }}</td>
                                    <td>{{ $employeeLeave->employee_id ?? "N/A" }}</td>
                                    <td>
                                        {{ $employeeLeave->cl_in_hand }}
                                    </td>
                                    <td>
                                        {{ $employeeLeave->el_in_hand }}
                                    </td>
                                    <td>
                                        {{ $employeeLeave->ml_in_hand }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('superadmin.leave.employee.edit', $employeeLeave->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>
                                        <form action="{{ route('superadmin.leave.employee.destroy', $employeeLeave->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item mt-1 text-danger" onclick="return confirm('Are you sure you want to delete this employee leave record?')"><i class="ti ti-trash f-20"></i></button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Bill No.</th>
                                    <th>Employee ID</th>
                                    <th>CL - In Hand</th>
                                    <th>EL - In Hand</th>
                                    <th>ML - In Hand</th>
                                    <th>Actions</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/flatpickr.js') }}"></script>
    <script>
        var total, pageTotal;
        var table = $('#dom-jqry').DataTable();
    </script>
@endsection
