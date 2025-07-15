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
              <a href="{{ route('superadmin.leave.type.list') }}" class="btn btn-primary">Employee Type List</a>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf

            <div class="row">
                <div class="mb-3 col-lg-12">
                    <label class="form-label">Employee Type:</label>
                    <input type="text" name="employee_type" class="form-control" placeholder="Enter Employee Type" value="{{ old('employee_type', $employeeType->employee_type ?? '') }}" required>
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
