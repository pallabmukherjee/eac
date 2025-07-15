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
            <h4>Gratuity List</h4>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="gratuity-list" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Relation Name</th>
                            <th>EMP Code</th>
                            <th>PPO Number</th>
                            <th>PPO Receive Date</th>
                            <th>PPO Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gratuitys as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->relation_name }}</td>
                            <td>{{ $item->employee_code }}</td>
                            <td>{{ $item->ppo_number }}</td>
                            <td>{{ $item->ppo_receive_date }}</td>
                            <td>{{ $item->ppo_amount }}</td>
                            <td>
                                <a href="{{ route('superadmin.gratuity.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>
                                <form action="{{ route('superadmin.gratuity.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="avtar avtar-xs btn-link-danger" style="border: none; background: none;">
                                        <i class="ti ti-trash f-20"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Relation Name</th>
                            <th>EMP Code</th>
                            <th>PPO Number</th>
                            <th>PPO Receive Date</th>
                            <th>PPO Amount</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
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
