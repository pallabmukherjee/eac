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
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4>Add User</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.user.create') }}">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-sm-4">
                                <label class="form-label" for="name">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" value="" required>
                            </div>
                            <div class="mb-3 col-sm-4">
                                <label class="form-label" for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" value="" required>
                            </div>
                            <div class="mb-3 col-sm-4">
                                <label class="form-label" for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" value="" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>User List</h4>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Srl</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->show_password }}</td>
                                    <td>
                                        <button
                                            class="btn btn-{{ $item->status == 'Blocked' ? 'danger' : 'success' }}"
                                            id="block-unblock-{{ $item->id }}"
                                            data-id="{{ $item->id }}"
                                            data-status="{{ $item->status }}">
                                            {{ $item->status == 'Blocked' ? 'Unblock' : 'Block' }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Srl</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Action</th>
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
            "order": [[0, 'asc']] // Sort the first column by descending order
        });
    </script>

    <script>
        $(document).ready(function() {
        // Block / Unblock button click
        $('button[id^="block-unblock"]').on('click', function() {
            var userId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = currentStatus === 'Blocked' ? 'Active' : 'Blocked';

            var button = $(this);
            button.prop('disabled', true);
            button.text('Updating...');

            // Send AJAX request to update user status
            $.ajax({
                url: '{{ route("superadmin.user.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',  // CSRF token for security
                    user_id: userId,
                    status: newStatus
                },
                success: function(response) {
                    // If the update is successful, update the button and status
                    if (response.success) {
                        button.text(newStatus === 'Blocked' ? 'Unblock' : 'Block');
                        button.removeClass('btn-danger btn-success');
                        button.addClass(newStatus === 'Blocked' ? 'btn-danger' : 'btn-success');
                        button.data('status', newStatus);
                    } else {
                        alert('Failed to update status');
                    }
                },
                error: function() {
                    alert('An error occurred while updating status.');
                },
                complete: function() {
                    button.prop('disabled', false); // Re-enable the button
                }
            });
        });
    });
    </script>
@endsection
