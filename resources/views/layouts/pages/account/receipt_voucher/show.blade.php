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
                                            <h3 class="mb-0">{{ $website->organization ?? "Please update your organization name from website setting" }}</h3>
                                            <h4 class="mb-0">Form No 97 [ Vide Rules 17 & 249 ]</h4>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">Receipt Voucher</h4>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">No.: {{ $receiptVoucher->bill_no }}</h5>
                                            <h5 class="mb-0">Date: {{ \Carbon\Carbon::parse($receiptVoucher->date)->format('d/m/Y') }}</h5>
                                        </div>
                                    </div>
                                    <h6 class="mb-0">Received From: Chairman, Krishnagar Municipality. [00001] R.N. Thakur Road, P.O. Krishnagar, Dist: Nadia, Pin: 741101</h6>
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
                                            <th>Amount</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ledgerItems as $item)
                                        @php
                                            $ledger_head = \App\Models\DetailedHead::where('ledgers_head_code', $item->ledger_head)->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="white-space: normal; word-wrap: break-word;">{{ $item->ledger_head }} - {{ $ledger_head->name }}</td>
                                            <td>{{ $item->cash_amount + $item->online_amount + $item->cheque_amount }}</td>
                                            <td style="white-space: normal; word-wrap: break-word;">{{ $item->remarks }}</td>

                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td style="text-align:right;"><h5>Total Rs.:</h5></td>
                                            <th><h5>{{$ledgerItemTotalAmount}}</h5></th>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-start">
                                <hr class="mb-2 mt-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-print-none align-items-center justify-content-end">
                <div class="col-auto btn-page">
                    <form action="{{ route('superadmin.account.receiptvoucher.generate_pdf', $receiptVoucher->voucher_id) }}" method="get" target="_blank">
                        <button type="submit" class="btn btn-primary btn-print-invoice">PDF Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
