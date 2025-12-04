{{-- Finance (Admin + Finance_HR) --}}
@if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
    <li class="menu-category">
        <span class="category-name">Finance</span>
    </li>

    @hasPermission('accounts.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-report-money"></i>
                </span>
                <span class="menu-label">Accounting</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Accounting</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Chart of Accounts</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Journal Entries</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Accounts Payable</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Accounts Receivable</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    @hasPermission('payments.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-coins"></i>
                </span>
                <span class="menu-label">Payments & Costing</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Payments & Costing</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Payments</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Product Costing</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Budgeting</a>
                </li>
            </ul>
        </li>
    @endhasPermission
@endif