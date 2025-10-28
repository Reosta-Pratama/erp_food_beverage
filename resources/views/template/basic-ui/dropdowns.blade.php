@extends('layouts.app', [
    'title' => 'Dropdowns'
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
                    <li class="breadcrumb-item active" aria-current="page">Dropdowns</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row">
        <!-- Default -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-list d-flex align-items-center flex-wrap">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="javascript:void(0);" role="button"
                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown link
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Default -->
    </div>

    <div class="row gx-4">
        <!-- Single -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Single Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Single -->

        <!-- Rounded -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle rounded-pill" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded -->
    </div>

    <div class="row gx-4">
        <!-- Outline -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Outline Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-warning dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-danger dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Outline -->

        <!-- Rounded Outline -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded Outline Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info dropdown-toggle rounded-pill" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-warning dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-danger dropdown-toggle rounded-pill"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Rounded Outline -->
    </div>

    <div class="row gx-4">
        <!-- Split -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Split Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-group my-1">
                        <button type="button" class="btn btn-primary">Action</button>
                        <button type="button"
                            class="btn btn-primary dropdown-toggle dropdown-toggle-split me-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1">
                        <button type="button" class="btn btn-secondary">Action</button>
                        <button type="button"
                            class="btn btn-secondary dropdown-toggle dropdown-toggle-split me-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1">
                        <button type="button" class="btn btn-info">Action</button>
                        <button type="button"
                            class="btn btn-info dropdown-toggle dropdown-toggle-split me-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1">
                        <button type="button" class="btn btn-success">Action</button>
                        <button type="button"
                            class="btn btn-success dropdown-toggle dropdown-toggle-split me-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1">
                        <button type="button" class="btn btn-warning">Action</button>
                        <button type="button"
                            class="btn btn-warning dropdown-toggle dropdown-toggle-split me-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1">
                        <button type="button" class="btn btn-danger">Action</button>
                        <button type="button"
                            class="btn btn-danger dropdown-toggle dropdown-toggle-split me-2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Split -->

        <!-- Sizing -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Sizing Dropdowns</div>
                </div>
                <div class="card-body">
                    <div class="btn-group my-1 me-2">
                        <button class="btn btn-primary btn-lg dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Large button
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1 me-2">
                        <button class="btn btn-light btn-lg" type="button">
                            Large split button
                        </button>
                        <button type="button"
                            class="btn btn-lg btn-light dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1 me-2">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Small button
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                    <div class="btn-group my-1">
                        <button class="btn btn-light btn-sm" type="button">
                            Small split button
                        </button>
                        <button type="button"
                            class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sizing -->
    </div>

    <div class="row gx-4">
        <!-- Drop Up -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropup</div>
                </div>
                <div class="card-body">
                    <div class="btn-group dropup my-1">
                        <button type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropup
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>

                    <div class="btn-group dropup my-1">
                        <button type="button" class="btn btn-info">
                            Split dropup
                        </button>
                        <button type="button"
                            class="btn btn-info dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Drop Up -->

        <!-- Drop Up Right -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropup Right</div>
                </div>
                <div class="card-body">
                    <div class="btn-group dropend my-1">
                        <button type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropright
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>

                    <div class="btn-group dropend my-1">
                        <button type="button" class="btn btn-info">
                            Split dropend
                        </button>
                        <button type="button"
                            class="btn btn-info dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropright</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Drop Up Right -->
    </div>

    <div class="row gx-4">
        <!-- Drop Left -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropup Left</div>
                </div>
                <div class="card-body">
                    <div class="btn-group dropstart my-1">
                        <button type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropleft
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <div class="btn-group dropstart my-1" role="group">
                            <button type="button"
                                class="btn btn-info dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropstart</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-info my-1">
                            Split dropleft
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Drop Left -->

        <!-- Active -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Active Dropdown</div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Dropstart
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:void(0);">Regular link</a></li>
                        <li><a class="dropdown-item active" href="javascript:void(0);" aria-current="true">Active link</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">Another link</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Active -->
    </div>

    <div class="row gx-4">
        <!-- Disabled -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disabled</div>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Dropstart
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:void(0);">Regular link</a></li>
                        <li><a class="dropdown-item disabled" href="javascript:void(0);" aria-current="true">Active link</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);">Another link</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Disabled -->

        <!-- Auto Close Behavior -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Auto Close</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true"
                                aria-expanded="false">
                                Default dropdown
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuClickableOutside" data-bs-toggle="dropdown"
                                data-bs-auto-close="inside" aria-expanded="false">
                                Clickable outside
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableOutside">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-info dropdown-toggle" type="button"
                                id="dropdownMenuClickableInside" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-expanded="false">
                                Clickable inside
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableInside">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-warning dropdown-toggle" type="button"
                                id="dropdownMenuClickable" data-bs-toggle="dropdown"
                                data-bs-auto-close="false" aria-expanded="false">
                                Manual close
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickable">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Auto Close Behavior -->

        <!-- Form -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">With Forms</div>
                </div>
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </button>
                        <div class="dropdown-menu">
                            <form class="px-4 py-3" novalidate>
                                <div class="mb-3">
                                    <label for="exampleDropdownFormEmail1" class="form-label">Email
                                        address</label>
                                    <input type="email" class="form-control" id="exampleDropdownFormEmail1"
                                        placeholder="email@example.com">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleDropdownFormPassword1"
                                        class="form-label">Password</label>
                                    <input type="password" class="form-control"
                                        id="exampleDropdownFormPassword1" placeholder="Password">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="dropdownCheck">
                                        <label class="form-check-label" for="dropdownCheck">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Sign in</button>
                            </form>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0);">New around here? Sign up</a>
                            <a class="dropdown-item" href="javascript:void(0);">Forgot password?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form -->
    </div>

    <div class="row gx-4">
        <!-- Dropdown centered -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropdown Menu Centered</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code>.dropdown-center</code> on the parent element.</p>
                    <div class="dropdown-center">
                        <button class="btn btn-primary dropdown-toggle" type="button"
                            id="dropdownCenterBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            Centered dropdown
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownCenterBtn">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Action two</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Action three</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dropdown centered -->

        <!-- Dropup centered -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropdup Menu Centered</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code>.dropup-center</code>on the parent element.</p>
                    <div class="dropup-center dropup">
                        <button class="btn btn-secondary dropdown-toggle" type="button"
                            id="dropupCenterBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            Centered dropup
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropupCenterBtn">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Action two</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Action three</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dropup centered -->
    </div>

    <div class="row gx-4">
        <!-- Menu Items -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Menu Items</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">You can use <code>&lt;a&gt;</code> or <code>&lt;button&gt;</code> as dropdown items.</p>
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle" type="button"
                            id="dropdownMenu1" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu Items -->

        <!-- Dropdown Options -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dropdown Option</div>
                </div>
                <div class="card-body">
                    <p class="card-title mb-3">Use <code>data-bs-offset</code> or <code>data-bs-reference</code> to change the location of the dropdown.</p>
                    <div class="d-flex align-items-center">
                        <div class="dropdown me-1">
                            <button type="button" class="btn btn-primary dropdown-toggle"
                                id="dropdownMenuOffset" data-bs-toggle="dropdown" aria-expanded="false"
                                data-bs-offset="10,20">
                                Offset
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info">Reference</button>
                            <button type="button"
                                class="btn btn-info dropdown-toggle dropdown-toggle-split"
                                id="dropdownMenuReference" data-bs-toggle="dropdown"
                                aria-expanded="false" data-bs-reference="parent">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dropdown Options -->
    </div>

    <div class="row gx-4">
        <!-- Alignment Options -->
        <div class="col-xl-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alignment Option</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle mb-0" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Dropdown
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle mb-0"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Right-aligned menu
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle mb-0"
                                data-bs-toggle="dropdown" data-bs-display="static"
                                aria-expanded="false">
                                Left-aligned, right-aligned lg
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle mb-0"
                                data-bs-toggle="dropdown" data-bs-display="static"
                                aria-expanded="false">
                                Right-aligned, left-aligned lg
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group dropstart">
                            <button type="button" class="btn btn-success dropdown-toggle mb-0"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Dropstart
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group dropend">
                            <button type="button" class="btn btn-danger dropdown-toggle mb-0"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Dropend
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-warning dropdown-toggle mb-0"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Dropup
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Menu item</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alignment Options -->
    </div>

    <div class="row gx-4">
        <!-- Menu Alignment -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Menu Alignment</div>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Right-aligned menu example
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu Alignment -->

        <!-- Responsive Alignment Right -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Responsive Alignment Right</div>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle text-wrap"
                            data-bs-toggle="dropdown" data-bs-display="static"
                            aria-expanded="false">
                            Left-aligned but right aligned when large screen
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Responsive Alignment Right -->

        <!-- Responsive Alignment Left -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Responsive Alignment Left</div>
                </div>
                <div class="card-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle text-wrap"
                            data-bs-toggle="dropdown" data-bs-display="static"
                            aria-expanded="false">
                            Left-aligned but right aligned when large screen
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-start">
                            <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li>
                            <li><button class="dropdown-item" type="button">Something else here</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Responsive Alignment Left -->
    </div>

    <div class="row gx-4">
        <!-- Custom Dropdown Menu -->
        <div class="col-xl-9">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Custom Dropdown</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Primary
                            </button>
                            <ul class="dropdown-menu dropmenu-item-primary">
                                <li><a class="dropdown-item active" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Secondary
                            </button>
                            <ul class="dropdown-menu dropmenu-item-secondary">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item active" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Warning
                            </button>
                            <ul class="dropdown-menu dropmenu-item-warning">
                                <li><a class="dropdown-item" href="javascript:void(0);">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item active" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Info
                            </button>
                            <ul class="dropdown-menu dropmenu-item-info">
                                <li><a class="dropdown-item active" href="javascript:void(0);">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-success-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Success
                            </button>
                            <ul class="dropdown-menu dropmenu-light-success">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item active" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-danger-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Danger
                            </button>
                            <ul class="dropdown-menu dropmenu-light-danger">
                                <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                <li><a class="dropdown-item active" href="javascript:void(0);">Active</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Custom Dropdown Menu -->

        <!-- Dark Dropdown -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Dark Dropdown</div>
                </div>
                <div class="card-body">
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button"
                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown button
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dark Dropdown -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection