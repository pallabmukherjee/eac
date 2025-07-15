@extends('layouts.main')

@section('title', 'Multi Form Layouts')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/tabulator.min.css') }}" >
@endsection

@section('content')
<div id="notification" style="display:none; padding: 10px; margin-top: 10px; border-radius: 5px;width: 300px;position: fixed;right: 0;color: #fff;top: 0;z-index: 9998;"></div>
<!-- [ Main Content ] start -->
<div class="row">
  <!-- [ form-element ] start -->
  <div class="col-lg-12">

    @if(Route::currentRouteName() != 'superadmin.account.detailedhead.edit')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-sm-8">
                    <h4>Trial Balance</h4>
                </div>
                <div class="col-sm-4">
                    <input type="text" id="searchBox" class="form-control" placeholder="Search...">
                </div>
              </div>
          </div>
        <div class="card-body">
            <div id="ledgerhead"></div>
        </div>
    </div>
    @endif

  </div>
  <!-- [ form-element ] end -->
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/js/tabulator.min.js') }}"></script>
<script type="text/javascript">
    // Define your data (usually, you'll fetch this from the server)
    var tableData = @json($detailedHeads); // Convert the Laravel data to a JavaScript array

    // Initialize the Tabulator
    var table = new Tabulator("#ledgerhead", {
        data: tableData, // The data to display
        layout: "fitColumns", // Automatically adjust the columns to fit the table width
        responsiveLayout: "collapse", // Collapse columns on smaller screens
        pagination: "local",
        paginationSize: 100,
        columns: [
            {title: "Ledger<br>Head", field: "ledgers_head_code", formatter: function(cell) {
                return cell.getValue() ?? 'N/A';
            }},
            {title: "Detailed<br>Head", field: "name", minWidth: 350, formatter: function(cell) {
                return cell.getValue() ?? 'N/A';
            }},
            {
                title: "Opening<br>Debit",
                field: "opening_debit",
                editor: true, // Enable cell editing
                validator:["numeric"],
                editorParams: {
                    type: 'input', // Define the editor as an input box
                    min: 0, // Minimum value allowed
                    step: 0.01 // Step for decimal values
                },
                cellEdited: function(cell) {
                    var rowData = cell.getRow().getData();
                    var id = rowData.id;
                    var newOpeningDebit = cell.getValue();

                    // Send updated value to the server via AJAX
                    $.ajax({
                        url: '{{ route('superadmin.account.ledgerhead.update') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            opening_debit: newOpeningDebit,
                            opening_credit: rowData.opening_credit // Send the credit as well if needed
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#notification').text(response.message).css('background-color', 'green').fadeIn();
                            } else {
                                $('#notification').text(response.message).css('background-color', 'red').fadeIn();
                            }
                            setTimeout(function() {
                                $('#notification').fadeOut();
                            }, 5000);
                        },
                        error: function() {
                            toastr.error('An error occurred while updating the data.');
                        }
                    });
                }
            },
            {
                title: "Opening<br>Credit",
                field: "opening_credit",
                editor: true, // Enable cell editing
                validator:["numeric"],
                editorParams: {
                    type: 'input', // Define the editor as an input box
                    min: 0,
                    step: 0.01
                },
                cellEdited: function(cell) {
                    var rowData = cell.getRow().getData();
                    var id = rowData.id;
                    var newOpeningCredit = cell.getValue();

                    // Send updated value to the server via AJAX
                    $.ajax({
                        url: '{{ route('superadmin.account.ledgerhead.update') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            opening_debit: rowData.opening_debit, // Send the debit as well
                            opening_credit: newOpeningCredit
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#notification').text(response.message).css('background-color', 'green').fadeIn();
                            } else {
                                $('#notification').text(response.message).css('background-color', 'red').fadeIn();
                            }
                            setTimeout(function() {
                                $('#notification').fadeOut();
                            }, 5000);
                        },
                        error: function() {
                            toastr.error('An error occurred while updating the data.');
                        }
                    });
                }
            },
            {title: "Debit<br>Amount", field: "debit_amount", formatter: function(cell) {
                return cell.getValue() ?? 'N/A';
            }},
            {title: "Credit<br>Amount", field: "credit_amount", formatter: function(cell) {
                return cell.getValue() ?? 'N/A';
            }},
            {title: "Closing<br>Debit", field: "closing_debit", formatter: function(cell) {
                return cell.getValue() ?? 'N/A';
            }},
            {title: "Closing<br>Credit", field: "closing_credit", formatter: function(cell) {
                return cell.getValue() ?? 'N/A';
            }},
        ],
    });

    // Add event listener for the search box
    document.getElementById("searchBox").addEventListener("input", function() {
        var query = this.value;
        // Set the global filter (it will search all columns)
        table.setFilter(function(data) {
            for (var key in data) {
                if (data[key] && data[key].toString().toLowerCase().includes(query.toLowerCase())) {
                    return true; // If a match is found, return true
                }
            }
            return false; // No match found, return false
        });
    });
</script>

@endsection
