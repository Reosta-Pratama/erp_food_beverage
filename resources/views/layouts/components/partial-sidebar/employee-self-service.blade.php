{{-- Employee Self Service (All Authenticated Users) --}}
<li class="menu-category">
    <span class="category-name">My Workspace</span>
</li>

<li class="menu-item {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
    <a href="{{ route('employee.attendance') }}" class="menu-link">
        <span class="menu-svg">
            <i class="ti ti-calendar-check"></i>
        </span>
        <span class="menu-label">My Attendance</span>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('employee.leaves') ? 'active' : '' }}">
    <a href="{{ route('employee.leaves') }}" class="menu-link">
        <span class="menu-svg">
            <i class="ti ti-plane-departure"></i>
        </span>
        <span class="menu-label">My Leave</span>
    </a>
</li>

<li class="menu-item {{ request()->routeIs('employee.payslips') ? 'active' : '' }}">
    <a href="{{ route('employee.payslips') }}" class="menu-link">
        <span class="menu-svg">
            <i class="ti ti-receipt-2"></i>
        </span>
        <span class="menu-label">My Payslip</span>
    </a>
</li>