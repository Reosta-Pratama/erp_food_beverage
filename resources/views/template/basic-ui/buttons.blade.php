@extends('layouts.app', [
    'title' => 'Buttons'
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
                    <li class="breadcrumb-item active" aria-current="page">Buttons</li>
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
                    <div class="card-title">Default Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary btn-wave">Primary</button>
                        <button type="button" class="btn btn-secondary btn-wave">Secondary</button>
                        <button type="button" class="btn btn-success btn-wave">Success</button>
                        <button type="button" class="btn btn-danger btn-wave">Danger</button>
                        <button type="button" class="btn btn-warning btn-wave">Warning</button>
                        <button type="button" class="btn btn-info btn-wave">Info</button>
                        <button type="button" class="btn btn-light btn-wave">Light</button>
                        <button type="button" class="btn btn-dark btn-wave text-white">Dark</button>
                        <button type="button" class="btn btn-link btn-wave">Link</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Rounded -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary rounded-pill btn-wave">Primary</button>
                        <button type="button" class="btn btn-secondary rounded-pill btn-wave">Secondary</button>
                        <button type="button" class="btn btn-success rounded-pill btn-wave">Success</button>
                        <button type="button" class="btn btn-danger rounded-pill btn-wave">Danger</button>
                        <button type="button" class="btn btn-warning rounded-pill btn-wave">Warning</button>
                        <button type="button" class="btn btn-info rounded-pill btn-wave">Info</button>
                        <button type="button" class="btn btn-light rounded-pill btn-wave">Light</button>
                        <button type="button" class="btn btn-dark rounded-pill btn-wave text-white">Dark</button>
                        <button type="button" class="btn btn-link rounded-pill btn-wave">Link</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded -->

        <!-- Light -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Light Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary-light btn-wave">Primary</button>
                        <button type="button" class="btn btn-secondary-light btn-wave">Secondary</button>
                        <button type="button" class="btn btn-success-light btn-wave">Success</button>
                        <button type="button" class="btn btn-danger-light btn-wave">Danger</button>
                        <button type="button" class="btn btn-warning-light btn-wave">Warning</button>
                        <button type="button" class="btn btn-info-light btn-wave">Info</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Light -->

        <!-- Light Rounded -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Light Rounded Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary-light rounded-pill btn-wave">Primary</button>
                        <button type="button" class="btn btn-secondary-light rounded-pill btn-wave">Secondary</button>
                        <button type="button" class="btn btn-success-light rounded-pill btn-wave">Success</button>
                        <button type="button" class="btn btn-danger-light rounded-pill btn-wave">Danger</button>
                        <button type="button" class="btn btn-warning-light rounded-pill btn-wave">Warning</button>
                        <button type="button" class="btn btn-info-light rounded-pill btn-wave">Info</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Light Rounded -->

        <!-- Outline -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Outline Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-outline-primary btn-wave">Primary</button>
                        <button type="button" class="btn btn-outline-secondary btn-wave">Secondary</button>
                        <button type="button" class="btn btn-outline-success btn-wave">Success</button>
                        <button type="button" class="btn btn-outline-danger btn-wave">Danger</button>
                        <button type="button" class="btn btn-outline-warning btn-wave">Warning</button>
                        <button type="button" class="btn btn-outline-info btn-wave">Info</button>
                        <button type="button" class="btn btn-outline-light btn-wave">Light</button>
                        <button type="button" class="btn btn-outline-dark btn-wave">Dark</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Outline -->

        <!-- Rounded Outline -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Outline Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-outline-primary rounded-pill btn-wave">Primary</button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill btn-wave">Secondary</button>
                        <button type="button" class="btn btn-outline-success rounded-pill btn-wave">Success</button>
                        <button type="button" class="btn btn-outline-danger rounded-pill btn-wave">Danger</button>
                        <button type="button" class="btn btn-outline-warning rounded-pill btn-wave">Warning</button>
                        <button type="button" class="btn btn-outline-info rounded-pill btn-wave">Info</button>
                        <button type="button" class="btn btn-outline-light rounded-pill btn-wave">Light</button>
                        <button type="button" class="btn btn-outline-dark rounded-pill btn-wave">Dark</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Outline -->

        <!-- Gradient -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Gradient Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary-gradient btn-wave">Primary</button>
                        <button type="button" class="btn btn-secondary-gradient btn-wave">Secondary</button>
                        <button type="button" class="btn btn-success-gradient btn-wave">Success</button>
                        <button type="button" class="btn btn-danger-gradient btn-wave">Danger</button>
                        <button type="button" class="btn btn-warning-gradient btn-wave">Warning</button>
                        <button type="button" class="btn btn-info-gradient btn-wave">Info</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gradient -->

        <!-- Rounded Gradient -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Gradient Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary-gradient rounded-pill btn-wave">Primary</button>
                        <button type="button" class="btn btn-secondary-gradient rounded-pill btn-wave">Secondary</button>
                        <button type="button" class="btn btn-success-gradient rounded-pill btn-wave">Success</button>
                        <button type="button" class="btn btn-danger-gradient rounded-pill btn-wave">Danger</button>
                        <button type="button" class="btn btn-warning-gradient rounded-pill btn-wave">Warning</button>
                        <button type="button" class="btn btn-info-gradient rounded-pill btn-wave">Info</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Gradient -->

        <!-- Tag -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tag Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <a class="btn btn-primary btn-wave" href="javascript:void(0);" role="button">Link</a>
                        <button class="btn btn-secondary btn-wave" type="submit">Button</button>
                        <input class="btn btn-info" type="button" value="Input">
                        <input class="btn btn-warning" type="submit" value="Submit">
                        <input class="btn btn-success" type="reset" value="Reset">
                    </div>
                </div>
            </div>
        </div>
        <!-- Tag -->

        <!-- Disable Tag -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disable Tag Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="mb-2">
                            <button type="button" class="btn btn-primary" disabled="">Primary button</button>
                            <button type="button" class="btn btn-secondary" disabled="">Button</button>
                            <button type="button" class="btn btn-outline-primary" disabled="">Primary button</button>
                            <button type="button" class="btn btn-outline-secondary" disabled="">Button</button>
                        </div>

                        <div>
                            <a class="btn btn-primary disabled" role="button" aria-disabled="true">Primary link</a>
                            <a class="btn btn-secondary disabled" role="button" aria-disabled="true">Link</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Disable Tag -->

        <!-- Toggle -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Toggle Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="mb-2">
                            <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="button">Toggle button</button>
                            <button type="button" class="btn btn-secondary active btn-wave" data-bs-toggle="button" aria-pressed="true">Active toggle button</button>
                            <button type="button" class="btn btn-info btn-wave" disabled data-bs-toggle="button" >Disabled toggle button</button>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="btn btn-primary btn-wave" role="button" data-bs-toggle="button">Toggle link</a>
                            <a href="javascript:void(0);" class="btn btn-secondary active btn-wave" role="button" data-bs-toggle="button" aria-pressed="true">Active toggle link</a>
                            <a class="btn btn-info disabled btn-wave" aria-disabled="true" role="button" data-bs-toggle="button">Disabled toggle link</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Toggle -->

        <!-- Loading -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Loading Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list d-md-flex flex-md-wrap gap-md-2">
                        <button class="btn btn-primary btn-loader">
                            <span class="me-2">Loading</span>
                            <span class="loading"><i class="ti ti-loader fs-16"></i></span>
                        </button>
                        <button class="btn btn-outline-secondary btn-loader">
                            <span class="me-2">Loading</span>
                            <span class="loading"><i class="ti ti-loader fs-16"></i></span>
                        </button>
                        <button class="btn btn-info-light btn-loader">
                            <span class="me-2">Loading</span>
                            <span class="loading"><i class="ti ti-loader-2 fs-16"></i></span>
                        </button>
                        <button class="btn btn-warning-light btn-loader">
                            <span class="me-2">Loading</span>
                            <span class="loading"><i class="ti ti-loader-3 fs-16"></i></span>
                        </button>
                        <button class="btn btn-success btn-loader disabled">
                            <span class="me-2">Disabled</span>
                            <span class="loading"><i class="ti ti-refresh fs-16"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Loading -->

        <!-- Icon -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Icon Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list d-md-flex">
                        <div class="mb-md-0 mb-2">
                            <button class="btn btn-icon btn-primary btn-wave">
                                <i class="ri-bank-fill"></i>
                            </button>
                            <button class="btn btn-icon btn-info btn-wave">
                                <i class="ri-medal-line"></i>
                            </button>
                            <button class="btn btn-icon btn-danger btn-wave">
                                <i class="ri-archive-line"></i>
                            </button>
                            <button class="btn btn-icon btn-warning btn-wave me-5">
                                <i class="ri-calendar-2-line"></i>
                            </button>
                        </div>
                        <div class="mb-md-0 mb-2">
                            <button class="btn btn-icon btn-primary-light rounded-pill btn-wave">
                                <i class="ri-home-smile-line"></i>
                            </button>
                            <button class="btn btn-icon btn-secondary-light rounded-pill btn-wave">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <button class="btn btn-icon btn-success-light rounded-pill btn-wave">
                                <i class="ri-notification-3-line"></i>
                            </button>
                            <button class="btn btn-icon btn-danger-light rounded-pill btn-wave me-5">
                                <i class="ri-chat-settings-line"></i>
                            </button>
                        </div>
                        <div class="">
                            <button class="btn btn-icon btn-outline-primary rounded-pill btn-wave">
                                <i class="ri-phone-line"></i>
                            </button>
                            <button class="btn btn-icon btn-outline-danger rounded-pill btn-wave">
                                <i class="ri-customer-service-2-line"></i>
                            </button>
                            <button class="btn btn-icon btn-outline-success rounded-pill btn-wave">
                                <i class="ri-live-line"></i>
                            </button>
                            <button class="btn btn-icon btn-outline-secondary rounded-pill btn-wave">
                                <i class="ri-save-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Icon -->

        <!-- Icon Size -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Icon Size Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list d-md-flex justify-content-md-between align-items-md-center">
                        <div class="mb-md-0 mb-2">
                            <button class="btn btn-sm btn-icon btn-primary btn-wave">
                                <i class="ri-bank-fill"></i>
                            </button>
                            <button class="btn btn-icon btn-info btn-wave">
                                <i class="ri-medal-line"></i>
                            </button>
                            <button class="btn btn-lg btn-icon btn-danger btn-wave">
                                <i class="ri-archive-line"></i>
                            </button>
                        </div>
                        <div class="mb-md-0 mb-2">
                            <button class="btn btn-sm btn-icon btn-primary-light rounded-pill btn-wave">
                                <i class="ri-home-smile-line"></i>
                            </button>
                            <button class="btn btn-icon btn-secondary-light rounded-pill btn-wave">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <button class="btn btn-lg btn-icon btn-success-light rounded-pill btn-wave">
                                <i class="ri-notification-3-line"></i>
                            </button>
                        </div>
                        <div class="mb-0">
                            <button class="btn btn-sm btn-icon btn-outline-primary rounded-pill btn-wave">
                                <i class="ri-phone-line"></i>
                            </button>
                            <button class="btn btn-icon btn-outline-danger rounded-pill btn-wave">
                                <i class="ri-customer-service-2-line"></i>
                            </button>
                            <button class="btn btn-lg btn-icon btn-outline-success rounded-pill btn-wave">
                                <i class="ri-live-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Icon Size -->

        <!-- Social -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Social Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button class="btn btn-icon btn-facebook btn-wave">
                            <i class="ti ti-brand-facebook"></i>
                        </button>
                        <button class="btn btn-icon btn-twitter btn-wave">
                            <i class="ri-twitter-x-line"></i>
                        </button>
                        <button class="btn btn-icon btn-instagram btn-wave">
                            <i class="ti ti-brand-instagram"></i>
                        </button>
                        <button class="btn btn-icon btn-github btn-wave">
                            <i class="ti ti-brand-github"></i>
                        </button>
                        <button class="btn btn-icon btn-youtube btn-wave">
                            <i class="ti ti-brand-youtube"></i>
                        </button>
                        <button class="btn btn-icon btn-google btn-wave">
                            <i class="ti ti-brand-google"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Social -->

        <!-- Width -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Width Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary btn-w-xs btn-wave">XS</button>
                        <button type="button" class="btn btn-secondary btn-w-sm btn-wave">SM</button>
                        <button type="button" class="btn btn-warning btn-w-md btn-wave">MD</button>
                        <button type="button" class="btn btn-info btn-w-lg btn-wave">LG</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Width -->

        <!-- Size -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Size Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button type="button" class="btn btn-primary btn-sm btn-wave">Small button</button>
                        <button type="button" class="btn btn-secondary btn-wave">Default button</button>
                        <button type="button" class="btn btn-success btn-lg btn-wave">Large button</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Size -->

        <!-- Shadow -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Shadow Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list d-flex">
                        <div class="mb-0 me-5">
                            <button class="btn btn-primary shadow-sm btn-wave">Button</button>
                            <button class="btn btn-primary shadow btn-wave">Button</button>
                            <button class="btn btn-primary shadow-lg btn-wave">Button</button>
                        </div>
                        <div class="mb-0 ">
                            <button class="btn btn-secondary btn-sm shadow-sm btn-wave">Button</button>
                            <button class="btn btn-info shadow btn-wave">Button</button>
                            <button class="btn btn-lg btn-success shadow-lg btn-wave">Button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shadow -->

        <!-- Shadow Color -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Shadow Color Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button class="btn btn-primary shadow-primary btn-wave">Button</button>
                        <button class="btn btn-secondary shadow-secondary btn-wave">Button</button>
                        <button class="btn btn-success shadow-success btn-wave">Button</button>
                        <button class="btn btn-info shadow-info btn-wave">Button</button>
                        <button class="btn btn-warning shadow-warning btn-wave">Button</button>
                        <button class="btn btn-danger shadow-danger btn-wave">Button</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shadow Color -->

        <!-- Raised -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Raised Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button class="btn btn-primary btn-raised-shadow btn-wave">Button</button>
                        <button class="btn btn-secondary btn-raised-shadow btn-wave">Button</button>
                        <button class="btn btn-success btn-raised-shadow btn-wave">Button</button>
                        <button class="btn btn-info btn-raised-shadow btn-wave">Button</button>
                        <button class="btn btn-warning btn-raised-shadow btn-wave">Button</button>
                        <button class="btn btn-danger btn-raised-shadow btn-wave">Button</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Raised -->

        <!-- Label -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Label Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button class="btn btn-primary label-btn">
                            <i class="ri-chat-smile-line label-btn-icon me-2"></i>
                            Primary
                        </button>
                        <button class="btn btn-secondary label-btn">
                            <i class="ri-settings-4-line label-btn-icon me-2"></i>
                            Secondary
                        </button>
                        <button class="btn btn-warning label-btn rounded-pill">
                            <i class="ri-spam-2-line label-btn-icon me-2 rounded-pill"></i>
                            Warning
                        </button>
                        <button class="btn btn-info label-btn rounded-pill">
                            <i class="ri-phone-line label-btn-icon me-2 rounded-pill"></i>
                            Info
                        </button>
                        <button class="btn btn-success label-btn label-end">
                            Success
                            <i class="ri-thumb-up-line label-btn-icon ms-2"></i>
                        </button>
                        <button class="btn btn-danger label-btn label-end rounded-pill">
                            Cancel
                            <i class="ri-close-line label-btn-icon ms-2 rounded-pill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Label -->

        <!-- Custom -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Custom Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button class="btn btn-info custom-button rounded-pill">
                            <span class="custom-btn-icons"><i class="ri-twitter-x-line text-info"></i></span>
                            Twitter
                        </button>
                        <button class="btn btn-danger-light btn-border-down">Border</button>
                        <button class="btn btn-secondary-light btn-border-start">Border</button>
                        <button class="btn btn-primary-light btn-border-end">Border</button>
                        <button class="btn btn-warning-light btn-border-top">Border</button>
                        <button class="btn btn-secondary btn-glare"><span>Glare Button</span></button>
                        <button class="btn btn-danger btn-hover btn-hover-animate">Like</button>
                        <button class="btn btn-success btn-darken-hover">Hover</button>
                        <button class="btn btn-orange btn-custom-border">Hover</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom -->

        <!-- Block -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Block Buttons</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="d-grid gap-2 mb-4">
                            <button class="btn btn-primary btn-wave" type="button">Button</button>
                            <button class="btn btn-secondary btn-wave" type="button">Button</button>
                        </div>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-info btn-wave" type="button">Button</button>
                            <button class="btn btn-success btn-wave" type="button">Button</button>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button class="btn btn-danger btn-wave" type="button">Button</button>
                            <button class="btn btn-warning btn-wave" type="button">Button</button>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-light me-md-2 btn-wave" type="button">Button</button>
                            <button class="btn btn-dark btn-wave" type="button">Button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Block -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection