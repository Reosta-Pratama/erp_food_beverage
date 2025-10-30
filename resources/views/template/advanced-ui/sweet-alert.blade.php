@extends('layouts.app', [
    'title' => 'Sweet Alert'
])

@section('styles')

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.css') }}">

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
                    <li class="breadcrumb-item active" aria-current="page">Sweet Alerts</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <h6 class="mb-3">Basic Element:</h6>

        <!-- Basic -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Basic</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="basic-alert">Basic Alert</button>
                </div>
            </div>
        </div>
        <!-- Basic -->

        <!-- Title With Text Under -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Title With Text Under</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-text">Title With Text</button>
                </div>
            </div>
        </div>
        <!-- Title With Text Under -->

        <!-- With Text, Error Icon & Footer -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">With Text, Error Icon & Footer</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-footer">Alert Footer</button>
                </div>
            </div>
        </div>
        <!-- With Text, Error Icon & Footer -->

        <!-- Alert With Long Window -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alert With Long Window</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="long-window">Long Window Here</button>
                </div>
            </div>
        </div>
        <!-- Alert With Long Window -->

        <!-- Custom HTML -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Custom HTML</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-description">Custom HTML Alert</button>
                </div>
            </div>
        </div>
        <!-- Custom HTML -->

        <!-- Alert With Multiple Button -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alert With Multiple Button</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="three-buttons">Multiple Buttons</button>
                </div>
            </div>
        </div>
        <!-- Alert With Multiple Button -->

        <!-- Custom Position Dialog -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Custom Position Dialog</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-dialog">Alert Dialog</button>
                </div>
            </div>
        </div>
        <!-- Custom Position Dialog -->

        <!-- Confirm Alert -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Confirm Alert</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-confirm">Confirm Alert</button>
                </div>
            </div>
        </div>
        <!-- Confirm Alert -->

        <!-- Alert With Parameter -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alert With Parameter</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-parameter">Alert Parameters</button>
                </div>
            </div>
        </div>
        <!-- Alert With Parameter -->

        <!-- Alert With Image -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alert With Image</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-image">Image Alert</button>
                </div>
            </div>
        </div>
        <!-- Alert With Image -->

        <!-- Alert With Background -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alert With Background</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-custom-bg">Custom Alert</button>
                </div>
            </div>
        </div>
        <!-- Alert With Background -->

        <!-- Auto Close -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Auto Close</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-auto-close">Auto Close</button>
                </div>
            </div>
        </div>
        <!-- Auto Close -->

        <!-- Ajax Request -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Ajax Request</div>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" id="alert-ajax">Ajax Request</button>
                </div>
            </div>
        </div>
        <!-- Ajax Request -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/sweet-alert-custom.js'
    ])

@endsection