@extends('layouts.app', [
    'title' => 'Spinners'
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
                    <li class="breadcrumb-item active" aria-current="page">Spinner</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Border Spinner -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Border Spinner</div>
                </div>
                <div class="card-body">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Border Spinner -->

        <!-- Growing Spinner -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Growing Spinner</div>
                </div>
                <div class="card-body">
                    <div class="spinner-grow" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Growing Spinner -->

        <!-- Color Spinner -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Color Spinner</div>
                </div>
                <div class="card-body">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Color Spinner -->

        <!-- Color Growing Spinner -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Color Growing Spinner</div>
                </div>
                <div class="card-body">
                    <div class="spinner-grow text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-dark" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Color Growing Spinner -->

        <!-- Alignment Flex -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alignment Flex</div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <strong>Loading...</strong>
                        <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alignment Flex -->

        <!-- Alignment Float -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alignment Float</div>
                </div>
                <div class="card-body">
                    <div class="clearfix mb-4">
                        <div class="spinner-border float-end" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="clearfix">
                        <div class="spinner-border float-start" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alignment Float -->

        <!-- Alignment Margin -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alignment Margin</div>
                </div>
                <div class="card-body">
                    <div class="spinner-border m-5" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alignment Margin -->

        <!-- Alignment Text Center -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Alignment Text Center</div>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Alignment Text Center -->

        <!-- Sizing Spinner -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Sizing Spinner</div>
                </div>
                <div class="card-body d-flex align-items-center">
                    <div class="spinner-border spinner-border-sm me-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow spinner-grow-sm me-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div
                        class="spinner-border me-4"
                        style="width: 3rem; height: 3rem;"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sizing Spinner -->

        <!-- Button Spinner -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Button Spinner</div>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <button class="btn btn-primary-light" type="button" disabled="disabled">
                            <span
                                class="spinner-border spinner-border-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Loading...</span>
                        </button>
                        <button class="btn btn-primary-light" type="button" disabled="disabled">
                            <span
                                class="spinner-border spinner-border-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-primary-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            <span class="visually-hidden">Loading...</span>
                        </button>
                        <button class="btn btn-primary-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-secondary-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-success-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-info-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-warning-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-danger-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-light" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                        <button class="btn btn-dark text-fixed-white" type="button" disabled="disabled">
                            <span
                                class="spinner-grow spinner-grow-sm align-middle"
                                role="status"
                                aria-hidden="true"></span>
                            Loading...
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button Spinner -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection