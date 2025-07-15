@extends('layouts.main')

@section('title', 'Multi Form Layouts')

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
                            <h4>Add Loan</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('superadmin.gratuity.loan.update', $empCode) }}">
                        @csrf
                        <input type="hidden" name="emp_code" value="{{ $empCode }}">
                        <div>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Bank Name <span class="text-danger">*</span></th>
                                            <th>Loan Amount <span class="text-danger">*</span></th>
                                            <th>Loan Details <span class="text-danger">*</span></th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loan-rows">
                                        @foreach($loanRecords as $index => $loan)
                                        <tr class="loan-row">
                                            <td>
                                                <input type="text" name="bank_name[]" class="form-control" value="{{ old('bank_name.' . $index, $loan->bank_name) }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="loan_amount[]" class="form-control" value="{{ old('loan_amount.' . $index, $loan->loan_amount) }}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="loan_details[]" class="form-control" value="{{ old('loan_details.' . $index, $loan->loan_details) }}" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-success add-row">Add</button>
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
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
    // Event delegation for add and remove button functionality
    document.getElementById('loan-rows').addEventListener('click', function(e) {
        if (e.target.classList.contains('add-row')) {
            const loanRows = document.getElementById('loan-rows');
            const newRow = document.querySelector('.loan-row').cloneNode(true);

            // Clear input fields in the new row
            newRow.querySelectorAll('input').forEach(input => input.value = '');

            loanRows.appendChild(newRow);
        }

        if (e.target.classList.contains('remove-row')) {
            // Prevent removing the last row
            const rows = document.querySelectorAll('.loan-row');
            if (rows.length > 1) {
                e.target.closest('.loan-row').remove();
            }
        }
    });
</script>
<script>
    var total, pageTotal;
    var table = $('#loan-list').DataTable({
        "order": [[0, 'asc']]
    });
</script>
@endsection
