@extends('layouts.main')

@section('title', 'Pensioner Other Report Generate')

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
                            <h4>Pensioner {{ \Carbon\Carbon::parse($report->created_at)->format('F') }} {{ \Carbon\Carbon::parse($report->created_at)->format('Y') }} Update</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <form action="{{ route('superadmin.pension.other.update', $report->bill_id) }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="text" name="details" class="form-control mb-3" placeholder="Enter Details" value="{{ old('details', $report->details ?? '') }}">
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
                                            <th>Amount</th>
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
                                                <td>
                                                    <input class="form-control form-control-sm no-negative" type="number" name="amount[{{ $item->id }}]" placeholder="Amount" value="{{ old('amount.' . $item->id, $item->amount ?? '') }}">
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
                                            <th>Amount</th>
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
<script>
    document.querySelectorAll('.no-negative').forEach(function(input) {
        input.addEventListener('input', function (e) {
            // Prevent negative sign entry by replacing it
            this.value = this.value.replace('-', '');
        });
    });
</script>

@endsection
