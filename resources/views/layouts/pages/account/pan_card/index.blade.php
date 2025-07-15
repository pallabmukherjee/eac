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
              <h4>{{ $panCard ? 'Edit Pan Card Label' : 'Add Pan Card Label' }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf
            @if($panCard)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $panCard->id }}">
            @endif

            <div class="row">
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Pan Card Code:</label>
                    <input type="text" name="code" class="form-control" placeholder="Enter Pan Card Code" value="{{ old('code', $panCard->code ?? '') }}" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Pan Card Label:</label>
                    <input type="text" name="label" class="form-control" placeholder="Enter Pan Card Label" value="{{ old('label', $panCard->label ?? '') }}" required>
                </div>
                <div class="mb-3 col-lg-4">
                    <label class="form-label">Tax:</label>
                    <input type="number" name="tax" class="form-control" placeholder="Enter Tax Percentage" value="{{ old('tax', $panCard->tax ?? '') }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ $panCard ? 'Update' : 'Save' }}</button>
        </form>
      </div>
    </div>

    @if(Route::currentRouteName() != 'superadmin.account.pan.edit')
    <div class="card">
        <div class="card-header">
            <h4>Pan Card List</h4>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Pan Card Code</th>
                            <th>Pan Card Label</th>
                            <th>Tax %</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($panCards as $item)
                        <tr>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->label }}</td>
                            <td>{{ $item->tax }}</td>
                            <td>
                                <!-- Edit Button -->
                                <a href="{{ route('superadmin.account.pan.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>

                                <!-- Delete Button -->
                                <form action="{{ route('superadmin.account.pan.destroy', $item->id) }}" method="POST" style="display:inline;">
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
                            <th>Pan Card Code</th>
                            <th>Pan Card Label</th>
                            <th>Tax %</th>
                            <th>Action</th>
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
