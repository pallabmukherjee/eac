@extends('layouts.main')

@section('title', 'Journal Voucher')

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
                                            <h4 class="mb-0">Journal Voucher</h4>
                                        </div>
                                        <div>
                                            <h5 class="mb-2">No.: {{ $journalVoucher->bill_no }}</h5>
                                            <h5 class="mb-0">Date: {{ \Carbon\Carbon::parse($journalVoucher->date)->format('d/m/Y') }}</h5>
                                        </div>
                                    </div>
                                    <h6 class="mb-0">[Vide Rules 17A, 22B & 230]</h6>
                                </div>
                                <div class="col-sm-12">
                                    {{-- <table>
                                        <tbody>
                                            <tr>
                                                <td><h6>Paid by:</h6> </td>
                                                <td><h6>{{ $paymentVoucher->schemeFund->ledgers_head_code ?? 'N/A' }} ({{ $paymentVoucher->schemeFund->name ?? 'N/A' }})</h6></td>
                                            </tr>
                                            <tr>
                                                <td><h6>Pay to:</h6></td>
                                                <td><h6>{{ $paymentVoucher->beneficiary->name ?? 'N/A' }}</h6></td>
                                            </tr>
                                            <tr>
                                                <td><h6>Bank:</h6></td>
                                                @php
                                                    $relatedLedger = \App\Models\DetailedHead::where('ledgers_head_code', $paymentVoucher->bank)->first();
                                                @endphp
                                                <td><h6>{{ $paymentVoucher->bank }} ({{ $relatedLedger->name }})</h6></td>
                                            </tr>
                                            <tr>
                                                <td><h6>Bill Ref.:</h6></td>
                                                <td><h6>{{ $paymentVoucher->reference_number ?? 'N/A' }}</h6></td>
                                            </tr>
                                            <tr>
                                                <td><h6>Dated:</h6></td>
                                                <td><h6>{{ $paymentVoucher->reference_date ?? 'N/A' }}</h6></td>
                                            </tr>
                                        </tbody>
                                    </table> --}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Srl</th>
                                            <th>Ledger Head</th>
                                            <th>Credit Amount</th>
                                            <th>Debit Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($journalVouchers as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->ledger_head }}</td>
                                            <td>
                                                @if ($item->crdr == 1)
                                                {{ $item->amount }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->crdr == 2)
                                                {{ $item->amount }}
                                                @endif
                                            </td>

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
                    <form action="{{ route('superadmin.account.journalvoucher.generate_pdf', $journalVoucher->voucher_id) }}" method="get" target="_blank">
                        <button type="submit" class="btn btn-primary btn-print-invoice">PDF Download</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
