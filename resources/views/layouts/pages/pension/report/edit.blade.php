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
                            <h4>Pensioner Report Update</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <form action="{{ route('superadmin.pension.report.update', $report->report_id) }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="me-3 mb-0">Month and Year</h5>
                                    <div style="width: 200px;">
                                        @php
                                            $defaultYear = $report ? $report->year : date('Y');
                                            $defaultMonth = $report ? $report->month : date('n');
                                            $defaultDate = \Carbon\Carbon::createFromDate($defaultYear, $defaultMonth, 1);
                                        @endphp
                                        <input type="text" class="form-control" id="month_year" name="month_year" value="{{ $defaultDate->format('F Y') }}" required>
                                        <input type="hidden" id="month" name="month" value="{{ $defaultMonth }}">
                                        <input type="hidden" id="year" name="year" value="{{ $defaultYear }}">
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered nowrap">
                                    <thead>
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
                                            <th>Net Pension</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pensionersReport as $item)
                                            <tr>
                                                <td>{{ $item->pensionerDetails->ppo_number }}</td>
                                                <td>{{ $item->pensionerDetails->pensioner_name }}</td>
                                                <td>{{ $item->pensionerDetails->pension_type == 1 ? 'Self' : 'Family member' }}</td>
                                                <td>{!! $item->pensionerDetails->life_certificate == 1 ? '<span class="text-primary">Yes</span>' : '<span class="text-danger">No</span>' !!}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->pensionerDetails->retirement_date)->format('d/m/Y') }}</td>
                                                <td>{!! $item->pensionerDetails->alive_status == 1 ? '<span class="text-success">Alive</span>' : '<span class="text-danger">Dead</span>' !!}</td>
                                                <td>
                                                    @php
                                                        $fiveYearDate = \Carbon\Carbon::parse($item->pensionerDetails->five_year_date);
                                                        $today = \Carbon\Carbon::today();
                                                    @endphp
                                                    @if ($fiveYearDate->isPast())
                                                        <span class="text-danger">{{ $fiveYearDate->format('d/m/Y') }}</span>
                                                    @else
                                                        <span class="text-primary">{{ $fiveYearDate->format('d/m/Y') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->gross }}</td>
                                                <td>
                                                    <input class="form-control form-control-sm no-negative" type="number" name="arrear[{{ $item->id }}]" placeholder="Arrear" value="{{ old('arrear.' . $item->id, $item->arrear ?? '') }}">
                                                </td>
                                                <td>
                                                    <input class="form-control form-control-sm no-negative" type="number" name="overdrawn[{{ $item->id }}]" placeholder="Overdrawn" value="{{ old('overdrawn.' . $item->id, $item->overdrawn ?? '') }}">
                                                </td>
                                                <td>{{ $item->net_pension }}</td>
                                                <td>
                                                    <input class="form-control form-control-sm" type="text" name="remarks[{{ $item->id }}]" placeholder="Remarks" value="{{ old('remarks.' . $item->id, $item->remarks ?? '') }}">
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
                                            <th>Net Pension</th>
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

    document.querySelectorAll('.no-negative').forEach(function(input) {
        input.addEventListener('input', function (e) {
            // Prevent negative sign entry by replacing it
            this.value = this.value.replace('-', '');
        });
    });
</script>

@endsection
