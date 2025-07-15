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
                        <div class="col-sm-6">
                            <h4>Edit Contra Voucher</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <h4>Bill No: {{ $voucherEntries[0]->bill_no }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="myForm" method="POST" action="{{ $url }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label class="form-label">Date:</label>
                                <input type="text" name="date" id="date" placeholder="Select Date"
                                       class="form-control"
                                       value="{{ old('date', $voucherEntries[0]->date ?? '') }}">
                            </div>

                            <div class="col-sm-12">
                                <div id="contra-voucher-forms">
                                    @foreach($voucherEntries as $index => $entry)
                                    <div class="contra-voucher-form">
                                        <div class="row">
                                            <!-- From -->
                                            <div class="col-sm-6 mb-3">
                                                <div class="bg-light rounded-3 p-3">
                                                    <h5>From</h5>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <select name="from_head[]" class="form-control">
                                                                <option value="">Ledger Head</option>
                                                                @foreach($selectHead as $item)
                                                                    <option value="{{ $item->ledgers_head_code }}"
                                                                        {{ $entry->from_head == $item->ledgers_head_code ? 'selected' : '' }}>
                                                                        {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select name="from_bank[]" class="form-control">
                                                                <option value="">Bank</option>
                                                                @foreach($bank as $item)
                                                                    <option value="{{ $item->ledgers_head_code }}"
                                                                        {{ $entry->from_bank == $item->ledgers_head_code ? 'selected' : '' }}>
                                                                        {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- To -->
                                            <div class="col-sm-6 mb-3">
                                                <div class="bg-light rounded-3 p-3">
                                                    <h5>To</h5>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <select name="to_head[]" class="form-control">
                                                                <option value="">Ledger Head</option>
                                                                @foreach($selectHead as $item)
                                                                    <option value="{{ $item->ledgers_head_code }}"
                                                                        {{ $entry->to_head == $item->ledgers_head_code ? 'selected' : '' }}>
                                                                        {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select name="to_bank[]" class="form-control">
                                                                <option value="">Bank</option>
                                                                @foreach($bank as $item)
                                                                    <option value="{{ $item->ledgers_head_code }}"
                                                                        {{ $entry->to_bank == $item->ledgers_head_code ? 'selected' : '' }}>
                                                                        {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cash -->
                                            <div class="col-sm-2">
                                                <div class="bg-light rounded-3 p-3">
                                                    <h5>Cash</h5>
                                                    <input type="number" name="cash_amount[]" class="form-control" placeholder="Cash Amount"
                                                           step="0.01" value="{{ old('cash_amount.' . $index, $entry->cash_amount) }}">
                                                </div>
                                            </div>

                                            <!-- Cheque -->
                                            <div class="col-sm-6">
                                                <div class="bg-light rounded-3 p-3">
                                                    <h5>Cheque</h5>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <input type="number" name="cheque_amount[]" placeholder="Cheque Amount"
                                                                   class="form-control" step="0.01"
                                                                   value="{{ old('cheque_amount.' . $index, $entry->cheque_amount) }}">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="cheque_no[]" placeholder="Cheque No"
                                                                   class="form-control"
                                                                   value="{{ old('cheque_no.' . $index, $entry->cheque_no) }}">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="date" name="cheque_date[]" placeholder="Date"
                                                                   class="form-control"
                                                                   value="{{ old('cheque_date.' . $index, $entry->cheque_date) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Online -->
                                            <div class="col-sm-4">
                                                <div class="bg-light rounded-3 p-3">
                                                    <h5>Online</h5>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <input type="number" name="online_amount[]" placeholder="Online Amount"
                                                                   class="form-control" step="0.01"
                                                                   value="{{ old('online_amount.' . $index, $entry->online_amount) }}">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="online_remarks[]" placeholder="Online Remarks"
                                                                   class="form-control"
                                                                   value="{{ old('online_remarks.' . $index, $entry->online_remarks) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="col-sm-12 form-buttons">
                                                <div class="d-flex justify-content-end mt-3 mb-3">
                                                    <button type="button" class="add-more-btn btn btn-primary me-3">Add More</button>
                                                    <button type="button" class="remove-btn btn btn-danger">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                            <span class="btn-text">Update</span>
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
                let container = document.getElementById('contra-voucher-forms');
                let newForm = document.querySelector('.contra-voucher-form').cloneNode(true);

                // Reset the fields in the new form
                newForm.querySelectorAll('input').forEach(input => input.value = '');
                newForm.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                // Append the new form to the container
                container.appendChild(newForm);
            }

            // Handle the 'Remove' button click
            if (event.target && event.target.classList.contains('remove-btn')) {
                let form = event.target.closest('.contra-voucher-form');
                let forms = document.querySelectorAll('.contra-voucher-form');

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
            const dateFields = ['#date', '#cheque_date', '#reference_date'];

            dateFields.forEach(function(fieldId) {
                const datePicker = new Datepicker(document.querySelector(fieldId), {
                    buttonClass: 'btn',
                    format: 'yyyy-mm-dd',
                });
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
