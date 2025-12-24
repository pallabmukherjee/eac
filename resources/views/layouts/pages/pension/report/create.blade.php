@extends('layouts.main')

@section('title', 'Generate Pension Bill')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/flatpickr.min.css') }}">
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
                            <h4>Generate Pension Bill</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.pension.report.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="month_year" class="form-label">Select Month and Year</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    <input type="text" class="form-control" id="month_year" name="month_year" placeholder="Select Month & Year" required>
                                </div>
                                <input type="hidden" id="month" name="month">
                                <input type="hidden" id="year" name="year">
                            </div>
                        </div>

                        <div class="dt-responsive">
                            <table id="create-report-table" class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Pensioner Details</th>
                                        <th>PPO Number</th>
                                        <th>Pension Type</th>
                                        <th class="text-center">Life Cert.</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Gross</th>
                                        <th style="width: 120px;">Arrear</th>
                                        <th style="width: 120px;">Overdrawn</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pensioners as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avtar avtar-s bg-light-primary">
                                                        <i class="ti ti-user f-18"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $item->pensioner_name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->ppo_number }}</td>
                                            <td>{{ $item->pension_type == 1 ? 'Self' : 'Family' }}</td>
                                            <td class="text-center">
                                                {!! $item->life_certificate == 1 ? '<span class="badge bg-light-success text-success">Yes</span>' : '<span class="badge bg-light-danger text-danger">No</span>' !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $item->alive_status == 1 ? '<span class="badge bg-light-success text-success">Alive</span>' : '<span class="badge bg-light-danger text-danger">Dead</span>' !!}
                                            </td>
                                            <td class="text-end">
                                                @php
                                                    $basic_pension = (intval($item->basic_pension) == $item->basic_pension) ? $item->basic_pension : ceil($item->basic_pension);
                                                    $medical_allowance = (intval($item->medical_allowance) == $item->medical_allowance) ? $item->medical_allowance : ceil($item->medical_allowance);
                                                    $other_allowance = (intval($item->other_allowance) == $item->other_allowance) ? $item->other_allowance : ceil($item->other_allowance);

                                                    $gross = $basic_pension + $basic_pension * ($item->ropa->dr / 100) + $medical_allowance + $other_allowance;
                                                    $gross_rounded = (intval($gross) == $gross) ? $gross : ceil($gross);
                                                @endphp
                                                <span class="fw-bold">{{ $gross_rounded }}</span>
                                                <input type="hidden" name="gross[{{ $item->id }}]" value="{{ old('gross.' . $item->id, $gross_rounded) }}">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" type="number" name="arrear[{{ $item->id }}]" placeholder="0.00" value="{{ old('arrear.' . $item->id, '') }}">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" type="number" name="overdrawn[{{ $item->id }}]" placeholder="0.00" value="{{ old('overdrawn.' . $item->id, '') }}">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm" type="text" name="remarks[{{ $item->id }}]" placeholder="Remarks" value="{{ old('remarks.' . $item->id, '') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Save Bill
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#month_year", {
            dateFormat: "F Y",
            defaultDate: new Date({{ date('Y') }}, {{ date('n') - 1 }}),
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    const selectedDate = selectedDates[0];
                    document.getElementById('month').value = selectedDate.getMonth() + 1;
                    document.getElementById('year').value = selectedDate.getFullYear();
                }
            },
            onReady: function(selectedDates, dateStr, instance) {
                if (instance.selectedDates.length > 0) {
                    const selectedDate = instance.selectedDates[0];
                    document.getElementById('month').value = selectedDate.getMonth() + 1;
                    document.getElementById('year').value = selectedDate.getFullYear();
                }
            }
        });

        $('#create-report-table').DataTable({
            "paging": false,
            "info": false,
            "ordering": false, 
            "searching": true 
        });
    });
</script>
@endsection
