@extends('layouts.app', [
    'title' => 'Object Fit'
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
                    <li class="breadcrumb-item active" aria-current="page">Object Fit</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Object Fit Contain -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain -->

        <!-- Object Fit Cover -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Cover</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-cover border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Cover -->

        <!-- Object Fit Fill -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Fill</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-fill border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Fill -->

        <!-- Object Fit Scale Down -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Scale Down</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-scale border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Scale Down -->

        <!-- Object Fit None -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit None</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-none border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit None -->

        <!-- Object Fit Contain (SM -Responsive) -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain (SM -Responsive)</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-sm-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain (SM -Responsive) -->

        <!-- Object Fit Contain (MD -Responsive) -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain (MD -Responsive)</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-md-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain (MD -Responsive) -->

        <!-- Object Fit Contain (LG -Responsive) -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain (LG -Responsive)</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-lg-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain (LG -Responsive) -->

        <!-- Object Fit Contain (XL -Responsive) -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain (XL -Responsive)</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-xl-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain (XL -Responsive) -->

        <!-- Object Fit Contain (XXL -Responsive) -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain (XXL -Responsive)</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-xxl-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain (XXL -Responsive) -->

        <!-- Object Fit Contain Video -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Contain Video</div>
                </div>
                <div class="card-body object-fit-container">
                    <img src="{{ asset('assets/images/mountain/mountain-object-fit.jpg') }}" class="object-fit-contain border rounded" alt="...">
                </div>
            </div>
        </div>
        <!-- Object Fit Contain Video -->

        <!-- Object Fit Cover Video -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Cover Video</div>
                </div>
                <div class="card-body object-fit-container">
                    <video src="{{ asset('assets/video/1.mp4') }}" class="object-fit-cover rounded border" autoplay loop muted></video>
                </div>
            </div>
        </div>
        <!-- Object Fit Cover Video -->

        <!-- Object Fit Fill Video -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Fill Video</div>
                </div>
                <div class="card-body object-fit-container">
                    <video src="{{ asset('assets/video/1.mp4') }}" class="object-fit-fill rounded border" autoplay loop muted></video>
                </div>
            </div>
        </div>
        <!-- Object Fit Fill Video -->

        <!-- Object Fit Scale Video -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit Scale Video</div>
                </div>
                <div class="card-body object-fit-container">
                    <video src="{{ asset('assets/video/1.mp4') }}" class="object-fit-scale rounded border" autoplay loop muted></video>
                </div>
            </div>
        </div>
        <!-- Object Fit Scale Video -->

        <!-- Object Fit None Video -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Object Fit None Video</div>
                </div>
                <div class="card-body object-fit-container">
                    <video src="{{ asset('assets/video/1.mp4') }}" class="object-fit-none rounded border" autoplay loop muted></video>
                </div>
            </div>
        </div>
        <!-- Object Fit None Video -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection