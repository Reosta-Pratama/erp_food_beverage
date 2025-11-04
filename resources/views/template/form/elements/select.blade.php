@extends('layouts.app', [
    'title' => 'Select'
])

@section('styles')
@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Form Select</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <h6 class="mb-3">Default Select:</h6>

        <!-- Default -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Default</div>
                </div>
                <div class="card-body">
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Default -->

        <!-- Disabled -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Disabled</div>
                </div>
                <div class="card-body">
                    <select class="form-select" aria-label="Disabled select example" disabled="">
                        <option selected="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Disabled -->

        <!-- Rounded -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Rounded</div>
                </div>
                <div class="card-body">
                    <select class="form-select rounded-pill" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Rounded -->

        <!-- Multiple Attribute Select -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Multiple Attribute Select</div>
                </div>
                <div class="card-body">
                    <select class="form-select" multiple="" aria-label="multiple select example">
                        <option selected="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Multiple Attribute Select -->

        <!-- Size Attribute -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Size Attribute</div>
                </div>
                <div class="card-body">
                    <select class="form-select" size="4" aria-label="size 3 select example">
                        <option selected="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        <option value="4">Four</option>
                        <option value="5">Five</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Size Attribute -->

        <!-- Sizing -->
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Sizing</div>
                </div>
                <div class="card-body">
                    <select class="form-select form-select-sm mb-3" aria-label=".form-select-sm example">
                        <option selected="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    
                    <select class="form-select mb-3" aria-label="Default select">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>

                    <select class="form-select form-select-lg"
                        aria-label=".form-select-lg example">
                        <option selected="">Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Sizing -->
    </div>

    <div class="row gx-4">
        <h6 class="mb-3">Choice JS:</h6>

        <div class="col-xl-6">
            <div class="row">
                <!-- Multiple Select -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Multiple Select</div>
                        </div>
                        <div class="card-body">
                            <p class="fw-medium mb-2">Default</p>
                            <select
                                class="form-control"
                                data-trigger="data-trigger"
                                name="choices-multiple-default"
                                id="choices-multiple-default"
                                multiple="multiple">
                                <option value="Choice 1" selected>Choice 1</option>
                                <option value="Choice 2">Choice 2</option>
                                <option value="Choice 3" selected>Choice 3</option>
                                <option value="Choice 4" disabled>Choice 4</option>
                                <option value="Choice 5">Choice 5</option>
                            </select>

                            <p class="fw-medium mb-2">With Remove Button</p>
                            <select
                                class="form-control"
                                name="choices-multiple-remove-button"
                                id="choices-multiple-remove-button"
                                multiple="multiple">
                                <option value="Choice 1" selected="selected">Choice 1</option>
                                <option value="Choice 2">Choice 2</option>
                                <option value="Choice 3">Choice 3</option>
                                <option value="Choice 4">Choice 4</option>
                            </select>

                            <p class="fw-medium mb-2">Option groups</p>
                            <select
                                class="form-control"
                                name="choices-multiple-groups"
                                id="choices-multiple-groups"
                                multiple="multiple">
                                <option value="">Choose a city</option>
                                <optgroup label="UK">
                                    <option value="London">London</option>
                                    <option value="Manchester">Manchester</option>
                                    <option value="Liverpool">Liverpool</option>
                                </optgroup>
                                <optgroup label="FR">
                                    <option value="Paris">Paris</option>
                                    <option value="Lyon">Lyon</option>
                                    <option value="Marseille">Marseille</option>
                                </optgroup>
                                <optgroup label="DE" disabled="disabled">
                                    <option value="Hamburg">Hamburg</option>
                                    <option value="Munich">Munich</option>
                                    <option value="Berlin">Berlin</option>
                                </optgroup>
                                <optgroup label="US">
                                    <option value="New York">New York</option>
                                    <option value="Washington" disabled="disabled">Washington</option>
                                    <option value="Michigan">Michigan</option>
                                </optgroup>
                                <optgroup label="SP">
                                    <option value="Madrid">Madrid</option>
                                    <option value="Barcelona">Barcelona</option>
                                    <option value="Malaga">Malaga</option>
                                </optgroup>
                                <optgroup label="CA">
                                    <option value="Montreal">Montreal</option>
                                    <option value="Toronto">Toronto</option>
                                    <option value="Vancouver">Vancouver</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Multiple Select -->

                <!-- Passing Through Option -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Passing Through Option</div>
                        </div>
                        <div class="card-body">
                            <input
                                class="form-control"
                                id="choices-text-preset-values"
                                type="text"
                                value="three"
                                placeholder="This is a placeholder">
                        </div>
                    </div>
                </div>
                <!-- Passing Through Option -->

                <!-- Option Added Via Config With No Search -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Option Added Via Config With No Search</div>
                        </div>
                        <div class="card-body">
                            <select class="form-control" name="choices-single-no-search" id="choices-single-no-search">
                                <option value="0">Zero</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Option Added Via Config With No Search -->
            </div>
        </div>

        <div class="col-xl-6">
            <div class="row">
                <!-- Single Select -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Single Select</div>
                        </div>
                        <div class="card-body">
                            <p class="fw-medium mb-2">Default</p>
                            <select
                                class="form-control"
                                data-trigger="data-trigger"
                                name="choices-single-default"
                                id="choices-single-default">
                                <option value="">This is a placeholder</option>
                                <option value="Choice 1">Choice 1</option>
                                <option value="Choice 2">Choice 2</option>
                                <option value="Choice 3">Choice 3</option>
                            </select>

                            <p class="fw-medium mb-2">Option groups</p>
                            <select
                                class="form-control"
                                data-trigger="data-trigger"
                                name="choices-single-groups"
                                id="choices-single-groups">
                                <option value="">Choose a city</option>
                                <optgroup label="UK">
                                    <option value="London">London</option>
                                    <option value="Manchester">Manchester</option>
                                    <option value="Liverpool">Liverpool</option>
                                </optgroup>
                                <optgroup label="FR">
                                    <option value="Paris">Paris</option>
                                    <option value="Lyon">Lyon</option>
                                    <option value="Marseille">Marseille</option>
                                </optgroup>
                                <optgroup label="DE" disabled="disabled">
                                    <option value="Hamburg">Hamburg</option>
                                    <option value="Munich">Munich</option>
                                    <option value="Berlin">Berlin</option>
                                </optgroup>
                                <optgroup label="US">
                                    <option value="New York">New York</option>
                                    <option value="Washington" disabled="disabled">Washington</option>
                                    <option value="Michigan">Michigan</option>
                                </optgroup>
                                <optgroup label="SP">
                                    <option value="Madrid">Madrid</option>
                                    <option value="Barcelona">Barcelona</option>
                                    <option value="Malaga">Malaga</option>
                                </optgroup>
                                <optgroup label="CA">
                                    <option value="Montreal">Montreal</option>
                                    <option value="Toronto">Toronto</option>
                                    <option value="Vancouver">Vancouver</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Single Select -->

                <!-- Email Address Only -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Email Address Only</div>
                        </div>
                        <div class="card-body">
                            <input class="form-control" id="choices-text-email-filter" type="text" placeholder="This is a placeholder">
                        </div>
                    </div>
                </div>
                <!-- Email Address Only -->

                <!-- Passing Unique Value -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Passing Unique Value</div>
                        </div>
                        <div class="card-body">
                            <input class="form-control" id="choices-text-unique-values" type="text" value="child-1, child-2" placeholder="This is a placeholder">
                        </div>
                    </div>
                </div>
                <!-- Passing Unique Value -->
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/form-select-custom.js'
    ])

@endsection