@extends('layouts.main')

@section('title', 'Update Gratuity')

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
                  <h4>Update Gratuity</h4>
                </div>
              </div>
          </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf

            <div class="row">
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Name of Employee:</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ old('name', $gratuity->name) }}" required>
                    @error('name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Spouse Name:</label>
                    <input type="text" name="relation_name" class="form-control" placeholder="Enter Family Name" value="{{ old('relation_name', $gratuity->relation_name) }}">
                    @error('relation_name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Employee Code:</label>
                    <input type="text" name="employee_code" class="form-control" placeholder="Enter Code" value="{{ old('employee_code', $gratuity->employee_code) }}" required>
                    @error('employee_code')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">PPO No.:</label>
                    <input type="text" name="ppo_number" class="form-control" placeholder="Enter PPO No." value="{{ old('ppo_number', $gratuity->ppo_number) }}" required>
                    @error('ppo_number')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">PPO Date:</label>
                    <input type="text" name="ppo_receive_date" id="ppo_receive_date" class="form-control" value="{{ old('ppo_receive_date', $gratuity->ppo_receive_date) }}" required>
                    @error('ppo_receive_date')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Status:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="retire_dead" value="1" {{ $gratuity->retire_dead == 1 ? 'checked' : '' }}> Retire</label>
                        <label><input type="radio" name="retire_dead" value="2" {{ $gratuity->retire_dead == 2 ? 'checked' : '' }}> Dead</label>
                    </div>
                    @error('retire_dead')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Retire/Death Date:</label>
                    <input type="text" name="retirement_date" id="retirement_date" class="form-control" value="{{ old('retirement_date', $gratuity->retirement_date) }}" required>
                    @error('retirement_date')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Financial Year:</label>
                    <select name="financial_year" class="form-control">
                        <option value="">Select Financial Year</option>
                        @foreach($financialYears as $item)
                            <option value="{{ $item->id }}" {{ old('financial_year', $gratuity->financial_year) == $item->id ? 'selected' : '' }}>
                                {{ $item->year }}
                            </option>
                        @endforeach
                    </select>
                    @error('financial_year')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Is Employee Alive/Dead:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="alive_status" value="1" {{ $gratuity->alive_status == 1 ? 'checked' : '' }}> Alive</label>
                        <label><input type="radio" name="alive_status" value="2" {{ $gratuity->alive_status == 2 ? 'checked' : '' }}> Dead</label>
                    </div>
                    @error('alive_status')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Loan Status:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="loan_status" value="1" {{ $gratuity->loan_status == 1 ? 'checked' : '' }}> Yes</label>
                        <label><input type="radio" name="loan_status" value="2" {{ $gratuity->loan_status == 2 ? 'checked' : '' }}> No</label>
                    </div>
                    @error('loan_status')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Is Spouse Dead/Alive:</label>
                    <div class="form-control">
                        <label class="me-3"><input type="radio" name="relation_died" value="1" {{ $gratuity->relation_died == 1 ? 'checked' : '' }} onclick="togglePaymentFields()"> Dead</label>
                        <label><input type="radio" name="relation_died" value="2" {{ $gratuity->relation_died == 2 ? 'checked' : '' }} onclick="togglePaymentFields()"> Alive</label>
                    </div>
                    @error('relation_died')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-lg-4" id="dateofdeathfield" style="display: none;">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Warission Name:</label>
                            <input type="text" name="warrant_name" class="form-control" value="{{ old('warrant_name', $gratuity->warrant_name) }}">
                            @error('warrant_name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Warission Aadhar No.:</label>
                            <input type="text" name="warrant_adhar_no" class="form-control" value="{{ old('warrant_adhar_no', $gratuity->warrant_adhar_no) }}">
                            @error('warrant_adhar_no')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Amount Sanctioned as per P.P.O:</label>
                    <input type="number" name="sanctioned_amount" class="form-control" placeholder="Enter Amount" value="{{ old('sanctioned_amount', $gratuity->sanctioned_amount) }}" required>
                    @error('sanctioned_amount')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Outstanding balance as on 31 March {{ date('Y') }}:</label>
                    <input type="number" name="ppo_amount" class="form-control" placeholder="Enter Amount" value="{{ old('ppo_amount', $gratuity->ppo_amount) }}" required>
                    @error('ppo_amount')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Ropa Year:</label>
                    <select name="ropa_year" class="form-control">
                        <option>Select Ropa Year</option>
                        @foreach($ropaYears as $item)
                            <option value="{{ $item->id }}" {{ old('ropa_year', $gratuity->ropa_year) == $item->id ? 'selected' : '' }}>
                                {{ $item->year }}
                            </option>
                        @endforeach
                    </select>
                    @error('ropa_year')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Bank Account No:</label>
                    <input type="number" name="bank_ac_no" class="form-control" placeholder="Enter Bank Account No" value="{{ old('bank_ac_no', $gratuity->bank_ac_no) }}" required>
                    @error('bank_ac_no')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">IFSC Code:</label>
                    <input type="text" name="ifsc" class="form-control" placeholder="Enter IFSC Code" value="{{ old('ifsc', $gratuity->ifsc) }}" required>
                    @error('ifsc')
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

<script>
    function togglePaymentFields() {
        var aliveStatus = document.querySelector('input[name="relation_died"]:checked').value;

        if (aliveStatus === '2') {
            document.getElementById('dateofdeathfield').style.display = 'none';
        } else if (aliveStatus === '1') {
            document.getElementById('dateofdeathfield').style.display = 'block';
        }
    }
</script>


<script>
    (function() {
        const dateFields = ['#ppo_receive_date', '#retirement_date'];

        dateFields.forEach(function(fieldId) {
            const datePicker = new Datepicker(document.querySelector(fieldId), {
                buttonClass: 'btn',
                format: 'yyyy-mm-dd',
            });
        });
    })();
</script>
@endsection
