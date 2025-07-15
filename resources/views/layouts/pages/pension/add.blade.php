@extends('layouts.main')

@section('title', 'Add Pensioners')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/datepicker-bs5.min.css') }}">
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
                  <h4>Add Pensioners</h4>
                </div>
              </div>
          </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf

            <div class="row">
                <!-- Updated the name for Name Of Pensioners -->
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Name Of Pensioners:</label>
                    <input type="text" name="pensioner_name" class="form-control" placeholder="Enter Name Of Pensioners" value="{{ old('pensioner_name') }}" required>
                    @error('pensioner_name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Type of Pension remains unchanged -->
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Type Of Pension:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="pension_type" value="1"> Self</label>
                        <label><input type="radio" name="pension_type" value="2"> Family member</label>
                    </div>
                    @error('pension_type')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Rest of the form fields remain the same -->
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Family Name:</label>
                    <input type="text" name="family_name" class="form-control" placeholder="Enter Family Name" value="{{ old('family_name') }}" required>
                    @error('family_name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Date of Birth:</label>
                    <input type="text" name="dob" id="dob" class="form-control" value="{{ old('dob') }}" required>
                    @error('dob')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Date of Retirement:</label>
                    <input type="text" name="retirement_date" id="retirement_date" class="form-control" value="{{ old('retirement_date') }}" required>
                    @error('retirement_date')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Alive/Dead:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="alive_status" value="1" onclick="togglePaymentFields()" checked> Alive</label>
                        <label><input type="radio" name="alive_status" value="2" onclick="togglePaymentFields()"> Dead</label>
                    </div>
                    @error('alive_status')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div id="dateofdeathfield" class="mb-3 col-lg-4" style="display: none;">
                    <label class="form-label">Date of Death:</label>
                    <input type="text" name="death_date" id="death_date" class="form-control" value="{{ old('death_date') }}">
                    @error('death_date')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Life Certificate:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="life_certificate" value="1"> Yes</label>
                        <label><input type="radio" name="life_certificate" value="2"> No</label>
                    </div>
                    @error('life_certificate')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Employee Code:</label>
                    <input type="text" name="employee_code" class="form-control" placeholder="Enter Employee Code" value="{{ old('employee_code') }}" required>
                    @error('employee_code')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">PPO No.:</label>
                    <input type="text" name="ppo_number" class="form-control" placeholder="Enter PPO No." value="{{ old('ppo_number') }}" required>
                    @error('ppo_number')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">PPO Date:</label>
                    <input type="text" name="ppo_date" id="ppo_date" class="form-control" value="{{ old('ppo_date') }}" required>
                    @error('ppo_date')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Ropa Year:</label>
                    <select name="ropa_year" class="form-control">
                        <option value="">Select</option>
                        @foreach($ropaYears as $item)
                            <option value="{{ $item->id }}" {{ old('ropa_year') == $item->year ? 'selected' : '' }}>
                                {{ $item->year }}
                            </option>
                        @endforeach
                    </select>
                    @error('ropa_year')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Aadhar No:</label>
                    <input type="number" name="aadhar_number" class="form-control" placeholder="Enter Aadhar Number" value="{{ old('aadhar_number') }}" required>
                    @error('aadhar_number')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Savings Ac No.:</label>
                    <input type="number" name="savings_account_number" class="form-control" placeholder="Enter Savings Account No." value="{{ old('savings_account_number') }}" required>
                    @error('savings_account_number')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">IFSC Code:</label>
                    <input type="text" name="ifsc_code" class="form-control" placeholder="Enter IFSC Code" value="{{ old('ifsc_code') }}" required>
                    @error('ifsc_code')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Basic Pension:</label>
                    <input type="number" name="basic_pension" class="form-control" placeholder="Enter Basic Pension" value="{{ old('basic_pension') }}" required>
                    @error('basic_pension')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Medical Allowance:</label>
                    <input type="number" name="medical_allowance" class="form-control" placeholder="Enter Medical Allowance" value="{{ old('medical_allowance') }}" required>
                    @error('medical_allowance')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Other Allowance:</label>
                    <input type="number" name="other_allowance" class="form-control" placeholder="Enter Other Allowance" value="{{ old('other_allowance') }}" required>
                    @error('other_allowance')
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

@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/choices.min.js') }}"></script>

<script>
    function togglePaymentFields() {
        var aliveStatus = document.querySelector('input[name="alive_status"]:checked').value;

        if (aliveStatus === '1') {
            document.getElementById('dateofdeathfield').style.display = 'none';
        } else if (aliveStatus === '2') {
            document.getElementById('dateofdeathfield').style.display = 'block';
        }
    }
</script>

<script>
    (function() {
        const dateFields = ['#dob', '#retirement_date', '#death_date', '#ppo_date'];

        dateFields.forEach(function(fieldId) {
            const datePicker = new Datepicker(document.querySelector(fieldId), {
                buttonClass: 'btn',
                format: 'yyyy-mm-dd',
            });
        });
    })();
</script>
@endsection
