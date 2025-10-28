@extends('layouts.app', [
    'title' => 'Breadcrumb'
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
                    <li class="breadcrumb-item active" aria-current="page">Breadcrumb</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Basic -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Basic</div>
                </div>
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Home</li>
                        </ol>
                    </nav>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Library</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Basic -->

        <!-- Style 1 -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 1</div>
                </div>
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style-1">
                            <li class="breadcrumb-item active" aria-current="page">Home</li>
                        </ol>
                    </nav>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style-1">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style-1 mb-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Library</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 1 -->
    </div>

    <div class="row gx-4">
        <!-- Style 2 -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 2</div>
                </div>
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style-2 mb-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Library</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 2 -->

        <!-- Style 3 -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Style 3</div>
                </div>
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-style2 mb-0">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-smart-home me-1 fs-15 d-inline-block"></i>
                                    Home
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-apps me-1 fs-15 d-inline-block"></i>
                                    About
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Services</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Style 3 -->
    </div>

    <div class="row gx-4">
        <!-- Divider -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Divider</div>
                </div>
                <div class="card-body">
                    <nav style="--bs-breadcrumb-divider: '~';" aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Divider -->

        <!-- SVG icon -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">SVG icon</div>
                </div>
                <div class="card-body">
                    <nav
                        style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
                        aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Home</a>
                            </li>
                            <li class="breadcrumb-item active embedded-breadcrumb" aria-current="page">Library</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- SVG icon -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection