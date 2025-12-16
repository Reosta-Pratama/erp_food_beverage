@extends('layouts.app', [
    'title' => 'Products Management'
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
                    <li class="breadcrumb-item active" aria-current="page">Products Management</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center d-flex align-items-center fade show mb-3" role="alert">
            <i class="ti ti-circle-check fs-18 me-2"></i>
            {{ session('success') }}
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Products Management</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.export') }}" class="btn btn-outline-success">
                <i class="ti ti-download me-2"></i>
                Export CSV
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Product
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row">
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Filter & Search</div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}"
                        id="filterForm" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label" for="search">Search</label>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                placeholder="Product name, code, category..."
                                value="{{ request('search') }}"
                            >
                        </div>

                        <!-- Product Type -->
                        <div class="col-md-3">
                            <label class="form-label" for="product_type">Product Type</label>
                            <select id="product_type" name="product_type" class="form-select single-select">
                                <option value="">-- All Types --</option>
                                @foreach ($productTypes as $type)
                                    <option value="{{ $type->product_type }}"
                                        {{ request('product_type') == $type->product_type ? 'selected' : '' }}>
                                        {{ ucfirst($type->product_type) }} ({{ $type->count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category -->
                        <div class="col-md-3">
                            <label class="form-label" for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-select single-select">
                                <option value="">-- All Categories --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ request('category_id') == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-2">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select single-select">
                                <option value="">-- All Status --</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="col-md-3">
                            <label class="form-label" for="price_filter">Price Range</label>
                            <select id="price_filter" name="price_filter" class="form-select single-select">
                                <option value="">-- All Price Range --</option>
                                <option value="budget" {{ request('price_filter') === 'budget' ? 'selected' : '' }}>
                                    Budget (&lt; 10,000)
                                </option>
                                <option value="mid" {{ request('price_filter') === 'mid' ? 'selected' : '' }}>
                                    Mid (10,000 – 50,000)
                                </option>
                                <option value="premium" {{ request('price_filter') === 'premium' ? 'selected' : '' }}>
                                    Premium (&gt; 50,000)
                                </option>
                                <option value="not_set" {{ request('price_filter') === 'not_set' ? 'selected' : '' }}>
                                    Price Not Set (0)
                                </option>
                            </select>
                        </div>

                        <!-- Margin Status -->
                        <div class="col-md-3">
                            <label class="form-label" for="margin_filter">Margin Status</label>
                            <select id="margin_filter" name="margin_filter" class="form-select single-select">
                                <option value="">-- All Margin --</option>
                                <option value="negative" {{ request('margin_filter') === 'negative' ? 'selected' : '' }}>
                                    Negative (Loss)
                                </option>
                                <option value="low" {{ request('margin_filter') === 'low' ? 'selected' : '' }}>
                                    Low (≤ 20%)
                                </option>
                                <option value="healthy" {{ request('margin_filter') === 'healthy' ? 'selected' : '' }}>
                                    Healthy (20–50%)
                                </option>
                                <option value="high" {{ request('margin_filter') === 'high' ? 'selected' : '' }}>
                                    High (&gt; 50%)
                                </option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <label class="form-label" for="sort_by">Sort By</label>
                            <select id="sort_by" name="sort_by" class="form-select single-select">
                                <option value="product_name" {{ request('sort_by') === 'product_name' ? 'selected' : '' }}>
                                    Product Name
                                </option>
                                <option value="product_code" {{ request('sort_by') === 'product_code' ? 'selected' : '' }}>
                                    Product Code
                                </option>
                                <option value="product_type" {{ request('sort_by') === 'product_type' ? 'selected' : '' }}>
                                    Product Type
                                </option>
                                <option value="category_name" {{ request('sort_by') === 'category_name' ? 'selected' : '' }}>
                                    Category
                                </option>
                                <option value="standard_cost" {{ request('sort_by') === 'standard_cost' ? 'selected' : '' }}>
                                    Standard Cost
                                </option>
                                <option value="selling_price" {{ request('sort_by') === 'selling_price' ? 'selected' : '' }}>
                                    Selling Price
                                </option>
                                <option value="profit_margin" {{ request('sort_by') === 'profit_margin' ? 'selected' : '' }}>
                                    Profit Margin
                                </option>
                                <option value="margin_percentage" {{ request('sort_by') === 'margin_percentage' ? 'selected' : '' }}>
                                    Margin %
                                </option>
                                <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>
                                    Created Date
                                </option>
                            </select>
                        </div>

                        <!-- Order -->
                        <div class="col-md-3">
                            <label class="form-label" for="sort_order">Sort Order</label>
                            <select id="sort_order" name="sort_order" class="form-select single-select">
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>
                                    Ascending
                                </option>
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>
                                    Descending
                                </option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" 
                                class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> 
                                Apply Filters
                            </button>
                            <a href="{{ route('products.index') }}" 
                                class="btn btn-danger">
                                <i class="ti ti-x me-1"></i> 
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        Product List
                        <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <button type="button"   
                                id="selectAll"
                                class="btn btn-sm btn-outline-primary" 
                                onclick="selectAll()">
                                <i class="ti ti-square-check me-1"></i>
                                Select All
                            </button>
                            <button type="button" 
                                class="btn btn-sm btn-outline-secondary" 
                                onclick="deselectAll()">
                                <i class="ti ti-square me-1"></i>
                                Deselect All
                            </button>
                            <span class="ms-3 text-muted" id="selectedCount">0 selected</span>
                        </div>

                        <button type="button" 
                            class="btn btn-sm btn-outline-success" 
                            onclick="showBulkPriceModal()">
                            <i class="ti ti-cash me-1"></i>
                            Bulk Update Prices
                        </button>
                    </div>
                </div>
                <div class="card-footer p-0">
                    @if ($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless table-hover mb-0">
                                <thead>
                                    <th width="50">
                                        <input type="checkbox" class="form-check-input" id="selectAllCheckbox" onchange="toggleAll(this)">
                                    </th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">UOM</th>
                                    <th scope="col">Standard Cost</th>
                                    <th scope="col">Selling Price</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input product-checkbox" 
                                                    value="{{ $product->product_id }}">
                                            </td>

                                            <td>
                                                <span class="badge bg-primary">{{ $product->product_code }}</span>
                                            </td>

                                            <td>
                                                <strong>{{ $product->product_name }}</strong>
                                            </td>

                                            <td>
                                                @if($product->product_type === 'Raw Material')
                                                    <span class="badge bg-secondary">Raw Material</span>
                                                @elseif($product->product_type === 'Semi-Finished')
                                                    <span class="badge bg-warning">Semi-Finished</span>
                                                @elseif($product->product_type === 'Finished Goods')
                                                    <span class="badge bg-success">Finished Goods</span>
                                                @elseif($product->product_type === 'Packaging')
                                                    <span class="badge bg-info">Packaging</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ $product->product_type }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('products.categories.show', $product->category_code) }}" 
                                                    class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover text-decoration-underline">
                                                    {{ $product->category_name }}
                                                </a>
                                            </td>

                                            <td>{{ $product->uom_name }}</td>
                                            <td>{{ number_format($product->standard_cost, 2) }}</td>
                                            <td>{{ number_format($product->selling_price, 2) }}</td>

                                            <td>
                                                @if($product->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    @hasPermission('products.manage')
                                                        <form action="{{ route('products.toggle-status', $product->product_code) }}" 
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-{{ $product->is_active ? 'secondary' : 'success' }}" 
                                                                    title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                                                <i class="ti ti-toggle-{{ $product->is_active ? 'right' : 'left' }}"></i>
                                                            </button>
                                                        </form>

                                                        <a href="{{ route('products.show', $product->product_code) }}" 
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="View Details">
                                                                <i class="ti ti-eye"></i>
                                                        </a>

                                                        <a href="{{ route('products.edit', $product->product_code) }}" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Edit">
                                                                <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $product->product_code }}', '{{ $product->product_name }}')"
                                                                title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endhasPermission
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-database-off text-muted display-1"></i>
                            <h5 class="text-muted mt-3">No Product Found</h5>
                            <p class="text-muted">Try adjusting your filters or check back later</p>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> 
                                Create First Product
                            </a>
                        </div>
                    @endif

                    @if($products->hasPages())
                        <div class="d-flex justify-content-center border-top p-4">
                            {{ $products->links('pagination.default') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!-- Delete Confirmation Form -->

@endsection

@section('modals')

    <!-- Bulk Price Update Modal -->
    <div class="modal fade" id="bulkPriceModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="bulkPriceModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('products.bulk-update-prices') }}" method="POST" 
                id="bulkPriceForm" class="modal-content">
                @csrf

                <div class="modal-header d-flex justify-content-between bg-success">
                    <h6 class="modal-title text-white">
                        Bulk Update Prices
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <span id="bulkSelectedCount">0</span> product(s) selected
                    </div>

                    <input type="hidden" name="product_ids" id="bulkProductIds">

                    <div class="mb-3">
                        <label for="price_type" class="form-label">Price Type <span class="text-danger">*</span></label>
                        <select class="form-select single-select" id="price_type" name="price_type" required>
                            <option value="standard_cost">Standard Cost</option>
                            <option value="selling_price">Selling Price</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="adjustment_type" class="form-label">Adjustment Type <span class="text-danger">*</span></label>
                        <select class="form-select single-select" id="adjustment_type" name="adjustment_type" required>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="adjustment_value" class="form-label">Adjustment Value <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control" 
                               id="adjustment_value" 
                               name="adjustment_value" 
                               step="0.01" 
                               required
                               placeholder="e.g., 10 for 10% or 10 for +10">
                        <small class="text-muted">Use positive values to increase, negative to decrease</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-trash me-2"></i>
                        Update Prices
                    </button>
                </div>

            </form>
        </div>
    </div>
    <!-- Bulk Price Update Modal -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/product-init.js'])

    <script>
        function confirmDelete(productCode, productName) {
            Swal.fire({
                icon: 'warning',
                title: `Delete product "${productName}"?`,
                text: "This action cannot be undone.",
                showCancelButton: true,
                confirmButtonColor: "#985ffd",
                cancelButtonColor: "#faf8fd",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/products/${productCode}`;
                    form.submit();
                }
            });

            return false;
        }
    </script>

@endsection