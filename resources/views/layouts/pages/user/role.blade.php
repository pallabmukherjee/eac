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
                    <h4>Role Management</h4>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Srl</th>
                                    <th>Name</th>
                                    <th>Payment</th>
                                    <th>Receipt</th>
                                    <th>Contra</th>
                                    <th>Journal</th>
                                    <th>Leave</th>
                                    <th>Pension</th>
                                    <th>Gratuity</th>
                                    <th>Report</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->user->name }}</td>
        <td>
            <input type="checkbox" class="payment" data-id="{{ $item->id }}" {{ $item->payment ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="receipt" data-id="{{ $item->id }}" {{ $item->receipt ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="contra" data-id="{{ $item->id }}" {{ $item->contra ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="journal" data-id="{{ $item->id }}" {{ $item->journal ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="leave" data-id="{{ $item->id }}" {{ $item->leave ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="pension" data-id="{{ $item->id }}" {{ $item->pension ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="gratuity" data-id="{{ $item->id }}" {{ $item->gratuity ? 'checked' : '' }} />
        </td>
        <td>
            <input type="checkbox" class="report" data-id="{{ $item->id }}" {{ $item->report ? 'checked' : '' }} />
        </td>
    </tr>
@endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Srl</th>
                                    <th>Name</th>
                                    <th>Payment</th>
                                    <th>Receipt</th>
                                    <th>Contra</th>
                                    <th>Journal</th>
                                    <th>Leave</th>
                                    <th>Pension</th>
                                    <th>Gratuity</th>
                                    <th>Report</th>
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
    $(document).ready(function() {
        // Handle change event on each checkbox
        $('input[type="checkbox"]').change(function() {
            var field = $(this).attr('class');  // Get the class (payment, receipt, etc.)
            var userId = $(this).data('id');    // Get the user ID from data-id
            var isChecked = $(this).is(':checked') ? 1 : 0;  // 1 for checked, 0 for unchecked

            // Send the update via AJAX
            $.ajax({
                url: '{{ route("superadmin.role.updateField") }}',  // Ensure this route is correct
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',  // CSRF token for security
                    user_id: userId,
                    field: field,
                    value: isChecked
                },
                success: function(response) {
                    if (response.success) {
                        // Optionally, show a success message or perform other actions
                        console.log('Field updated successfully');
                    } else {
                        // Handle failure (optional)
                        alert('Failed to update the field');
                    }
                },
                error: function(xhr, status, error) {
                    // Log the error details to the console for debugging
                    console.error('AJAX Error:', error);
                    alert('An error occurred while updating the field.');
                }
            });
        });
    });
</script>


@endsection
