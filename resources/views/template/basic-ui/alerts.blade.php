@extends('layouts.app')

@section('styles')
@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-1">Alerts</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">UI Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Alerts</li>
                </ol>
            </nav>
        </div>
        <div class="btn-list">
            <button class="btn btn-primary-light btn-wave">
                <i class="bi bi-file-earmark-pdf-fill align-middle me-1"></i>
                Export Report
            </button>
            <button class="btn btn-secondary-light btn-wave me-0">
                <i class="bi bi-file-earmark-excel-fill align-middle me-1"></i>
                Export Data
            </button>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Basic -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Basic Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Basic -->

        <!-- Live Example -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Live Example</div>
                </div>
                <div class="card-body">
                    <div id="liveAlertPlaceholder"></div>
                    <button type="button" class="btn btn-primary" id="liveAlertBtn">Show live alert</button>
                </div>
            </div>
        </div>
        <!-- Live Example -->

        <!-- Default -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary" role="alert">
                        A simple primary alert—check it out!
                    </div>
                    <div class="alert alert-secondary" role="alert">
                        A simple secondary alert—check it out!
                    </div>
                    <div class="alert alert-success" role="alert">
                        A simple success alert—check it out!
                    </div>
                    <div class="alert alert-danger" role="alert">
                        A simple danger alert—check it out!
                    </div>
                    <div class="alert alert-warning" role="alert">
                        A simple warning alert—check it out!
                    </div>
                    <div class="alert alert-info" role="alert">
                        A simple info alert—check it out!
                    </div>
                    <div class="alert alert-light" role="alert">
                        A simple light alert—check it out!
                    </div>
                    <div class="alert alert-dark" role="alert">
                        A simple dark alert—check it out!
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Solid Colored -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Solid Colored Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-solid-primary alert-dismissible fade show">
                        A simple solid primary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-secondary alert-dismissible fade show">
                        A simple solid secondary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-success alert-dismissible fade show">
                        A simple solid success alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-danger alert-dismissible fade show">
                        A simple solid danger alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-warning alert-dismissible fade show">
                        A simple solid warning alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-info alert-dismissible fade show">
                        A simple solid info alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-light alert-dismissible fade show">
                        A simple solid light alert—check it out!
                        <button
                            type="button"
                            class="btn-close text-default"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-dark alert-dismissible fade show text-white">
                        A simple solid dark alert—check it out!
                        <button
                            type="button"
                            class="btn-close text-white"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Solid Colored -->

        <!-- Rounded Solid -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Solid Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-solid-primary rounded-pill alert-dismissible fade show">
                        A simple solid rounded primary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-solid-secondary rounded-pill alert-dismissible fade show">
                        A simple solid rounded secondary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-success rounded-pill alert-dismissible fade show">
                        A simple solid rounded success alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-info rounded-pill alert-dismissible fade show">
                        A simple solid rounded info alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-warning rounded-pill alert-dismissible fade show">
                        A simple solid rounded warning alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-danger rounded-pill alert-dismissible fade show">
                        A simple solid rounded danger alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Solid -->

        <!-- Rounded Outline -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Outline Alert</div>
                </div>
                <div class="card-body">
                    <div
                        class="alert alert-outline-primary rounded-pill alert-dismissible fade show">
                        A simple outline rounded primary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-outline-secondary rounded-pill alert-dismissible fade show">
                        A simple outline rounded secondary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-outline-success rounded-pill alert-dismissible fade show">
                        A simple outline rounded success alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-info rounded-pill alert-dismissible fade show">
                        A simple outline rounded info alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-outline-warning rounded-pill alert-dismissible fade show">
                        A simple outline rounded warning alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-outline-danger rounded-pill alert-dismissible fade show">
                        A simple outline rounded danger alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Outline -->

        <!-- Outline -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Outline Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-outline-primary alert-dismissible fade show">
                        A simple outline primary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-secondary alert-dismissible fade show">
                        A simple outline secondary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-success alert-dismissible fade show">
                        A simple outline success alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-danger alert-dismissible fade show">
                        A simple outline danger alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-warning alert-dismissible fade show">
                        A simple outline warning alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-info alert-dismissible fade show">
                        A simple outline info alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-light alert-dismissible fade show">
                        A simple outline light alert—check it out!
                        <button
                            type="button"
                            class="btn-close text-default"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-outline-dark alert-dismissible fade show">
                        A simple outline dark alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Outline -->

        <!-- Links in -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Links in Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary" role="alert">
                        A simple primary alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-secondary" role="alert">
                        A simple secondary alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-success" role="alert">
                        A simple success alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-danger" role="alert">
                        A simple danger alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-warning" role="alert">
                        A simple warning alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-info" role="alert">
                        A simple info alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-light" role="alert">
                        A simple light alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                    <div class="alert alert-dark" role="alert">
                        A simple dark alert with
                        <a href="javascript:void(0);" class="alert-link">an example link</a>. Give it a click if you like.
                    </div>
                </div>
            </div>
        </div>
        <!-- Links in -->

        <!-- Default With Shadows -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default With Shadows Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary shadow-sm">A simple primary alert with small shadow—check it out!</div>
                    <div class="alert alert-primary shadow">A simple primary alert with normal shadow—check it out!</div>
                    <div class="alert alert-primary shadow-lg">A simple primary alert with large shadow—check it out!</div>
                    <div class="alert alert-secondary shadow-sm">A simple secondary alert with small shadow—check it out!</div>
                    <div class="alert alert-secondary shadow">A simple secondary alert with normal shadow—check it out!</div>
                    <div class="alert alert-secondary shadow-lg">A simple secondary alert with large shadow—check it out!</div>
                </div>
            </div>
        </div>
        <!-- Default With Shadows -->

        <!-- Solid With Shadows -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Solid With Shadows Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-solid-primary shadow-sm alert-dismissible fade show">
                        A simple solid primary alert with small shadow—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-primary shadow alert-dismissible fade show">
                        A simple solid primary alert with normal shadow—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-primary shadow-lg alert-dismissible fade show">
                        A simple solid primary alert with large shadow—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-secondary shadow-sm alert-dismissible fade show">
                        A simple solid secondary alert with small shadow—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-secondary shadow alert-dismissible fade show">
                        A simple solid secondary alert with normal shadow—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-solid-secondary shadow-lg alert-dismissible fade show">
                        A simple solid secondary alert with large shadow—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Solid With Shadows -->

        <!-- Rounded Default -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Default Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary rounded-pill alert-dismissible fade show">
                        A simple rounded primary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-secondary rounded-pill alert-dismissible fade show">
                        A simple rounded secondary alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-success rounded-pill alert-dismissible fade show">
                        A simple rounded success alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                        A simple rounded danger alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-warning rounded-pill alert-dismissible fade show">
                        A simple rounded warning alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-info rounded-pill alert-dismissible fade show">
                        A simple rounded info alert—check it out!
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Default -->

        <!-- Rounded With Custom Close Button -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded With Custom Close Button Alert</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary rounded-pill alert-dismissible fade show">
                        A simple rounded primary alert—check it out!
                        <button
                            type="button"
                            class="btn-close custom-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-secondary rounded-pill alert-dismissible fade show">
                        A simple rounded secondary alert—check it out!
                        <button
                            type="button"
                            class="btn-close custom-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-success rounded-pill alert-dismissible fade show">
                        A simple rounded success alert—check it out!
                        <button
                            type="button"
                            class="btn-close custom-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                        A simple rounded danger &nbsp; alert—check it out!
                        <button
                            type="button"
                            class="btn-close custom-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-warning rounded-pill alert-dismissible fade show">
                        A simple rounded warning alert—check it out!
                        <button
                            type="button"
                            class="btn-close custom-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div class="alert alert-info rounded-pill alert-dismissible fade show">
                        A simple rounded info alert—check it out!
                        <button
                            type="button"
                            class="btn-close custom-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded With Custom Close Button -->

        <!-- Customize With SVG -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Customize With SVG Alert</div>
                </div>
                <div class="card-body">
                    <div
                        class="alert svg-primary alert-primary alert-dismissible fade show custom-alert-icon shadow-sm"
                        role="alert">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-alert-square-rounded"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                            d="M12 2l.642 .005l.616 .017l.299 .013l.579 .034l.553 .046c4.687 .455 6.65 2.333 7.166 6.906l.03 .29l.046 .553l.041 .727l.006 .15l.017 .617l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.455 4.687 -2.333 6.65 -6.906 7.166l-.29 .03l-.553 .046l-.727 .041l-.15 .006l-.617 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.687 -.455 -6.65 -2.333 -7.166 -6.906l-.03 -.29l-.046 -.553l-.041 -.727l-.006 -.15l-.017 -.617l-.004 -.318v-.648l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.455 -4.687 2.333 -6.65 6.906 -7.166l.29 -.03l.553 -.046l.727 -.041l.15 -.006l.617 -.017c.21 -.003 .424 -.005 .642 -.005zm.01 13l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z"/></svg>
                        A customized primary alert with an icon
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert svg-secondary alert-secondary alert-dismissible fade show custom-alert-icon shadow-sm"
                        role="alert">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                            d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z"/></svg>
                        A customized secondary alert with an icon
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert svg-warning alert-warning alert-dismissible fade show custom-alert-icon shadow-sm"
                        role="alert">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                            d="M12 1.67c.955 0 1.845 .467 2.39 1.247l.105 .16l8.114 13.548a2.914 2.914 0 0 1 -2.307 4.363l-.195 .008h-16.225a2.914 2.914 0 0 1 -2.582 -4.2l.099 -.185l8.11 -13.538a2.914 2.914 0 0 1 2.491 -1.403zm.01 13.33l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -7a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z"/></svg>
                        A customized warning alert with an icon
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert svg-danger alert-danger alert-dismissible fade show custom-alert-icon shadow-sm"
                        role="alert">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="icon icon-tabler icons-tabler-filled icon-tabler-alert-octagon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                            d="M14.897 1a4 4 0 0 1 2.664 1.016l.165 .156l4.1 4.1a4 4 0 0 1 1.168 2.605l.006 .227v5.794a4 4 0 0 1 -1.016 2.664l-.156 .165l-4.1 4.1a4 4 0 0 1 -2.603 1.168l-.227 .006h-5.795a3.999 3.999 0 0 1 -2.664 -1.017l-.165 -.156l-4.1 -4.1a4 4 0 0 1 -1.168 -2.604l-.006 -.227v-5.794a4 4 0 0 1 1.016 -2.664l.156 -.165l4.1 -4.1a4 4 0 0 1 2.605 -1.168l.227 -.006h5.793zm-2.887 14l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z"/></svg>
                        A customized danger alert with an icon
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Customize With SVG -->

        <!-- Icon -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Icon Alert</div>
                </div>
                <div class="card-body">
                    <div
                        class="alert alert-primary svg-primary d-flex align-items-center"
                        role="alert">
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
                            class="flex-shrink-0 me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                        </svg>
                        <div>
                            An example alert with an icon
                        </div>
                    </div>
                    <div
                        class="alert alert-success svg-success d-flex align-items-center"
                        role="alert">
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
                            class="flex-shrink-0 me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M9 12l2 2l4 -4"/>
                        </svg>
                        <div>
                            An example success alert with an icon
                        </div>
                    </div>
                    <div
                        class="alert alert-warning svg-warning d-flex align-items-center"
                        role="alert">
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
                            class="flex-shrink-0 me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4"/><path
                            d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/><path d="M12 16h.01"/>
                        </svg>
                        <div>
                            An example warning alert with an icon
                        </div>
                    </div>
                    <div
                        class="alert alert-danger svg-danger d-flex align-items-center"
                        role="alert">
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
                            class="flex-shrink-0 me-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                            d="M12.802 2.165l5.575 2.389c.48 .206 .863 .589 1.07 1.07l2.388 5.574c.22 .512 .22 1.092 0 1.604l-2.389 5.575c-.206 .48 -.589 .863 -1.07 1.07l-5.574 2.388c-.512 .22 -1.092 .22 -1.604 0l-5.575 -2.389a2.036 2.036 0 0 1 -1.07 -1.07l-2.388 -5.574a2.036 2.036 0 0 1 0 -1.604l2.389 -5.575c.206 -.48 .589 -.863 1.07 -1.07l5.574 -2.388a2.036 2.036 0 0 1 1.604 0z"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                        </svg>
                        <div>
                            An example danger alert with an icon
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Icon -->

        <!-- Images -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Images Alert</div>
                </div>
                <div class="card-body">
                    <div
                        class="alert alert-img alert-primary alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                        </div>
                        <div>A simple primary alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-secondary alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                        </div>
                        <div>A simple secondary alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-success alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/8.jpg') }}" alt="img">
                        </div>
                        <div>A simple success alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-danger alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/4.jpg') }}" alt="img">
                        </div>
                        <div>A simple danger alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-warning alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                        </div>
                        <div>A simple warning alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-info alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/5.jpg') }}" alt="img">
                        </div>
                        <div>A simple info alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-light alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/6.jpg') }}" alt="img">
                        </div>
                        <div>A simple light alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-dark alert-dismissible fase show rounded-pill flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3 avatar-rounded">
                            <img src="{{ asset('assets/images/avatar/7.jpg') }}" alt="img">
                        </div>
                        <div>A simple dark alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x text-muted"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Images -->

        <!-- Image Different Size -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Image Different Size Alert</div>
                </div>
                <div class="card-body">
                    <div
                        class="alert alert-img alert-primary alert-dismissible fase show flex-wrap"
                        role="alert">
                        <div class="avatar avatar-xs me-3">
                            <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="img">
                        </div>
                        <div>A simple primary alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-secondary alert-dismissible fase show flex-wrap"
                        role="alert">
                        <div class="avatar avatar-sm me-3">
                            <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="img">
                        </div>
                        <div>A simple secondary alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-warning alert-dismissible fase show flex-wrap"
                        role="alert">
                        <div class="avatar me-3">
                            <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="img">
                        </div>
                        <div>A simple warning alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-danger alert-dismissible fase show flex-wrap"
                        role="alert">
                        <div class="avatar avatar-md me-3">
                            <img src="{{ asset('assets/images/avatar/4.jpg') }}" alt="img">
                        </div>
                        <div>A simple danger alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-info alert-dismissible fase show flex-wrap"
                        role="alert">
                        <div class="avatar avatar-lg me-3">
                            <img src="{{ asset('assets/images/avatar/5.jpg') }}" alt="img">
                        </div>
                        <div>A simple info alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <div
                        class="alert alert-img alert-dark alert-dismissible fase show flex-wrap"
                        role="alert">
                        <div class="avatar avatar-xl me-3">
                            <img src="{{ asset('assets/images/avatar/6.jpg') }}" alt="img">
                        </div>
                        <div>A simple info alert with image—check it out!</div>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                            <i class="ti ti-x text-muted"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Image Different Size -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Alert -->
    @vite(['resources/assets/js/custom/alert-custom.js'])
    <!-- Alert -->

@endsection