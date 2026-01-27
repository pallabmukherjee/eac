@extends('layouts.main')

@section('title', 'Trial Balance')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/dataTables.bootstrap5.min.css') }}">
<style>
    .update-opening:focus {
        border-color: #51459d;
        box-shadow: 0 0 0 0.2rem rgba(81, 69, 157, 0.25);
    }
    #notification {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .wrap-text {
        white-space: normal !important;
        word-wrap: break-word;
    }
    /* Ensure table header stays fixed when scrolling vertically */
    .dataTables_scrollHeadInner {
        width: 100% !important;
    }
    .dataTables_scrollHeadInner table {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    /* Apply padding only to the visible header to prevent blank row in scroll body */
    #ledgerhead-table_wrapper .dataTables_scrollHead thead th {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    /* Hide the repetitive header row in the scroll body */
    .dataTables_scrollBody thead th {
        padding: 0 !important;
        height: 0 !important;
        line-height: 0 !important;
        border: none !important;
    }
    .dataTables_scrollBody thead tr {
        height: 0 !important;
    }
    /* Beautify DataTables Controls */
    .dataTables_wrapper .row:first-child {
        margin-bottom: 2.5rem;
        align-items: center;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 0 !important;
    }
    /* Add specific space for the scroll container */
    .dataTables_scroll {
        margin-top: 1rem;
    }
    .dataTables_wrapper .dataTables_length select {
        width: auto;
        display: inline-block;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid #dbdee1;
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dbdee1;
        padding: 0.45rem 0.75rem;
        margin-left: 0.5rem;
        background-color: #f8f9fa;
        width: 250px;
    }
    .dataTables_wrapper .dataTables_filter input:focus,
    .dataTables_wrapper .dataTables_length select:focus {
        outline: none;
        border-color: #51459d;
        box-shadow: 0 0 0 0.15rem rgba(81, 69, 157, 0.1);
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1.5rem;
    }
</style>
@endsection

@section('content')
<div id="notification" style="display:none; padding: 15px; margin-top: 20px; border-radius: 8px; width: 320px; position: fixed; right: 20px; color: #fff; top: 20px; z-index: 9999; font-weight: 500;"></div>

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Trial Balance</h4>
                <div>
                    <a href="{{ route('superadmin.account.ledgerhead.export-excel') }}" class="btn btn-success btn-sm me-2">
                        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                    </a>
                    <a href="{{ route('superadmin.account.ledgerhead.export-pdf') }}" class="btn btn-danger btn-sm">
                        <i class="ti ti-file-type-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="dt-responsive">
                    <table id="ledgerhead-table" class="table table-hover table-borderless align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 35%;">Head of Account</th>
                                <th class="text-end">Opening Debit</th>
                                <th class="text-end">Opening Credit</th>
                                <th class="text-end">Debit Amount</th>
                                <th class="text-end">Credit Amount</th>
                                <th class="text-end">Closing Debit</th>
                                <th class="text-end">Closing Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detailedHeads as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="avtar avtar-s bg-light-primary">
                                                <i class="ti ti-book f-18"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1 wrap-text text-primary" style="font-size: 1rem; line-height: 1.4;">{{ $item->name }}</h6>
                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                <span class="badge bg-light-secondary text-secondary rounded-pill f-12 border border-secondary border-opacity-25">
                                                    LH: {{ $item->ledgers_head_code ?? 'N/A' }}
                                                </span>
                                                <span class="text-muted f-12">
                                                    DH Code: {{ $item->code }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light-info text-info f-14 px-3">Rs. {{ number_format($item->opening_debit, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light-secondary text-secondary f-14 px-3">Rs. {{ number_format($item->opening_credit, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light-danger text-danger f-14 px-3">Rs. {{ number_format($item->debit_amount, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light-warning text-warning f-14 px-3">Rs. {{ number_format($item->credit_amount, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light-primary text-primary f-14 px-3">Rs. {{ number_format($item->closing_debit, 2) }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-light-success text-success f-14 px-3">Rs. {{ number_format($item->closing_credit, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#ledgerhead-table').DataTable({
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 50,
            "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "All"]],
            "scrollY": "60vh",
            "scrollX": true,
            "scrollCollapse": true,
            "order": [[0, 'asc']],
            "columnDefs": [
                { "orderable": false, "targets": [1, 2] },
                { "width": "35%", "targets": 0 }
            ],
            "language": {
                "emptyTable": "No data available in table"
            }
        });

        $(document).on('change', '.update-opening', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var value = $(this).val();
            var $input = $(this);

            $.ajax({
                url: '{{ route('superadmin.account.ledgerhead.update') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    [type]: value
                },
                success: function(response) {
                    if (response.success) {
                        $('#notification').text(response.message).css({'background-color': '#28a745'}).fadeIn();
                        $input.addClass('is-valid').removeClass('is-invalid');
                    } else {
                        $('#notification').text(response.message).css({'background-color': '#dc3545'}).fadeIn();
                        $input.addClass('is-invalid').removeClass('is-valid');
                    }
                    setTimeout(function() {
                        $('#notification').fadeOut();
                        $input.removeClass('is-valid is-invalid');
                    }, 3000);
                },
                error: function() {
                    $('#notification').text('An error occurred while updating.').css({'background-color': '#dc3545'}).fadeIn();
                    setTimeout(function() {
                        $('#notification').fadeOut();
                    }, 3000);
                }
            });
        });
    });
</script>
@endsection