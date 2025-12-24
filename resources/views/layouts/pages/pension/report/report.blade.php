@extends('layouts.main')

@section('title', 'Pension Bill')

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
                            <h4>Pension Bill</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.report.create') }}" class="btn btn-primary">Generate Bill</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="report-list" class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">#</th>
                                    <th class="py-3">Bill Details</th>
                                    <th class="py-3">Generation Date</th>
                                    <th class="py-3 text-end">Total Amount</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pensionerReport as $item)
                                <tr>
                                    <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avtar avtar-s bg-light-primary">
                                                    <i class="ti ti-calendar-stats f-18"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ \Carbon\Carbon::create()->month($item->month)->format('F') }} {{ $item->year }}</h6>
                                                <small class="text-muted">ID: {{ $item->report_id }}</small>
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
                                        <span class="badge bg-light-primary text-primary f-14 fw-bold">Rs. {{ number_format($item->total_amount, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('superadmin.pension.report.show', $item->report_id) }}" class="btn btn-icon btn-link-primary avtar-xs" data-bs-toggle="tooltip" title="View Details">
                                                <i class="ti ti-eye f-20"></i>
                                            </a>
                                            <a href="{{ route('superadmin.pension.report.edit', $item->report_id) }}" class="btn btn-icon btn-link-secondary avtar-xs" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit f-20"></i>
                                            </a>
                                            <form action="{{ route('superadmin.pension.report.destroy', $item->report_id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');">
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
        var table = $('#report-list').DataTable({
            "order": [[0, 'asc']]
        });
    </script>
@endsection