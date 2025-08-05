<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
@php
    $userRole = \App\Models\UserRole::where('user_id', auth()->id())->first();
@endphp
<li class="pc-item">
    <a href="{{ route('dashboard') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ph-duotone ph-gauge"></i>
        </span>
        <span class="pc-mtext">Dashboard</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>Transaction</label>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ph-duotone ph-list-bullets"></i>
        </span>
        <span class="pc-mtext">Transaction</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        @if ($userRole && $userRole->payment == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.paymentvoucher.index') }}">Payment Voucher</a></li>
        @endif

        @if ($userRole && $userRole->receipt == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.receiptvoucher.index') }}">Receipt Voucher</a></li>
        @endif

        @if ($userRole && $userRole->contra == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.contravoucher.index') }}">Contra Voucher</a></li>
        @endif

        @if ($userRole && $userRole->journal == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.journalvoucher.index') }}">Journal Voucher</a></li>
        @endif
    </ul>
</li>



<li class="pc-item pc-caption">
    <label>Search and enquiry</label>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-search"></i>
        </span>
        <span class="pc-mtext">Search and enquiry</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        @if ($userRole && $userRole->payment == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.paymentvoucher.list') }}">Payment Voucher</a></li>
        @endif

        @if ($userRole && $userRole->receipt == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.receiptvoucher.list') }}">Receipt Voucher</a></li>
        @endif

        @if ($userRole && $userRole->contra == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.contravoucher.list') }}">Contra Voucher</a></li>
        @endif

        @if ($userRole && $userRole->journal == 1)
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.journalvoucher.list') }}">Journal Voucher</a></li>
        @endif
    </ul>
</li>

@if ($userRole && ($userRole->payment == 1 || $userRole->receipt || $userRole->contra || $userRole->journal))
<li class="pc-item">
    <a href="{{ route('superadmin.account.beneficiary.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-clipboard-list"></i>
        </span>
        <span class="pc-mtext">Beneficiary</span>
    </a>
</li>
@endif

@if ($userRole && $userRole->report == 1)
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-report-analytics"></i>
        </span>
        <span class="pc-mtext">Report</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.ledgerhead.index') }}">Trial Balance</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.contraSummary') }}">Contra Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.journalSummary') }}">Journal Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.receiptSummary') }}">Receipt Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.paymentSummaryBenificiary') }}">Payment Summary Party or Benificiary Wise</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.balanceSheet') }}">Balance Sheet</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.chequeOnline') }}">Cheque or Online Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.majorHeadIncomeExpenditure') }}">Major Head Income & Expenditure Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.minorHeadIncomeExpenditure') }}">Minor Head Income & Expenditure Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.ledgerSummary') }}">Ledger Summary</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.paymentSummaryAccountHead') }}">Payment Summary Account Head Wise</a></li>
    </ul>
</li>
@endif

</ul>
</li>
