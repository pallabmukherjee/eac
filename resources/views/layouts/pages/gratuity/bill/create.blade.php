@extends('layouts.main')

@section('title', 'Gratuity Application')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
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
                            <h4>Gratuity Application</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        <div id="application-rows">
                            <div class="application-row bg-light rounded-3 p-3 mb-4">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                                        <select name="emp_id[]" class="form-control emp-select" required>
                                            <option value="">Search Employee</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Pending Loan</label>
                                        <input type="text" class="form-control pending-loan-amount" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Pending Gratuity</label>
                                        <input type="text" class="form-control pending-gratuity-amount" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Requested Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="amount[]" class="form-control" placeholder="Enter Amount" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end gap-2">
                                        <button type="button" class="btn btn-success add-row"><i class="ti ti-plus"></i></button>
                                        <button type="button" class="btn btn-danger remove-row"><i class="ti ti-trash"></i></button>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Prayer No. <span class="text-danger">*</span></label>
                                        <input type="text" name="prayer_no[]" class="form-control" placeholder="Prayer No" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Prayer Date</label>
                                        <input type="date" name="prayer_date[]" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Remarks</label>
                                        <input type="text" name="remarks[]" class="form-control" placeholder="Remarks">
                                    </div>

                                    <div class="col-12 loan-section d-none">
                                        <hr>
                                        <h6>Loan Repayment</h6>
                                        <div class="loan-rows-container">
                                            <!-- Loan rows will be dynamically added here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Save Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function initSelect2(element) {
        $(element).select2({
            ajax: {
                url: "{{ route('superadmin.gratuity.search.employees') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            placeholder: 'Search for an employee',
            minimumInputLength: 1,
            width: '100%'
        }).on('select2:select', function (e) {
            var data = e.params.data;
            var $row = $(this).closest('.application-row');
            
            $.ajax({
                url: "{{ route('superadmin.gratuity.employee.details', ':id') }}".replace(':id', data.id),
                type: 'GET',
                success: function(response) {
                    $row.find('.pending-loan-amount').val(response.loan_amount);
                    $row.find('.pending-gratuity-amount').val(response.pending_gratuity);
                    
                    // Fetch individual loans if any
                    fetchEmployeeLoans(data.id, $row);
                }
            });
        });
    }

    function fetchEmployeeLoans(empId, $row) {
        $.ajax({
            url: "{{ route('superadmin.gratuity.loan.index') }}", // We might need a specific endpoint for this
            type: 'GET',
            data: { emp_id: empId, ajax: 1 },
            success: function(loans) {
                const $container = $row.find('.loan-rows-container');
                const $section = $row.find('.loan-section');
                $container.empty();
                
                if (loans && loans.length > 0) {
                    $section.removeClass('d-none');
                    let loanHtml = `
                        <div class="row mb-2 loan-payment-row">
                            <div class="col-md-5">
                                <select name="loan_bank[${empId}][]" class="form-control loan-bank-select">
                                    <option value="">Select Loan Bank</option>
                                    ${loans.map(l => `<option value="${l.id}" data-amount="${l.loan_amount}">${l.bank_name} (Bal: ${l.loan_amount})</option>`).join('')}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="loan_amount_to_pay[${empId}][]" class="form-control" placeholder="Amount to pay">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-sm btn-success add-loan-row"><i class="ti ti-plus"></i></button>
                                <button type="button" class="btn btn-sm btn-danger remove-loan-row"><i class="ti ti-trash"></i></button>
                            </div>
                        </div>
                    `;
                    $container.append(loanHtml);
                } else {
                    $section.addClass('d-none');
                }
            }
        });
    }

    $(document).ready(function() {
        initSelect2('.emp-select');

        $(document).on('click', '.add-row', function() {
            const $currentRow = $(this).closest('.application-row');
            const $newRow = $('.application-row:first').clone();
            $newRow.find('input').val('');
            $newRow.find('input[name="prayer_date[]"]').val("{{ date('Y-m-d') }}");
            $newRow.find('.select2-container').remove();
            $newRow.find('.emp-select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').empty().append('<option value="">Search Employee</option>');
            $newRow.find('.loan-section').addClass('d-none');
            $newRow.find('.loan-rows-container').empty();
            $currentRow.after($newRow);
            initSelect2($newRow.find('.emp-select'));
        });

        $(document).on('click', '.remove-row', function() {
            if ($('.application-row').length > 1) {
                $(this).closest('.application-row').remove();
            }
        });

        $(document).on('click', '.add-loan-row', function() {
            const $row = $(this).closest('.loan-payment-row');
            const $newRow = $row.clone();
            $newRow.find('input').val('');
            $row.after($newRow);
        });

        $(document).on('click', '.remove-loan-row', function() {
            if ($(this).closest('.loan-rows-container').find('.loan-payment-row').length > 1) {
                $(this).closest('.loan-payment-row').remove();
            }
        });

        $(document).on('input', 'input[name="amount[]"]', function() {
            const $row = $(this).closest('.application-row');
            const pending = parseFloat($row.find('.pending-gratuity-amount').val()) || 0;
            if (parseFloat($(this).val()) > pending) {
                alert('Amount exceeds pending gratuity!');
                $(this).val(pending);
            }
        });
    });
</script>
@endsection