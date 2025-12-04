{{-- Human Resources (Admin + Finance_HR) --}}
@if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
    <li class="menu-category">
        <span class="category-name">Human Resources</span>
    </li>

    {{-- Employee Management --}}
    @hasPermission('employees.manage')
        <li class="menu-item has-sub {{ request()->routeIs('hrm.employees.*', 'hrm.departments.*', 'hrm.positions.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-user-circle"></i>
                </span>
                <span class="menu-label">Employee Management</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Employee Management</a>
                </li>
                <li class="menu-item {{ request()->routeIs('hrm.employees.*') ? 'active' : '' }}">
                    <a href="{{ route('hrm.employees.index') }}" class="menu-link">Employee Directory</a>
                </li>
                <li class="menu-item {{ request()->routeIs('hrm.departments.*') ? 'active' : '' }}">
                    <a href="{{ route('hrm.departments.index') }}" class="menu-link">Departments</a>
                </li>
                <li class="menu-item {{ request()->routeIs('hrm.positions.*') ? 'active' : '' }}">
                    <a href="{{ route('hrm.positions.index') }}" class="menu-link">Positions</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Attendance & Leave (Sprint 18-19) --}}
    @hasPermission('attendance.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-clock-check"></i>
                </span>
                <span class="menu-label">Attendance & Leave</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Attendance & Leave</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Attendance Records</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Shift Management</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Leave Requests</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Leave Types</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Payroll (Sprint 18) --}}
    @hasPermission('payroll.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-cash"></i>
                </span>
                <span class="menu-label">Payroll</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Payroll</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Payroll Processing</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Salary History</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Training & Performance (Sprint 20) --}}
    <li class="menu-item has-sub">
        <a href="javascript:void(0);" class="menu-link">
            <span class="menu-svg">
                <i class="ti ti-certificate"></i>
            </span>
            <span class="menu-label">Training & Performance</span>
            <i class="ri-arrow-right-s-line menu-icon"></i>
        </a>
        <ul class="menu-item-child child1">
            <li class="menu-item menu-label1">
                <a href="javascript:void(0)">Training & Performance</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Training Programs</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Performance Reviews</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Certifications</a>
            </li>
        </ul>
    </li>
@endif