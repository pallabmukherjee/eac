@extends('layouts.main')

@section('title', 'Payment Deduction queue')

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
                          <h4>Payment Deduction queue</h4>
                        </div>
                      </div>
                  </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Srl</th>
                                    <th>Vouchar ID</th>
                                    <th>Date</th>
                                    <th>Ation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingvouchar as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->bill_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                    <td><a href="{{ route('superadmin.account.pendingaction.show', $item->voucher_id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-eye f-20"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Srl</th>
                                    <th>Vouchar ID</th>
                                    <th>Date</th>
                                    <th>Ation</th>
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
        var table = $('#dom-jqry').DataTable();
    </script>
@endsection
