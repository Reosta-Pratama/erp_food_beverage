{{-- Reports & Analytics --}}
<li class="menu-category">
    <span class="category-name">Reports</span>
</li>

<li class="menu-item has-sub">
    <a href="javascript:void(0);" class="menu-link">
        <span class="menu-svg">
            <i class="ti ti-chart-bar"></i>
        </span>
        <span class="menu-label">Reports & Analytics</span>
        <i class="ri-arrow-right-s-line menu-icon"></i>
    </a>
    <ul class="menu-item-child child1">
        <li class="menu-item menu-label1">
            <a href="javascript:void(0)">Reports & Analytics</a>
        </li>

        {{-- Production Reports (Admin + Operator) --}}
        @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
            @hasPermission('production_reports.view')
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Production Reports</a>
                </li>
            @endhasPermission
            @hasPermission('stock_reports.view')
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Stock Reports</a>
                </li>
            @endhasPermission
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">Quality Reports</a>
            </li>
        @endif

        {{-- Finance & HR Reports (Admin + Finance_HR) --}}
        @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
            @hasPermission('finance_reports.view')
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Financial Reports</a>
                </li>
            @endhasPermission
            @hasPermission('hr_reports.view')
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">HR Reports</a>
                </li>
            @endhasPermission
        @endif
    </ul>
</li>