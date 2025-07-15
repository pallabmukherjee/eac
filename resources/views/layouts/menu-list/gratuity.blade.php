<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
<li class="pc-item">
    <a href="{{ route('dashboard') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-gauge"></i>
        </span>
        <span class="pc-mtext">Dashboard</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-user-plus"></i>
        </span>
        <span class="pc-mtext">Create Gratuity</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.list') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-user"></i>
        </span>
        <span class="pc-mtext">Gratuity List</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.bill.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-file"></i>
        </span>
        <span class="pc-mtext">Gratuity Bill</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.loan.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-circles-three-plus"></i>
        </span>
        <span class="pc-mtext">Create Loan</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.request.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-git-pull-request"></i>
        </span>
        <span class="pc-mtext">Gratuity Application</span>
    </a>
</li>

{{-- <li class="pc-item">
    <a href="{{ route('superadmin.gratuity.report.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-report-analytics"></i>
        </span>
        <span class="pc-mtext">Gratuity Report</span>
    </a>
</li> --}}

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.request.pending') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-calendar-stats"></i>
        </span>
        <span class="pc-mtext">Gratuity Status</span>
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
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.gratuity.ropa.index') }}">Ropa Year</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.gratuity.financial.index') }}">Financial Year</a></li>
    </ul>
</li>

</ul>
</li>
