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
                            <h4>{{ $title }}</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.leave.employee.index') }}" class="btn btn-primary">Employee Information List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        <input type="hidden" name="emp_id" value="{{ $employeeLeave->id }}">

                        <!-- CL Section -->
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <input name="leave_type_cl" type="hidden" value="CL">
                                <h5>Casual Leave (CL)</h5>
                                <div class="mb-2">
                                    <input type="text" name="cl_dates" class="form-control" id="cl-datepicker" placeholder="Select CL dates (comma separated)">
                                    @if($errors->has('cl_dates'))
                                        <div class="text-danger">{{ $errors->first('cl_dates') }}</div>
                                    @endif
                                </div>
                                <h6>Cl In Hand: {{ $employeeLeave->cl_in_hand }}</h6>
                            </div>

                            <!-- EL Section -->
                            <div class="mb-3 col-lg-4">
                                <input name="leave_type_el" type="hidden" value="EL">
                                <h5>Earned Leave (EL)</h5>
                                <div class="mb-2">
                                    <input type="text" name="el_dates" class="form-control" id="el-datepicker" placeholder="Select EL dates (comma separated)">
                                    @if($errors->has('el_dates'))
                                        <div class="text-danger">{{ $errors->first('el_dates') }}</div>
                                    @endif
                                </div>
                                <h6>EL In Hand: {{ $employeeLeave->el_in_hand }}</h6>
                            </div>

                            <!-- ML Section -->
                            <div class="mb-3 col-lg-4">
                                <input name="leave_type_ml" type="hidden" value="ML">
                                <h5>Medical Leave (ML)</h5>
                                <div class="mb-2">
                                    <input type="text" name="ml_dates" class="form-control" id="ml-datepicker" placeholder="Select ML dates (comma separated)">
                                    @if($errors->has('ml_dates'))
                                        <div class="text-danger">{{ $errors->first('ml_dates') }}</div>
                                    @endif
                                </div>
                                <h6>ML In Hand: {{ $employeeLeave->ml_in_hand }}</h6>
                            </div>
                        </div>

                        <button type="submit" id="submit_button" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection


@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/flatpickr.js') }}"></script>
<script>
    const clInHand = {{ $employeeLeave->cl_in_hand }};
    const mlInHand = {{ $employeeLeave->ml_in_hand }};

    flatpickr("#cl-datepicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length > clInHand) {
                alert(`You can only select up to ${clInHand} Casual Leave dates.`);
                this.clear();
            }
        },
    });

    flatpickr("#el-datepicker", {
      mode: "multiple", // Enable multiple date selection
      dateFormat: "Y-m-d", // Format for displaying the dates
    });

    flatpickr("#ml-datepicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length > mlInHand) {
                alert(`You can only select up to ${clInHand} Medical Leave dates.`);
                this.clear();
            }
        },
    });
  </script>
@endsection
