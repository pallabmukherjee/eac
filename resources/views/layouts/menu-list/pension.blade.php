<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.pension.dashboard') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-gauge"></i>
        </span>
        <span class="pc-mtext">Dashboard</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.pension.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-user"></i>
        </span>
        <span class="pc-mtext">Pensioner Data</span>
    </a>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.pension.report.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-report-analytics"></i>
        </span>
        <span class="pc-mtext">Pension Bill</span>
    </a>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.pension.other.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-file"></i>
        </span>
        <span class="pc-mtext">Other Bill</span>
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
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.pension.ropa.index') }}">Ropa year</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.pension.signs.index') }}">Signs</a></li>
    </ul>
</li>

</ul>
</li>
