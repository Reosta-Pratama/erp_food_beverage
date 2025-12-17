@extends('layouts.app', [
    'title' => 'Create BOM'
])

@section('styles')

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.css') }}">

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
                        <a href="javascript:void(0);">BOM & Recipes</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('inventory.bom.index') }}">Bill of Materials</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create BOM</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New BOM</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('inventory.bom.index') }}"
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
    <form method="POST" action="{{ route('inventory.bom.store') }}"
        id="bomForm" class="row">
        @csrf

        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">BOM Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="product_id">
                                Product (Finished Goods) <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('product_id') is-invalid @enderror" 
                                    id="product_id" 
                                    name="product_id"
                                    data-name="Product">
                                <option value="">Select Product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" 
                                            {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                        {{ $product->product_name }} ({{ $product->product_code }}) - {{ $product->uom_code }}
                                    </option>
                                @endforeach
                            </select>

                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="bom_version">
                                BOM Version <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                   class="form-control @error('bom_version') is-invalid @enderror" 
                                   id="bom_version"
                                   name="bom_version" 
                                   value="{{ old('bom_version', '1.0') }}"
                                   placeholder="e.g., 1.0, 2.1"
                                   required>

                            @error('bom_version')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="effective_date" class="form-label">
                                Effective Date <span class="text-danger">*</span>
                            </label>

                            @php
                                $inputDob = old('effective_date', date('Y-m-d'));
                            @endphp

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> 
                                        <i class="ti ti-calendar"></i> 
                                    </div>
                                    <input class="form-control single-date @error('effective_date') is-invalid @enderror"
                                        date=@json($inputDob)
                                        id="effective_date"
                                        name="effective_date"
                                        type="text"  
                                        required>
                                </div>
                            </div>

                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                        type="checkbox" 
                                        id="isActive" 
                                        name="isActive" 
                                        value="1"
                                        {{ old('isActive', true) ? 'checked' : '' }}>

                                <label class="form-check-label" for="isActive">
                                    <strong>Active BOM</strong><br>
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="notes">Notes</label>

                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                    id="notes" 
                                    name="notes" 
                                    rows="3"
                                    placeholder="Additional notes...">{{ old('notes') }}</textarea>

                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center gap-2">
                    <div class="card-title">BOM Items</div>
                    <button type="button" class="btn btn-sm btn-primary" 
                        onclick="addBOMItem()">
                        <i class="ti ti-plus"></i> 
                        Add Item
                    </button>
                </div>
                
                <div class="card-body">
                    @error('items')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div id="bomItemsContainer" class="row g-3">
                        {{-- BOM items will be added here dynamically --}}
                    </div>
                    
                    <div id="emptyState" class="text-center py-5">
                        <i class="ti ti-package-off text-muted display-1"></i>
                        <h5 class="text-muted mt-3">No items added yet</h5>
                        <p class="text-muted">Click "Add Item" to start building your BOM</p>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Total Items:</span>
                            <code class="text-primary fs-18" id="totalItems">0</code>
                        </div>
                        <div>
                            <span class="text-muted">Estimated Cost:</span>
                            <code class="text-primary fs-18" id="totalCost">Rp 0.00</code>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('inventory.bom.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-circle-check me-2"></i>
                            Create BOM
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->

    <!-- BOM Item Template -->
    <template id="bomItemTemplate">
        <div class="bom-item col-12" data-index="INDEX_PLACEHOLDER">
            <div class="card custom mb-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="mb-0">Item #<span class="item-number">INDEX_PLACEHOLDER</span></h6>
                        <button type="button" class="btn btn-sm btn-danger" 
                            onclick="removeBOMItem(this)">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>

                    <div class="row">
                        {{-- Material Selection --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Material <span class="text-danger">*</span>
                            </label>

                            <select class="form-select material-select" 
                                    name="items[INDEX_PLACEHOLDER][material_id]" 
                                    onchange="updateMaterialInfo(this)"
                                    data-name="Material">
                                <option value="">Select Material...</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->product_id }}" 
                                            data-cost="{{ $material->standard_cost }}"
                                            data-uom="{{ $material->uom_id }}"
                                            data-uom-code="{{ $material->uom_code }}"
                                            data-type="{{ $material->product_type }}">
                                        {{ $material->product_name }} ({{ $material->product_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Item Type --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Item Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" 
                                    name="items[INDEX_PLACEHOLDER][item_type]" 
                                    required>
                                <option value="">Select Type...</option>
                                <option value="Raw Material">Raw Material</option>
                                <option value="Semi-Finished">Semi-Finished</option>
                                <option value="Packaging">Packaging</option>
                            </select>
                        </div>

                        {{-- Quantity Required --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Quantity <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                class="form-control quantity-input" 
                                name="items[INDEX_PLACEHOLDER][quantity_required]" 
                                step="0.0001" 
                                min="0.0001"
                                onchange="calculateItemCost(this)"
                                required>
                        </div>

                        {{-- Unit of Measure --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                UOM <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" 
                                    name="items[INDEX_PLACEHOLDER][uom_id]" 
                                    required>
                                <option value="">Select UOM...</option>
                                @foreach($uoms as $uom)
                                    <option value="{{ $uom->uom_id }}">
                                        {{ $uom->uom_name }} ({{ $uom->uom_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Scrap Percentage --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Scrap %</label>
                            <input type="number" 
                                class="form-control" 
                                name="items[INDEX_PLACEHOLDER][scrap_percentage]" 
                                step="0.01" 
                                min="0" 
                                max="100"
                                value="0">
                        </div>

                        {{-- Cost Info (Read-only) --}}
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <div class="d-flex justify-content-between">
                                    <span>Unit Cost: <strong class="unit-cost">Rp 0.00</strong></span>
                                    <span>Item Cost: <strong class="item-cost">Rp 0.00</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <!-- BOM Item Template -->

@endsection

@section('modals')
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/bom-init.js'])

@endsection