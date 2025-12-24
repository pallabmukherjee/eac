@extends('layouts.main')

@section('title', 'Generate Other Bill')

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
                            <h4>Generate Other Bill</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.report.index') }}" class="btn btn-primary">Pension Bill</a>
                          </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.pension.other.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="details" class="form-label">Bill Details</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-file-description"></i></span>
                                    <input type="text" name="details" id="details" class="form-control" placeholder="Enter Description or Details for this Bill" value="" required>
                                </div>
                            </div>
                        </div>

                        <div class="dt-responsive">
                            <table id="create-other-bill-table" class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Pensioner Details</th>
                                        <th>PPO Number</th>
                                        <th>Pension Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Gross</th>
                                        <th style="width: 150px;">Amount</th>
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
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">Rs.</span>
                                                    <input class="form-control" type="number" name="amount[{{ $item->id }}]" placeholder="0.00" value="{{ old('amount.' . $item->id, '') }}">
                                                </div>
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
<script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#create-other-bill-table').DataTable({
            "paging": false,
            "info": false,
            "ordering": false,
            "searching": true
        });
    });
</script>
@endsection