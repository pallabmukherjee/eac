<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.leave.dashboard') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-gauge"></i>
        </span>
        <span class="pc-mtext">Dashboard</span>
    </a>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.leave.employee.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-user"></i>
        </span>
        <span class="pc-mtext">Leave Data</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>Reports</label>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.leave.report.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-report-analytics"></i>
        </span>
        <span class="pc-mtext">Leave Reports</span>
    </a>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.leave.calendar.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-calendar"></i>
        </span>
        <span class="pc-mtext">Leave Calendar</span>
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
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.leave.type.list') }}">Employee Type</a></li>
    </ul>
</li>
</ul>
</li>
