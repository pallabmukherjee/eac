@extends('layouts.main')

@section('title', 'Edit Receipt Voucher')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/datepicker-bs5.min.css') }}">
@endsection

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4>Edit Receipt Voucher</h4>
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h4>Bill No: {{ $receiptVoucher->bill_no }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form id="myForm" method="POST" action="{{ $url }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="mb-3 col-lg-3">
                    <label class="form-label">Date:</label>
                    <input type="text" id="date" name="date" class="form-control" value="{{ old('date', $receiptVoucher->date) }}" required>
                </div>
            </div>

            <div class="section bg-light rounded-3 p-3 mb-3">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Ledger Head</th>
                                    <th>Cash</th>
                                    <th>Cheque Amount</th>
                                    <th>Online Transfer</th>
                                    <th>Online Amount</th>
                                    <th>Remarks</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="ledger-rows">
                                @foreach($rvLedgerHeads as $index => $row)
                                <tr>
                                    <td width="250px">
                                        <select name="head[]" class="form-control" required>
                                            <option value="">Select</option>
                                            @foreach($selectHead as $item)
                                                <option value="{{ $item->ledgers_head_code }}" {{ $item->ledgers_head_code == $row->ledger_head ? 'selected' : '' }}>
                                                    {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="150px">
                                        <input type="number" name="cash_amount[]" class="form-control cash-amount" step="0.01" value="{{ $row->cash_amount }}">
                                    </td>
                                    <td>
                                        <input type="number" name="cheque_total_amount[]" class="form-control cheque-amount" step="0.01" value="{{ $row->cheque_amount }}">
                                    </td>
                                    <td width="250px">
                                        <select name="online_head[]" class="form-control">
                                            <option value="">Select Bank</option>
                                            @foreach($bank as $item)
                                                <option value="{{ $item->ledgers_head_code }}" {{ $item->ledgers_head_code == $row->online_head ? 'selected' : '' }}>
                                                    {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="online_amount[]" class="form-control online-amount" step="0.01" value="{{ $row->online_amount }}">
                                    </td>
                                    <td width="250px">
                                        <input type="text" name="remarks[]" class="form-control" value="{{ $row->remarks }}">
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="add-row avtar avtar-s btn-link-success btn-pc-default"><i class="ti ti-plus f-20"></i></a>
                                        <a href="#" class="remove-row avtar avtar-s btn-link-danger btn-pc-default"><i class="ti ti-trash f-20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <h5 id="totalAmount"></h5>

            <div class="row mb-3 mt-3">
                <div class="col-sm-12">
                    <input type="checkbox" id="cheque-checkbox" name="cheque_checkbox" {{ $rvChequeList->isNotEmpty() ? 'checked' : '' }}>
                    <label for="cheque-checkbox">Cheque</label>
                </div>
            </div>

            <div class="section bg-light rounded-3 p-3 mb-3" id="section-to-toggle" style="{{ $rvChequeList->isNotEmpty() ? '' : 'display:none;' }}">
                <div class="row">
                    <div class="col-12 mb-4">
                        <h5>Cheque</h5>
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Depositor Name</th>
                                    <th>Bank Name</th>
                                    <th>Amount</th>
                                    <th>Cheque No</th>
                                    <th>Cheque Submit Bank</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="cheque-rows">
                                @foreach($rvChequeList as $cheque)
                                <tr>
                                    <td><input type="text" class="form-control" name="depositor_name[][name]" value="{{ $cheque->depositor_name }}"></td>
                                    <td><input type="text" class="form-control" name="bank[][head_code]" value="{{ $cheque->bank_name }}"></td>
                                    <td><input type="number" class="form-control" name="cheque_amount[][amount]" step="0.01" value="{{ $cheque->amount }}"></td>
                                    <td><input type="text" class="form-control" name="cheque_no[][cheque_number]" value="{{ $cheque->cheque_no }}"></td>
                                    <td>
                                        <select name="cheque_bank[][cheque_bank]" class="form-control">
                                            <option value="">Select Bank</option>
                                            @foreach($bank as $item)
                                                <option value="{{ $item->ledgers_head_code }}" {{ $item->ledgers_head_code == $cheque->cheque_submit_bank ? 'selected' : '' }}>
                                                    {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="remove-row avtar avtar-s btn-link-danger btn-pc-default"><i class="ti ti-trash f-20"></i></a>
                                        <a href="#" class="add-row avtar avtar-s btn-link-success btn-pc-default"><i class="ti ti-plus f-20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row" id="row-to-toggle" style="{{ $rvChequeList->isNotEmpty() ? '' : 'display:none;' }}">
                <div class="col-sm-12">
                    <p id="totals">Ledger cheque amount = 0<br>Cheque amount = 0<br>Status: <span id="amount-status">Not Matched</span></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                <span class="btn-text">Update</span>
            </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/choices.min.js') }}"></script>

    <script>
        var resetSimple = new Choices(document.getElementById('head'));
        var resetSimple = new Choices(document.getElementById('bank'));
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
        $(document).ready(function() {
            // Function to update totals
            const updateTotals = () => {
                let totalCash = 0;
                let totalCheque = 0;
                let totalOnline = 0;

                // Get all cash amount inputs
                $('.cash-amount').each(function() {
                    totalCash += parseFloat($(this).val()) || 0;
                });

                // Get all cheque amount inputs
                $('.cheque-amount').each(function() {
                    totalCheque += parseFloat($(this).val()) || 0;
                });

                // Get all online amount inputs
                $('.online-amount').each(function() {
                    totalOnline += parseFloat($(this).val()) || 0;
                });

                // Update the totals display
                $('#totalAmount').text(`Total Collection = ${totalCash + totalCheque + totalOnline} | Total Cash = ${totalCash} | Total Cheque Amount = ${totalCheque} | Total Online Amount = ${totalOnline}`);
            };

            // Add new row
            $('tbody.ledger-rows').on('click', '.add-row', function(e) {
                e.preventDefault();

                // Clone the first row and append it to the table
                var newRow = $('tbody.ledger-rows tr:first').clone();
                newRow.find('input').val(''); // Reset input values
                $('tbody.ledger-rows').append(newRow);

                // Attach input event listeners to the new row's inputs
                newRow.find('.cash-amount, .cheque-amount, .online-amount').on('input', updateTotals);

                // Update totals after adding a new row
                updateTotals();
            });

            // Remove row
            $(document).on('click', '.remove-row', function(e) {
                e.preventDefault();

                // Get the number of rows in the table
                var rowsCount = $('tbody.ledger-rows tr').length;

                // Allow removal only if there is more than 1 row
                if (rowsCount > 1) {
                    $(this).closest('tr').remove();
                    updateTotals(); // Update totals after removing a row
                }
            });

            // Attach input event listeners to existing inputs
            $('.cash-amount, .cheque-amount, .online-amount').on('input', updateTotals);

            // Initial calculation on page load
            updateTotals();
        });
    </script>

    <script>
        $(document).ready(function() {
            // Add new row
            $('tbody.cheque-rows').on('click', '.add-row', function(e) {
                e.preventDefault();

                // Clone the first row and append it to the table
                var newRow = $('tbody.cheque-rows tr:first').clone();
                newRow.find('input').val(''); // Reset input values
                $('tbody.cheque-rows').append(newRow);
            });

            // Remove row
            $(document).on('click', '.remove-row', function(e) {
                e.preventDefault();

                // Get the number of rows in the table
                var rowsCount = $('tbody.cheque-rows tr').length;

                // Allow removal only if there is more than 1 row
                if (rowsCount > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to calculate sums and compare amounts
            function calculateSums() {
                let totalChequeAmount = 0;
                let totalChequeTotalAmount = 0;

                // Iterate through each row and calculate the sums
                $('tbody tr').each(function() {
                    let chequeAmount = parseFloat($(this).find('input[name="cheque_amount[][amount]"]').val()) || 0;
                    let chequeTotalAmount = parseFloat($(this).find('input[name="cheque_total_amount[]"]').val()) || 0;

                    totalChequeAmount += chequeAmount;
                    totalChequeTotalAmount += chequeTotalAmount;
                });

                // Update the sums in the <p> tag
                $('#totals').html(`
                    Ledger cheque amount = ${totalChequeTotalAmount}<br>
                    cheque amount = ${totalChequeAmount}<br>
                    Status: <span id="amount-status">${totalChequeAmount === totalChequeTotalAmount ? 'Matched' : 'Not Matched'}</span>
                `);

                // Update the status color based on the comparison
                if (totalChequeAmount === totalChequeTotalAmount) {
                    $('#amount-status').css('color', 'green');
                    $('button[type="submit"]').prop('disabled', false);
                } else {
                    $('#amount-status').css('color', 'red');
                    $('button[type="submit"]').prop('disabled', true);
                }
            }

            // Trigger the calculation when values change in either field
            $(document).on('input', 'input[name="cheque_amount[][amount]"], input[name="cheque_total_amount[]"]', function() {
                calculateSums();
            });

            // Initial calculation when the page loads
            calculateSums();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#cheque-checkbox').change(function() {
                // Check if the checkbox is checked or unchecked
                if($(this).is(':checked')) {
                    // Show both the section and the row when checkbox is checked
                    $('#section-to-toggle').show();
                    $('#row-to-toggle').show();
                } else {
                    // Hide both the section and the row when checkbox is unchecked
                    $('#section-to-toggle').hide();
                    $('#row-to-toggle').hide();
                }
            });
        });
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
