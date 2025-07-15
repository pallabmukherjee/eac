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
                          <h4>Contra Voucher Report</h4>
                        </div>
                      </div>
                  </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Srl</th>
                                    <th>Voucher No.</th>
                                    <th>Date</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mergedVouchers as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->bill_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('superadmin.account.contravoucher.show', $item->voucher_id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-eye f-20"></i></a>

                                        @if ($item->edit_status == 1 || $item->created_at->diffInHours(now()) <= 24)
                                            <a href="{{ route('superadmin.account.contravoucher.edit', $item->voucher_id) }}" class="badge bg-success">Edit NOw</a>
                                        @elseif ($item->edit_status == 2)
                                            <span class="badge bg-danger">Request Rejected</span>
                                        @elseif ($item->edit_status == 3)
                                            Request Sent
                                        @else
                                            <a href="{{ route('superadmin.account.contravoucher.edit.request', $item->voucher_id) }}" class="">Send Edit Request</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Srl</th>
                                    <th>Voucher No.</th>
                                    <th>Date</th>
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
            "order": [[0, 'asc']]
        });
    </script>
@endsection
