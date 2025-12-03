<aside
    id="sidebar"
    class="app-sidebar">
    <!-- Main Sidebar Header -->
    <div class="sidebar-header">
        <a href="javascript:void(0)">
            <div class="img-sidebar-open">
                <img src="{{ asset('assets/images/logo/logo-transparent.webp') }}" alt="Logo {{ config('app.name') }}">
                <h1>{{ config('app.name') }}</h1>
            </div>
            <img class="img-sidebar-close" src="{{ asset('assets/images/logo/logo-transparent.webp') }}" alt="Logo {{ config('app.name') }}">
        </a>

        <div class="sidebar-toggle">
            <button data-bs-toggle="sidebar" class="btn btn-icon btn-wave">
                <i class="ti ti-chevrons-left"></i>
            </button>
        </div>
    </div>

    <!-- Main Sidebar Content -->
    <div id="sidebar-content" class="sidebar-content">
        <!-- Nav Sidebar -->
        <nav class="menu">
            <ul class="menu-list">

                <!-- Dashboard -->
                <li class="menu-item {{ request()->is('admin/dashboard', 'operator/dashboard', 'finance-hr/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-smart-home"></i>
                        </span>
                        <span class="menu-label">Main Dashboard</span>
                    </a>
                </li>

                <!-- ========================================
                     EMPLOYEE SELF SERVICE (ESS)
                ========================================= -->
                    <li class="menu-category">
                        <span class="category-name">Employee Self Service</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('employee.attendance') }}" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-calendar-check"></i>
                            </span>
                            <span class="menu-label">Attendance</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('employee.leaves') }}" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-plane-departure"></i>
                            </span>
                            <span class="menu-label">Leave</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('employee.payslips') }}" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-receipt-2"></i>
                            </span>
                            <span class="menu-label">Payslip</span>
                        </a>
                    </li>


                <!-- ========================================
                     HUMAN RESOURCE MANAGEMENT (HRM)
                ========================================= -->
                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
                    <li class="menu-category">
                        <span class="category-name">Human Resources</span>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-user-circle"></i>
                            </span>
                            <span class="menu-label">Employee</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Employee</a>
                            </li>
                            @canRead('employees')
                                <li class="menu-item">
                                    <a href="{{ route('hrm.employees.index') }}" class="menu-link">Employee Directory</a>
                                </li>
                            @endcanRead
                            @canRead('departments')
                                <li class="menu-item">
                                    <a href="{{ route('hrm.departments.index') }}" class="menu-link">Departments</a>
                                </li>
                            @endcanRead
                            @canRead('positions')
                                <li class="menu-item">
                                    <a href="{{ route('hrm.positions.index') }}" class="menu-link">Positions</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

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
                            @canRead('attendance')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Attendance</a>
                                </li>
                            @endcanRead
                            @canRead('shifts')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Shifts</a>
                                </li>
                            @endcanRead
                            @canRead('leaves')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Leave Requests</a>
                                </li>
                            @endcanRead
                            @canRead('leave_types')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Leave Types</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

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
                            @canRead('payroll')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Payroll Processing</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-certificate"></i>
                            </span>
                            <span class="menu-label">Training</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Training</a>
                            </li>
                            @canRead('training_programs')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Training Programs</a>
                                </li>
                            @endcanRead
                            @canRead('performance_reviews')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Performance Reviews</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     INVENTORY MANAGEMENT
                ========================================= -->
                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
                    <li class="menu-category">
                        <span class="category-name">Inventory</span>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-package"></i>
                            </span>
                            <span class="menu-label">Product Management</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Product Management</a>
                            </li>
                            @canRead('products.manage')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Products</a>
                                </li>
                            @endcanRead
                            @canRead('product_categories')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Product Categories</a>
                                </li>
                            @endcanRead
                            @canRead('bom')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Bill of Materials (BOM)</a>
                                </li>
                            @endcanRead
                            @canRead('recipes')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Recipes</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

                    <li class="menu-item has-sub">
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
                            @canRead('warehouses')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Warehouses</a>
                                </li>
                            @endcanRead
                            @canRead('warehouse_locations')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Warehouse Locations</a>
                                </li>
                            @endcanRead
                            @canRead('inventory')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Stock Tracking</a>
                                </li>
                            @endcanRead
                            @canRead('stock_movements')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Stock Movements</a>
                                </li>
                            @endcanRead
                            @canRead('lots')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Lot & Batch</a>
                                </li>
                            @endcanRead
                            @canRead('expiry_tracking')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Expiry Tracking</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     PRODUCTION MANAGEMENT
                ========================================= -->
                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
                    <li class="menu-category">
                        <span class="category-name">Production</span>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-cpu"></i>
                            </span>
                            <span class="menu-label">Production Planning</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Production Planning</a>
                            </li>
                            @canRead('production_planning')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Production Plans (MRP)</a>
                                </li>
                            @endcanRead
                            @canRead('work_orders')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Work Orders</a>
                                </li>
                            @endcanRead
                            @canRead('batches')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Production Batches</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

                    <li class="menu-item has-sub">
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
                            @canRead('quality_control')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">QC Inspections</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     QUALITY ASSURANCE
                ========================================= -->
                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
                    <li class="menu-category">
                        <span class="category-name">Quality Assurance</span>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-checklist"></i>
                            </span>
                            <span class="menu-label">QA Compliance</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">QA Compliance</a>
                            </li>
                            @canRead('sanitation')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Sanitation & Hygiene</a>
                                </li>
                            @endcanRead
                            @canRead('daily_audits')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Daily Audits</a>
                                </li>
                            @endcanRead
                            @canRead('certifications')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Certification Documents</a>
                                </li>
                            @endcanRead
                            @canRead('ncr')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Non-Conformance Reports (NCR)</a>
                                </li>
                            @endcanRead
                            @canRead('capa')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">CAPA</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     BUSINESS MANAGEMENT
                ========================================= -->
                <li class="menu-category">
                    <span class="category-name">Business Operations</span>
                </li>

                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
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
                            @canRead('suppliers')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Suppliers</a>
                                </li>
                            @endcanRead
                            @canRead('purchase_orders')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Purchase Orders</a>
                                </li>
                            @endcanRead
                            @canRead('purchase_receipts')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Purchase Receipts</a>
                                </li>
                            @endcanRead
                            @canRead('purchase_returns')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Purchase Returns</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
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
                            @canRead('customers')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Customers</a>
                                </li>
                            @endcanRead
                            @canRead('sales_orders')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Sales Orders</a>
                                </li>
                            @endcanRead
                            @canRead('sales_returns')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Sales Returns</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-truck-delivery"></i>
                            </span>
                            <span class="menu-label">Logistics</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Logistics</a>
                            </li>
                            @canRead('deliveries')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Deliveries</a>
                                </li>
                            @endcanRead
                            @canRead('delivery_routes')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Delivery Routes</a>
                                </li>
                            @endcanRead
                            @canRead('vehicles')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Fleet Management</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     FINANCE
                ========================================= -->
                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
                    <li class="menu-category">
                        <span class="category-name">Finance</span>
                    </li>

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
                            @canRead('chart_of_accounts')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Chart of Accounts</a>
                                </li>
                            @endcanRead
                            @canRead('journal_entries')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Journal Entries</a>
                                </li>
                            @endcanRead
                            @canRead('accounts_payable')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Accounts Payable</a>
                                </li>
                            @endcanRead
                            @canRead('accounts_receivable')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Accounts Receivable</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>

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
                            @canRead('payments')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Payments</a>
                                </li>
                            @endcanRead
                            @canRead('product_costing')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Product Costing</a>
                                </li>
                            @endcanRead
                            @canRead('budgets')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Budgeting</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     MAINTENANCE
                ========================================= -->
                @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
                    <li class="menu-category">
                        <span class="category-name">Maintenance</span>
                    </li>

                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-tools"></i>
                            </span>
                            <span class="menu-label">Machine Maintenance</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Machine Maintenance</a>
                            </li>
                            @canRead('machines')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Machines</a>
                                </li>
                            @endcanRead
                            @canRead('maintenance_schedules')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Maintenance Schedule</a>
                                </li>
                            @endcanRead
                            @canRead('maintenance_history')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Maintenance History</a>
                                </li>
                            @endcanRead
                            @canRead('maintenance_requests')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Maintenance Requests</a>
                                </li>
                            @endcanRead
                            @canRead('downtime_tracking')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Downtime Tracking</a>
                                </li>
                            @endcanRead
                            @canRead('spare_parts')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Spare Parts</a>
                                </li>
                            @endcanRead
                            @canRead('technicians')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Technicians</a>
                                </li>
                            @endcanRead
                        </ul>
                    </li>
                @endif

                <!-- ========================================
                     ANNOUNCEMENTS & COMMUNICATION
                ========================================= -->
                <li class="menu-category">
                    <span class="category-name">Communication</span>
                </li>

                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-speakerphone"></i>
                        </span>
                        <span class="menu-label">Announcements</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Announcements</a>
                        </li>
                        @canRead('announcements')
                            <li class="menu-item">
                                <a href="javascript:void(0)" class="menu-link">Announcements</a>
                            </li>
                        @endcanRead
                        @canRead('meetings')
                            <li class="menu-item">
                                <a href="javascript:void(0)" class="menu-link">Meetings</a>
                            </li>
                        @endcanRead
                        @canRead('broadcast_messages')
                            <li class="menu-item">
                                <a href="javascript:void(0)" class="menu-link">Broadcast Messages</a>
                            </li>
                        @endcanRead
                    </ul>
                </li>

                <!-- ========================================
                     REPORTS
                ========================================= -->
                <li class="menu-category">
                    <span class="category-name">Reports & Analytics</span>
                </li>

                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-chart-bar"></i>
                        </span>
                        <span class="menu-label">Reports</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Reports</a>
                        </li>
                        @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'operator')
                            @canRead('reports')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Production Reports</a>
                                </li>
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Stock Reports</a>
                                </li>
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Quality Reports</a>
                                </li>
                            @endcanRead
                        @endif
                        @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
                            @canRead('reports')
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Financial Reports</a>
                                </li>
                                <li class="menu-item">
                                    <a href="javascript:void(0)" class="menu-link">Employee Performance</a>
                                </li>
                            @endcanRead
                        @endif
                    </ul>
                </li>

                <!-- ========================================
                     SYSTEM ADMINISTRATION
                ========================================= -->
                @isAdmin
                    <li class="menu-category">
                        <span class="category-name">System</span>
                    </li>

                    <li class="menu-item has-sub">
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
                            <li class="menu-item">
                                <a href="{{ route('admin.users.index') }}" class="menu-link">Users</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.roles.index') }}" class="menu-link">Roles</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.permissions.index') }}" class="menu-link">Permissions</a>
                            </li>
                            <li class="menu-item has-sub">
                                <a href="javascript:void(0);" class="menu-link">
                                    Logs
                                    <i class="ri-arrow-right-s-line menu-icon"></i>
                                </a>
                                <ul class="menu-item-child child2">
                                    <li class="menu-item">
                                        <a href="{{ route('admin.logs.activity.index') }}" class="menu-link">Activity Logs</a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('admin.logs.audit.index') }}" class="menu-link">Audit Logs</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-sub">
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
                            <li class="menu-item">
                                <a href="{{ route('admin.settings.company-profile.index') }}" class="menu-link">Company Profile</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.settings.uom.index') }}" class="menu-link">Units of Measure</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('admin.settings.currencies.index') }}" class="menu-link">Currencies</a>
                            </li>
                            <li class="menu-item">
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

            </ul>
        </nav>
    </div>
</aside>