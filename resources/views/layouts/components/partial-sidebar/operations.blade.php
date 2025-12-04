{{-- Operations (Admin + Operator) --}}
@if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
    <li class="menu-category">
        <span class="category-name">Operations</span>
    </li>

    {{-- Products & Inventory --}}
    @hasPermission('products.manage')
        <li class="menu-item has-sub {{ request()->routeIs('products.*', 'inventory.bom.*', 'inventory.recipes.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-package"></i>
                </span>
                <span class="menu-label">Products & Materials</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Products & Materials</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Products</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Product Categories</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- BOM & Recipe --}}
    @hasPermission('bom.manage')
        <li class="menu-item has-sub {{ request()->routeIs('inventory.bom.*', 'inventory.recipes.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-file-text"></i>
                </span>
                <span class="menu-label">BOM & Recipes</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">BOM & Recipes</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Bill of Materials (BOM)</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Recipes</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Warehouse Management --}}
    @hasPermission('warehouse.manage')
        <li class="menu-item has-sub {{ request()->routeIs('inventory.warehouses.*', 'inventory.locations.*', 'inventory.stock.*', 'inventory.movements.*', 'inventory.lots.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-building-warehouse"></i>
                </span>
                <span class="menu-label">Warehouse</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Warehouse</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Warehouses</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Warehouse Locations</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Stock Movements</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Stock Tracking --}}
    @hasPermission('stock.view')
        <li class="menu-item has-sub {{ request()->routeIs('inventory.stock.*', 'inventory.lots.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-clipboard-list"></i>
                </span>
                <span class="menu-label">Stock & Lot Tracking</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Stock & Lot Tracking</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Stock Levels</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Lot & Batch</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Expiry Tracking</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Low Stock Alert</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Production Planning --}}
    @hasPermission('production_planning.manage')
        <li class="menu-item has-sub {{ request()->routeIs('production.planning.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-calendar-stats"></i>
                </span>
                <span class="menu-label">Production Planning</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Production Planning</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Production Plans (MRP)</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Work Orders & Batches --}}
    @hasPermission('work_orders.manage')
        <li class="menu-item has-sub {{ request()->routeIs('production.work-orders.*', 'production.batches.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-cpu"></i>
                </span>
                <span class="menu-label">Production Execution</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Production Execution</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Work Orders</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Production Batches</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Quality Control --}}
    @hasPermission('quality_control.manage')
        <li class="menu-item has-sub {{ request()->routeIs('quality-control.*') ? 'active' : '' }}">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-shield-check"></i>
                </span>
                <span class="menu-label">Quality Control</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Quality Control</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">QC Inspections</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Quality Assurance (Sprint 17) --}}
    @hasPermission('quality_assurance.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-checklist"></i>
                </span>
                <span class="menu-label">Quality Assurance</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Quality Assurance</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Sanitation & Hygiene</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Daily Audits</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Certification Documents</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">NCR</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">CAPA</a>
                </li>
            </ul>
        </li>
    @endhasPermission

    {{-- Maintenance (Sprint 21) --}}
    @hasPermission('maintenance.manage')
        <li class="menu-item has-sub">
            <a href="javascript:void(0);" class="menu-link">
                <span class="menu-svg">
                    <i class="ti ti-tools"></i>
                </span>
                <span class="menu-label">Maintenance</span>
                <i class="ri-arrow-right-s-line menu-icon"></i>
            </a>
            <ul class="menu-item-child child1">
                <li class="menu-item menu-label1">
                    <a href="javascript:void(0)">Maintenance</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Machines</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Maintenance Schedule</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Maintenance History</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Maintenance Requests</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Downtime Tracking</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Spare Parts</a>
                </li>
            </ul>
        </li>
    @endhasPermission
@endif