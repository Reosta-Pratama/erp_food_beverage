@extends('layouts.app', [
    'title' => 'Product Details'
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
                    <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Product Details</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.edit', $product->product_code) }}" 
               class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i> 
                Edit
            </a>
            <button type="button" 
                    class="btn btn-danger"
                    onclick="confirmDelete('{{ $product->product_code }}', '{{ $product->product_name }}')">
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
                    <div class="card-title">Product Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Product Code</label>
                            <div>
                                <code class="fs-5 text-primary">{{ $product->product_code }}</code>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Product Name</label>
                            <div>
                                <strong class="fs-5">{{ $product->product_name }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Product Type</label>
                            <div>
                                @if($product->product_type === 'Raw Material')
                                    <span class="badge bg-secondary fs-6">Raw Material</span>
                                @elseif($product->product_type === 'Semi-Finished')
                                    <span class="badge bg-warning fs-6">Semi-Finished</span>
                                @elseif($product->product_type === 'Finished Goods')
                                    <span class="badge bg-success fs-6">Finished Goods</span>
                                @elseif($product->product_type === 'Packaging')
                                    <span class="badge bg-info fs-6">Packaging</span>
                                @else
                                    <span class="badge bg-light text-dark fs-6">{{ $product->product_type }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Category</label>
                            <div>
                                <strong>{{ $product->category_name }}</strong>
                                <a href="{{ route('products.categories.show', $product->category_code) }}" 
                                    class="btn btn-sm btn-outline-light">
                                    <i class="ti ti-arrow-up-right"></i> 
                                        View 
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Unit of Measure</label>
                            <div>
                                <strong>{{ $product->uom_name }}</strong>
                                <span class="text-muted">({{ $product->uom_code }})</span>
                                <br>
                                <small class="text-muted">{{ $product->uom_type }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Status</label>
                            <div>
                                @if($product->is_active)
                                    <span class="badge bg-success fs-6">
                                        <i class="ti ti-circle-check me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="ti ti-circle-x me-1"></i>Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($product->description)
                            <div class="col-md-12">
                                <label class="text-muted small">Description</label>
                                <div>
                                    <p class="mb-0">{{ $product->description }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Pricing Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Standard Cost</label>
                            <div>
                                <span class="fs-5 fw-semibold text-info">
                                    Rp {{ number_format($product->standard_cost, 2) }}
                                </span>
                                <br>
                                <small class="text-muted">per {{ $product->uom_name }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Selling Cost</label>
                            <div>
                                <span class="fs-5 fw-semibold text-success">
                                    Rp {{ number_format($product->selling_price, 2) }}
                                </span>
                                <br>
                                <small class="text-muted">per {{ $product->uom_name }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Profit Margin</label>
                            <div>
                                @php
                                    $margin = $product->selling_price > 0 
                                        ? (($product->selling_price - $product->standard_cost) / $product->selling_price * 100) 
                                        : 0;
                                @endphp
                                <span class="fs-5 fw-semibold {{ $margin >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($margin, 2) }}%
                                </span>
                                <br>
                                <small class="text-muted">margin</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-light mb-0">
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <strong>Markup:</strong>
                                        @php
                                            $markup = $product->standard_cost > 0 
                                                ? (($product->selling_price - $product->standard_cost) / $product->standard_cost * 100) 
                                                : 0;
                                        @endphp
                                        <span class="text-primary">{{ number_format($markup, 2) }}%</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Profit per Unit:</strong>
                                        <span class="text-success">
                                            Rp {{ number_format($product->selling_price - $product->standard_cost, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($inventorySummary && $inventorySummary->total_on_hand > 0)
                <div class="card custom">
                    <div class="card-header">
                        <div class="card-title">Inventory Summary</div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-md-4">
                                <label class="text-muted small">On Hand</label>
                                <h3 class="mb-0 text-primary">
                                    {{ number_format($inventorySummary->total_on_hand, 2) }}
                                </h3>
                                <small class="text-muted">{{ $product->uom_code }}</small>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Reserved</label>
                                <h3 class="mb-0 text-warning">
                                    {{ number_format($inventorySummary->total_reserved, 2) }}
                                </h3>
                                <small class="text-muted">{{ $product->uom_code }}</small>
                            </div>
                            <div class="col-md-4">
                                <label class="text-muted small">Available</label>
                                <h3 class="mb-0 text-success">
                                    {{ number_format($inventorySummary->total_available, 2) }}
                                </h3>
                                <small class="text-muted">{{ $product->uom_code }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                                <h4 class="mb-0">
                                    @if($hasBOM)
                                        <span class="text-success">Yes</span>
                                    @else
                                        <span class="text-danger">No</span>
                                    @endif
                                </h4>
                                <small class="text-muted">Has BOM</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-category-2 text-warning fs-3"></i>
                                </div>
                                <h4 class="mb-0">
                                    @if($hasRecipe)
                                        <span class="text-success">Yes</span>
                                    @else
                                        <span class="text-danger">No</span>
                                    @endif
                                </h4>
                                <small class="text-muted">Has Recipe</small>
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
                                            {{ \Carbon\Carbon::parse($product->created_at)->format('d M Y, H:i') }}
                                            <br><small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($product->created_at)->diffForHumans() }})
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td>
                                        <div>
                                            {{ \Carbon\Carbon::parse($product->updated_at)->format('d M Y, H:i') }}
                                            <br><small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($product->updated_at)->diffForHumans() }})
                                            </small>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Product ID:</td>
                                    <td>
                                        {{ $product->product_id }}
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

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!-- Delete Confirmation Form -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

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