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
                <h4>Update Payment Voucher</h4>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h4>Bill No: {{ $paymentVoucher->bill_no }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form id="myForm" method="POST" action="{{ $url }}">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Date Input -->
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Date:</label>
                    <input type="text" id="date" name="date" class="form-control" placeholder="" value="{{ old('date', $paymentVoucher->date) }}">
                </div>

                <!-- Payee Dropdown -->
                <div class="mb-3 col-lg-4">
                    <div class="d-flex justify-content-between">
                        <label class="form-label">Payee:</label>
                        <label class="form-label"><a href="{{ route('superadmin.account.beneficiary.index') }}">Add Beneficiary</a></label>
                    </div>
                    <select name="payee" class="form-control" id="payee">
                        <option value="">Select Payee</option>
                        @foreach($beneficiarys as $item)
                            <option value="{{ $item->id }}" {{ old('payee', $paymentVoucher->payee) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Scheme Fund Dropdown -->
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Scheme Fund:</label>
                    <select name="scheme_fund" class="form-control" id="scheme_fund">
                        <option value="">Select Scheme Fund</option>
                        @foreach($schemefund as $item)
                            <option value="{{ $item->ledgers_head_code }}" {{ old('scheme_fund', $paymentVoucher->scheme_fund) == $item->ledgers_head_code ? 'selected' : '' }}>
                                {{ $item->ledgers_head_code }} - {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Mode Radio Buttons -->
                <div class="row">
                    <div class="mb-3 col-lg-4">
                        <label class="form-label">Payment Mode:</label>
                        <div class="form-control">
                            <label class="me-3"><input type="radio" name="payment_mode" value="1" {{ old('payment_mode', $paymentVoucher->payment_mode) == 1 ? 'checked' : '' }} onclick="togglePaymentFields()"> Cash in Hand</label>
                            <label><input type="radio" name="payment_mode" value="2" {{ old('payment_mode', $paymentVoucher->payment_mode) == 2 ? 'checked' : '' }} onclick="togglePaymentFields()"> Bank</label>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div id="chequeFields" style="display:none;" class="row">
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Select Bank:</label>
                                <select name="bank" class="form-control" id="bank">
                                    <option value="">Select Bank</option>
                                    @foreach($bank as $item)
                                    <option value="{{ $item->ledgers_head_code }}" {{ old('bank', $paymentVoucher->bank) == $item->ledgers_head_code ? 'selected' : '' }}>
                                        {{ $item->ledgers_head_code }} - {{ $item->name }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-lg-3">
                                <label class="form-label">Cheque No:</label>
                                <input type="text" name="cheque_no" class="form-control" placeholder="Cheque Number" value="{{ old('cheque_no', $bankcheque->cheque_no ?? 'NA') }}">
                            </div>

                            <div class="mb-3 col-lg-3">
                                <label class="form-label">Cheque Date:</label>
                                <input type="text" id="cheque_date" name="cheque_date" class="form-control" placeholder="Cheque Date" value="{{ old('cheque_date', $bankcheque->date ?? 'NA') }}">
                            </div>
                        </div>
                        <div id="bankFields" style="display:none;" class="row">

                        </div>
                    </div>
                </div>

                <div class="mb-3 col-lg-6">
                    <label class="form-label">Reference Number:</label>
                    <input type="text" name="reference_number" placeholder="Reference Number" class="form-control" value="{{ old('reference_number', $paymentVoucher->reference_number) }}">
                </div>
                <div class="mb-3 col-lg-6">
                    <label class="form-label">Reference Date:</label>
                    <input type="text" id="reference_date" name="reference_date" placeholder="Reference Date" class="form-control" value="{{ old('reference_date', $paymentVoucher->reference_date) }}">
                </div>


                <h4>Billing</h4>
                <!-- Dynamic Ledger Fields -->
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Select Ledger <span class="text-danger">*</span></th>
                            <th>Amount <span class="text-danger">*</span></th>
                            <th>Pay/Deduct <span class="text-danger">*</span></th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ledgerFieldsContainer">
                        @foreach($ledgerItem as $index => $item)
                        <tr class="ledger-fields">
                            <td width="700px">
                                <label class="form-label d-none">Select Ledger:</label>
                                <select name="ledger[]" class="form-control ledger-select">
                                    <option value="">Select Ledger</option>
                                    <!-- Populate this with your dynamic data -->
                                    @foreach($ledger as $ldg)
                                        <option value="{{ $ldg->ledgers_head_code }}" {{ old('ledger.'.$index, $item->ledger) == $ldg->ledgers_head_code ? 'selected' : '' }}>
                                            {{ $ldg->ledgers_head_code }} - {{ $ldg->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <label class="form-label d-none">Amount:</label>
                                <input type="number" name="amount[]" class="form-control amount-field" placeholder="Amount" value="{{ old('amount.'.$index, $item->amount) }}" step="0.01">
                            </td>
                            <td>
                                <label class="form-label d-none">Pay/Deduct:</label>
                                <select name="pay_deduct[]" class="form-control pay-deduct-field">
                                    <option value="">Select Option</option>
                                    <option value="1" {{ old('pay_deduct.'.$index, $item->pay_deduct) == 1 ? 'selected' : '' }}>Pay</option>
                                    <option value="2" {{ old('pay_deduct.'.$index, $item->pay_deduct) == 2 ? 'selected' : '' }}>Deduct</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-success add-field-btn">Add</button>
                                <button type="button" class="btn btn-danger remove-field-btn" style="display:none;">Remove</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="mb-3 col-lg-12">
                    <label class="form-label">Narration:</label>
                    <textarea name="narration" class="form-control" cols="30" rows="2">{{ old('narration', $paymentVoucher->narration) }}</textarea>
                </div>
                <div class="col-lg-12">
                    <p>Net Payment (<span id="totalPayAmount">00.00</span> - <span id="totalDeductAmount">00.00</span>) = <span id="netAmount">00.00</span></p>
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
        var resetSimple = new Choices(document.getElementById('scheme_fund'));
        var resetSimple = new Choices(document.getElementById('payee'));
        var resetSimple = new Choices(document.getElementById('bank'));
    </script>

    <script>
        function togglePaymentFields() {
            var paymentMode = document.querySelector('input[name="payment_mode"]:checked').value;

            if (paymentMode === '2') {
                document.getElementById('chequeFields').style.display = 'flex';
                document.getElementById('bankFields').style.display = 'none';
            } else if (paymentMode === '1') {
                document.getElementById('chequeFields').style.display = 'none';
                document.getElementById('bankFields').style.display = 'flex';
            }
        }
    </script>

    <script>
        // Function to initialize Choices.js for a select element
        const initializeChoices = (selectElement) => {
            return new Choices(selectElement, {
                searchEnabled: true,
                placeholder: true,
                placeholderValue: 'Select Ledger',
            });
        };

        // Initialize Choices for the first select
        const initialSelect = document.querySelector('.ledger-select');
        const initialChoices = initializeChoices(initialSelect);

        // Function to create a new ledger row
        function createLedgerRow() {
            const newRow = document.createElement('tr');
            newRow.className = 'ledger-fields';
            newRow.innerHTML = `
                <td>
                    <label class="form-label d-none">Select Ledger:</label>
                    <select name="ledger[]" class="form-control ledger-select">
                        <option value="">Select Ledger</option>
                        <!-- Populate this with your dynamic data -->
                        @foreach($ledger as $item)
                            <option value="{{ $item->ledgers_head_code }}" {{ old('ledger[]') == $item->ledgers_head_code ? 'selected' : '' }}>
                                {{ $item->ledgers_head_code }} - {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <label class="form-label d-none">Amount:</label>
                    <input type="number" name="amount[]" class="form-control amount-field" placeholder="Amount" value="">
                </td>
                <td>
                    <label class="form-label d-none">Pay/Deduct:</label>
                    <select name="pay_deduct[]" class="form-control pay-deduct-field">
                        <option value="">Select Option</option>
                        <option value="1">Pay</option>
                        <option value="2">Deduct</option>
                    </select>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-success add-field-btn">Add</button>
                    <button type="button" class="btn btn-danger remove-field-btn">Remove</button>
                </td>
            `;
            return newRow;
        }

        // Function to calculate total pay, total deduct, and net amount
        function calculateAmounts() {
            let totalPay = 0;
            let totalDeduct = 0;

            document.querySelectorAll('.ledger-fields').forEach(function (row) {
                let amount = parseFloat(row.querySelector('.amount-field').value) || 0;
                let payDeduct = row.querySelector('.pay-deduct-field').value;

                if (payDeduct == '1') {
                    totalPay += amount;
                } else if (payDeduct == '2') {
                    totalDeduct += amount;
                }
            });

            let netAmount = totalPay - totalDeduct;

            document.getElementById('totalPayAmount').textContent = totalPay.toFixed(2);
            document.getElementById('totalDeductAmount').textContent = totalDeduct.toFixed(2);
            document.getElementById('netAmount').textContent = netAmount.toFixed(2);
        }

        // Event listener for adding/removing rows
        document.querySelector('#ledgerFieldsContainer').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('add-field-btn')) {
                // Create a new ledger row and append it to the table
                const newLedgerRow = createLedgerRow();
                document.querySelector('#ledgerFieldsContainer').appendChild(newLedgerRow);

                // Initialize Choices.js for the new select element
                const newSelect = newLedgerRow.querySelector('.ledger-select');
                initializeChoices(newSelect); // Initialize Choices for the new select

                // Show the remove button for the newly added row
                newLedgerRow.querySelector('.remove-field-btn').style.display = 'inline-block';
            }

            if (e.target && e.target.classList.contains('remove-field-btn')) {
                // Remove the parent ledger row
                if (document.querySelectorAll('.ledger-fields').length > 1) {
                    e.target.closest('.ledger-fields').remove();
                }
            }

            // Recalculate amounts when fields change
            if (e.target && (e.target.classList.contains('amount-field') || e.target.classList.contains('pay-deduct-field'))) {
                calculateAmounts();
            }
        });

        // Event listener to update the net amount when fields change
        document.querySelector('#ledgerFieldsContainer').addEventListener('input', function (e) {
            if (e.target && (e.target.classList.contains('amount-field') || e.target.classList.contains('pay-deduct-field'))) {
                calculateAmounts(); // Recalculate when an amount or pay/deduct field changes
            }
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
