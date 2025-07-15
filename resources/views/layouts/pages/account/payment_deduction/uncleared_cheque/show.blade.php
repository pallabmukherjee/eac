@extends('layouts.main')

@section('title', 'Export Bootstrap Table')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('assets/css/plugins/flatpickr.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="row align-items-center g-3">
                                <div class="col-sm-12 mb-5">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <h3 class="mb-0">Krishnanagar Municipality</h3>
                                            <h4 class="mb-0">Form 98</h4>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">Payment Voucher</h4>
                                        </div>
                                        <div>
                                            {{-- <h5 class="mb-2">No.: {{ $paymentVoucher->p_voucher_id }}</h5>
                                            <h5 class="mb-0">Date: {{ $paymentVoucher->date }}</h5> --}}
                                        </div>
                                    </div>
                                    <h6 class="mb-0">[Vide Rules 17A, 22B & 230]</h6>
                                </div>
                                <div class="col-sm-12">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6>Paid by:</h6>
                                                </td>
                                                <td>
                                                    {{-- <h6>{{ $paymentVoucher->schemeFund->ledgers_head_code ?? 'N/A' }}
                                                        ({{ $paymentVoucher->schemeFund->name ?? 'N/A' }})</h6> --}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Pay to:</h6>
                                                </td>
                                                <td>
                                                    {{-- <h6>{{ $paymentVoucher->beneficiary->name ?? 'N/A' }}</h6> --}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Bank:</h6>
                                                </td>
                                                {{-- @php
                                                    $relatedLedger = \App\Models\DetailedHead::where(
                                                        'ledgers_head_code',
                                                        $paymentVoucher->bank,
                                                    )->first();
                                                @endphp
                                                <td>
                                                    <h6>{{ $paymentVoucher->bank }} ({{ $relatedLedger->name }})</h6>
                                                </td> --}}
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Bill Ref.:</h6>
                                                </td>
                                                <td>
                                                    {{-- <h6>{{ $paymentVoucher->reference_number ?? 'N/A' }}</h6> --}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6>Dated:</h6>
                                                </td>
                                                <td>
                                                    {{-- <h6>{{ $paymentVoucher->reference_date ?? 'N/A' }}</h6> --}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Srl</th>
                                            <th>Depositor Name</th>
                                            <th>Bank Name</th>
                                            <th>Cheque Submit Bank</th>
                                            <th>Cheque No</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ledgerItem as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->depositor_name }}</td>
                                                <td>{{ $item->bank_name }}</td>
                                                <td width="400px">
                                                    @if($item->cheque_submit_bank == null)
                                                    <select name="online_head[{{ $item->id }}]" class="form-control" id="online_head_{{ $item->id }}">
                                                        <option value="">Select Bank</option>
                                                        @foreach($bank as $bankItem)
                                                            <option value="{{ $bankItem->ledgers_head_code }}" {{ old('online_head.' . $item->id) == $bankItem->ledgers_head_code ? 'selected' : '' }}>
                                                                {{ $bankItem->ledgers_head_code }} - {{ $bankItem->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @else
                                                        <span class="text-primary">{{ $item->cheque_submit_bank }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->cheque_no }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>
                                                    @if($item->status == 1)
                                                        {{-- <form action="{{ route('superadmin.account.pendingaction.ucstore', $item->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm">Pay</button>
                                                        </form> --}}
                                                        <button type="button" class="btn btn-primary btn-sm pay-btn" data-id="{{ $item->id }}">Pay</button>
                                                    @elseif($item->status == 2)
                                                        <span class="text-primary">Payment successfully</span>
                                                    @endif
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
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // When the "Pay" button is clicked
    $('.pay-btn').click(function() {
        var itemId = $(this).data('id'); // Get the item ID
        var bankCode = $('#online_head_' + itemId).val(); // Get the selected bank code for the item

        if (!bankCode) {
            alert('Please select a bank.');
            return;
        }

        // Send the data to the server using AJAX
        $.ajax({
            url: '{{ route('superadmin.account.pendingaction.ucstore') }}', // Define the route URL here
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: itemId,
                bank_code: bankCode // Send the selected bank code
            },
            success: function(response) {
                window.location.reload();
                // You can also update the status in the UI or redirect to another page
            },
            error: function(xhr, status, error) {
                // Handle any errors here
                alert('An error occurred: ' + error);
            }
        });
    });
</script>
@endsection
