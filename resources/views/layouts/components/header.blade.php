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

                    <nav class="menu">
                        <ul class="menu-list">
                            <li class="menu-item">
                                <a href="javascript:void(0)" class="menu-link">
                                    <span class="menu-text">Home</span>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <span class="menu-text">My Account</span>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="" class="menu-link">
                                    <span class="menu-text">Support</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
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

                            <li class="menu-item offcanvas-cart">
                                <a
                                    class="menu-link"
                                    data-bs-toggle="offcanvas"
                                    href="#offcanvasCart"
                                    role="button"
                                    aria-controls="offcanvasCart">
                                    <i class="ti ti-shopping-cart"></i>
                                    <span class="header-icon-badge badge bg-primary rounded-pill" id="cart-icon-badge">5</span>
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
                                    class="menu-link avatar avatar-sm"
                                    href="javascript:void(0)"
                                    role="button" 
                                    data-bs-toggle="dropdown" data-bs-offset="0,10"
                                    aria-expanded="false">
                                    <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                </a>

                                <ul
                                    class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdownMenuProfile">
                                    <li class="info-profile">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-0 fs-14">Mr. ChatGPT</h6>
                                                <span class="text-muted fs-12">expert.chatgpt@gmail.com</span>
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
                                        <button type="button" 
                                            class="btn btn-danger btn-sm btn-wave w-100">
                                            Sign Out
                                        </button>
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

    <!-- Offcanvas Cart -->
    <div id="offcanvasCart" 
        class="offcanvas offcanvas-end" tabindex="-1" 
        aria-labelledby="offcanvasCart">
        <div class="offcanvas-header border-bottom border-block-end-dashed">
            <h5 class="offcanvas-title d-flex align-items-center gap-2" id="offcanvasCart">
                Shopping Cart
                <span class="badge bg-secondary-transparent fs-12" id="cart-data">5</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 header-offcanvas-cart">
            <div class="list-cart">
                <ul class="list-group list-group-flush mb-0">
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-primary avatar-rounded">BB</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Boca Bola</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : Kike</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 3 ✕ Rp50</p>
                                    </div>
                                </div>

                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted">
                                        <i class="ti ti-trash"></i>
                                    </span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp30<span
                                        class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp49</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-secondary avatar-rounded">SC</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Smart Chair</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : Urban</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 1 ✕ Rp89</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp89<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp99</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-danger avatar-rounded">TS</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Thunder Speaker</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : Sonic</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 2 ✕ Rp70</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp140<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp160</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-info avatar-rounded">KP</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Keyboard Pro</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : Nexa</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 1 ✕ Rp45</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp45<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp55</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-warning avatar-rounded">HW</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Home Warmer</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : CozyLife</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 1 ✕ Rp60</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp60<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp70</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-success avatar-rounded">HL</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Hand Lamp</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : Shine</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 2 ✕ Rp35</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp70<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp80</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-primary avatar-rounded">WF</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Water Filter</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : AquaSafe</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 1 ✕ Rp55</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp55<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp65</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-secondary avatar-rounded">ET</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Eco Thermos</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : GreenCup</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 1 ✕ Rp40</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp40<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp50</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-danger avatar-rounded">MP</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Mini Projector</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : BeamGo</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 1 ✕ Rp98</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp98<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp120</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="avatar avatar-md bg-info avatar-rounded">FS</span>
                            </div>
                            <div class="flex-fill d-flex justify-content-between align-items-center">
                                <div class="fw-medium mb-0">
                                    <span>Food Sealer</span>
                                    <div class="d-flex gap-2 text-truncate fs-12 fw-normal text-muted lh-1 mt-1">
                                        <div class="fs-12">Brand : SealPro</div>
                                        <div class="vr"></div>
                                        <p class="mb-0 header-cart-text text-truncate">Qty : 2 ✕ Rp48</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="fs-14 text-muted"><i class="ti ti-trash"></i></span>
                                    <h6 class="fw-medium mb-0 text-nowrap">Rp96<span class="text-decoration-line-through text-muted fw-normal ms-1 fs-13 d-inline-block">Rp110</span></h6>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>

            <div class="checkout-cart">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="fw-medium fs-14">Order Total :</div>
                    <h6 class="mb-0">Rp740</h6>
                </div>

                <div class="text-center d-grid">
                    <a
                        href="javascript:void(0)"
                        class="btn btn-primary btn-wave waves-effect waves-light">
                        Proceed to checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Offcanvas Cart -->

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