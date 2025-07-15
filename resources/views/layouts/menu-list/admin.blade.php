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

</ul>
</li>
