@extends('layouts.main')

@section('title', 'Export Bootstrap Table')


@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/style.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-sm-12">
                          <h4>Payment Vouchers</h4>
                        </div>
                      </div>
                  </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Voucher No.</th>
                                    <th>Date</th>
                                    <th>Payee</th>
                                    <th>Scheme Fund</th>
                                    <th>Payment Mode</th>
                                    <th>Bank</th>
                                    <th>Reference Number</th>
                                    <th>Reference Date</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentVouchers as $item)
                                <tr>
                                    <td>{{ $item->bill_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->beneficiary->name }}</td>
                                    <td>{{ $item->schemeFund->name }}</td>
                                    <td>
                                        @if ($item->payment_mode == 1)
                                        <span class="text-danger">Cash in Hand</span>
                                        @elseif ($item->payment_mode == 2)
                                        <span class="text-primary">Bank</span>
                                        @else
                                            Unknown
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $relatedLedger = \App\Models\DetailedHead::where('ledgers_head_code', $item->bank)->first();
                                        @endphp
                                        {{ $relatedLedger->name }}
                                    </td>
                                    <td>{{ $item->reference_number ?? 'N/A' }}</td>
                                    <td>{{ $item->reference_date ? \Carbon\Carbon::parse($item->reference_date)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('superadmin.account.paymentvoucher.show', $item->p_voucher_id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-eye f-20"></i></a>
                                        @if ($item->edit_status == 1 || $item->created_at->diffInHours(now()) <= 24)
                                            <a href="{{ route('superadmin.account.paymentvoucher.edit', $item->p_voucher_id) }}" class="badge bg-success">Edit NOw</a>
                                        @elseif ($item->edit_status == 2)
                                            <span class="badge bg-danger">Request Rejected</span>
                                        @elseif ($item->edit_status == 3)
                                            Request Sent
                                        @else
                                            <a href="{{ route('superadmin.account.paymentvoucher.edit.request', $item->p_voucher_id) }}" class="">Send Edit Request</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Voucher No.</th>
                                    <th>Date</th>
                                    <th>Payee</th>
                                    <th>Scheme Fund</th>
                                    <th>Payment Mode</th>
                                    <th>Bank</th>
                                    <th>Reference Number</th>
                                    <th>Reference Date</th>
                                    <th>View</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var total, pageTotal;
        var table = $('#dom-jqry').DataTable({
            "order": [['id']],
            "columnDefs": [
                {
                    targets: 5,
                    width: '10%',
                    createdCell: function (td) {
                        $(td).css({
                            'white-space': 'normal',
                            'word-break': 'break-word'
                        });
                    }
                }
            ]
        });
    </script>


@endsection
