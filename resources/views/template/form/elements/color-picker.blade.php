@extends('layouts.app', [
    'title' => 'Color Picker'
])

@section('styles')

    <!-- Pickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/@simonwep/pickr/themes/classic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/@simonwep/pickr/themes/monolith.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/@simonwep/pickr/themes/nano.min.css') }}">

@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Color Pickers</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Bootstrap Color Picker -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Bootstrap Color Picker</div>
                </div>
                <div class="card-body">
                    <input
                        type="color"
                        class="form-control form-control-color border-0"
                        id="exampleColorInput"
                        value="#136ad0"
                        title="Choose your color">
                </div>
            </div>
        </div>
        <!-- Bootstrap Color Picker -->

        <!-- Pickr: Classic -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pickr: Classic</div>
                </div>
                <div class="card-body d-flex">
                    <div>
                        <div class="theme-container"></div>
                        <div class="pickr-container example-picker"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pickr: Classic -->

        <!-- Pickr: Monolith -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pickr: Monolith</div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="theme-container1"></div>
                        <div class="pickr-container1 example-picker"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pickr: Monolith -->

        <!-- Pickr: Nano -->
        <div class="col-xl-3">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pickr: Nano</div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="theme-container2"></div>
                        <div class="pickr-container2 example-picker"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pickr: Nano -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Pickr JS -->
    <script src="{{ asset('assets/plugin/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/form-color-picker-custom.js'
    ])

@endsection