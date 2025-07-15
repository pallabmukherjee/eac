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
                        <div class="col-sm-6">
                            <h4>Pensioner {{ \Carbon\Carbon::parse($otherBill->created_at)->format('F') }} {{ \Carbon\Carbon::parse($otherBill->created_at)->format('Y') }} Other Bill</h4>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <a href="{{ route('superadmin.pension.other.pdf', $otherBill->bill_id) }}" class="btn btn-primary" target="_blank">View PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>{{ $otherBill->details }}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Srl</th>
                                        <th>pensioner_name</th>
                                        <th>PPO</th>
                                        <th>Employee Code</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pensionersReport as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->pensionerDetails->pensioner_name }}</td>
                                            <td>{{ $item->pensionerDetails->ppo_number }}</td>
                                            <td>{{ $item->pensionerDetails->employee_code }}</td>
                                            <td>{{ $item->amount }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Srl</th>
                                        <th>pensioner_name</th>
                                        <th>PPO</th>
                                        <th>Employee Code</th>
                                        <th>Amount</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ form-element ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection
