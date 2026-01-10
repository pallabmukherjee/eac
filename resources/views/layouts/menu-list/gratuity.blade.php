<li class="pc-item pc-caption">
    <label>Navigation</label>
</li>
<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.report.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-gauge"></i>
        </span>
        <span class="pc-mtext">Dashboard</span>
    </a>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.list') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ti ti-user"></i>
        </span>
        <span class="pc-mtext">Gratuity Data</span>
    </a>
</li>

@php
    $currentRouteName = Route::currentRouteName();
    $isBillRoute = Illuminate\Support\Str::is('superadmin.gratuity.bill.*', $currentRouteName);

    $isApproved = false;
    if ($isBillRoute) {
        if (request()->input('status') === 'approved') {
            $isApproved = true;
        }
        // Use 'else if' to avoid DB query on index page
        else if (in_array($currentRouteName, ['superadmin.gratuity.bill.edit', 'superadmin.gratuity.bill.show', 'superadmin.gratuity.bill.approve'])) {
            $route_bill_id = request()->route('bill_id');
            if ($route_bill_id) {
                $bill = \App\Models\GratuityBill::where('bill_id', $route_bill_id)->first();
                if ($bill && $bill->status === 2) { // 2 = Approved
                    $isApproved = true;
                }
            }
        }
    }

    $isPendingActive = $isBillRoute && !$isApproved;
    $isAcceptedActive = $isBillRoute && $isApproved;
    $parentMenuActive = $isBillRoute;
@endphp

<li class="pc-item pc-hasmenu {{ $parentMenuActive ? 'pc-trigger active' : '' }}">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-git-pull-request"></i>
        </span>
        <span class="pc-mtext">Gratuity Applications</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu" style="{{ $parentMenuActive ? 'display: block;' : '' }}">
        <li class="pc-item {{ $isPendingActive ? 'active' : '' }}"><a class="pc-link no-auto-active"
                href="{{ route('superadmin.gratuity.bill.index') }}">Pending</a></li>
        <li class="pc-item {{ $isAcceptedActive ? 'active' : '' }}"><a class="pc-link no-auto-active"
                href="{{ route('superadmin.gratuity.bill.index', ['status' => 'approved']) }}">Accepted</a></li>
    </ul>
</li>

<li class="pc-item">
    <a href="{{ route('superadmin.gratuity.loan.index') }}" class="pc-link">
        <span class="pc-micon">
        <i class="ph-duotone ph-circles-three-plus"></i>
        </span>
        <span class="pc-mtext">Employee loan</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>Reports</label>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-report"></i>
        </span>
        <span class="pc-mtext">Gratuity Reports</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.gratuity.report.yearly') }}">Yearly Total Paid</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.gratuity.report.monthly') }}">Monthly Total Paid</a></li>
        <li class="pc-item"><a class="pc-link" href="{{ route('superadmin.gratuity.report.employee.wise') }}">Employee-wise Report</a></li>
    </ul>
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
