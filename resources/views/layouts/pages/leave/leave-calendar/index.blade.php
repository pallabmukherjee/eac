@extends('layouts.main')

@section('title', 'Leave Calendar')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Leave Calendar</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.leave.calendar.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="calendar" style="width: 100%;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Leave Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="employee_id" class="form-label">Employee</label>
                                                <select name="employee_id" id="employee_id" class="form-select">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->emp_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="leave_type" class="form-label">Leave Type</label>
                                                <select name="leave_type" id="leave_type" class="form-select">
                                                    @foreach ($leaveTypes as $leaveType)
                                                        <option value="{{ $leaveType }}">{{ $leaveType }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="selected_dates" class="form-label">Selected Dates</label>
                                                <input type="text" name="selected_dates" id="selected_dates" class="form-control" readonly>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Leave</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const calendar = new VanillaCalendar('#calendar', {
                type: 'multiple',
                actions: {
                    clickDay(e, dates) {
                        const selectedDatesInput = document.getElementById('selected_dates');
                        selectedDatesInput.value = calendar.selectedDates.join(',');
                    },
                },
            });
            calendar.init();
        });
    </script>
@endsection