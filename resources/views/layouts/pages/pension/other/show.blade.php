@extends('layouts.main')

@section('title', 'Other Bill Details')

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
                            <h4>Other Bill: {{ \Carbon\Carbon::parse($otherBill->created_at)->format('F Y') }}</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.other.pdf', $otherBill->bill_id) }}" class="btn btn-primary me-2" target="_blank">
                                <i class="ti ti-file-type-pdf me-1"></i> View PDF
                            </a>
                            <a href="{{ route('superadmin.pension.other.csv', $otherBill->bill_id) }}" class="btn btn-success">
                                <i class="ti ti-file-type-csv me-1"></i> Download CSV
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($otherBill->details)
                        <div class="alert alert-secondary mb-4" role="alert">
                            <h5 class="alert-heading"><i class="ti ti-info-circle me-1"></i> Description</h5>
                            <p class="mb-0">{{ $otherBill->details }}</p>
                        </div>
                    @endif

                    <div class="dt-responsive">
                        <table id="other-bill-details" class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">Pensioner Details</th>
                                    <th class="py-3">PPO Number</th>
                                    <th class="py-3 text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pensionersReport as $item)
                                <tr>
                                    <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-info">
                                                    <i class="ti ti-user f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $item->pensionerDetails->pensioner_name }}</h6>
                                                <small class="text-muted">Code: {{ $item->pensionerDetails->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $item->pensionerDetails->ppo_number }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        Rs. {{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
        var table = $('#other-bill-details').DataTable({
            "order": [[0, 'asc']]
        });
    </script>
@endsection