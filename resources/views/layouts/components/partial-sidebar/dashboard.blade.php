{{-- Dashboard --}}
<li class="menu-item {{ request()->routeIs('dashboard', 'admin.dashboard', 'operator.dashboard', 'finance_hr.dashboard') ? 'active' : '' }}">
    <a href="{{ route('dashboard') }}" class="menu-link">
        <span class="menu-svg">
            <i class="ti ti-smart-home"></i>
        </span>
        <span class="menu-label">Dashboard</span>
    </a>
</li>