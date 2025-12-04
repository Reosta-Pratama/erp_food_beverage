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

                                <!-- Profile Dropdown -->
                                @include('layouts.components.partial-header.profile-dropdown')
                                <!-- Profile Dropdown -->
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
    @include('layouts.components.partial-header.modal-search')
    <!-- Modal Search -->

    <!-- Offcanvas Notification -->
    @include('layouts.components.partial-header.offcanvas-notif')
    <!-- Offcanvas Notification -->
</div>