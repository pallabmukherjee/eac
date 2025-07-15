@extends('layouts.main')

@section('title', 'Uncleared Cheque')

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
                          <h4>Uncleared Cheque</h4>
                        </div>
                      </div>
                  </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Srl</th>
                                    <th>Vouchar No.</th>
                                    <th>Cheque No.</th>
                                    <th>Bank Head</th>
                                    <th>Date</th>
                                    <th>Ation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingvouchar as $item)
                                @php
                                    $ledger_head = \App\Models\DetailedHead::where('ledgers_head_code', $item->cheque_submit_bank)->first();
                                @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->bill_no }}</td>
                                        <td>{{ $item->cheque_no }}</td>
                                        <td style="white-space: normal; word-wrap: break-word;">{{ $item->cheque_submit_bank }} - {{ $ledger_head->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            {{-- <a href="{{ route('superadmin.account.pendingaction.ucshow', $item->id) }}" class="avtar avtar-xs btn-link-secondary">
                                                <i class="ti ti-eye f-20"></i>
                                            </a> --}}

                                            <form class="status-form" data-id="{{ $item->id }}">
                                                <label><input type="radio" name="status_{{ $item->id }}" value="1" {{ $item->status == 1 ? 'checked' : '' }} class="status-radio" data-status="1"> Pending</label>
                                                <label><input type="radio" name="status_{{ $item->id }}" value="2" {{ $item->status == 2 ? 'checked' : '' }} class="status-radio" data-status="2"> Cleared</label>
                                                <label><input type="radio" name="status_{{ $item->id }}" value="3" {{ $item->status == 3 ? 'checked' : '' }} class="status-radio" data-status="3"> Uncleared</label>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Srl</th>
                                    <th>Vouchar No.</th>
                                    <th>Cheque No.</th>
                                    <th>Bank Head</th>
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


<script>
$(document).ready(function() {
    // Listen for change in any of the radio buttons
    $('.status-radio').on('change', function() {
        var status = $(this).data('status'); // Get the selected status value (1, 2, or 3)
        var voucherId = $(this).closest('form').data('id'); // Get the voucher ID from the form data

        // Perform AJAX request to update the status
        $.ajax({
            url: "{{ route('superadmin.account.pendingaction.updateStatus') }}", // Your update status route
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',  // CSRF token for security
                id: voucherId,  // Voucher ID
                status: status  // Status value (1, 2, or 3)
            },
            success: function(response) {
                if (response.success) {
                    alert('Status updated successfully.');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);  // Log the error message for debugging
                alert('Something went wrong, please try again.');
            }
        });
    });
});

</script>
@endsection
