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
              <h4>{{ $beneficiary ? 'Edit Beneficiary' : 'Add Beneficiary' }}</h4>
            </div>
          </div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ $url }}">
            @csrf
            @if($beneficiary)
                @method('PUT')
                <input type="hidden" name="id" value="{{ $beneficiary->id }}">
            @endif
            <div class="row">
                <h4>Personal Details</h4>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Institute/Vendor/Beneficiary *</label>
                    <input type="text" name="institute_vendor_beneficiary" class="form-control" placeholder="Enter Institute/Vendor/Beneficiary Name" value="{{ old('institute_vendor_beneficiary', $beneficiary->name ?? '') }}" required>
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Mobile</label>
                    <input type="number" name="mobile" class="form-control" placeholder="Enter Mobile Number" value="{{ old('mobile', $beneficiary->mobile ?? '') }}">
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">PAN Card</label>
                    <input type="text" name="pan_card" class="form-control" placeholder="Enter PAN Card Number" value="{{ old('pan_card', $beneficiary->pan_card ?? '') }}">
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">GST (Optional)</label>
                    <input type="text" name="gst" class="form-control" placeholder="Enter GST Number" value="{{ old('gst', $beneficiary->gst ?? '') }}">
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Aadhaar No.</label>
                    <input type="number" name="aadhaar_no" class="form-control" placeholder="Enter Aadhaar Number" value="{{ old('aadhaar_no', $beneficiary->aadhaar_no ?? '') }}">
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Enter Address" value="{{ old('address', $beneficiary->address ?? '') }}">
                </div>

                <h4>Bank Details</h4>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="Enter Bank Name" value="{{ old('bank_name', $beneficiary->bank_name ?? '') }}">
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" class="form-control" placeholder="Enter IFSC Code" value="{{ old('ifsc_code', $beneficiary->ifsc_code ?? '') }}">
                </div>

                <div class="mb-3 col-lg-4">
                    <label class="form-label">Account No.</label>
                    <input type="number" name="account_no" class="form-control" placeholder="Enter Account Number" value="{{ old('account_no', $beneficiary->account_no ?? '') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ $beneficiary ? 'Update' : 'Save' }}</button>
        </form>
      </div>
    </div>

    @if(Route::currentRouteName() != 'superadmin.account.beneficiary.edit')
    <div class="card">
        <div class="card-header">
            <h4>Beneficiary List</h4>
        </div>
        <div class="card-body">
            <div class="dt-responsive">
                <table id="dom-jqry" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Pan Card</th>
                            <th>Pan Category</th>
                            <th>GST</th>
                            <th>Aadhaar no</th>
                            <th>Address</th>
                            <th>Bank Name</th>
                            <th>Account No</th>
                            <th>IFSC Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($beneficiarys as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->mobile ?? 'NA'  }}</td>
                            <td>{{ $item->pan_card ?? 'NA'  }}</td>
                            <td>{{ $item->pancard->label ?? 'NA' }}</td>
                            <td>{{ $item->gst ?? 'NA'  }}</td>
                            <td>{{ $item->aadhaar_no ?? 'NA'  }}</td>
                            <td>{{ $item->address ?? 'NA'  }}</td>
                            <td>{{ $item->bank_name ?? 'NA'  }}</td>
                            <td>{{ $item->account_no ?? 'NA'  }}</td>
                            <td>{{ $item->ifsc_code ?? 'NA'  }}</td>
                            <td>
                                <a href="{{ route('superadmin.account.beneficiary.edit', $item->id) }}" class="avtar avtar-xs btn-link-secondary"><i class="ti ti-edit f-20"></i></a>

                                <form action="{{ route('superadmin.account.beneficiary.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="avtar avtar-xs btn-link-secondary" onclick="return confirm('Are you sure you want to delete this Beneficiary?');"><i class="ti ti-trash f-20"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Pan Card</th>
                            <th>Pan Category</th>
                            <th>GST</th>
                            <th>Aadhaar no</th>
                            <th>Address</th>
                            <th>Bank Name</th>
                            <th>Account No</th>
                            <th>IFSC Code</th>
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
        var table = $('#dom-jqry').DataTable({
            "order": [['id']]
        });
    </script>
@endsection
