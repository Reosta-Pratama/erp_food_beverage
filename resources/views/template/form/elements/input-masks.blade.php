@extends('layouts.app', [
    'title' => 'Input Mask'
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
                        <a href="javascript:void(0);">Form</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Form Elements</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Input Masks</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row gx-4">
        <!-- Date Format 1 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Date Format 1</div>
                </div>
                <div class="card-body">
                    <input class="form-control date-format-1" placeholder="DD-MM-YYYY">
                </div>
            </div>
        </div>
        <!-- Date Format 1 -->

        <!-- Date Format 2 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Date Format 2</div>
                </div>
                <div class="card-body">
                    <input class="form-control date-format-2" placeholder="MM-DD-YYYY">
                </div>
            </div>
        </div>
        <!-- Date Format 2 -->

        <!-- Date Format 3 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Date Format 3</div>
                </div>
                <div class="card-body">
                    <input class="form-control date-format-3" placeholder="MM-YY">
                </div>
            </div>
        </div>
        <!-- Date Format 3 -->

        <!-- Number Format -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Number Format</div>
                </div>
                <div class="card-body">
                    <input class="form-control number-format" placeholder="Number Here">
                </div>
            </div>
        </div>
        <!-- Number Format -->

        <!-- Time Format 1 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Time Format 1</div>
                </div>
                <div class="card-body">
                    <input class="form-control time-format-1" placeholder="hh:mm:ss">
                </div>
            </div>
        </div>
        <!-- Time Format 1 -->

        <!-- Time Format 2 -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Time Format 2</div>
                </div>
                <div class="card-body">
                    <input class="form-control time-format-2" placeholder="hh:mm">
                </div>
            </div>
        </div>
        <!-- Time Format 2 -->

        <!-- Formatting Into Block -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Formatting Into Block</div>
                </div>
                <div class="card-body">
                    <input class="form-control formatting-blocks" placeholder="ABCD EFG HIJ KLMN">
                </div>
            </div>
        </div>
        <!-- Formatting Into Block -->

        <!-- Delimiter -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Delimiter</div>
                </div>
                <div class="card-body">
                    <input class="form-control delimiter" placeholder="ABC.DEF.GHi">
                </div>
            </div>
        </div>
        <!-- Delimiter -->

        <!-- Multiple Delimiter -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Multiple Delimiter</div>
                </div>
                <div class="card-body">
                    <input class="form-control delimiters" placeholder="ABC/DEF/GHi-JK">
                </div>
            </div>
        </div>
        <!-- Multiple Delimiter -->

        <!-- Prefix -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Prefix</div>
                </div>
                <div class="card-body">
                    <input class="form-control prefix-element" type="text">
                </div>
            </div>
        </div>
        <!-- Prefix -->

        <!-- Phone Number Format -->
        <div class="col-xl-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Phone Number Format</div>
                </div>
                <div class="card-body">
                    <input class="form-control phone-number"  placeholder="ID(+62)">
                </div>
            </div>
        </div>
        <!-- Phone Number Format -->
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Cleave JS -->
    <script src="{{ asset('assets/plugin/cleave.js/cleave.min.js') }}"></script>
    
    <!-- Page JS -->
    @vite([
        'resources/assets/js/custom/form-input-masks-custom.js'
    ])

@endsection