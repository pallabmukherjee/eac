@extends('layouts.main')

@section('title', 'Add ROPA Year')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ form-element ] start -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Gratuity Request</h4>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gratuityStatus as $item)
                                <tr>
                                    <td>{{ $item->empName->name }}</td>
                                    <td>{{ $item->amount }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="btn-group" role="group">
                                                <label class="me-3"><input type="radio" name="status-{{ $item->id }}" id="Pending-{{ $item->id }}" class="status-btn" data-id="{{ $item->id }}" data-status="1" {{ $item->status == '1' ? 'checked' : '' }}> Pending</label>
                                                <label class="me-3"><input type="radio" name="status-{{ $item->id }}" id="approved-{{ $item->id }}" class="status-btn" data-id="{{ $item->id }}" data-status="2" {{ $item->status == '2' ? 'checked' : '' }}> Approved</label>
                                                <label class="me-3"><input type="radio" name="status-{{ $item->id }}" id="partial-{{ $item->id }}" class="status-btn" data-id="{{ $item->id }}" data-status="3" {{ $item->status == '3' ? 'checked' : '' }}> Partial Approved</label>
                                                <label><input type="radio" name="status-{{ $item->id }}" id="rejected-{{ $item->id }}" class="status-btn" data-id="{{ $item->id }}" data-status="4" {{ $item->status == '4' ? 'checked' : '' }}> Rejected</label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Employee Name</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var total, pageTotal;
        var table = $('#dom-jqry').DataTable({
            "order": [['id']] // Sort the first column by descending order
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-btn').forEach(radio => {
        radio.addEventListener('change', function() {
            const requestId = this.dataset.id;
            const status = this.dataset.status;

            console.log(`Request ID: ${requestId}, Status: ${status}`);  // Debugging line

            // Disable all radios during processing
            document.querySelectorAll(`input[name="status-${requestId}"]`).forEach(r => r.disabled = true);

            // Ensure correct URL and method
            fetch("{{ route('superadmin.gratuity.request.update-status', ['id' => ':id']) }}".replace(':id', requestId), {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Status update response:', data);  // Debugging line

                // Re-enable radios after the request
                document.querySelectorAll(`input[name="status-${requestId}"]`).forEach(r => r.disabled = false);
            })
            .catch(error => {
                console.error('Error:', error);
                document.querySelectorAll(`input[name="status-${requestId}"]`).forEach(r => r.disabled = false);
            });
        });
    });
});
</script>
@endsection
