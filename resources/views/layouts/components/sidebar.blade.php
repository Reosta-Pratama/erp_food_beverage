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

                <!-- Menu item -->
                <li class="menu-item {{ request()->is('admin/dashboard', 'operator/dashboard', 'finance-hr/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" 
                    class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-smart-home"></i>
                        </span>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>

                <!-- Menu category -->
                <li class="menu-category">
                    <span class="category-name">Management</span>
                </li>

                <!-- Menu item -->
                @isAdmin
                    <li class="menu-item has-sub">
                        <a href="javascript:void(0);" class="menu-link">
                            <span class="menu-svg">
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="menu-label">Users</span>
                            <i class="ri-arrow-right-s-line menu-icon"></i>
                        </a>
                        <ul class="menu-item-child child1">
                            <li class="menu-item menu-label1">
                                <a href="javascript:void(0)">Users</a>
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
                                        <a href="{{ route('admin.logs.audit.index') }}" class="menu-link">Audit Log</a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('admin.logs.activity.index') }}" class="menu-link">Activity Log</a>
                                    </li>
                                </ul>
                            </li> 
                            <li class="menu-item">
                                <a href="{{ route('admin.users.index') }}" class="menu-link">User Management</a>
                            </li>
                        </ul>
                    </li>
                @endisAdmin

                <!-- Menu category -->
                <li class="menu-category">
                    <span class="category-name">Template</span>
                </li>

                <!-- Menu item -->
                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-carousel-horizontal"></i>
                        </span>
                        <span class="menu-label">Basic UI</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Basic UI</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.alerts') }}" class="menu-link">Alert</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.badge') }}" class="menu-link">Bagde</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.breadcrumb') }}" class="menu-link">Breadcrumb</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.buttonGroup') }}" class="menu-link">Button Group</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.buttons') }}" class="menu-link">Buttons</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.cards') }}" class="menu-link">Cards</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.dropdowns') }}" class="menu-link">Dropdowns</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.imageFigures') }}" class="menu-link">Image Figures</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.linkInteraction') }}" class="menu-link">Link Interactions</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.listGroup') }}" class="menu-link">List Group</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.navs') }}" class="menu-link">Navs</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.objectFit') }}" class="menu-link">Object Fit</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.pagination') }}" class="menu-link">Pagination</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.popovers') }}" class="menu-link">Popovers</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.progress') }}" class="menu-link">Progress</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.spinners') }}" class="menu-link">Spinner</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.toasts') }}" class="menu-link">Toasts</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.tooltips') }}" class="menu-link">Tooltips</a>
                        </li> 
                        <li class="menu-item">
                            <a href="{{ route('template.typography') }}" class="menu-link">Typography</a>
                        </li> 
                    </ul>
                </li>

                <!-- Menu item -->
                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-layers-intersect"></i>
                        </span>
                        <span class="menu-label">Advanced UI</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Advanced UI</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.modals') }}" class="menu-link">Modals</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.offcanvas') }}" class="menu-link">Offcanvas</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.placeholder') }}" class="menu-link">Placeholder</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.scrollspy') }}" class="menu-link">Scrollspy</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.sweetAlert') }}" class="menu-link">Sweet Alert</a>
                        </li>
                    </ul>
                </li>

                <!-- Menu item -->
                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-file-text"></i>
                        </span>
                        <span class="menu-label">Forms</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Forms</a>
                        </li>
                        <li class="menu-item has-sub">
                            <a href="javascript:void(0);" class="menu-link">
                                Form Elements
                                <i class="ri-arrow-right-s-line menu-icon"></i>
                            </a>
                            <ul class="menu-item-child child2">
                                <li class="menu-item">
                                    <a href="{{ route('template.inputs') }}" class="menu-link">Inputs</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.checkRadios') }}" class="menu-link">Check & Radios</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.inputGroup') }}" class="menu-link">Input Group</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.select') }}" class="menu-link">Form Select</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.rangeSlider') }}" class="menu-link">Range Slider</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.inputMask') }}" class="menu-link">Input Masks</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.fileUploads') }}" class="menu-link">File Uploads</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.datetimePicker') }}" class="menu-link">Date & Time Picker</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('template.colorPicker') }}" class="menu-link">Color Pickers</a>
                                </li>
                            </ul>
                        </li>  
                        <li class="menu-item">
                            <a href="{{ route('template.formAdvanced') }}" class="menu-link">Form Advanceds</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.formLayout') }}" class="menu-link">Form Layouts</a>
                        </li>
                    </ul>
                </li>

                <!-- Menu item -->
                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-shield-check"></i>
                        </span>
                        <span class="menu-label">Authentication</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Authentication</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.createPassword') }}" class="menu-link">Create Password</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.lockScreen') }}" class="menu-link">Lock Screen</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.resetPassword') }}" class="menu-link">Reset Password</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.signIn') }}" class="menu-link">Sign In</a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('template.signUp') }}" class="menu-link">Sign Up</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>