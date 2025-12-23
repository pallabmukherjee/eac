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
                            <h4>{{ $status == 2 ? 'Accepted' : 'Pending' }} Applications</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.gratuity.bill.create') }}" class="btn btn-primary">Create Gratuity Application</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="bill-list" class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">Bill Details</th>
                                    <th class="py-3">Generation Date</th>
                                    <th class="py-3 text-end">Total Amount</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bill as $item)
                                @php
                                    $totalBillAmount = \App\Models\GratuityBillSummary::where('bill_id', $item->bill_id)
                                        ->selectRaw('SUM(COALESCE(gratuity_amount, 0) + COALESCE(loan_amount, 0)) as total')
                                        ->first()->total;
                                @endphp
                                <tr>
                                    <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s {{ $item->status == 2 ? 'bg-light-success' : 'bg-light-warning' }}">
                                                    <i class="ti {{ $item->status == 2 ? 'ti-check' : 'ti-file-text' }} f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $item->bill_no }}</h6>
                                                <small class="text-muted">ID: {{ $item->bill_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}</span>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-light-primary text-primary f-14">Rs. {{ number_format($totalBillAmount, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status == 1)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($item->status == 2)
                                            <span class="badge bg-success">Approved</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if($item->status == 1)
                                            <a href="{{ route('superadmin.gratuity.bill.approve', $item->bill_id) }}" class="btn btn-icon btn-link-success avtar-xs" data-bs-toggle="tooltip" title="Approve">
                                                <i class="ti ti-check f-20"></i>
                                            </a>
                                            @endif
                                            <a href="{{ route('superadmin.gratuity.bill.show', $item->bill_id ) }}" class="btn btn-icon btn-link-primary avtar-xs" data-bs-toggle="tooltip" title="View Details">
                                                <i class="ti ti-eye f-20"></i>
                                            </a>
                                            <a href="{{ route('superadmin.gratuity.bill.edit', $item->bill_id) }}" class="btn btn-icon btn-link-secondary avtar-xs" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit f-20"></i>
                                            </a>
                                            <a href="{{ route('superadmin.gratuity.bill.pdf', $item->bill_id) }}" class="btn btn-icon btn-link-success avtar-xs" target="_blank" data-bs-toggle="tooltip" title="Download PDF">
                                                <i class="ti ti-file-download f-20"></i>
                                            </a>
                                            <form action="{{ route('superadmin.gratuity.bill.destroy', $item->bill_id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this bill? This will also remove associated summary and payment records.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-link-danger avtar-xs" style="border: none; background: none;" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="ti ti-trash f-20"></i>
                                                </button>
                                            </form>
                                        </div>
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
        var table = $('#bill-list').DataTable({
            "order": [[0, 'asc']]
        });
    </script>
@endsection
