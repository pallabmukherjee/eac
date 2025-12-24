@extends('layouts.main')

@section('title', 'Update Pension Bill')

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
                            <h4>Update Pension Bill</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.pension.report.update', $report->report_id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="month_year" class="form-label">Month and Year</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                    @php
                                        $defaultYear = $report ? $report->year : date('Y');
                                        $defaultMonth = $report ? $report->month : date('n');
                                        $defaultDate = \Carbon\Carbon::createFromDate($defaultYear, $defaultMonth, 1);
                                    @endphp
                                    <input type="text" class="form-control" id="month_year" name="month_year" value="{{ $defaultDate->format('F Y') }}" required>
                                </div>
                                <input type="hidden" id="month" name="month" value="{{ $defaultMonth }}">
                                <input type="hidden" id="year" name="year" value="{{ $defaultYear }}">
                            </div>
                        </div>

                        <div class="dt-responsive">
                            <table id="edit-report-table" class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Pensioner Details</th>
                                        <th>PPO Number</th>
                                        <th class="text-center">Life Cert.</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Gross</th>
                                        <th style="width: 120px;">Arrear</th>
                                        <th style="width: 120px;">Overdrawn</th>
                                        <th class="text-end">Net Pension</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pensionersReport as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avtar avtar-s bg-light-primary">
                                                        <i class="ti ti-user f-18"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $item->pensionerDetails->pensioner_name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->pensionerDetails->ppo_number }}</td>
                                            <td class="text-center">
                                                {!! $item->pensionerDetails->life_certificate == 1 ? '<span class="badge bg-light-success text-success">Yes</span>' : '<span class="badge bg-light-danger text-danger">No</span>' !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $item->pensionerDetails->alive_status == 1 ? '<span class="badge bg-light-success text-success">Alive</span>' : '<span class="badge bg-light-danger text-danger">Dead</span>' !!}
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold">{{ $item->gross }}</span>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm no-negative" type="number" name="arrear[{{ $item->id }}]" placeholder="0.00" value="{{ old('arrear.' . $item->id, $item->arrear ?? '') }}">
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm no-negative" type="number" name="overdrawn[{{ $item->id }}]" placeholder="0.00" value="{{ old('overdrawn.' . $item->id, $item->overdrawn ?? '') }}">
                                            </td>
                                            <td class="text-end fw-bold text-primary">{{ $item->net_pension }}</td>
                                            <td>
                                                <input class="form-control form-control-sm" type="text" name="remarks[{{ $item->id }}]" placeholder="Remarks" value="{{ old('remarks.' . $item->id, $item->remarks ?? '') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Update Bill
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

        document.querySelectorAll('.no-negative').forEach(function(input) {
            input.addEventListener('input', function (e) {
                // Prevent negative sign entry by replacing it
                this.value = this.value.replace('-', '');
            });
        });

        $('#edit-report-table').DataTable({
            "paging": false,
            "info": false,
            "ordering": false,
            "searching": true
        });
    });
</script>
@endsection