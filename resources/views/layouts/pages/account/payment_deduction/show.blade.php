@extends('layouts.main')

@section('title', 'Export Bootstrap Table')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/flatpickr.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-12 mb-5">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <h3 class="mb-0">Krishnanagar Municipality</h3>
                                            <h4 class="mb-0">Form 98</h4>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">Pending Deduction Queue</h4>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">No.: {{ $paymentVoucher->bill_no }}</h5>
                                            <h5 class="mb-0">Date: {{ \Carbon\Carbon::parse($paymentVoucher->date)->format('d/m/Y') }}</h5>
                                        </div>
                                    </div>
                                    <h6 class="mb-0">[Vide Rules 17A, 22B & 230]</h6>
                                </div>
                                <div class="col-sm-12">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6>Paid by:</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $paymentVoucher->schemeFund->ledgers_head_code ?? 'N/A' }}
                                                        ({{ $paymentVoucher->schemeFund->name ?? 'N/A' }})</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Pay to:</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $paymentVoucher->beneficiary->name ?? 'N/A' }}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Bank:</h6>
                                                </td>
                                                @php
                                                    $relatedLedger = \App\Models\DetailedHead::where(
                                                        'ledgers_head_code',
                                                        $paymentVoucher->bank,
                                                    )->first();
                                                @endphp
                                                <td>
                                                    <h6>{{ $paymentVoucher->bank }} ({{ $relatedLedger->name }})</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Bill Ref.:</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $paymentVoucher->reference_number ?? 'N/A' }}</h6>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Dated:</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $paymentVoucher->reference_date ?? 'N/A' }}</h6>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('superadmin.account.pendingaction.store', $paymentVoucher->p_voucher_id) }}"
                            method="POST">
                            @csrf
                            <div class="col-12">
                                <div class="row">
                                    <div class="mb-3 col-lg-6">
                                        <label class="form-label">Scheme Fund:</label>
                                        <select name="scheme_fund" class="form-control" id="scheme_fund">
                                            <option value="">Select Scheme Fund</option>
                                            @foreach ($schemefund as $item)
                                                <option value="{{ $item->ledgers_head_code }}"
                                                    {{ old('scheme_fund', $paymentVoucher->scheme_fund) == $item->ledgers_head_code ? 'selected' : '' }}>
                                                    {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-lg-6">
                                        <label class="form-label">Select Bank:</label>
                                        <select name="bank" class="form-control" id="bank">
                                            <option value="">Select Bank</option>
                                            @foreach ($bank as $item)
                                                <option value="{{ $item->ledgers_head_code }}"
                                                    {{ old('bank', $paymentVoucher->bank) == $item->ledgers_head_code ? 'selected' : '' }}>
                                                    {{ $item->ledgers_head_code }} - {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Srl</th>
                                                <th>Account Head</th>
                                                <th>Deduction Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ledgerItem as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->ledger_head }}</td>
                                                    <td>{{ $item->amount }}</td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td style="text-align:right;">
                                                    <h5>Total:</h5>
                                                </td>
                                                <td>
                                                    <h5>{{ $pay }}</h5>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1">
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-8">
                                    <h5 class="mb-0">{{ $totalAmountInWords }}</h5>
                                </div>
                                <div class="col-4">
                                    <div class=" d-flex align-items-center justify-content-end">
                                        <button type="submit" class="btn btn-success">Pay Now</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
