@extends('layouts.app', [
    'title' => 'Edit Unit - ' . $uom->uom_name
])

@section('styles')

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.css') }}">

    <style>
        .crud-checkbox-card {
            transition: all 300ms ease;
            cursor: pointer;
        }
        .crud-checkbox-card:hover {
            border-color: var(--primary);
        }
        .crud-checkbox-card.checked {
            border-color: var(--primary);
        }
    </style>

@endsection

@section('content')


    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Settings</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Units of Measure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Units of Measure</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit Units of Measure</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.uom.show', $uom->uom_code) }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h6 class="d-flex align-items-center">
                <i class="ti ti-exclamation-circle fs-18 me-2"></i>
                Please fix the following errors:
            </h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert">
                <i class="ti ti-x"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible d-flex align-items-center fade show mb-3" role="alert">
            <i class="ti ti-exclamation-circle fs-18 me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert">
                <i class="ti ti-x"></i>
            </button>
        </div>
    @endif

    <!-- Container -->
    <form action="{{ route('admin.settings.uom.update', $uom->uom_code) }}" method="POST" 
        id="uomForm" class="row justify-content-center mb-4">
        @csrf
        @method('PUT')

        <div class="col-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Current Information</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">Unit Code</small>
                            <code class="d-block mb-3">{{ $uom->uom_code }}</code>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Created</small>
                            <div class="small">{{ \Carbon\Carbon::parse($uom->created_at)->format('d M Y - H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-8">
            <div  class="card custom">
                <div class="card-header">
                    <div class="card-title">Edit Unit Information</div>
                </div>
                <div class="card-body">

                    {{-- Unit Name --}}
                    <div class="mb-3">
                        <label for="uom_name" class="form-label fw-bold">
                            Unit Name <span class="text-danger">*</span>
                        </label>

                        <input type="text" 
                                class="form-control form-control-lg @error('uom_name') is-invalid @enderror" 
                                id="uom_name" 
                                name="uom_name" 
                                value="{{ old('uom_name', $uom->uom_name) }}" 
                                placeholder="e.g., Kilogram, Liter, Meter, Piece"
                                required
                                autocomplete="off"
                                autofocus>

                        @error('uom_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Enter the full name of the unit (e.g., "Kilogram" not "kg")</small>
                    </div>

                    {{-- Unit Type --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Unit Type <span class="text-danger">*</span>
                        </label>
                        <p class="text-muted small mb-3">Select the category that best describes this unit</p>
                        
                        <div class="row g-3">
                            @php
                                $types = [
                                    ['value' => 'Weight', 'icon' => 'ti-file-analytics', 'color' => 'success', 'examples' => 'kg, g, ton'],
                                    ['value' => 'Volume', 'icon' => 'ti-flask', 'color' => 'primary', 'examples' => 'liter, ml, gallon'],
                                    ['value' => 'Length', 'icon' => 'ti-ruler', 'color' => 'warning', 'examples' => 'meter, cm, inch'],
                                    ['value' => 'Quantity', 'icon' => 'ti-123', 'color' => 'danger', 'examples' => 'piece, box, dozen'],
                                    ['value' => 'Time', 'icon' => 'ti-clock', 'color' => 'info', 'examples' => 'hour, day, month'],
                                    ['value' => 'Other', 'icon' => 'ti-dots', 'color' => 'secondary', 'examples' => 'custom units'],
                                ];
                            @endphp

                            @foreach($types as $type)
                                <div class="col-md-4">
                                    <label class="card custom crud-checkbox-card text-center p-3 mb-0" 
                                        for="type_{{ $type['value'] }}">
                                        <input type="radio" 
                                           class="form-check-input crud-checkbox d-none {{ $uom->uom_type === $type['value'] ? 'checked' : '' }}" 
                                           id="type_{{ $type['value'] }}" 
                                           name="uom_type"
                                           value="{{ $type['value'] }}"
                                           {{ old('uom_type', $uom->uom_type) === $type['value'] ? 'checked' : '' }}>
                                            <i class="ti {{ $type['icon'] }} text-{{ $type['color'] }} fs-36"></i>
                                            <h6 class="mb-1">{{ $type['value'] }}</h6>
                                            <small class="text-muted">{{ $type['examples'] }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        @error('uom_type')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="col-8">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.settings.uom.show', $uom->uom_code) }}" 
                    class="btn btn-outline-secondary">
                    <i class="ti ti-x me-2"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check me-2"></i>
                    Update Unit
                </button>
            </div>
        </div>
    </form>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-measure.js'])

@endsection