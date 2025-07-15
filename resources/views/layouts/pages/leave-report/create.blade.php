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
                            <form method="POST" action="{{ route('superadmin.leave.report.report') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary me-2">Generate Leave Report</button>
                            </form>
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
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#leaveModal"
                                                data-emp-id="{{ $employeeLeave->id }}"
                                                data-emp-name="{{ $employeeLeave->emp_name }}"
                                                data-cl-in-hand="{{ $employeeLeave->cl_in_hand }}"
                                                data-el-in-hand="{{ $employeeLeave->el_in_hand }}"
                                                data-ml-in-hand="{{ $employeeLeave->ml_in_hand }}">
                                            Add Leave
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Employee Number</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
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
<!-- Single Leave Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="leaveModalLabel">Add Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="leaveForm" action="{{ route('superadmin.leave.enjoyed.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="emp_id" id="emp_id" value="">
                    <div class="mb-3">
                        <label for="cl_dates" class="form-label">Select CL Dates: <h6 id="clInHandText"></h6></label>
                        <input type="text" name="cl_dates" class="form-control" id="cl-datepicker" placeholder="Select CL dates (comma separated)" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="el_dates" class="form-label">Select EL Dates: <h6 id="elInHandText"></h6></label>
                        <input type="text" name="el_dates" class="form-control" id="el-datepicker" placeholder="Select EL dates (comma separated)" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="ml_dates" class="form-label">Select ML Dates: <h6 id="mlInHandText"></h6></label>
                        <input type="text" name="ml_dates" class="form-control" id="ml-datepicker" placeholder="Select ML dates (comma separated)" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Leave</button>
                </form>
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

<script>
    // Initialize Flatpickr for the date inputs
    flatpickr("#cl-datepicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            this.input.value = selectedDates.map(date => date.toISOString().split('T')[0]).join(', ');
        },
    });

    flatpickr("#el-datepicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            this.input.value = selectedDates.map(date => date.toISOString().split('T')[0]).join(', ');
        },
    });

    flatpickr("#ml-datepicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            this.input.value = selectedDates.map(date => date.toISOString().split('T')[0]).join(', ');
        },
    });

    // Event listener for the modal show event
    $('#leaveModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var empId = button.data('emp-id');
        var empName = button.data('emp-name');
        var clInHand = button.data('cl-in-hand');
        var elInHand = button.data('el-in-hand');
        var mlInHand = button.data('ml-in-hand');

        // Update the modal's content
        var modal = $(this);
        modal.find('.modal-title').text('Add Leave for ' + empName);
        modal.find('#emp_id').val(empId); // Set the employee ID in the hidden input
        modal.find('#clInHandText').text('CL In Hand: ' + clInHand);
        modal.find('#elInHandText').text('EL In Hand: ' + elInHand);
        modal.find('#mlInHandText').text('ML In Hand: ' + mlInHand);

        // Clear previous dates
        modal.find('#cl-datepicker').val('');
        modal.find('#el-datepicker').val('');
        modal.find('#ml-datepicker').val('');
    });
</script>
@endsection
