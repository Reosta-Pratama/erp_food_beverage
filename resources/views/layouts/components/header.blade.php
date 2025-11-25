<div class="header-box">
    <!-- Header Container -->
    <header
        id="header"
        class="app-header">
        <!-- Header Container -->
        <div class="header-container container-fluid">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-logo">
                        <a href="javascript:void(0)">
                            <img src="{{ asset('assets/images/logo/logo-transparent.webp') }}" alt="Logo {{ config('app.name') }}">
                        </a>
                    </div>

                    <div class="header-toggle">
                        <button data-bs-toggle="mobile-sidebar" class="btn btn-icon btn-wave">
                            <i class="ti ti-category-2"></i>
                        </button>
                    </div>

                    <h2 class="header-title">
                        {{ $title }}
                    </h2>
                </div>

                <div class="header-right">
                    <div class="menu">
                        <ul class="menu-list">
                            <li class="menu-item modal-search">
                                <a
                                    class="menu-link"
                                    href="javascript:void(0)"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalSearch">
                                    <i class="ti ti-search"></i>
                                </a>
                            </li>

                            <li class="menu-item offcanvas-notification">
                                <a
                                    class="menu-link"
                                    data-bs-toggle="offcanvas"
                                    href="#offcanvasNotification"
                                    role="button"
                                    aria-controls="offcanvasNotification">
                                    <i class="header-icon-bell ti ti-bell"></i>
                                    <span class="header-icon-pulse bg-secondary rounded pulse pulse-secondary"></span>
                                </a>
                            </li>

                            <li class="menu-item toggle-fullscreen">
                                <a
                                    id="fullscreen-toggle"
                                    class="menu-link"
                                    href="javascript:void(0)">
                                    <i class="ti ti-window-maximize"></i>
                                </a>
                            </li>

                            <li class="menu-item dropdown-profile position-relative">
                                <a
                                    id="dropdownMenuProfile"
                                    class="menu-link avatar avatar-sm avatar-font"
                                    href="javascript:void(0)"
                                    role="button" 
                                    data-bs-toggle="dropdown" data-bs-offset="0,10"
                                    data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <span>{{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}</span>
                                </a>

                                <ul
                                    class="profile-dropdown dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdownMenuProfile">
                                    <li class="info-profile">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar avatar-md avatar-font">
                                                <span>{{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}</span>
                                            </span>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-0 fs-14">{{ Auth::user()->full_name }}</h6>
                                                <span class="text-muted fs-12">{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="menu-profile">
                                        <ul>
                                            <li>
                                                <a class="active" href="">
                                                    <i class="ti ti-id"></i>
                                                    <span>My Account</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <i class="ti ti-folders"></i>
                                                    File Manager
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <i class="ti ti-settings"></i>
                                                    Setting
                                                </a>
                                            </li>
                                            <li>
                                                <a href="">
                                                    <i class="ti ti-help"></i>
                                                    Support
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    
                                    <li class="sign-out-profile">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm btn-wave w-100">
                                                Sign Out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Container -->
    </header>
    <!-- Header Container -->

    <!-- Modal Search -->
    <div id="modalSearch" 
        class="modal fade"
        tabindex="-1"
        aria-labelledby="modalSearch"
        data-bs-keyboard="false"
        aria-hidden="true">
        <!-- Scrollable modal -->
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2">Search Bar</h6>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="header-search-bar">
                        <input type="text" 
                            class="form-control" 
                            id="header-search" 
                            placeholder="Search anything here ..." 
                            spellcheck=false autocomplete="off" autocapitalize="off">
                        <a href="javascript:void(0);" class="header-search-icon border-0">
                            <i class="ri-search-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search -->

    <!-- Offcanvas Notification -->
    <div id="offcanvasNotification" 
        class="offcanvas offcanvas-end" tabindex="-1" 
        aria-labelledby="offcanvasNotification">
        <div class="offcanvas-header border-bottom border-block-end-dashed">
            <h5 class="offcanvas-title" id="offcanvasNotification">Notifications</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div>
                <ul class="list-group list-group-flush mb-0">
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-primary avatar-rounded">NW</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">New Website Created
                                    <span class="badge bg-light text-muted float-end">20 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>30 mins ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-secondary avatar-rounded">JS</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">User Registered
                                    <span class="badge bg-light text-muted float-end">19 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>1 hour ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-info avatar-rounded">EM</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Email Verified
                                    <span class="badge bg-light text-muted float-end">18 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>2 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-success avatar-rounded">PR</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Profile Updated
                                    <span class="badge bg-light text-muted float-end">17 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>3 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-danger avatar-rounded">ER</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Error Reported
                                    <span class="badge bg-light text-muted float-end">16 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>5 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-warning avatar-rounded">WR</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Warning Issued
                                    <span class="badge bg-light text-muted float-end">15 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>7 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-dark avatar-rounded">BL</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">User Blacklisted
                                    <span class="badge bg-light text-muted float-end">14 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>10 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-secondary avatar-rounded">PM</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Payment Made
                                    <span class="badge bg-light text-muted float-end">13 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>12 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-info avatar-rounded">IN</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Invoice Sent
                                    <span class="badge bg-light text-muted float-end">12 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>14 hours ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-success avatar-rounded">SU</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">Subscription Upgraded
                                    <span class="badge bg-light text-muted float-end">11 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>1 day ago
                                </span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-primary avatar-rounded">UP</span>
                            </div>
                            <div class="flex-fill">
                                <p class="fw-medium mb-0">User Promoted
                                    <span class="badge bg-light text-muted float-end">10 Mar 2024</span>
                                </p>
                                <span class="fs-12 text-muted">
                                    <i class="ri-time-line align-middle me-1 d-inline-block"></i>2 days ago
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Offcanvas Notification -->
</div>