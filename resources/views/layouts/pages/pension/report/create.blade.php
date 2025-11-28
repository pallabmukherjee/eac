@extends('layouts.main')

@section('title', 'Pensioner Report Generate')

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
                            <h4>Pensioner Report Generate</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <form action="{{ route('superadmin.pension.report.store') }}" method="POST">
                                @csrf
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="me-3 mb-0">Month and Year</h5>
                                    <div style="width: 200px;">
                                        <input type="text" class="form-control" id="month_year" name="month_year" required>
                                        <input type="hidden" id="month" name="month">
                                        <input type="hidden" id="year" name="year">
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Srl</th>
                                            <th>PPO Code</th>
                                            <th>Pensioner Name</th>
                                            <th>Type Of Pension</th>
                                            <th>Life Certificate</th>
                                            <th>Date of Retirement</th>
                                            <th>Alive Status</th>
                                            <th>5 Years Completed</th>
                                            <th>Gross</th>
                                            <th>Arrear</th>
                                            <th>Overdrawn</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pensioners as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->ppo_number }}</td>
                                                <td>{{ $item->pensioner_name }}</td>
                                                <td>{{ $item->pension_type == 1 ? 'Self' : 'Family member' }}</td>
                                                <td>{!! $item->life_certificate == 1 ? '<span class="text-primary">Yes</span>' : '<span class="text-danger">No</span>' !!}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->retirement_date)->format('d/m/Y') }}</td>
                                                <td>{!! $item->alive_status == 1 ? '<span class="text-success">Alive</span>' : '<span class="text-danger">Dead</span>' !!}</td>
                                                <td>
                                                    @php
                                                        $fiveYearDate = \Carbon\Carbon::parse($item->five_year_date);
                                                        $today = \Carbon\Carbon::today();
                                                    @endphp
                                                    @if ($fiveYearDate->isPast())
                                                        <span class="text-danger">{{ \Carbon\Carbon::parse($item->five_year_date)->format('d/m/Y') }}</span>
                                                    @else
                                                        <span class="text-primary">{{ \Carbon\Carbon::parse($item->five_year_date)->format('d/m/Y') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $basic_pension = (intval($item->basic_pension) == $item->basic_pension) ? $item->basic_pension : ceil($item->basic_pension);
                                                        $medical_allowance = (intval($item->medical_allowance) == $item->medical_allowance) ? $item->medical_allowance : ceil($item->medical_allowance);
                                                        $other_allowance = (intval($item->other_allowance) == $item->other_allowance) ? $item->other_allowance : ceil($item->other_allowance);

                                                        $gross = $basic_pension + $basic_pension * ($item->ropa->dr / 100) + $medical_allowance + $other_allowance;
                                                        $gross_rounded = (intval($gross) == $gross) ? $gross : ceil($gross);
                                                    @endphp

                                                    {{ $gross_rounded }}
                                                    <input type="hidden" name="gross[{{ $item->id }}]" value="{{ old('gross.' . $item->id, $gross_rounded) }}">
                                                </td>
                                                <td>
                                                    <input class="form-control form-control-sm" type="number" name="arrear[{{ $item->id }}]" placeholder="Arrear" value="{{ old('arrear.' . $item->id, '') }}">
                                                </td>
                                                <td>
                                                    <input class="form-control form-control-sm" type="number" name="overdrawn[{{ $item->id }}]" placeholder="Overdrawn" value="{{ old('overdrawn.' . $item->id, '') }}">
                                                </td>
                                                <td>
                                                    <input class="form-control form-control-sm" type="text" name="remarks[{{ $item->id }}]" placeholder="Remarks" value="{{ old('remarks.' . $item->id, '') }}">
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
                                            <th>Gross</th>
                                            <th>Arrear</th>
                                            <th>Overdrawn</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <button type="submit" class="btn btn-primary">Save Reports</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/js/plugins/flatpickr.min.js') }}"></script>
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
    });
</script>
@endsection