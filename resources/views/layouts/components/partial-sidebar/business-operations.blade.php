{{-- Business Operations --}}
<li class="menu-category">
    <span class="category-name">Business</span>
</li>

{{-- Purchase (Admin + Finance_HR) --}}
@if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
    @hasPermission('suppliers.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-shopping-cart"></i>
                </span>
                <span class="menu-label">Purchase</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Purchase</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Suppliers</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Purchase Orders</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Purchase Receipts</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Purchase Returns</a>
                </li>
            </ul>
        </li>
    @endhasPermission
@endif

{{-- Sales (Admin + Operator) --}}
@if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
    @hasPermission('customers.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-currency-dollar"></i>
                </span>
                <span class="menu-label">Sales</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Sales</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Customers</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Sales Orders</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Sales Returns</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Logistics (Admin + Operator) --}}
    @hasPermission('logistics.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-truck-delivery"></i>
                </span>
                <span class="menu-label">Logistics & Delivery</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Logistics & Delivery</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Deliveries</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Delivery Routes</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Fleet Management</a>
                </li>
            </ul>
        </li>
    @endhasPermission
@endif