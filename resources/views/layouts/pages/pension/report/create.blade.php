@extends('layouts.main')

@section('title', 'Pensioner Report Generate')

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
                            <h4>Pensioner Report Generate</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <form action="{{ route('superadmin.pension.report.store') }}" method="POST">
                                @csrf
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-md-2">
                                        <label for="month" class="form-label">Month</label>
                                        <select class="form-select" id="month" name="month" required>
                                            <option value="">Select Month</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="year" class="form-label">Year</label>
                                        <select class="form-select" id="year" name="year" required>
                                            <option value="">Select Year</option>
                                            @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                                                <option value="{{ $i }}" {{ old('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
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
