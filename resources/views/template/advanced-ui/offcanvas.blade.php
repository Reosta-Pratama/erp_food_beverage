@extends('layouts.app', [
    'title' => 'Offcanvas'
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
                        <a href="javascript:void(0);">Advanced UI</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Offcanvas</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Default Offcanvas -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default Offcanvas</div>
                </div>
                <div class="card-body">
                    <a
                        class="btn btn-primary mb-1"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasExample"
                        role="button"
                        aria-controls="offcanvasExample">
                        Link with href
                    </a>

                    <button
                        class="btn btn-primary mb-1"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasExample"
                        aria-controls="offcanvasExample">
                        Button with data-bs-target
                    </button>

                    <div
                        class="offcanvas offcanvas-start"
                        tabindex="-1"
                        id="offcanvasExample"
                        aria-labelledby="offcanvasExampleLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div>
                                Some text as placeholder. In real life you can have the elements you have
                                chosen. Like, text, images, lists, etc.
                            </div>
                            <div class="dropdown mt-3">
                                <button
                                    class="btn btn-secondary dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown">
                                    Dropdown button
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#">Action</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Another action</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default Offcanvas -->

        <!-- Body Scrolling -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Body Scrolling</div>
                </div>
                <div class="card-body">
                    <button
                        class="btn btn-primary"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasScrolling"
                        aria-controls="offcanvasScrolling">Enable body scrolling
                    </button>

                    <div class="offcanvas offcanvas-start" data-bs-scroll="true"
                        data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling"
                        aria-labelledby="offcanvasScrollingLabel">
                        <div class="offcanvas-header border-bottom border-block-end-dashed">
                            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Notifications</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
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
                </div>
            </div>
        </div>
        <!-- Body Scrolling -->

        <!-- Static Backdrop -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Static Backdrop</div>
                </div>
                <div class="card-body">
                    <button
                        class="btn btn-primary"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#staticBackdrop"
                        aria-controls="staticBackdrop">
                        Toggle static offcanvas
                    </button>

                    <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1"
                        id="staticBackdrop" aria-labelledby="staticBackdropLabel">
                        <div class="offcanvas-header border-bottom border-block-end-dashed">
                            <h5 class="offcanvas-title" id="staticBackdropLabel">Notifications</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
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
                </div>
            </div>
        </div>
        <!-- Static Backdrop -->

        <!-- Body Scrolling & Backdrop -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Body Scrolling & Backdrop</div>
                </div>
                <div class="card-body">
                    <button
                        class="btn btn-primary"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBothOptions"
                        aria-controls="offcanvasWithBothOptions">Enable both scrolling &amp; backdrop
                    </button>

                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1"
                        id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                        <div class="offcanvas-header border-bottom border-block-end-dashed">
                            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Notifications</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
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
                </div>
            </div>
        </div>
        <!-- Body Scrolling & Backdrop -->

        <!-- Placement -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Placement</div>
                </div>
                <div class="card-body">
                    <button
                        class="btn btn-primary mb-1"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasTop"
                        aria-controls="offcanvasTop">Toggle top offcanvas</button>
                    <div
                        class="offcanvas offcanvas-top"
                        tabindex="-1"
                        id="offcanvasTop"
                        aria-labelledby="offcanvasTopLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasTopLabel">Offcanvas top</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            ...
                        </div>
                    </div>

                    <button
                        class="btn btn-primary mb-1"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight"
                        aria-controls="offcanvasRight">Toggle right offcanvas</button>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel1">
                        <div class="offcanvas-header border-bottom border-block-end-dashed">
                            <h5 class="offcanvas-title" id="offcanvasRightLabel1">Notifications
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
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

                    <button
                        class="btn btn-primary mb-1"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasBottom"
                        aria-controls="offcanvasBottom">Toggle bottom offcanvas</button>
                    <div
                        class="offcanvas offcanvas-bottom"
                        tabindex="-1"
                        id="offcanvasBottom"
                        aria-labelledby="offcanvasBottomLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasBottomLabel">Offcanvas bottom
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body small">
                            ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Placement -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection