@extends('layouts.main')

@section('title', 'Edit Gratuity Application')

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
                            <h4>Edit Gratuity Application: {{ $report->bill_no }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        @method('PUT')
                        @foreach($gratuityBills as $summary)
                        @php
                            $item = $summary->empDetails;
                            $displayPpoAmount = $item->ppo_amount + $summary->total_amount;
                        @endphp
                        <div class="row bg-light rounded-3 p-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control" value="{{ $item->name }} ({{ $item->employee_code }})" disabled>
                                <input type="hidden" name="emp_id[]" value="{{ $item->id }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Gratuity Amount <span class="text-danger">*</span></label>
                                <input type="number" name="amount[]" class="form-control" value="{{ $summary->gratuity_amount }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prayer No. <span class="text-danger">*</span></label>
                                <input type="text" name="prayer_no[]" class="form-control" value="{{ $summary->prayer_no }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prayer Date</label>
                                <input type="date" name="prayer_date[]" class="form-control" value="{{ $summary->prayer_date }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Remarks</label>
                                <input type="text" name="remarks[]" class="form-control" value="{{ $summary->remarks }}">
                            </div>

                            @if ($item->loan_status == 1)
                                @php
                                    $allLoans = \App\Models\Loan::where('emp_code', $item->id)->get();
                                    $paidLoans = \App\Models\GratuityLoanPay::where('bill_id', $report->bill_id)->where('emp_id', $item->id)->get();
                                @endphp
                                <div class="col-12 mt-3">
                                    <hr>
                                    <h6>Loan Repayment</h6>
                                    <div class="loan-rows-container">
                                        @foreach($paidLoans as $paidLoan)
                                        <div class="row mb-2 loan-payment-row">
                                            <div class="col-md-5">
                                                <select name="loan_bank[{{ $item->id }}][]" class="form-control loan-bank-select">
                                                    @foreach($allLoans as $loanOption)
                                                        @php
                                                            $currentBal = ($loanOption->id == $paidLoan->bank) ? ($loanOption->loan_amount + $paidLoan->amount) : $loanOption->loan_amount;
                                                        @endphp
                                                        <option value="{{ $loanOption->id }}" data-amount="{{ $currentBal }}" {{ $loanOption->id == $paidLoan->bank ? 'selected' : '' }}>
                                                            {{ $loanOption->bank_name }} (Bal: {{ $currentBal }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" name="loan_amount_to_pay[{{ $item->id }}][]" class="form-control" value="{{ $paidLoan->amount }}">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-sm btn-success add-loan-row"><i class="ti ti-plus"></i></button>
                                                <button type="button" class="btn btn-sm btn-danger remove-loan-row"><i class="ti ti-trash"></i></button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Update Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endsection