@extends('layouts.app', [
    'title' => 'Bill of Materials (BOM)'
])

@section('styles')

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
                    <li class="breadcrumb-item active" aria-current="page">Bill of Materials</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center fade show mb-3" role="alert">
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
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-primary bg-primary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list-details"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 5h8" /><path d="M13 9h5" /><path d="M13 15h8" /><path d="M13 19h5" /><path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-primary fs-24 mb-1">{{ $boms->count() }}</h4>
                        <span class="fs-base fw-semibold">Total BOMs</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-success bg-success bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-success fs-24 mb-1">{{ $boms->where('is_active', 1)->count() }}</h4>
                        <span class="fs-base fw-semibold">Active BOMs</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-secondary bg-secondary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-secondary fs-24 mb-1">{{ $boms->where('is_active', 0)->count() }}</h4>
                        <span class="fs-base fw-semibold">inactive BOMs</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l11 0" /><path d="M9 12l11 0" /><path d="M9 18l11 0" /><path d="M5 6l0 .01" /><path d="M5 12l0 .01" /><path d="M5 18l0 .01" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-danger fs-24 mb-1">{{ $boms->count() }}</h4>
                        <span class="fs-base fw-semibold">Showing</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Bill of Materials (BOM)</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-dark btn-wave" onclick="toggleFilters()">
                <i class="ti ti-filter me-2"></i> 
                Filters
            </button>
            <a href="{{ route('inventory.bom.export') }}" class="btn btn-outline-success">
                <i class="ti ti-download me-2"></i>
                Export CSV
            </a>
            <a href="{{ route('inventory.bom.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New BOM
            </a>
        </div>
    </div>

    <div class="row">
        <div id="filtersCard" class="col-12" style="display: none;">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Filter & Search</div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('inventory.bom.index') }}"
                        id="filterForm" class="row g-3">

                        <div class="col-md-3">
                            <label class="form-label" for="search">Search</label>
                            <input
                                type="text"
                                id="search"
                                name="search"
                                class="form-control"
                                placeholder="BOM code, product name..."
                                value="{{ request('search') }}"
                            >
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="product_id">Product</label>
                            <select id="product_id" name="product_id" class="form-select single-select">
                                <option value="">-- All Products --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->product_id }}"
                                        {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                                        {{ $product->product_code }} - {{ $product->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="product_type">Product Type</label>
                            <select id="product_type" name="product_type" class="form-select single-select">
                                <option value="">-- All Type --</option>
                                <option value="Finished Goods" {{ request('product_type') == 'Finished Goods' ? 'selected' : '' }}>
                                    Finished Goods
                                </option>
                                <option value="Raw Material" {{ request('product_type') == 'Raw Material' ? 'selected' : '' }}>
                                    Raw Material
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
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

                        <div class="col-md-3">
                            <label class="form-label" for="items_filter">Items</label>
                            <select id="items_filter" name="items_filter" class="form-select single-select">
                                <option value="">-- All Item --</option>
                                <option value="empty" {{ request('items_filter') === 'empty' ? 'selected' : '' }}>
                                    Empty
                                </option>
                                <option value="simple" {{ request('items_filter') === 'simple' ? 'selected' : '' }}>
                                    1–3 Items
                                </option>
                                <option value="standard" {{ request('items_filter') === 'standard' ? 'selected' : '' }}>
                                    4–7 Items
                                </option>
                                <option value="complex" {{ request('items_filter') === 'complex' ? 'selected' : '' }}>
                                    8+ Items
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="daterange">Effective Date</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> 
                                        <i class="ti ti-calendar"></i> 
                                    </div>
                                    <input class="form-control daterange" 
                                        id="daterange"
                                        name="daterange"
                                        date_from=@json(request('date_from'))
                                        date_to=@json(request('date_to'))
                                        type="text" 
                                        placeholder="Effective date range...">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" id="sort_by">Sort By</label>
                            <select id="sort_by" name="sort_by" class="form-select single-select">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    Created Date
                                </option>
                                <option value="bom_code" {{ request('sort_by') == 'bom_code' ? 'selected' : '' }}>
                                    BOM Code
                                </option>
                                <option value="product_name" {{ request('sort_by') == 'product_name' ? 'selected' : '' }}>
                                    Product Name
                                </option>
                                <option value="product_type" {{ request('sort_by') == 'product_type' ? 'selected' : '' }}>
                                    Product Type
                                </option>
                                <option value="bom_version" {{ request('sort_by') == 'bom_version' ? 'selected' : '' }}>
                                    Version
                                </option>
                                <option value="effective_date" {{ request('sort_by') == 'effective_date' ? 'selected' : '' }}>
                                    Effective Date
                                </option>
                                <option value="items_count" {{ request('sort_by') == 'items_count' ? 'selected' : '' }}>
                                    Items Count
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="sort_order">Order</label>
                            <select id="sort_order" name="sort_order" class="form-select single-select">
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>
                                    Descending
                                </option>
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>
                                    Ascending
                                </option>
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" 
                                class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> 
                                Apply Filters
                            </button>
                            <a href="{{ route('inventory.bom.index') }}" 
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
                        BOM List
                        <span class="badge bg-primary ms-2">{{ $boms->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $boms->firstItem() ?? 0 }} - {{ $boms->lastItem() ?? 0 }} of {{ $boms->total() }}
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($boms->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless table-hover mb-0">
                                <thead>
                                    <th scope="col">Code</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Version</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Items</th>
                                    <th scope="col">Effective Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($boms as $bom)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $bom->bom_code }}</span>
                                            </td>

                                            <td>
                                                <strong>{{ $bom->product_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $bom->product_code }}</small>
                                            </td>

                                            <td>
                                                <span class="badge bg-light-transparent text-dark">
                                                    v{{ $bom->bom_version }}
                                                </span>
                                            </td>

                                            <td>
                                                @if($bom->product_type === 'Raw Material')
                                                    <span class="badge bg-secondary-transparent">Raw Material</span>
                                                @elseif($bom->product_type === 'Semi-Finished')
                                                    <span class="badge bg-warning-transparent">Semi-Finished</span>
                                                @elseif($bom->product_type === 'Finished Goods')
                                                    <span class="badge bg-success-transparent">Finished Goods</span>
                                                @elseif($bom->product_type === 'Packaging')
                                                    <span class="badge bg-info-transparent">Packaging</span>
                                                @else
                                                    <span class="badge bg-light-transparent text-dark">{{ $bom->product_type }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-light-transparent text-dark">
                                                    {{ $bom->items_count }} items
                                                </span>
                                            </td>

                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($bom->effective_date)->format('d M Y') }}</small>
                                            </td>

                                            <td>
                                                @if($bom->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    @hasPermission('bom.manage')
                                                        <a href="{{ route('inventory.bom.show', $bom->bom_code) }}" 
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="View Details">
                                                                <i class="ti ti-eye"></i>
                                                        </a>

                                                        <a href="{{ route('inventory.bom.edit', $bom->bom_code) }}" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Edit">
                                                                <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $bom->bom_code }}', '{{ $bom->product_name }}')"
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
                            <h5 class="text-muted mt-3">No Bill of Material Found</h5>
                            <p class="text-muted">Try adjusting your filters or check back later</p>
                            <a href="{{ route('inventory.bom.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> 
                                Create First Bom
                            </a>
                        </div>
                    @endif
                </div>

                @if($boms->hasPages())
                    <div class="d-flex justify-content-center border-top p-4">
                        {{ $boms->links('pagination.default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('modals')

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteForm" method="POST"
                class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-header d-flex justify-content-between bg-danger">
                    <h6 class="modal-title text-white">
                        Delete BOM
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this BOM?</p>

                    <div class="alert alert-warning">
                        <strong class="me-2">BOM Code:</strong><span id="deleteBomCode"></span>
                        <br>
                        <strong class="me-2">Product:</strong><span id="deleteProductName"></span>
                    </div>

                    <p class="text-danger">This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-2"></i>
                        Delete BOM
                    </button>
                </div>

            </form>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->

@endsection

@section('scripts')

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/bom-init.js'])

@endsection