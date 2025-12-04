{{-- System Administration (Admin Only) --}}
@isAdmin
    <li class="menu-category">
        <span class="category-name">System</span>
    </li>

    {{-- User Management --}}
    <li class="menu-item has-sub {{ request()->routeIs('admin.users.*', 'admin.roles.*', 'admin.permissions.*', 'admin.logs.*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="menu-link">
            <span class="menu-svg">
                <i class="ti ti-users"></i>
            </span>
            <span class="menu-label">User Management</span>
            <i class="ri-arrow-right-s-line menu-icon"></i>
        </a>
        <ul class="menu-item-child child1">
            <li class="menu-item menu-label1">
                <a href="javascript:void(0)">User Management</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">Users</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <a href="{{ route('admin.roles.index') }}" class="menu-link">Roles</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.permissions.index') }}" class="menu-link">Permissions</a>
            </li>
            <li class="menu-item has-sub {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-link">
                    Logs
                    <i class="ri-arrow-right-s-line menu-icon"></i>
                </a>
                <ul class="menu-item-child child2">
                    <li class="menu-item {{ request()->routeIs('admin.logs.activity.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.logs.activity.index') }}" class="menu-link">Activity Logs</a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('admin.logs.audit.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.logs.audit.index') }}" class="menu-link">Audit Logs</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    {{-- Settings --}}
    <li class="menu-item has-sub {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="menu-link">
            <span class="menu-svg">
                <i class="ti ti-settings"></i>
            </span>
            <span class="menu-label">Settings</span>
            <i class="ri-arrow-right-s-line menu-icon"></i>
        </a>
        <ul class="menu-item-child child1">
            <li class="menu-item menu-label1">
                <a href="javascript:void(0)">Settings</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.settings.company-profile.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.company-profile.index') }}" class="menu-link">Company Profile</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.settings.uom.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.uom.index') }}" class="menu-link">Units of Measure</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.settings.currencies.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.currencies.index') }}" class="menu-link">Currencies</a>
            </li>
            <li class="menu-item {{ request()->routeIs('admin.settings.tax-rates.*') ? 'active' : '' }}">
                <a href="{{ route('admin.settings.tax-rates.index') }}" class="menu-link">Tax Rates</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Document Formats</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Workflow Configuration</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Email & Notification</a>
            </li>
        </ul>
    </li>
@endisAdmin