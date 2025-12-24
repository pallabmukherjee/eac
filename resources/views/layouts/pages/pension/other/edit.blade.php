@extends('layouts.main')

@section('title', 'Update Other Bill')

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
                            <h4>Update Other Bill: {{ \Carbon\Carbon::parse($report->created_at)->format('F Y') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.pension.other.update', $report->bill_id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="details" class="form-label">Bill Details</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-file-description"></i></span>
                                    <input type="text" name="details" id="details" class="form-control" placeholder="Enter Description or Details" value="{{ old('details', $report->details ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="dt-responsive">
                            <table id="edit-other-bill-table" class="table table-hover table-borderless align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Pensioner Details</th>
                                        <th>PPO Number</th>
                                        <th>Pension Type</th>
                                        <th class="text-center">Life Cert.</th>
                                        <th class="text-center">Status</th>
                                        <th style="width: 150px;">Amount</th>
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
                                            <td>{{ $item->pensionerDetails->pension_type == 1 ? 'Self' : 'Family' }}</td>
                                            <td class="text-center">
                                                {!! $item->pensionerDetails->life_certificate == 1 ? '<span class="badge bg-light-success text-success">Yes</span>' : '<span class="badge bg-light-danger text-danger">No</span>' !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $item->pensionerDetails->alive_status == 1 ? '<span class="badge bg-light-success text-success">Alive</span>' : '<span class="badge bg-light-danger text-danger">Dead</span>' !!}
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">Rs.</span>
                                                    <input class="form-control no-negative" type="number" name="amount[{{ $item->id }}]" placeholder="0.00" value="{{ old('amount.' . $item->id, $item->amount ?? '') }}">
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
<script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.no-negative').forEach(function(input) {
            input.addEventListener('input', function (e) {
                // Prevent negative sign entry by replacing it
                this.value = this.value.replace('-', '');
            });
        });

        $('#edit-other-bill-table').DataTable({
            "paging": false,
            "info": false,
            "ordering": false,
            "searching": true
        });
    });
</script>
@endsection