@extends('layouts.app', [
    'title' => 'Create Product Category'
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
                        <a href="{{ route('products.categories.index') }}">Product Categories</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Category</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Category</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.categories.index') }}"
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
    <form action="{{ route('products.categories.create') }}" method="POST"
        id="categoryForm" class="row">
        @csrf
    </form>
    <!-- Container -->



<div class="container-fluid">
   
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('products.categories.store') }}" method="POST" id="categoryForm">
                @csrf
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Category Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('category_name') is-invalid @enderror" 
                                   id="category_name" 
                                   name="category_name" 
                                   value="{{ old('category_name') }}"
                                   required
                                   placeholder="e.g., Raw Materials, Finished Goods">
                            @error('category_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="parent_category_id" class="form-label">
                                Parent Category
                            </label>
                            <select class="form-select @error('parent_category_id') is-invalid @enderror" 
                                    id="parent_category_id" 
                                    name="parent_category_id">
                                <option value="">None (Root Category)</option>
                                @foreach($parentCategories as $parent)
                                <option value="{{ $parent->category_id }}" 
                                        {{ old('parent_category_id') == $parent->category_id ? 'selected' : '' }}>
                                    {{ $parent->category_name }}
                                </option>
                                @endforeach
                            </select>
                            @error('parent_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Select a parent category to create hierarchy</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Describe this category...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional</small>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Category
                        </button>
                        <a href="{{ route('products.categories.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-primary me-2"></i>Category Guidelines
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Category code is auto-generated</li>
                        <li class="mb-2">Use clear, descriptive names</li>
                        <li class="mb-2">Categories can be organized hierarchically</li>
                        <li class="mb-2">Root categories have no parent</li>
                        <li>Subcategories inherit parent structure</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-diagram-3 text-success me-2"></i>Category Structure Example
                    </h6>
                    <div class="small">
                        <div class="mb-2">
                            <strong><i class="bi bi-folder-fill text-primary me-1"></i> Raw Materials</strong>
                            <div class="ms-3 mt-1">
                                <i class="bi bi-arrow-return-right text-muted me-1"></i> Ingredients<br>
                                <i class="bi bi-arrow-return-right text-muted me-1"></i> Packaging
                            </div>
                        </div>
                        <div class="mb-2">
                            <strong><i class="bi bi-folder-fill text-primary me-1"></i> Finished Goods</strong>
                            <div class="ms-3 mt-1">
                                <i class="bi bi-arrow-return-right text-muted me-1"></i> Beverages<br>
                                <i class="bi bi-arrow-return-right text-muted me-1"></i> Snacks
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/product-category-init.js'])

@endsection