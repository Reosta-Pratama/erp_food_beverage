@extends('layouts.app', [
    'title' => 'Datetime Picker'
])

@section('styles')

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/flatpickr/flatpickr.min.css') }}">

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
                    <li class="breadcrumb-item active" aria-current="page">Date & Time Picker</li>
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
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-calendar-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="date" placeholder="Choose date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Basic -->

        <!-- With Time -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">With Time</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-calendar-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="datetime" placeholder="Choose date with time">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- With Time -->

        <!-- Human Friendly -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Human Friendly</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-calendar-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="humanfrienndlydate" placeholder="Human friendly dates">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Human Friendly -->

        <!-- Date Range -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Date Range</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-calendar-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="daterange" placeholder="Date range picker">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Date Range -->

        <!-- Basic Time Picker -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Basic Time Picker</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-time-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="timepikcr" placeholder="Choose time">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Basic Time Picker -->

        <!-- Time Picker With 24 Hour -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Time Picker With 24 Hour</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-time-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="timepickr1" placeholder="Choose time in 24hr format">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Time Picker With 24 Hour -->

        <!-- Time Picker With Limit -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Time Picker With Limit</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-time-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="limittime" placeholder="choose time min 16:00 to max 22:30">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Time Picker With Limit -->

        <!-- Date Time Picker With Limited Range -->
        <div class="col-xl-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Date Time Picker With Limited Range</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-text text-muted"> 
                                <i class="ri-time-line"></i> 
                            </div>
                            <input type="text" class="form-control" id="limitdatetime" placeholder="date with time limit from 16:00 to 22:00">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Date Time Picker With Limited Range -->
    </div>

    <div class="row gx-4">
        <div class="col-xl-6">
            <div class="row">
                <!-- Date Picker With Week Number -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Date Picker With Week Number</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> 
                                        <i class="ri-calendar-line"></i> 
                                    </div>
                                    <input type="text" class="form-control" id="weeknum" placeholder="Choose date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Date Picker With Week Number -->

                <!-- Inline Time Picker -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Inline Time Picker</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" id="inlinetime" placeholder="Choose time">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inline Time Picker -->

                <!-- Preloading Time -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Preloading Time</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> <i class="ri-time-line"></i> </div>
                                    <input type="text" class="form-control" id="pretime" placeholder="Preloading time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Preloading Time -->
            </div>
        </div>

        <div class="col-xl-6">
            <div class="row">
                <!-- Inline Calender -->
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Inline Calender</div>
                        </div>
                        <div class="card-body">
                            <div class="form-group overflow-auto">
                                <input type="text" class="form-control" id="inlinecalendar" placeholder="Choose date">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inline Calender -->
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/form-date-time-picker-custom.js'
    ])

@endsection