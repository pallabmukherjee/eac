@extends('layouts.main')

@section('title', 'Add ROPA Year')

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
                            <h4>{{ $title }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $url }}">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label class="form-label">Year:</label>
                                <input type="text" name="year" class="form-control" placeholder="Enter Year" value="{{ old('year', $ropaYear->year ?? '') }}" required>
                                @error('year')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>

            @if(Route::currentRouteName() != 'superadmin.gratuity.ropa.edit')
            <div class="card">
                <div class="card-header">
                    <h4>{{ $title }} List</h4>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ropaYears as $item)
                                <tr>
                                    <td>{{ $item->year }}</td>
                                    <td><a href="{{ route('superadmin.gratuity.ropa.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Year</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        var total, pageTotal;
        var table = $('#dom-jqry').DataTable({
            "order": [['id']] // Sort the first column by descending order
        });
    </script>
@endsection
