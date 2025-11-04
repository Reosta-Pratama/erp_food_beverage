@extends('layouts.app', [
    'title' => 'Range Slider'
])

@section('styles')

    <!-- nouiSlider CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/nouislider/nouislider.min.css') }}">

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
                    <li class="breadcrumb-item active" aria-current="page">Range Slider</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <h6 class="mb-3">Default Slider:</h6>

        <!-- Default -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default</div>
                </div>
                <div class="card-body">
                    <input type="range" class="form-range" id="customRange1">
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Disabled -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disabled</div>
                </div>
                <div class="card-body">
                    <input type="range" class="form-range" id="disabledRange" disabled>
                </div>
            </div>
        </div>
        <!-- Disabled -->

        <!-- Range With Min & Max -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Range With Min & Max</div>
                </div>
                <div class="card-body">
                    <input type="range" class="form-range" min="0" max="5" id="customRange2">
                </div>
            </div>
        </div>
        <!-- Range With Min & Max -->

        <!-- Range With Steps -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Range With Steps</div>
                </div>
                <div class="card-body">
                    <input type="range" class="form-range" min="0" max="5" step="0.5" id="customRange3">
                </div>
            </div>
        </div>
        <!-- Range With Steps -->
    </div>

    <div class="row gx-4">
        <h6 class="mb-3">With noUiSlider JS:</h6>

        <!-- Basic -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Basic</div>
                </div>
                <div class="card-body">
                    <div id="slider"></div>
                </div>
            </div>
        </div>
        <!-- Basic -->

        <!-- Fit Handles -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Fit Handles</div>
                </div>
                <div class="card-body">
                    <div id="slider-fit"></div>
                </div>
            </div>
        </div>
        <!-- Fit Handles -->

        <!-- Rounded -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded</div>
                </div>
                <div class="card-body">
                    <div id="slider-round"></div>
                </div>
            </div>
        </div>
        <!-- Rounded -->

        <!-- Squared -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Squared</div>
                </div>
                <div class="card-body">
                    <div id="slider-square"></div>
                </div>
            </div>
        </div>
        <!-- Squared -->
    </div>

    <div class="row gx-4">
        <h6 class="mb-3">Color noUiSlider JS:</h6>

        <!-- Primary -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Primary</div>
                </div>
                <div class="card-body">
                    <div id="primary-colored-slider"></div>
                </div>
            </div>
        </div>
        <!-- Primary -->

        <!-- Secondary -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Secondary</div>
                </div>
                <div class="card-body">
                    <div id="secondary-colored-slider"></div>
                </div>
            </div>
        </div>
        <!-- Secondary -->

        <!-- Warning -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Warning</div>
                </div>
                <div class="card-body">
                    <div id="warning-colored-slider"></div>
                </div>
            </div>
        </div>
        <!-- Warning -->

        <!-- Info -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Info</div>
                </div>
                <div class="card-body">
                    <div id="info-colored-slider"></div>
                </div>
            </div>
        </div>
        <!-- Info -->

        <!-- Success -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Success</div>
                </div>
                <div class="card-body">
                    <div id="success-colored-slider"></div>
                </div>
            </div>
        </div>
        <!-- Success -->

        <!-- Danger -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Danger</div>
                </div>
                <div class="card-body">
                    <div id="danger-colored-slider"></div>
                </div>
            </div>
        </div>
        <!-- Danger -->
    </div>

    <div class="row gx-4">
        <h6 class="mb-3">Custom noUiSlider JS:</h6>

        <div class="col-xl-4">
            <div class="row">
                <!-- Color Picker Slider -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Color Picker Slider</div>
                        </div>
                        <div class="card-body">
                            <div class="sliders" id="color1"></div>
                            <div class="sliders" id="color2"></div>
                            <div class="sliders" id="color3"></div>
                            <div id="result"></div>
                        </div>
                    </div>
                </div>
                <!-- Color Picker Slider -->

                <!-- Slider Toogle -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Slider Toogle</div>
                        </div>
                        <div class="card-body">
                            <div id="slider-toggle"></div>
                        </div>
                    </div>
                </div>
                <!-- Slider Toogle -->
            </div>
        </div>

        <div class="col-xl-8">
            <div class="row">
                <!-- Locking Slider -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Locking Slider</div>
                        </div>
                        <div class="card-body">
                            <div id="slider1"></div>
                            <div id="slider1-span" class="my-1"></div>
                            <div id="slider2"></div>
                            <div id="slider2-span" class="my-1"></div>
                            <div id="slider3"></div>
                            <div id="slider3-span" class="my-1"></div>
                            <button id="lockbutton" class="btn btn-sm btn-primary float-end">Lock</button>
                        </div>
                    </div>
                </div>
                <!-- Locking Slider -->

                <!-- Merging Tooltip Slider -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Merging Tooltip Slider</div>
                        </div>
                        <div class="card-body">
                            <div id="merging-tooltips"></div>
                        </div>
                    </div>
                </div>
                <!-- Merging Tooltip Slider -->

                <!-- Non Linear Slider -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Non Linear Slider</div>
                        </div>
                        <div class="card-body">
                            <div id="nonlinear"></div>
                            <div id="lower-value" class="mt-5"></div>
                            <div id="upper-value" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                <!-- Non Linear Slider -->

                <!-- Sliding Handles Tooltip -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Sliding Handles Tooltip</div>
                        </div>
                        <div class="card-body">
                            <div id="slider-hide"></div>
                        </div>
                    </div>
                </div>
                <!-- Sliding Handles Tooltip -->
            </div>
        </div>

        <!-- Colored Connect Elements -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Colored Connect Elements</div>
                </div>
                <div class="card-body">
                    <div class="slider" id="color-slider"></div>
                </div>
            </div>
        </div>
        <!-- Colored Connect Elements -->

        <!-- Toogle Movement By Clicking Pips -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Toogle Movement By Clicking Pips</div>
                </div>
                <div class="card-body pb-5">
                    <div id="slider-pips"></div>
                </div>
            </div>
        </div>
        <!-- Toogle Movement By Clicking Pips -->

        <!-- Soft Limit -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Soft Limit</div>
                </div>
                <div class="card-body pb-5">
                    <div id="soft"></div>
                </div>
            </div>
        </div>
        <!-- Soft Limit -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- noUiSlider JS -->
    <script src="{{ asset('assets/plugin/nouislider/nouislider.min.js') }}"></script>

    <!-- wNumb JS -->
    <script src="{{asset('assets/plugin/wnumb/wNumb.min.js')}}"></script>

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/form-range-slider-custom.js'
    ])

@endsection