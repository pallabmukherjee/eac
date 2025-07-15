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
            <div class="col-sm-12">
              <h4>{{ $majorHead ? 'Edit Major Head' : 'Add Major Head' }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf
            @if($majorHead)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $majorHead->id }}">
            @endif

            <div class="row">
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Major Head Code:</label>
                    <input type="number" name="code" class="form-control" placeholder="Enter Major Head Code" value="{{ old('code', $majorHead->code ?? '') }}" required oninput="if(this.value.length > 3) this.value = this.value.slice(0,3)">
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Major Head Name:</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Major Head Name" value="{{ old('name', $majorHead->name ?? '') }}">
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Schedule Reference No:</label>
                    <input type="text" name="schedule_reference_no" class="form-control" placeholder="Enter Major Head Name" value="{{ old('schedule_reference_no', $majorHead->schedule_reference_no ?? '') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ $majorHead ? 'Update' : 'Save' }}</button>
        </form>
      </div>
    </div>

    @if(Route::currentRouteName() != 'superadmin.account.majorhead.edit')
    <div class="card">
        <div class="card-header">
            <h4>Major Head List</h4>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Major Head Code</th>
                            <th>Major Head Name</th>
                            <th>Schedule Reference No</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($majorHeads as $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->schedule_reference_no ?? 'NA' }}</td>
                            <td>
                                <!-- Edit Button -->
                                <a href="{{ route('superadmin.account.majorhead.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>

                                <!-- Delete Button -->
                                <form action="{{ route('superadmin.account.majorhead.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="avtar avtar-xs btn-link-secondary" onclick="return confirm('Are you sure you want to delete this major head?');"><i class="ti ti-trash f-20"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Major Head Code</th>
                            <th>Major Head Name</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

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
        "order": [["code"]]
    });
</script>
@endsection
