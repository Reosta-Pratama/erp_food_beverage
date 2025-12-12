@extends('layouts.app', [
    'title' => 'Edit Category'
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
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $category->category_name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit {{ $category->category_name }}</h2>

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
    <form action="{{ route('products.categories.update', $category->category_code) }}" method="POST"
        id="categoryForm" class="row">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Category Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Category Code (Read-only) -->
                        <div class="col-12">
                            <label class="form-label">Category Code</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $category->category_code }}"
                                   readonly
                                   disabled>
                            <small class="form-text text-muted">
                                Auto-generated and cannot be changed
                            </small>
                        </div>

                        <div class="col-12">
                            <label for="category_name" class="form-label">
                                Category Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                   class="form-control @error('category_name') is-invalid @enderror" 
                                   id="category_name" 
                                   name="category_name" 
                                   value="{{ old('category_name', $category->category_name) }}"
                                   required
                                   placeholder="e.g., Raw Materials, Finished Goods">

                            @error('category_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="parent_category_id" class="form-label">
                                Parent Category
                            </label>

                            <div>
                                <select class="form-select single-select @error('parent_category_id') is-invalid @enderror" 
                                        id="parent_category_id" 
                                        name="parent_category_id">
                                    <option value="">None (Root Category)</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->category_id }}" 
                                                {{ old('parent_category_id', $category->parent_category_id) == $parent->category_id ? 'selected' : '' }}>
                                            {{ $parent->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('parent_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Optional: Select a parent category to create hierarchy</small>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Describe this category...">{{ old('description', $category->description) }}</textarea>
                            
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <small class="text-muted">Optional</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('products.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> 
                            Cancel
                        </a>
                        <div>
                            <a href="{{ route('products.categories.show', $category->category_code) }}" 
                                class="btn btn-info me-2">
                                <i class="ti ti-eye me-1"></i> 
                                View Details
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-circle-check me-1"></i> 
                                Update Category
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Current Information</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Code</strong></span>
                                <span class="text-muted">{{ $category->category_code }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Created</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($category->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Last Updated</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($category->updated_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Category Guidelines</div>
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
                                    Category code cannot be changed
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
                                    Use clear, descriptive names
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
                                    Categories can be organized hierarchically
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
                                    Root categories have no parent
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
                                    Subcategories inherit parent structure
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

    @vite(['resources/assets/js/erp/product-category-init.js'])

@endsection