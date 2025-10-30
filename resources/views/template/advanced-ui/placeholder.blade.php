@extends('layouts.app', [
    'title' => 'Placeholder'
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
                    <li class="breadcrumb-item active" aria-current="page">Placeholder</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row">
        <div class="col-xl-6">
            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card custom">
                        <img class="card-img-top" src="{{ asset('assets/images/nature/1.jpg') }}" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">
                                Some quick example text to build on the card title and make
                                up the bulk of the card's content.
                            </p>
                            <a href="javascript:void(0);" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card custom" aria-hidden="true">
                        <img class="card-img-top" src="{{ asset('assets/images/nature/1.jpg') }}" alt="">
                        <div class="card-body">
                            <div class="h5 card-title placeholder-glow">
                                <span class="placeholder col-6"></span>
                            </div>
                            <p class="card-text placeholder-glow">
                                <span class="placeholder col-7"></span>
                                <span class="placeholder col-4"></span>
                                <span class="placeholder col-4"></span>
                                <span class="placeholder col-6"></span>
                                <span class="placeholder col-6"></span>
                            </p>
                            <a href="javascript:void(0);" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Animation -->
            <div class="col-12">
                <div class="card custom">
                    <div class="card-header">
                        <div class="card-title">Animation</div>
                    </div>
                    <div class="card-body">
                        <p class="placeholder-glow mb-0">
                            <span class="placeholder col-12"></span>
                        </p>
                        <p class="placeholder-wave mb-0">
                            <span class="placeholder col-12"></span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Animation -->
        </div>

        <div class="col-xl-6">
            <div class="row">
                <!-- Sizing -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Sizing</div>
                        </div>
                        <div class="card-body">
                            <span class="placeholder col-12 placeholder-xl mb-1"></span>
                            <span class="placeholder col-12 placeholder-lg"></span>
                            <span class="placeholder col-12"></span>
                            <span class="placeholder col-12 placeholder-sm"></span>
                            <span class="placeholder col-12 placeholder-xs"></span>
                        </div>
                    </div>
                </div>
                <!-- Sizing -->

                <!-- Color -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Color</div>
                        </div>
                        <div class="card-body">
                            <span class="placeholder col-12"></span>
                            <span class="placeholder col-12 bg-primary"></span>
                            <span class="placeholder col-12 bg-secondary"></span>
                            <span class="placeholder col-12 bg-success"></span>
                            <span class="placeholder col-12 bg-danger"></span>
                            <span class="placeholder col-12 bg-warning"></span>
                            <span class="placeholder col-12 bg-info"></span>
                            <span class="placeholder col-12 bg-light"></span>
                            <span class="placeholder col-12 bg-dark"></span>
                        </div>
                    </div>
                </div>
                <!-- Color -->
            </div>
        </div>

        <!-- Width -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Width</div>
                </div>
                <div class="card-body">
                    <span class="placeholder bg-primary col-6"></span>
                    <span class="placeholder bg-primary w-75"></span>
                    <span class="placeholder bg-primary" style="width: 25%;"></span>
                </div>
            </div>
        </div>
        <!-- Width -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection