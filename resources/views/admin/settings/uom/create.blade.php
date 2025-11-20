@extends('layouts.app', [
    'title' => 'Add New Unit of Measure'
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
                    <li class="breadcrumb-item active" aria-current="page">Create New Units of Measure</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Units of Measure</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.uom.create') }}"
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

    <!-- Container -->
    <form action="{{ route('admin.settings.uom.store') }}" method="POST" id="uomForm"
        class="row">
        @csrf

        <div class="col-md-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Unit Information</div>
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
                                value="{{ old('uom_name') }}" 
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
                                           class="form-check-input crud-checkbox d-none" 
                                           id="type_{{ $type['value'] }}" 
                                           name="uom_type"
                                           value="{{ $type['value'] }}"
                                           {{ old('uom_type') === $type['value'] ? 'checked' : '' }}>
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
        
        <div class="col-md-6">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Common Examples</div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.settings.uom.index') }}" 
                    class="btn btn-outline-secondary">
                    <i class="ti ti-x me-2"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-check me-2"></i>
                    Create Unit
                </button>
            </div>
        </div>

    </form>
    <!-- Container -->


<div class="container-fluid">
   

    <form action="{{ route('admin.settings.uom.store') }}" method="POST" id="uomForm">
        @csrf
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                {{-- Unit Information --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Unit Information
                        </h5>
                    </div>
                    <div class="card-body">
                        
                        {{-- Unit Name --}}
                        <div class="mb-4">
                            <label for="uom_name" class="form-label fw-bold">
                                Unit Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('uom_name') is-invalid @enderror" 
                                   id="uom_name" 
                                   name="uom_name" 
                                   value="{{ old('uom_name') }}" 
                                   placeholder="e.g., Kilogram, Liter, Meter, Piece"
                                   required
                                   autofocus>
                            @error('uom_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter the full name of the unit (e.g., "Kilogram" not "kg")</small>
                        </div>

                        

                    </div>
                </div>

                {{-- Examples --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightbulb me-2"></i>Common Examples
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success"><i class="bi bi-bar-chart me-2"></i>Weight Units</h6>
                                <ul class="small">
                                    <li>Kilogram (kg)</li>
                                    <li>Gram (g)</li>
                                    <li>Ton</li>
                                    <li>Pound (lb)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary"><i class="bi bi-cup me-2"></i>Volume Units</h6>
                                <ul class="small">
                                    <li>Liter (L)</li>
                                    <li>Milliliter (mL)</li>
                                    <li>Gallon</li>
                                    <li>Cubic Meter (m³)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning"><i class="bi bi-rulers me-2"></i>Length Units</h6>
                                <ul class="small">
                                    <li>Meter (m)</li>
                                    <li>Centimeter (cm)</li>
                                    <li>Inch</li>
                                    <li>Foot</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-danger"><i class="bi bi-123 me-2"></i>Quantity Units</h6>
                                <ul class="small">
                                    <li>Piece (pcs)</li>
                                    <li>Box</li>
                                    <li>Dozen</li>
                                    <li>Pack</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.settings.uom.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Create Unit
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection

@section('modals')
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-measure.js'])

@endsection