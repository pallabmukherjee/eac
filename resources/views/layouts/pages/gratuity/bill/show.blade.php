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
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>PPO No.</th>
                                    <th>Bank A/C No.</th>
                                    <th>IFSC</th>
                                    <th>Approved Amount</th>
                                    <th>Financial Year</th>
                                    <th>Ropa Year</th>
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
                                    <td>{{ $item->empDetails->name }}</td>
                                    <td>{{ $item->empDetails->ppo_number }}</td>
                                    <td>{{ $item->empDetails->bank_ac_no ?? 'NA' }}</td>
                                    <td>{{ $item->empDetails->ifsc ?? 'NA' }}</td>
                                    <td>{{ $item->gratuity_amount }}</td>
                                    <td>{{ $financialYear->year }}</td>
                                    <td>{{ $gratuityRopaYear->year }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>PPO No.</th>
                                    <th>Bank A/C No.</th>
                                    <th>IFSC</th>
                                    <th>Approved Amount</th>
                                    <th>Financial Year</th>
                                    <th>Ropa Year</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <h5>Total Approved Amount: Rs. {{ number_format($totalGratuity, 2) }}</h5>
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
        var total, pageTotal;
        var table = $('#dom-jqry').DataTable({
            "order": [['id']] // Sort the first column by descending order
        });
    </script>
@endsection
