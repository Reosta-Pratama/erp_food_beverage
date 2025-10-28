@extends('layouts.app', [
    'title' => 'Badge'
])

@section('styles')
@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">UI Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Badge</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Default -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge bg-primary">Primary</span>
                    <span class="badge bg-secondary">Secondary</span>
                    <span class="badge bg-success">Success</span>
                    <span class="badge bg-danger">Danger</span>
                    <span class="badge bg-warning">Warning</span>
                    <span class="badge bg-info">Info</span>
                    <span class="badge bg-light text-dark">Light</span>
                    <span class="badge bg-dark text-white">Dark</span>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Light -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Light Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge bg-primary-transparent">Primary</span>
                    <span class="badge bg-secondary-transparent">Secondary</span>
                    <span class="badge bg-success-transparent">Success</span>
                    <span class="badge bg-danger-transparent">Danger</span>
                    <span class="badge bg-warning-transparent">Warning</span>
                    <span class="badge bg-info-transparent">Info</span>
                    <span class="badge bg-light-transparent text-dark">Light</span>
                    <span class="badge bg-dark-transparent">Dark</span>
                </div>
            </div>
        </div>
        <!-- Light -->
    </div>

    <div class="row gx-4">
        <!-- Pill -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pill Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill bg-primary">Primary</span>
                    <span class="badge rounded-pill bg-secondary">Secondary</span>
                    <span class="badge rounded-pill bg-success">Success</span>
                    <span class="badge rounded-pill bg-danger">Danger</span>
                    <span class="badge rounded-pill bg-warning">Warning</span>
                    <span class="badge rounded-pill bg-info">Info</span>
                    <span class="badge rounded-pill bg-light text-dark">Light</span>
                    <span class="badge rounded-pill bg-dark">Dark</span>
                </div>
            </div>
        </div>
        <!-- Pill -->

        <!-- Light Pill -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Light Pill Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill bg-primary-transparent">Primary</span>
                    <span class="badge rounded-pill bg-secondary-transparent">Secondary</span>
                    <span class="badge rounded-pill bg-success-transparent">Success</span>
                    <span class="badge rounded-pill bg-danger-transparent">Danger</span>
                    <span class="badge rounded-pill bg-warning-transparent">Warning</span>
                    <span class="badge rounded-pill bg-info-transparent">Info</span>
                    <span class="badge rounded-pill bg-light-transparent text-dark">Light</span>
                    <span class="badge rounded-pill bg-dark-transparent">Dark</span>
                </div>
            </div>
        </div>
        <!-- Light Pill -->
    </div>

    <div class="row gx-4">
        <!-- Gradient -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Gradient Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge bg-primary-gradient">Primary</span>
                    <span class="badge bg-secondary-gradient">Secondary</span>
                    <span class="badge bg-success-gradient">Success</span>
                    <span class="badge bg-danger-gradient">Danger</span>
                    <span class="badge bg-warning-gradient">Warning</span>
                    <span class="badge bg-info-gradient">Info</span>
                    <span class="badge bg-light-gradient text-dark">Light</span>
                    <span class="badge bg-dark-gradient">Dark</span>
                </div>
            </div>
        </div>
        <!-- Gradient -->

        <!-- Gradient Pill -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Gradient Pill Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill bg-primary-gradient">Primary</span>
                    <span class="badge rounded-pill bg-secondary-gradient">Secondary</span>
                    <span class="badge rounded-pill bg-success-gradient">Success</span>
                    <span class="badge rounded-pill bg-danger-gradient">Danger</span>
                    <span class="badge rounded-pill bg-warning-gradient">Warning</span>
                    <span class="badge rounded-pill bg-info-gradient">Info</span>
                    <span class="badge rounded-pill bg-light-gradient text-dark">Light</span>
                    <span class="badge rounded-pill bg-dark-gradient text-white">Dark</span>
                </div>
            </div>
        </div>
        <!-- Gradient Pill -->
    </div>

    <div class="row gx-4">
        <!-- Button -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Button Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary my-1 me-2">
                        Notifications
                        <span class="badge ms-2 bg-success">4</span>
                    </button>
                    <button type="button" class="btn btn-secondary my-1 me-2">
                        Notifications
                        <span class="badge ms-2 bg-primary">7</span>
                    </button>
                    <button type="button" class="btn btn-success my-1 me-2">
                        Notifications
                        <span class="badge ms-2 bg-danger">12</span>
                    </button>
                    <button type="button" class="btn btn-info my-1 me-2">
                        Notifications
                        <span class="badge ms-2 bg-warning">32</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Button -->

        <!-- Outline Button -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Outline Button Badges</div>
                </div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary my-1 me-2">
                        Notifications
                        <span class="badge ms-2">4</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary my-1 me-2">
                        Notifications
                        <span class="badge ms-2">7</span>
                    </button>
                    <button type="button" class="btn btn-outline-success my-1 me-2">
                        Notifications
                        <span class="badge ms-2">12</span>
                    </button>
                    <button type="button" class="btn btn-outline-info my-1 me-2">
                        Notifications
                        <span class="badge ms-2">32</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Outline Button -->
    </div>

    <div class="row gx-4">
        <div class="col-xl-6">
            <div class="row">
                <!-- Positioned -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Positioned Badges</div>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-4">
                            <button type="button" class="btn btn-primary position-relative">
                                Inbox
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    99+
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-secondary position-relative">
                                Profile
                                <span
                                    class="position-absolute top-80 start-100 translate-middle p-2 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </button>
                            <span class="avatar">
                                <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                                <span
                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </span>
                            <span class="avatar avatar-rounded">
                                <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                                <span
                                    class="position-absolute top-80 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </span>
                            <span class="avatar avatar-rounded">
                                <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge bg-secondary rounded-pill shadow-lg">1000+
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Positioned -->

                <!-- Custom -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Default Badges</div>
                        </div>
                        <div class="card-body d-flex flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-5 flex-wrap">
                                <div>
                                    <span
                                        class="badge bg-outline-secondary custom-badge fs-15 d-inline-flex align-items-center">
                                        <i class="ti ti-flame me-1"></i>
                                        Hot
                                    </span>
                                </div>
                                <div>
                                    <span class="icon-badge">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                            d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
                                        </svg>
                                        <span class="badge rounded-pill bg-success">14</span>
                                    </span>
                                </div>
                                <div>
                                    <span class="badge border bg-light text-default custom-badge">
                                        <i class="ti ti-eye me-2 d-inline-block"></i>
                                        13.2k
                                    </span>
                                </div>
                                <div>
                                    <span class="text-badge">
                                        <span class="text fs-18">Inbox</span>
                                        <span class="badge rounded-pill bg-success">32</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Custom -->
            </div>
        </div>

        <!-- Heading -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Heading Badges</div>
                </div>
                <div class="card-body">
                    <h1>Example heading
                        <span class="badge bg-primary">New</span>
                    </h1>
                    <h2>Example heading
                        <span class="badge bg-primary">New</span>
                    </h2>
                    <h3>Example heading
                        <span class="badge bg-primary">New</span>
                    </h3>
                    <h4>Example heading
                        <span class="badge bg-primary">New</span>
                    </h4>
                    <h5>Example heading
                        <span class="badge bg-primary">New</span>
                    </h5>
                    <h6>Example heading
                        <span class="badge bg-primary">New</span>
                    </h6>
                </div>
            </div>
        </div>
        <!-- Heading -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection