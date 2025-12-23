@extends('layouts.main')

@section('title', 'Approve Gratuity Application')

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
                            <h4>Approve Gratuity Application: {{ $bill->bill_no }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.gratuity.bill.approve.confirm', $bill->bill_id) }}">
                        @csrf
                        @foreach($gratuityBills as $summary)
                        @php
                            $item = $summary->empDetails;
                        @endphp
                        <div class="row bg-light rounded-3 p-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control" value="{{ $item->name }} ({{ $item->employee_code }})" disabled>
                                <input type="hidden" name="summary_id[]" value="{{ $summary->id }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Gratuity Amount</label>
                                <input type="text" class="form-control" value="{{ $summary->gratuity_amount }}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prayer No.</label>
                                <input type="text" class="form-control" value="{{ $summary->prayer_no }}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prayer Date</label>
                                <input type="text" class="form-control" value="{{ $summary->prayer_date }}" disabled>
                            </div>
                            
                            <div class="col-12 mt-3">
                                <hr>
                                <h6>Approval Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label">Voucher No. <span class="text-danger">*</span></label>
                                        <input type="text" name="voucher_no[]" class="form-control" placeholder="Voucher No" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Voucher Date <span class="text-danger">*</span></label>
                                        <input type="date" name="voucher_date[]" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">ID No. <span class="text-danger">*</span></label>
                                        <input type="text" name="id_no[]" class="form-control" placeholder="ID No" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Reference No. <span class="text-danger">*</span></label>
                                        <input type="text" name="reference_no[]" class="form-control" placeholder="Reference No" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Reference Date <span class="text-danger">*</span></label>
                                        <input type="date" name="reference_date[]" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg px-5">Confirm Approval</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
