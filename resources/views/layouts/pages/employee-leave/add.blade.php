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

            <div class="row">
                <div class="mb-3 col-lg-3">
                    <label class="form-label">Employee Number:</label>
                    <input type="text" name="emp_number" class="form-control" placeholder="Enter Employee Number" value="{{ old('emp_number', $employeeLeave->emp_number ?? '') }}" required>
                    @error('emp_number')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Employee Name:</label>
                    <input type="text" name="emp_name" class="form-control" placeholder="Enter Employee Name" value="{{ old('emp_name', $employeeLeave->emp_name ?? '') }}" required>
                    @error('emp_name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Bill no:</label>
                    <input type="text" name="bill_no" class="form-control" placeholder="Enter Bill no" value="{{ old('bill_no', $employeeLeave->bill_no ?? '') }}" required>
                    @error('bill_no')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Employee ID:</label>
                    <input type="text" name="employee_id" class="form-control" placeholder="Enter Employee ID" value="{{ old('employee_id', $employeeLeave->employee_id ?? '') }}" required>
                    @error('employee_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Designation:</label>
                    <select name="designation" class="form-control" required>
                        <option value="">Select Designation</option>
                        @foreach($employeeTypes as $employeeType)
                            <option value="{{ $employeeType->id }}" {{ (old('designation', $employeeLeave->designation ?? '') == $employeeType->id) ? 'selected' : '' }}>
                                {{ $employeeType->employee_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('designation')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Casual Leave (CL):</label>
                    <input type="number" name="cl" class="form-control" placeholder="Enter Casual Leave" value="{{ old('cl', $employeeLeave->cl ?? '') }}" required>
                    @error('cl')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Earned Leave (EL):</label>
                    <input type="number" name="el" class="form-control" placeholder="Enter Earned Leave" value="{{ old('el', $employeeLeave->el ?? '') }}" required>
                    @error('el')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-3">
                    <label class="form-label">Medical Leave (ML):</label>
                    <input type="number" name="ml" class="form-control" placeholder="Enter Medical Leave" value="{{ old('ml', $employeeLeave->ml ?? '') }}" required>
                    @error('ml')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
  <!-- [ form-element ] end -->
</div>
<!-- [ Main Content ] end -->
@endsection
