@extends('layouts.app', [
    'title' => 'Edit Product'
])

@section('styles')

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.css') }}">

@endsection

@section('content')


    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Products & Materials</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.index') }}">Products Management</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit {{ $product->product_name }}</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.index') }}"
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
    <form action="{{ route('products.update', $product->product_code) }}" method="POST"
        id="productForm" class="row">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Product Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Product Code (Read-only) -->
                        <div class="col-12">
                            <label class="form-label">Product Code</label>
                            <input type="text" 
                                    class="form-control" 
                                    value="{{ $product->product_code }}"
                                    readonly
                                    disabled>
                            <small class="form-text text-muted">
                                Auto-generated and cannot be changed
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="product_name" class="form-label">
                                Product Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control @error('product_name') is-invalid @enderror" 
                                    id="product_name" 
                                    name="product_name" 
                                    data-name="Product Name"
                                    value="{{ old('product_name', $product->product_name) }}"
                                    required
                                    placeholder="e.g., Premium Sugar 1kg">

                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="product_type" class="form-label">
                                Product Type <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('product_type') is-invalid @enderror" 
                                    id="product_type" 
                                    name="product_type"
                                    data-name="Product Type">
                                <option value="">Select Type</option>
                                <option value="Raw Material" {{ old('product_type', $product->product_type) == 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                                <option value="Semi-Finished" {{ old('product_type', $product->product_type) == 'Semi-Finished' ? 'selected' : '' }}>Semi-Finished</option>
                                <option value="Finished Goods" {{ old('product_type', $product->product_type) == 'Finished Goods' ? 'selected' : '' }}>Finished Goods</option>
                                <option value="Packaging" {{ old('product_type', $product->product_type) == 'Packaging' ? 'selected' : '' }}>Packaging</option>
                                <option value="Consumable" {{ old('product_type', $product->product_type) == 'Consumable' ? 'selected' : '' }}>Consumable</option>
                            </select>

                            @error('product_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">
                                Category <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id"
                                    data-name="Category">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" 
                                            {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="uom_id" class="form-label">
                                Unit of Measure <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('uom_id') is-invalid @enderror" 
                                    id="uom_id" 
                                    name="uom_id" 
                                    data-name="Unit of Measure">
                                <option value="">Select UOM</option>
                                @php
                                    $currentType = '';
                                @endphp
                                @foreach($uoms as $uom)
                                    @if($currentType !== $uom->uom_type)
                                        @if($currentType !== '')
                                            </optgroup>
                                        @endif
                                        <optgroup label="{{ $uom->uom_type }}">
                                        @php $currentType = $uom->uom_type; @endphp
                                    @endif
                                    <option value="{{ $uom->uom_id }}" 
                                            {{ old('uom_id', $product->uom_id) == $uom->uom_id ? 'selected' : '' }}>
                                        {{ $uom->uom_name }} ({{ $uom->uom_code }})
                                    </option>
                                @endforeach
                                @if($currentType !== '')
                                    </optgroup>
                                @endif
                            </select>

                            @error('uom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>

                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Product description, specifications, notes...">{{ old('description', $product->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pricing Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="standard_cost" class="form-label">Standard Cost</label>

                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                        class="form-control @error('standard_cost') is-invalid @enderror" 
                                        id="standard_cost" 
                                        name="standard_cost" 
                                        value="{{ old('standard_cost', $product->standard_cost) }}"
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00">

                                @error('standard_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Cost per unit</small>
                        </div>

                        <div class="col-md-6">
                            <label for="selling_price" class="form-label">Selling Price</label>

                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                        class="form-control @error('selling_price') is-invalid @enderror" 
                                        id="selling_price" 
                                        name="selling_price" 
                                        value="{{ old('selling_price', $product->selling_price) }}"
                                        step="0.01"
                                        min="0"
                                        placeholder="0.00">

                                @error('selling_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <small class="text-muted">Price per unit</small>
                        </div>

                        <div class="col-md-6">
                            <div class="alert alert-info mb-0">
                                <strong>Margin:</strong> 
                                <span id="marginDisplay">-</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="alert alert-success mb-0">
                                <strong>Markup:</strong> 
                                <span id="markupDisplay">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> 
                            Cancel
                        </a>
                        <div>
                            <a href="{{ route('products.show', $product->product_code) }}" 
                                class="btn btn-info me-2">
                                <i class="ti ti-eye me-1"></i> 
                                View Details
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-circle-check me-1"></i> 
                                Update Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Status</div>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                                type="checkbox" 
                                id="is_active" 
                                name="is_active" 
                                value="1"
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Active Status</strong>
                            <br>
                            <small class="text-muted">Active products are available for transactions</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Current Information</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Code</strong></span>
                                <span class="text-muted">{{ $product->product_code }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Created</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($product->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Last Updated</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($product->updated_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Update Guidelines</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Product code cannot be changed
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Product name should be descriptive
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Changing UOM affects inventory calculations
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Price changes don't affect existing orders
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Cannot delete if has related records
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Inactive products don't appear in transactions
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>
    
    @vite(['resources/assets/js/erp/product-init.js'])

@endsection