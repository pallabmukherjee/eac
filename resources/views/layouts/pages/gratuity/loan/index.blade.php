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
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label class="form-label">Emp Code:</label>
                                <select name="emp_code" class="form-control">
                                    <option value="">Select</option>
                                    @foreach($emp as $item)
                                        <option value="{{ $item->id }}" {{ old('emp_code', $minorHead->id ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->employee_code }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('emp_code')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

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
                                        <tr class="loan-row">
                                            <td><input type="text" name="bank_name[]" class="form-control" placeholder="Enter Bank Name" value="{{ old('bank_name') }}" required></td>
                                            <td><input type="number" name="loan_amount[]" class="form-control" placeholder="Enter Loan Amount" value="{{ old('loan_amount') }}" required></td>
                                            <td><input type="text" name="loan_details[]" class="form-control" placeholder="Enter Loan Details" value="{{ old('loan_details') }}" required></td>
                                            <td><button type="button" class="btn btn-success add-row">Add</button> <button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Default Row -->

                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Loan List</h4>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="loan-list" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp Name</th>
                                    <th>Emp Code</th>
                                    <th>Bank Name</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Details</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->emp->name ?? 'NA' }}</td>
                                    <td>{{ $item->emp->employee_code ?? 'NA' }}</td>
                                    <td>{{ $item->bank_name }}</td>
                                    <td>{{ $item->loan_amount }}</td>
                                    <td>{{ $item->loan_details ?? "N/A" }}</td>
                                    <td>
                                        <a href="{{ route('superadmin.gratuity.loan.edit', $item->emp_code) }}" class="btn btn-sm btn-warning">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('superadmin.gratuity.loan.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this loan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Emp Name</th>
                                    <th>Emp Code</th>
                                    <th>Bank Name</th>
                                    <th>Loan Amount</th>
                                    <th>Loan Details</th>
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
