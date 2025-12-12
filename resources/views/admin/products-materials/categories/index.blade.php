@extends('layouts.app', [
    'title' => 'Product Categories'
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
                        <a href="javascript:void(0);">Products & Materials</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Product Categories</li>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Product Categories</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('products.categories.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Category
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
                    <form method="GET" action="{{ route('products.categories.index') }}" 
                        id="filterForm" class="row g-3">

                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label" for="search">Search</label>
                            <input 
                                type="text" 
                                id="search"
                                name="search" 
                                class="form-control"
                                placeholder="Search name, code, description..."
                                value="{{ request('search') }}"
                            >
                        </div>
                        
                        <!-- Parent Category -->
                        <div class="col-md-4">
                            <label class="form-label" for="parent">Parent Category</label>
                            <select id="parent" name="parent" class="form-select single-select">
                                <option value="">-- Select Parent Category --</option>
                                <option value="root" {{ request('parent') === 'root' ? 'selected' : '' }}>Root Only</option>
                                <option value="child" {{ request('parent') === 'child' ? 'selected' : '' }}>Child Categories</option>

                                {{-- Specific parent categories --}}
                                @foreach ($parentCategories as $p)
                                    <option value="{{ $p->category_id }}" 
                                        {{ request('parent') == $p->category_id ? 'selected' : '' }}>
                                        {{ $p->category_code }} — {{ $p->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Product Filter -->
                        <div class="col-md-4">
                            <label class="form-label" for="product_filter">Product Filter</label>
                            <select id="product_filter" name="product_filter" class="form-select single-select">
                                <option value="">-- Select Product Filter --</option>
                                <option value="empty" {{ request('product_filter') === 'empty' ? 'selected' : '' }}>
                                    No Products (0)
                                </option>
                                <option value="has_products" {{ request('product_filter') === 'has_products' ? 'selected' : '' }}>
                                    Has Products (>0)
                                </option>
                                <option value="active" {{ request('product_filter') === 'active' ? 'selected' : '' }}>
                                    Active Category (5+ products)
                                </option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-4">
                            <label class="form-label" for="sort_by">Sort By</label>
                            <select id="sort_by" name="sort_by" class="form-select single-select">
                                <option value="hierarchy" {{ request('sort_by') === 'hierarchy' ? 'selected' : '' }}>Hierarchy</option>
                                <option value="category_code" {{ request('sort_by') === 'category_code' ? 'selected' : '' }}>Category Code</option>
                                <option value="category_name" {{ request('sort_by') === 'category_name' ? 'selected' : '' }}>Category Name</option>
                                <option value="parent_name" {{ request('sort_by') === 'parent_name' ? 'selected' : '' }}>Parent Name</option>
                                <option value="products_count" {{ request('sort_by') === 'products_count' ? 'selected' : '' }}>Products Count</option>
                                <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created At</option>
                            </select>
                        </div>
                        
                        <!-- Order -->
                        <div class="col-md-4">
                            <label class="form-label" for="sort_order">Order</label>
                            <select id="sort_order" name="sort_order" class="form-select single-select">
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" 
                                class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> 
                                Apply Filters
                            </button>
                            <a href="{{ route('products.categories.index') }}" 
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
                        Category List
                        <span class="badge bg-primary ms-2">{{ $categories->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }}
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless table-hover mb-0">
                                <thead>
                                    <th scope="col">
                                        <a href="{{ route('products.categories.index', [
                                            ...request()->all(),
                                            'sort_by'    => 'category_code',
                                            'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                            Code
                                            @if(request('sort_by') == 'category_code')
                                                <i class="ti ti-arrow-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th scope="col">
                                        <a href="{{ route('products.categories.index', [
                                            ...request()->all(),
                                            'sort_by'    => 'category_name',
                                            'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                            Category Name
                                            @if(request('sort_by') == 'category_name')
                                                <i class="ti ti-arrow-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th scope="col">
                                        <a href="{{ route('products.categories.index', [
                                            ...request()->all(),
                                            'sort_by'    => 'parent_name',
                                            'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                            Parent Category
                                            @if(request('sort_by') == 'parent_name')
                                                <i class="ti ti-arrow-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th scope="col">
                                        <a href="{{ route('products.categories.index', [
                                            ...request()->all(),
                                            'sort_by'    => 'products_count',
                                            'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                            Products
                                            @if(request('sort_by') == 'products_count')
                                                <i class="ti ti-arrow-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th scope="col">
                                        <a href="{{ route('products.categories.index', [
                                            ...request()->all(),
                                            'sort_by'    => 'created_at',
                                            'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc',
                                        ]) }}">
                                            Created Date
                                            @if(request('sort_by') == 'created_at')
                                                <i class="ti ti-arrow-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>

                                    <th scope="col" class="text-center">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $category->category_code }}</span>
                                            </td>

                                            <td>
                                                @if($category->parent_category_id)
                                                    <i class="ti ti-corner-down-right text-muted me-2"></i>
                                                @endif
                                                <strong>{{ $category->category_name }}</strong>
                                                @if($category->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                                @endif
                                            </td>

                                            <td>
                                                @if($category->parent_name)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="ti ti-folder me-1"></i>
                                                        {{ $category->parent_name }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="ti ti-folder-filled me-1"></i>
                                                        Root Category
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($category->products_count > 0)
                                                    <span class="badge bg-{{ $category->products_count >= 5 ? 'success' : 'light text-dark' }}">
                                                        {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">No products</span>
                                                @endif
                                            </td>

                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($category->created_at)->format('d M Y') }}</small>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    @hasPermission('products.manage')
                                                        <a href="{{ route('products.categories.show', $category->category_code) }}" 
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="View Details">
                                                                <i class="ti ti-eye"></i>
                                                        </a>

                                                        <a href="{{ route('products.categories.edit', $category->category_code) }}" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Edit">
                                                                <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $category->category_code }}', '{{ $category->category_name }}', {{ $category->products_count }})"
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
                            <h5 class="text-muted mt-3">No Category Found</h5>
                            <p class="text-muted">Try adjusting your filters or check back later</p>
                            <a href="{{ route('products.categories.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> 
                                Create First Category
                            </a>
                        </div>
                    @endif
                </div>

                @if($categories->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $categories->links('pagination.default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('modals')

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="deleteForm" class="modal-content">
                @csrf

                <div class="modal-header d-flex justify-content-between bg-danger">
                    <h6 class="modal-title text-white">
                        Confirm Delete
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete category <strong id="deleteCategoryName"></strong>?</p>

                    <div id="deleteWarning" class="alert alert-warning" style="display: none;">
                        <i class="ti ti-exclamation-circle me-2"></i>
                        <span id="deleteWarningText"></span>
                    </div>

                    <p class="text-danger small mb-0">
                        <i class="ti ti-info-circle me-1"></i>
                        This action cannot be undone.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="ti ti-trash me-2"></i>
                        Delete Category
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')

    @vite(['resources/assets/js/erp/product-category-init.js'])

    <script>
        function confirmDelete(code, name, productsCount) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const deleteForm = document.getElementById('deleteForm');
            const deleteCategoryName = document.getElementById('deleteCategoryName');
            const deleteWarning = document.getElementById('deleteWarning');
            const deleteWarningText = document.getElementById('deleteWarningText');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            
            deleteForm.action = `/products/categories/${code}`;
            deleteCategoryName.textContent = name;
            
            if (productsCount > 0) {
                deleteWarning.style.display = 'block';
                deleteWarningText.textContent = `This category has ${productsCount} product(s) assigned. Please remove or reassign the products first.`;
                confirmDeleteBtn.disabled = true;
            } else {
                deleteWarning.style.display = 'none';
                confirmDeleteBtn.disabled = false;
            }
            
            deleteModal.show();
        }
    </script>

@endsection