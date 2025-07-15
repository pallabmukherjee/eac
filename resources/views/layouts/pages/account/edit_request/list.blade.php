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
                          <h4>Vouchers Edit Request</h4>
                        </div>
                      </div>
                  </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Bill No.</th>
                                    <th>Requested By</th>
                                    <th>Requested Date</th>
                                    <th>Action</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($voucahrRequest as $item)
                                <tr>
                                    <td>{{ $item->bill_no }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($item->edit_status == 0)
                                            <button class="btn btn-success btn-sm approve-btn" data-id="{{ $item->id }}">Approve</button>
                                            <button class="btn btn-danger btn-sm reject-btn" data-id="{{ $item->id }}">Reject</button>
                                        @elseif($item->edit_status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($item->edit_status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $prefix = substr($item->vouchar_id, 0, 2);
                                            switch ($prefix) {
                                                case 'PV':
                                                    $route = route('superadmin.account.paymentvoucher.show', $item->vouchar_id);
                                                    break;
                                                case 'RV':
                                                    $route = route('superadmin.account.receiptvoucher.show', $item->vouchar_id);
                                                    break;
                                                case 'JV':
                                                    $route = route('superadmin.account.journalvoucher.show', $item->vouchar_id);
                                                    break;
                                                case 'CV':
                                                    $route = route('superadmin.account.contravoucher.show', $item->vouchar_id);
                                                    break;
                                                default:
                                                    $route = '#'; // Or a fallback route
                                            }
                                        @endphp
                                        <a href="{{ $route }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-eye f-20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Bill No.</th>
                                    <th>Requested By</th>
                                    <th>Requested Date</th>
                                    <th>Action</th>
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
            "order": [['id']]
        });
    </script>

<script>
    $(document).ready(function () {
        $('.approve-btn, .reject-btn').on('click', function () {
            const button = $(this);
            const id = button.data('id');
            const status = button.hasClass('approve-btn') ? 1 : 2;
            const parentTd = button.closest('td');

            $.ajax({
                url: "{{ route('superadmin.account.edit.update-status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },
                success: function (response) {
                    const badge = status === 1
                        ? '<span class="badge bg-success">Approved</span>'
                        : '<span class="badge bg-danger">Rejected</span>';

                    parentTd.html(badge);
                },
                error: function () {
                    console.error('Status update failed.');
                }
            });
        });
    });
</script>


@endsection
