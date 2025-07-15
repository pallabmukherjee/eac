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
              <h4>{{ $detailedHead ? 'Edit Detailed Head' : 'Add Detailed Head' }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf
            @if($detailedHead)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $detailedHead->id }}">
            @endif

            <div class="row">
                <input type="hidden" name="major_head_code" id="major_head_code" value="{{ old('major_head_code', $detailedHead->major_head_code ?? '') }}">
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Minor Head:</label>
                    <select name="minor_head_code" id="minor_head_code" class="form-control" required>
                        <option value="">Select Minor Head</option>
                        @foreach($minorHeads as $minorHead)
                            <option value="{{ $minorHead->id }}" data-major-head-id="{{ $minorHead->major_head_code }}" {{ old('minor_head_code', $detailedHead->minor_head_code ?? '') == $minorHead->id ? 'selected' : '' }}>
                                {{ $minorHead->majorHead->code }}{{ $minorHead->code }} - {{ $minorHead->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Detailed Head Code:</label>
                    <input type="number" name="code" class="form-control" placeholder="Enter Major Head Code" value="{{ old('code', $detailedHead->code ?? '') }}" required  oninput="if(this.value.length > 2) this.value = this.value.slice(0,2)">
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Detailed Head Name:</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter Detailed Head Name" value="{{ old('name', $detailedHead->name ?? '') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ $detailedHead ? 'Update' : 'Save' }}</button>
        </form>
      </div>
    </div>

    @if(Route::currentRouteName() != 'superadmin.account.detailedhead.edit')
    <div class="card">
        <div class="card-header">
            <h4>Detailed Head List</h4>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Major Head Code</th>
                            <th>Minor Head Code</th>
                            <th>Detailed Head</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailedHeads as $item)
                        <tr>
                            <td>{{ $item->majorHead->code }} - {{ $item->majorHead->name }}</td>
                            <td>{{ $item->minorHead->code }} - {{ $item->minorHead->name }}</td>
                            <td>{{ $item->code }} - {{ $item->name }}</td>
                            <td>
                                <a href="{{ route('superadmin.account.detailedhead.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>

                                <form action="{{ route('superadmin.account.detailedhead.destroy', $item->id) }}" method="POST" style="display:inline;">
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
                            <th>Detailed Head</th>
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

<script>
    document.getElementById('minor_head_code').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var majorHeadId = selectedOption.getAttribute('data-major-head-id');
        document.getElementById('major_head_code').value = majorHeadId; // Set the hidden input value
    });

    // Set the major head code on page load if a minor head is already selected
    window.onload = function() {
        var selectedOption = document.getElementById('minor_head_code').options[document.getElementById('minor_head_code').selectedIndex];
        var majorHeadId = selectedOption.getAttribute('data-major-head-id');
        document.getElementById('major_head_code').value = majorHeadId; // Set the hidden input value
    };
</script>
@endsection
