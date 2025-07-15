<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.account.account') }}" class="pc-link">
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
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.paymentvoucher.index') }}">Payment Voucher</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.receiptvoucher.index') }}">Receipt Voucher</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.contravoucher.index') }}">Contra Voucher</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.journalvoucher.index') }}">Journal Voucher</a></li>
    </ul>
</li>

<li class="pc-item pc-caption">
    <label>Pending Actions</label>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-report"></i>
        </span>
        <span class="pc-mtext">Pending Actions</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.pendingaction.index') }}">Payment Deduction queue</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.pendingaction.uncleared') }}">Uncleared Cheque</a></li>
    </ul>
</li>


<li class="pc-item pc-caption">
    <label>Report</label>
</li>
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
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.paymentvoucher.list') }}">Payment Voucher</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.receiptvoucher.list') }}">Receipt Voucher</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.contravoucher.list') }}">Contra Voucher</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.journalvoucher.list') }}">Journal Voucher</a></li>
    </ul>
</li>

<li class="pc-item pc-caption">
    <label>Setup</label>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon"> <i class="ph-duotone ph-tree-structure"></i></span>
        <span class="pc-mtext">Setup</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item pc-hasmenu">
            <a href="#!" class="pc-link">Payment Head<span class="pc-arrow"><i
                        data-feather="chevron-right"></i></span></a>
            <ul class="pc-submenu">
                <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.majorhead.index') }}">Major Head</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.minorhead.index') }}">Minor Head</a></li>
                <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.detailedhead.index') }}">Detailed Head</a></li>
            </ul>
        </li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.beneficiary.index') }}">Beneficiary</a></li>
    </ul>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.account.edit.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-chalkboard-teacher"></i>
        </span>
        <span class="pc-mtext">Edit Reqest</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>Settings</label>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-settings"></i>
        </span>
        <span class="pc-mtext">Settings</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.pan.index') }}">Pan Card</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.account.report.reportstore') }}">Yearly Report</a></li>
    </ul>
</li>


</ul>
</li>
