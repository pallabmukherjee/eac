@extends('layouts.main')

@section('title', 'Multi Form Layouts')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/flatpickr.min.css') }}">
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
                    <h4>{{ $report->report_month }}</h4>
                    @php
                        $currentMonthYear = date('F-Y'); // Gets current month-year (e.g. "March-2025")
                        $isCurrentMonth = ($report->report_month == $currentMonthYear);
                    @endphp
                </div>
                <div class="col-sm-6 text-sm-end">
                    <form action="{{ route('superadmin.leave.report.download') }}" method="POST" id="download-form">
                        @csrf
                        <input type="hidden" name="month" value="{{ $report->report_month }}">
                        <button type="submit" name="format" value="csv" class="btn btn-success">Download CSV</button>
                        <button type="submit" name="format" value="pdf" class="btn btn-primary">Download PDF</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Srl</th>
                            <th>Emp Name</th>
                            <th>Designation	</th>
                            <th>CL</th>
                            <th>CL Enjoyed</th>
                            <th>CL Date</th>
                            <th>CL In Hand</th>
                            <th>EL</th>
                            <th>EL Enjoyed</th>
                            <th>EL Date</th>
                            <th>EL In Hand</th>
                            <th>ML</th>
                            <th>ML Enjoyed</th>
                            <th>ML Date</th>
                            <th>ML In Hand</th>
                            @if($isCurrentMonth)
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveReport as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->emp_name }}</td>
                            <td>{{ $item->employeeType->employee_type }}</td>
                            <td>{{ $item->cl }}</td>
                            <td>{{ $item->cl_enjoyed }}</td>
                            <td>{{ $item->cl_date ?? "N/A" }}</td>
                            <td>{{ $item->cl_in_hand }}</td>
                            <td>{{ $item->el }}</td>
                            <td>{{ $item->el_enjoyed }}</td>
                            <td>{{ $item->el_date ?? "N/A" }}</td>
                            <td>{{ $item->el_in_hand }}</td>
                            <td>{{ $item->ml }}</td>
                            <td>{{ $item->ml_enjoyed }}</td>
                            <td>{{ $item->ml_date ?? "N/A" }}</td>
                            <td>{{ $item->ml_in_hand }}</td>
                            @if($isCurrentMonth)
                            <td>
                                <!-- Edit Button with Data Attributes -->
                                <button class="btn btn-primary edit-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-cl-date="{{ $item->cl_date ?? '0' }}"
                                    data-el-date="{{ $item->el_date ?? '0' }}"
                                    data-ml-date="{{ $item->ml_date ?? '0' }}"
                                    data-id="{{ $item->id }}"
                                    data-emp-id="{{ $item->emp_id }}">
                                Edit
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Srl</th>
                            <th>Emp Name</th>
                            <th>Designation	</th>
                            <th>CL</th>
                            <th>CL Enjoyed</th>
                            <th>CL Date</th>
                            <th>CL In Hand</th>
                            <th>EL</th>
                            <th>EL Enjoyed</th>
                            <th>EL Date</th>
                            <th>EL In Hand</th>
                            <th>ML</th>
                            <th>ML Enjoyed</th>
                            <th>ML Date</th>
                            <th>ML In Hand</th>
                            @if($isCurrentMonth)
                            <th>Action</th>
                            @endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
  </div>
  <!-- [ form-element ] end -->
</div>
<!-- [ Main Content ] end -->

<!-- Modal Structure -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Leave Dates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editLeaveForm" action="{{ route('superadmin.leave.report.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="leave_id">
                    <input type="hidden" name="emp_id" id="emp_id">

                    <div class="mb-3">
                        <label for="cl_date" class="form-label">CL Dates</label>
                        <input type="text" name="cl_date" class="form-control" id="cl-datepicker" placeholder="Select CL dates (comma separated)">
                    </div>
                    <div class="mb-3">
                        <label for="el_date" class="form-label">EL Dates</label>
                        <input type="text" name="el_date" class="form-control" id="el-datepicker" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="ml_date" class="form-label">ML Dates</label>
                        <input type="text" name="ml_date" class="form-control" id="ml-datepicker" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/flatpickr.js') }}"></script>
<script>
    var total, pageTotal;
    var table = $('#dom-jqry').DataTable({
        "order": [[0, 'asc']]
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        const clPicker = flatpickr('#cl-datepicker', {
            mode: "multiple",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        const elPicker = flatpickr('#el-datepicker', {
            mode: "multiple",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        const mlPicker = flatpickr('#ml-datepicker', {
            mode: "multiple",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Edit button click handler
        document.addEventListener('click', function(e) {
            if (!e.target.classList.contains('edit-btn')) return;

            const btn = e.target;

            // Set employee ID
            document.getElementById('leave_id').value = btn.dataset.id;
            document.getElementById('emp_id').value = btn.dataset.empId; // Set emp_id

            // Process each date type
            processDates(clPicker, btn.dataset.clDate);
            processDates(elPicker, btn.dataset.elDate);
            processDates(mlPicker, btn.dataset.mlDate);
        });

        // Date processing function
        function processDates(picker, dateString) {
            picker.clear();

            if (!dateString || dateString === '0' || dateString === 'N/A') {
                return;
            }

            const dates = dateString.split(',')
                .map(date => date.trim())
                .filter(date => date.length > 0)
                .map(date => {
                    // If just day number (e.g., "09")
                    if (/^\d{1,2}$/.test(date)) {
                        const now = new Date();
                        return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${date.padStart(2, '0')}`;
                    }
                    return date; // Assume already in YYYY-MM-DD format
                });

            picker.setDate(dates);
        }

        // Form submission handler
        document.getElementById('editLeaveForm').addEventListener('submit', function(e) {
            // Ensure dates are properly formatted before submission
            const formatDates = (picker) => {
                return picker.selectedDates.map(date => {
                    return picker.formatDate(date, 'Y-m-d');
                }).join(', ');
            };

            document.getElementById('cl-datepicker').value = formatDates(clPicker);
            document.getElementById('el-datepicker').value = formatDates(elPicker);
            document.getElementById('ml-datepicker').value = formatDates(mlPicker);
        });
    });
    </script>
@endsection
