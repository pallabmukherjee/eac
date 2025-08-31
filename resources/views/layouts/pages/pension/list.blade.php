@extends('layouts.main')

@section('title', 'Pensioner Data')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/style.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Pensioner Data</h4>
                        <a href="{{ route('superadmin.pension.export') }}" class="btn btn-primary">Download Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="pensioner-data" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>PPO Code</th>
                                    <th>Pensioner Name</th>
                                    <th>Type Of Pension</th>
                                    <th>Life Certificate</th>
                                    <th>Date of Retirement</th>
                                    <th>Alive Status</th>
                                    <th>5 Years Completed</th>
                                    <th>80 Years Completed</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pensioners as $item)
                                <tr>
                                    <td>{{ $item->ppo_number }}</td>
                                    <td>{{ $item->pensioner_name }}</td>
                                    <td>{{ $item->pension_type == 1 ? 'Self' : 'Family member' }}</td>
                                    <td>
                                        <div class="form-check form-switch custom-switch-v1 mb-2">
                                            <input type="checkbox" class="form-check-input input-primary"
                                                   id="customswitchv2-{{ $item->id }}"
                                                   {{ $item->life_certificate == 1 ? 'checked' : '' }}
                                                   data-id="{{ $item->id }}">
                                            <label class="form-check-label" for="customswitchv2-{{ $item->id }}">
                                                {!! $item->life_certificate == 1 ? '<span class="text-primary">Yes</span>' : '<span class="text-danger">No</span>' !!}
                                            </label>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->retirement_date)->format('d/m/Y') }}</td>
                                    <td>{!! $item->alive_status == 1 ? '<span class="text-success">Alive</span>' : '<span class="text-danger">Dead</span>' !!}</td>
                                    <td>
                                        @php
                                            $fiveYearDate = \Carbon\Carbon::parse($item->five_year_date);
                                            $today = \Carbon\Carbon::today();
                                        @endphp
                                        @if($fiveYearDate->isPast())
                                            <span class="text-danger">{{ \Carbon\Carbon::parse($item->five_year_date)->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-primary">{{ \Carbon\Carbon::parse($item->five_year_date)->format('d/m/Y') }}</span>
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
                                    <td>
                                        <a href="{{ route('superadmin.pension.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>PPO Code</th>
                                    <th>Pensioner Name</th>
                                    <th>Type Of Pension</th>
                                    <th>Life Certificate</th>
                                    <th>Date of Retirement</th>
                                    <th>Alive Status</th>
                                    <th>5 Years Completed</th>
                                    <th>80 Years Completed</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var total, pageTotal;
        var table = $('#pensioner-data').DataTable({
            "order": [['id']]  // Change 'desc' to 'asc' for ascending order
        });
    </script>

    <script>
        $(document).ready(function() {
            // On change of the switch
            $('input[type="checkbox"].form-check-input').on('change', function() {
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
