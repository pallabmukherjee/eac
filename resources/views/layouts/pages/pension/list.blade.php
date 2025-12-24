@extends('layouts.main')

@section('title', 'Pensioner Data')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/style.css') }}">
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
                    <h4>Pensioner Data</h4>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <a href="{{ route('superadmin.pension.create') }}" class="btn btn-primary me-2">
                        <i class="ti ti-plus me-1"></i> Add New
                    </a>
                    <a href="{{ route('superadmin.pension.exportPdf') }}" class="btn btn-secondary" target="_blank">
                        <i class="ti ti-file-download me-1"></i> Download PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="pensioner-data" class="table table-hover table-borderless align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Pensioner Details</th>
                            <th class="py-3">Type</th>
                            <th class="py-3">Life Cert.</th>
                            <th class="py-3">Retirement</th>
                            <th class="py-3">Alive Status</th>
                            <th class="py-3">5 Years</th>
                            <th class="py-3">80 Years</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pensioners as $item)
                        <tr>
                            <td><span class="text-muted">{{ $item->id }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s bg-light-primary">
                                            <i class="ti ti-user f-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $item->pensioner_name }}</h6>
                                        <small class="text-muted">PPO: {{ $item->ppo_number }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->pension_type == 1 ? 'Self' : 'Family member' }}</td>
                            <td>
                                <div class="form-check form-switch custom-switch-v1">
                                    <input type="checkbox" class="form-check-input input-primary"
                                           id="customswitchv2-{{ $item->id }}"
                                           {{ $item->life_certificate == 1 ? 'checked' : '' }}
                                           data-id="{{ $item->id }}">
                                    <label class="form-check-label" for="customswitchv2-{{ $item->id }}">
                                        {!! $item->life_certificate == 1 ? '<span class="text-primary">Yes</span>' : '<span class="text-danger">No</span>' !!}
                                    </label>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->retirement_date)->format('d M, Y') }}</td>
                            <td>{!! $item->alive_status == 1 ? '<span class="badge bg-light-success text-success">Alive</span>' : '<span class="badge bg-light-danger text-danger">Dead</span>' !!}</td>
                            <td>
                                @php
                                    $fiveYearDate = \Carbon\Carbon::parse($item->five_year_date);
                                @endphp
                                @if($fiveYearDate->isPast())
                                    <span class="text-danger">{{ $fiveYearDate->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-primary">{{ $fiveYearDate->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->dob)
                                    @php
                                        $eightyYearDate = \Carbon\Carbon::parse($item->dob)->addYears(80);
                                    @endphp
                                    @if($eightyYearDate->isPast())
                                        <span class="text-danger">{{ $eightyYearDate->format('d/m/Y') }}</span>
                                    @else
                                        {{ $eightyYearDate->format('d/m/Y') }}
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.pension.edit', $item->id) }}" class="btn btn-icon btn-link-primary avtar-xs" data-bs-toggle="tooltip" title="Edit">
                                    <i class="ti ti-edit f-20"></i>
                                </a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var table = $('#pensioner-data').DataTable({
            "order": [['id', 'asc']],
            "columnDefs": [
                {
                    "targets": 1,
                    "createdCell": function (td) {
                        $(td).css({
                            'white-space': 'normal',
                            'word-break': 'break-word'
                        });
                    }
                }
            ]
        });

        $(document).ready(function() {
            // On change of the switch
            $(document).on('change', '#pensioner-data input[type="checkbox"].form-check-input', function() {
                var pensionerId = $(this).data('id'); // Get pensioner ID
                var lifeCertificateValue = $(this).prop('checked') ? 1 : 2; // Determine the new life_certificate value (1 or 2)

                // Make the AJAX request to update the pensioner's life_certificate
                $.ajax({
                    url: "{{ route('superadmin.pension.updateLifeCertificate') }}",  // Use the named route
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',  // CSRF token for security
                        id: pensionerId,              // The pensioner ID
                        life_certificate: lifeCertificateValue  // New life_certificate value
                    },
                    success: function(response) {
                        // Handle the success response
                        if(response.success) {
                            // Update the label based on the new value
                            var label = lifeCertificateValue == 1 ?
                                '<span class="text-primary">Yes</span>' :
                                '<span class="text-danger">No</span>';
                            $('#customswitchv2-' + pensionerId).next('label').html(label);
                        } else {
                            // If there was an error updating, you can show an error message
                            alert('Failed to update life certificate status');
                            // Optionally, revert the checkbox state
                            $('#customswitchv2-' + pensionerId).prop('checked', !$('#customswitchv2-' + pensionerId).prop('checked'));
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX error
                        console.log(error);
                        alert('Error occurred while updating.');
                    }
                });
            });
        });
    </script>
@endsection