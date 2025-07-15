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
                            <h4>Create Gratuity Bill</h4>
                        </div>
                    </div>
                </div>
                @if ($gratuityRequest->count())
                <div class="card-body">
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        @foreach($gratuityRequest as $item)
                        <div class="row bg-light rounded-3 p-2 mb-3">
                            <div class="col-sm-4">
                                <h6>Gratuity Remaining Amount: {{ $item->empName->ppo_amount }}</h6>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge bg-primary">Requested Amount: {{ $item->amount }}</span>

                                @if ($item->status == 2)
                                    <span class="badge bg-primary">Approved</span>
                                @elseif ($item->status == 3)
                                    <span class="badge bg-warning">Partial Approved</span>
                                @endif

                                @if ($item->empName->loan_status == 1)
                                    @php
                                        $loanAmountSum = \App\Models\Loan::where('emp_code', $item->empName->id)->sum('loan_amount');
                                    @endphp
                                    <h5 class="text-primary">Total Remaining Loan Amount: {{ $loanAmountSum }}</h5>
                                @elseif ($item->empName->loan_status == 2)
                                    <span class="badge bg-primary">No Loan Available</span>
                                @endif
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" value="{{ $item->empName->name }}" disabled>
                            </div>

                            <!-- Gratuity Amount -->
                            <div class="col-sm-2">
                                <input type="number" name="amount[]" class="form-control" placeholder="Enter Gratuity amount to pay" value="">
                                <!-- Hidden input for emp_id -->
                                <input type="hidden" name="emp_id[]" value="{{ $item->empName->id }}">
                            </div>

                            <!-- Voucher Number -->
                            <div class="col-sm-2">
                                <input type="text" name="voucher_number[]" class="form-control" placeholder="Voucher number" value="">
                            </div>

                            <!-- Voucher Date -->
                            <div class="col-sm-2">
                                <input type="date" name="voucher_date[]" class="form-control" placeholder="Voucher Date" value="">
                            </div>

                            <!-- ID Number -->
                            <div class="col-sm-2">
                                <input type="text" name="id_no[]" class="form-control" placeholder="ID No" value="">
                            </div>

                            <!-- Reference Number -->
                            <div class="col-sm-2">
                                <input type="text" name="reference[]" class="form-control" placeholder="Reference No" value="">
                            </div>

                            @if ($item->empName->loan_status == 1)
                                @php
                                    $loan = \App\Models\Loan::where('emp_code', $item->empName->id)->orderBy('created_at', 'desc')->get();
                                @endphp

                                @if ($loan->count())
                                    <div class="loan-row">
                                        <div class="row">
                                            <div class="col-sm-4 mt-2">
                                                <select name="loan_bank[{{ $item->empName->id }}][]" class="form-control">
                                                    <option value="">Select Loan Bank</option>
                                                    @foreach($loan as $loanItem)
                                                        <option value="{{ $loanItem->id }}">
                                                            {{ $loanItem->bank_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3 mt-2">
                                                <input type="number" name="loan_amount_to_pay[{{ $item->empName->id }}][]" class="form-control" placeholder="Loan amount to pay" value="">
                                            </div>
                                            <div class="col-sm-2">
                                                <!-- Add New Row Icon -->
                                                <a href="#" class="add-row avtar avtar-s btn-link-success btn-pc-default">
                                                    <i class="ti ti-plus f-20"></i>
                                                </a>
                                                <!-- Remove Row Icon -->
                                                <a href="#" class="remove-row avtar avtar-s btn-link-danger btn-pc-default">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
                @else
                <div class="card-body text-center">
                    <h5>No Data Available</h5>
                    <a class="btn btn-primary" href="{{ route('superadmin.gratuity.request.pending') }}">Approved Gratuity Application</a>
                </div>
                @endif
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new loan row functionality (delegated)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-row')) {
                e.preventDefault();
                const loanRow = e.target.closest('.loan-row');
                const newRow = loanRow.cloneNode(true); // deep clone

                // Reset input values for the new row
                newRow.querySelectorAll('input').forEach(input => {
                    input.value = ''; // clear the input values
                });

                // Append the new row to the same container
                loanRow.parentNode.appendChild(newRow);
            }
        });

        // Remove loan row functionality (delegated)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.preventDefault();
                const rowToRemove = e.target.closest('.loan-row');
                rowToRemove.remove();
            }
        });
    });
</script>
@endsection
