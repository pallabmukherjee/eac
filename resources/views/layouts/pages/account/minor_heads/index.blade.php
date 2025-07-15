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
              <h4>{{ $minorHead ? 'Edit Minor Head' : 'Add Minor Head' }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf
            @if($minorHead)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $minorHead->id }}">
            @endif

            <div class="row">
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Major Head:</label>
                    <select name="major_head_code" class="form-control" required>
                        <option value="">Select Major Head</option>
                        @foreach($majorHeads as $majorHead)
                            <option value="{{ $majorHead->id }}" {{ old('major_head_code', $minorHead->major_head_code ?? '') == $majorHead->id ? 'selected' : '' }}>
                                {{ $majorHead->code }} - {{ $majorHead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Minor Head Code:</label>
                    <input type="number" name="code" class="form-control" placeholder="Enter Major Head Code" value="{{ old('code', $minorHead->code ?? '') }}" required oninput="if(this.value.length > 2) this.value = this.value.slice(0,2)">
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Minor Head Name:</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Major Head Name" value="{{ old('name', $minorHead->name ?? '') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ $minorHead ? 'Update' : 'Save' }}</button>
        </form>
      </div>
    </div>

    @if(Route::currentRouteName() != 'superadmin.account.minorhead.edit')
    <div class="card">
        <div class="card-header">
            <h4>Minor Head List</h4>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Major Head Code</th>
                            <th>Minor Head Code</th>
                            <th>Minor Head Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($minorHeads as $item)
                        <tr>
                            <td>{{ $item->majorHead->code }} - {{ $item->majorHead->name }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <!-- Edit Button -->
                                <a href="{{ route('superadmin.account.minorhead.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>

                                <!-- Delete Button -->
                                <form action="{{ route('superadmin.account.minorhead.destroy', $item->id) }}" method="POST" style="display:inline;">
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
                            <th>Minor Head Code</th>
                            <th>Minor Head Name</th>
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
        var table = $('#dom-jqry').DataTable();
    </script>
@endsection
