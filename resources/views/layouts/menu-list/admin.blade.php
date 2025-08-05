<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
@php
    $userRole = \App\Models\UserRole::where('user_id', auth()->id())->first();
@endphp

<li class="pc-item">
    <a href="{{ route('admin.dashboard') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ph-duotone ph-gauge"></i>
        </span>
        <span class="pc-mtext">Dashboard</span>
    </a>
</li>

@if ($userRole && ($userRole->payment == 1 || $userRole->receipt || $userRole->contra || $userRole->journal))
<li class="pc-item">
    <a href="{{ route('admin.account') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-building-bank"></i>
        </span>
        <span class="pc-mtext">Accounts</span>
    </a>
</li>
@endif

@if ($userRole && $userRole->leave == 1)
<li class="pc-item">
    <a href="{{ route('superadmin.leave.employee.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-airplane-tilt"></i>
        </span>
        <span class="pc-mtext">Leave</span>
    </a>
</li>
@endif

@if ($userRole && $userRole->pension == 1)
<li class="pc-item">
    <a href="{{ route('superadmin.pension.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-chalkboard-teacher"></i>
        </span>
        <span class="pc-mtext">Pension</span>
    </a>
</li>
@endif

@if ($userRole && $userRole->gratuity == 1)
<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-clipboard-list"></i>
        </span>
        <span class="pc-mtext">Gratuity</span>
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