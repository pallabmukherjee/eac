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
                    <h4>Gratuity Data</h4>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <a href="{{ route('superadmin.gratuity.add') }}" class="btn btn-primary me-1">
                        <i class="ti ti-plus me-1"></i> Add New Data
                    </a>
                    <a href="{{ route('superadmin.gratuity.export') }}" class="btn btn-success me-1">
                        <i class="ti ti-file-type-csv me-1"></i> Excel
                    </a>
                    <a href="{{ route('superadmin.gratuity.exportPdf') }}" class="btn btn-secondary" target="_blank">
                        <i class="ti ti-file-type-pdf me-1"></i> PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="gratuity-list" class="table table-hover table-borderless align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3">Employee Details</th>
                            <th class="py-3">Relation Name</th>
                            <th class="py-3">PPO Details</th>
                            <th class="py-3 text-end">PPO Amount</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gratuitys as $item)
                        <tr>
                            <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avtar avtar-s bg-light-primary">
                                            <i class="ti ti-user f-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $item->name }}</h6>
                                        <small class="text-muted">Code: {{ $item->employee_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->relation_name }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $item->ppo_number }}</span>
                                    <small class="text-muted">Received: {{ \Carbon\Carbon::parse($item->ppo_receive_date)->format('d M, Y') }}</small>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-light-success text-success f-14">Rs. {{ number_format($item->ppo_amount, 2) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('superadmin.gratuity.edit', $item->id) }}" class="btn btn-icon btn-link-primary avtar-xs" data-bs-toggle="tooltip" title="Edit">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <form action="{{ route('superadmin.gratuity.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
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
        var total, pageTotal;
        var table = $('#gratuity-list').DataTable({
            "order": [['id']],
            "columnDefs": [
                {
                    "targets": 1,
                    "createdCell": function (td) {
                        $(td).css({
                            'white-space': 'normal',
                            'word-break': 'break-word'
                        });
                    }
                },
                {
                    "targets": 4,
                    "createdCell": function (td) {
                        $(td).css({
                            'white-space': 'normal',
                            'word-break': 'break-word'
                        });
                    }
                }
            ]
        });
    </script>
@endsection
