@extends('layouts.main')

@section('title', 'Multi Form Layouts')

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
                            <h4>Gratuity Bill No. {{ $report->bill_no }}</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.gratuity.bill.pdf', $report->bill_id) }}" class="btn btn-primary" target="_blank">View PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="bill-details" class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Employee Details</th>
                                    <th class="py-3">PPO Details</th>
                                    <th class="py-3">Bank Details</th>
                                    <th class="py-3 text-end">Approved Amount</th>
                                    <th class="py-3">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalGratuity = 0; @endphp

                                @foreach($gratuityBills as $item)
                                @php
                                    $gratuityRopaYear = App\Models\GratuityRopaYear::where('id', $item->empDetails->ropa_year)->first();
                                    $financialYear = App\Models\FinancialYear::where('id', $item->empDetails->financial_year)->first();
                                    $totalGratuity += $item->gratuity_amount;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ti ti-user f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $item->empDetails->name }}</h6>
                                                <small class="text-muted">Code: {{ $item->empDetails->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $item->empDetails->ppo_number }}</span>
                                            <small class="text-muted">Ropa: {{ $gratuityRopaYear->year ?? 'NA' }} | FY: {{ $financialYear->year ?? 'NA' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $item->empDetails->bank_ac_no ?? 'NA' }}</span>
                                            <small class="text-muted">IFSC: {{ $item->empDetails->ifsc ?? 'NA' }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light-success text-success f-14">Rs. {{ number_format($item->gratuity_amount, 2) }}</span>
                                    </td>
                                    <td><span class="text-muted">{{ $item->remarks ?? 'NA' }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-end">
                        <h4 class="text-primary">Total Approved Amount: Rs. {{ number_format($totalGratuity, 2) }}</h4>
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
        var table = $('#bill-details').DataTable({
            "order": [[0, 'asc']]
        });
    </script>
@endsection
