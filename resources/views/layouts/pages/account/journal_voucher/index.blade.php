@extends('layouts.main')

@section('title', 'Multi Form Layouts')

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
            <div class="col-sm-12">
                <h4>Journal Voucher</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form id="myForm" method="POST" action="{{ $url }}">
            @csrf
            <div class="row">
                <div class="mb-3 col-lg-6">
                    <label class="form-label">Date:</label>
                    <input type="text" name="date" placeholder="Select Date" id="date" class="form-control" value="{{ old('date', '') }}">
                </div>
                <div class="mb-3 col-lg-6">
                    <label class="form-label">Narration:</label>
                    <input type="text" name="narration" class="form-control" value="{{ old('narration', '') }}">
                </div>

                <div id="journal-voucher-forms">
                    <div class="journal-voucher-form">
                        <div class="row">
                            <div class="col-sm-9 mb-3">
                                <div class="bg-light rounded-3 p-3">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="form-label">Select Ledger:</label>
                                            <select name="ledger_head[]" class="form-control">
                                                <option value="">Select Ledger</option>
                                                @foreach($ledger as $item)
                                                    <option value="{{ $item->ledgers_head_code }}" {{ old('ledger_head[]') == $item->ledgers_head_code ? 'selected' : '' }}>
                                                        {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">Amount:</label>
                                            <input type="number" name="amount[]" class="form-control" step="0.01" value="{{ old('amount', '') }}">
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="form-label">Credit/Debit:</label>
                                            <select name="crdr[]" class="form-control">
                                                <option value="">Select Credit/Debit</option>
                                                <option value="1">Credit</option>
                                                <option value="2">Debit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 form-buttons">
                                <div class="d-flex justify-content-end mt-3 mb-3">
                                    <button type="button" class="add-more-btn btn btn-primary me-3">Add More</button>
                                    <!-- Button to remove the current form -->
                                    <button type="button" class="remove-btn btn btn-danger">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3 col-lg-12">
                    <label class="form-label">Remarks:</label>
                    <textarea name="remarks" id="" cols="30" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                <span class="btn-text">Submit</span>
            </button>
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
        var resetSimple = new Choices(document.getElementById('head'));
        var resetSimple = new Choices(document.getElementById('bank'));
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Handle the 'Add More' button click
            document.addEventListener('click', function (event) {
                // Check if the clicked element is an Add More button
                if (event.target && event.target.classList.contains('add-more-btn')) {
                    let container = document.getElementById('journal-voucher-forms');
                    let newForm = document.querySelector('.journal-voucher-form').cloneNode(true);

                    // Reset the fields in the new form
                    newForm.querySelectorAll('input').forEach(input => input.value = '');
                    newForm.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                    // Append the new form to the container
                    container.appendChild(newForm);
                }

                // Handle the 'Remove' button click
                if (event.target && event.target.classList.contains('remove-btn')) {
                    let form = event.target.closest('.journal-voucher-form');
                    let forms = document.querySelectorAll('.journal-voucher-form');

                    // Only remove the form if it's not the last one
                    if (forms.length > 1) {
                        form.remove();
                    } else {
                        alert("You cannot remove the last form!");
                    }
                }
            });
        });
    </script>

    <script>
        (function() {
            const d_week = new Datepicker(document.querySelector('#date'), {
                buttonClass: 'btn',
                format: 'yyyy-mm-dd',
            });
        })();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('myForm');
            const submitBtn = document.getElementById('submitBtn');
            const spinner = submitBtn.querySelector('.spinner-border');
            const btnText = submitBtn.querySelector('.btn-text');

            form.addEventListener('submit', function () {
                // Disable the button immediately
                submitBtn.disabled = true;

                // Show spinner and change text
                spinner.classList.remove('d-none');
                btnText.textContent = 'Submitting...';
            });
        });
    </script>
@endsection
