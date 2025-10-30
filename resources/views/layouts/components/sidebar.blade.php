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

                <!-- Menu category -->
                <li class="menu-category">
                    <span class="category-name">General</span>
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
                                    <a href="" class="menu-link">Inputs</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Check & Radios</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Input Group</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Form Select</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Range Slider</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Input Masks</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">File Uploads</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Date & Time Picker</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Color Pickers</a>
                                </li>
                            </ul>
                        </li>  
                        <li class="menu-item">
                            <a href="" class="menu-link">Form Advanceds</a>
                        </li>
                        <li class="menu-item">
                            <a href="" class="menu-link">Form Layouts</a>
                        </li>
                    </ul>
                </li>

                <!-- Menu category -->
                <li class="menu-category">
                    <span class="category-name">main</span>
                </li>

                <!-- Menu item -->
                <li class="menu-item has-sub">
                    <a href="javascript:void(0);" class="menu-link">
                        <span class="menu-svg">
                            <i class="ti ti-shield"></i>
                        </span>
                        <span class="menu-label">Admin</span>
                        <i class="ri-arrow-right-s-line menu-icon"></i>
                    </a>
                    <ul class="menu-item-child child1">
                        <li class="menu-item menu-label1">
                            <a href="javascript:void(0)">Admin</a>
                        </li>
                        <li class="menu-item">
                            <a href="" class="menu-link">Dashboard</a>
                        </li>
                        <li class="menu-item has-sub">
                            <a href="javascript:void(0);" class="menu-link">
                                Appoinments
                                <i class="ri-arrow-right-s-line menu-icon"></i>
                            </a>
                            <ul class="menu-item-child child2">
                                <li class="menu-item">
                                    <a href="" class="menu-link">Calendar</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">View All</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Add Appoinment</a>
                                </li>
                                <li class="menu-item">
                                    <a href="" class="menu-link">Edit Appoinment</a>
                                </li>
                            </ul>
                        </li>   
                    </ul>
                </li>

                <!-- Menu category -->
                <li class="menu-category">
                    <span class="category-name">Pages</span>
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
                            <a href="" class="menu-link">Coming Soon</a>
                        </li>
                        <li class="menu-item">
                            <a href="" class="menu-link">Create Password</a>
                        </li> 
                        <li class="menu-item">
                            <a href="" class="menu-link">Lock Screen</a>
                        </li>   
                        <li class="menu-item">
                            <a href="" class="menu-link">Reset Password</a>
                        </li> 
                        <li class="menu-item">
                            <a href="" class="menu-link">Sign Up</a>
                        </li>
                        <li class="menu-item">
                            <a href="" class="menu-link">Sign In</a>
                        </li>
                        <li class="menu-item">
                            <a href="" class="menu-link">Two Step Verication</a>
                        </li>
                        <li class="menu-item">
                            <a href="" class="menu-link">Under Maintenance</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>