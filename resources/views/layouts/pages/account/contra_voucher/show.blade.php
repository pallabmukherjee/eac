@extends('layouts.main')

@section('title', 'Export Bootstrap Table')

@section('css')
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
                                            <h4 class="mb-0">Contra Voucher</h4>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">No.: {{ $contraVoucher->bill_no }}</h5>
                                            <h5 class="mb-0">Date: {{ \Carbon\Carbon::parse($contraVoucher->date)->format('d/m/Y') }}</h5>
                                        </div>
                                    </div>
                                    <h6 class="mb-0">[Vide Rules 17A, 22B & 230]</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Srl</th>
                                            <th>From Head</th>
                                            <th>From Bank</th>
                                            <th>To Head</th>
                                            <th>To Bank</th>
                                            <th>Cash Amount</th>
                                            <th>Cheque Amount</th>
                                            <th>Cheque Date</th>
                                            <th>Cheque No.</th>
                                            <th>Online Amount</th>
                                            <th>Online Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($contraVouchers as $item)
                                        @php
                                            $from_head = \App\Models\DetailedHead::where('ledgers_head_code', $item->from_head)->first();
                                            $from_bank = \App\Models\DetailedHead::where('ledgers_head_code', $item->from_bank)->first();
                                            $to_head = \App\Models\DetailedHead::where('ledgers_head_code', $item->to_head)->first();
                                            $to_bank = \App\Models\DetailedHead::where('ledgers_head_code', $item->to_bank)->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="white-space: normal; word-wrap: break-word;">{{ $item->from_head }} - {{ $from_head->name }}</td>
                                            <td style="white-space: normal; word-wrap: break-word;">{{ $item->from_bank }} - {{ $from_bank->name }}</td>
                                            <td style="white-space: normal; word-wrap: break-word;">{{ $item->to_head }} - {{ $to_head->name }}</td>
                                            <td style="white-space: normal; word-wrap: break-word;">{{ $item->to_bank }} - {{ $to_bank->name }}</td>
                                            <td>{{ $item->cash_amount }}</td>
                                            <td>{{ $item->cheque_amount }}</td>
                                            <td>{{ $item->cheque_date }}</td>
                                            <td>{{ $item->cheque_no }}</td>
                                            <td>{{ $item->online_amount }}</td>
                                            <td>{{ $item->online_remarks }}</td>

                                        </tr>
                                        @endforeach
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
                    <form action="{{ route('superadmin.account.contravoucher.generate_pdf', $contraVoucher->voucher_id) }}" method="get" target="_blank">
                        <button type="submit" class="btn btn-primary btn-print-invoice">PDF Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
