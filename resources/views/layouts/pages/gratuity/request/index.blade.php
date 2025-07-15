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
                            <h4>Gratuity Application</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Select Employee <span class="text-danger">*</span></th>
                                        <th>Requested Gratuity Amount <span class="text-danger">*</span></th>
                                        <th>Prayer no.</th>
                                        <th>Prayer Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="loan-rows">
                                    <tr class="loan-row">
                                        <td>
                                            <select name="emp_code[]" class="form-control">
                                                <option value="">Select Employee</option>
                                                @foreach($emp as $item)
                                                    <option value="{{ $item->id }}" {{ old('emp_code', $minorHead->id ?? '') == $item->id ? 'selected' : '' }}>
                                                        {{ $item->employee_code }} - {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="requested_amount[]" class="form-control" placeholder="Enter Requested Gratuity Amount" value="{{ old('requested_amount') }}" required></td>
                                        <td><input type="text" name="prayer_no[]" class="form-control" placeholder="Enter Prayer no." value="{{ old('prayer_no') }}"></td>
                                        <td><input type="date" name="prayer_date[]" class="form-control" placeholder="Enter Prayer Date" value="{{ old('prayer_date') }}"></td>
                                        <td>
                                            <button type="button" class="btn btn-success add-row">Add</button>
                                            <button type="button" class="btn btn-danger remove-row">Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
@endsection
