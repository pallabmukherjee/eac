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
                    <form method="POST" action="{{ $url }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Name field -->
                            <div class="mb-3 col-lg-4">
                                <label class="form-label" for="name">Website Name:</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Website Name" value="{{ old('name', $websiteSetting->name ?? '') }}">
                                @error('name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label class="form-label" for="name">Organization Name:</label>
                                <input type="text" name="organization" id="organization" class="form-control" placeholder="Enter Organization name" value="{{ old('organization', $websiteSetting->organization ?? '') }}">
                                @error('organization')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Logo field (file upload) -->
                            <div class="mb-3 col-lg-4">
                                <label class="form-label" for="logo">Logo:</label>
                                <input type="file" name="logo" id="logo" class="form-control">
                                @error('logo')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection
