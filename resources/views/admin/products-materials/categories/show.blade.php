@extends('layouts.app', [
    'title' => 'Category Details'
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('products.categories.index') }}">Product Categories</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Category Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Category Details</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.categories.edit', $category->category_code) }}" 
               class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i> 
                Edit
            </a>
            <button type="button" 
                    class="btn btn-danger"
                    onclick="confirmDelete('{{ $category->category_code }}', '{{ $category->category_name }}', {{ $products->count() }})">
                <i class="ti ti-trash me-2"></i> 
                Delete
            </button>
        </div>
    </div>

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

    <!-- Container -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Category Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Category Code</label>
                            <div>
                                <code class="fs-5 text-primary">{{ $category->category_code }}</code>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Category Name</label>
                            <div>
                                <strong class="fs-5">{{ $category->category_name }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Category Type</label>
                            <div>
                                @if($category->parent_name)
                                    <span class="badge bg-success fs-6">Subcategory</span>
                                @else
                                    <span class="badge bg-danger fs-6">Root</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Parent Category</label>
                            <div class="d-flex align-items-center gap-2">
                                @if($category->parent_name)
                                    <span class="badge bg-secondary fs-6">
                                        <i class="ti ti-folder me-2"></i>
                                        {{ $category->parent_name }}
                                    </span>
                                    <a href="{{ route('products.categories.show', $category->parent_code) }}" 
                                       class="btn btn-sm btn-outline-light">
                                        <i class="ti ti-arrow-up-right"></i> 
                                        View Parent
                                    </a>
                                @else
                                    <span class="badge bg-primary fs-6">
                                        <i class="ti ti-folder-filled me-2"></i>
                                        Root Category
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($category->description)
                            <div class="col-md-12">
                                <label class="text-muted small">Description</label>
                                <div>
                                    <p class="mb-0">{{ $category->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($subcategories->count() > 0)
                <div class="card custom">
                    <div class="card-header justify-content-between align-items-center">
                        <div class="card-title">Subcategories</div>
                        <span class="badge bg-primary">{{ $subcategories->count() }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush small">
                            @foreach($subcategories as $sub)
                                <a href="{{ route('products.categories.show', $sub->category_code) }}" 
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ti ti-corner-down-right text-muted me-2"></i>
                                        <strong>{{ $sub->category_name }}</strong>
                                        @if($sub->description)
                                            <br>
                                            <small class="text-muted ms-4">
                                                {{ Str::limit($sub->description, 60) }}
                                            </small>
                                        @endif
                                    </div>
                                    <i class="ti ti-arrow-right text-muted"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title">Product in Category</div>
                    <span class="badge bg-primary">{{ $products->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover mb-0">
                                <thead>
                                    <th scope="col">Code</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">UOM</th>
                                    <th scope="col">Cost</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Status</th>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $product->product_code }}</span>
                                            </td>

                                            <td>
                                                <strong>{{ $product->product_name }}</strong>
                                            </td>

                                            <td>
                                                <span class="badge bg-light text-dark">{{ $product->product_type }}</span>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-database-off text-muted display-1"></i>
                            <h5 class="text-muted mt-3">No products Found</h5>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> 
                                Create First Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-primary bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-box text-primary fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $products->count() }}</h4>
                                <small class="text-muted">Products</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-category-2 text-success fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $subcategories->count() }}</h4>
                                <small class="text-muted">Subcategories</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Metadata</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Created At:</td>
                                    <td>
                                        <div>
                                            {{ \Carbon\Carbon::parse($category->created_at)->format('d M Y, H:i') }}
                                            <br><small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($category->created_at)->diffForHumans() }})
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td>
                                        <div>
                                            {{ \Carbon\Carbon::parse($category->updated_at)->format('d M Y, H:i') }}
                                            <br><small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($category->updated_at)->diffForHumans() }})
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Category ID:</td>
                                    <td>
                                        {{ $category->category_id }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                @method('DELETE')

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