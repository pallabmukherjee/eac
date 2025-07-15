@extends('layouts.main')

@section('title', 'Contra Summary')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/datepicker-bs5.min.css') }}">
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
                            <h4>{{ $title }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $url }}" target="_blank">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="text" id="from_date" name="from_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="text" id="to_date" name="to_date" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="ledger" class="form-label">Select Ledger</label>
                                <select name="ledger" id="ledger" class="form-select">
                                    <option value="" disabled selected>Select Ledger</option>
                                    @foreach($detailedHead as $item)
                                        <option value="{{ $item->ledgers_head_code }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">View PDF</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/datepicker-full.min.js') }}"></script>

<script>
    (function() {
        const dateFields = ['#from_date', '#to_date'];

        dateFields.forEach(function(fieldId) {
            const datePicker = new Datepicker(document.querySelector(fieldId), {
                buttonClass: 'btn',
                format: 'yyyy-mm-dd',
            });
        });
    })();
</script>
@endsection
