@extends('layouts.main')

@section('title', 'Signing Authorities')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Signing Authorities</h4>
                        <a href="{{ route('superadmin.pension.signs.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive">
                        <table id="signs-data" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($signs as $sign)
                                <tr>
                                    <td>{{ $sign->name }}</td>
                                    <td>{{ $sign->designation }}</td>
                                    <td>
                                        <a href="{{ route('superadmin.pension.signs.edit', $sign->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>
                                        <form action="{{ route('superadmin.pension.signs.destroy', $sign->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="avtar avtar-xs btn-link-danger" onclick="return confirm('Are you sure you want to delete this item?')"><i class="ti ti-trash f-20"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
