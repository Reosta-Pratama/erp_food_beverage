@extends('layouts.app', [
    'title' => 'BOM Details'
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
                        <a href="javascript:void(0);">BOM & Recipes</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('inventory.bom.index') }}">Bill of Materials</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">BOM Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">BOM Details</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('inventory.bom.edit', $bom->bom_code) }}" 
               class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i> 
                Edit
            </a>
            <button type="button" 
                    class="btn btn-danger"
                    onclick="confirmDelete('{{ $bom->bom_code }}', '{{ $bom->product_name }}')">
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
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">BOM Information</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted" width="150">BOM Code:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $bom->bom_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Version:</td>
                                    <td>
                                        <span class="badge bg-light-transparent text-dark">v{{ $bom->bom_version }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Effective Date:</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($bom->effective_date)->format('d M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Status:</td>
                                    <td>
                                        @if($bom->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Total Items:</td>
                                    <td>
                                        <span class="badge bg-light-transparent text-dark">{{ $items->count() }} items</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Estimated Cost:</td>
                                    <td>
                                        <code class="fs-16 text-primary">
                                            Rp {{ number_format($totalCost, 2, ',', '.') }}
                                        </code>
                                    </td>
                                </tr>
                                @if ($bom->notes)
                                    <tr>
                                        <td class="text-muted" width="150">Notes:</td>
                                        <td>{{ $bom->notes }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Product Information</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted" width="150">Product Code:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $bom->product_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Product Code:</td>
                                    <td class="fw-bold">{{ $bom->product_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Product Type:</td>
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
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Unit of Measure:</td>
                                    <td>
                                        <code class="fs-16">{{ $bom->uom_name }} ({{ $bom->uom_code }})</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted" width="150">Standard Cost:</td>
                                    <td>
                                        <code class="fs-16 text-primary">Rp {{ number_format($bom->standard_cost, 2, ',', '.') }}</code>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center gap-2">
                    <div class="card-title">BOM Items</div>
                    <span class="badge bg-primary">{{ $items->count() }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if ($items->isEmpty())
                            <div class="text-center py-3">
                                <i class="ti ti-database-off text-muted display-3"></i>
                                <h5 class="text-muted mt-3">No items found</h5>
                            </div>
                        @else
                            @foreach (['Raw Material', 'Semi-Finished', 'Finished Goods', 'Packaging', 'Consumable'] as $itemType)
                                @php
                                    $typeItems = $items->where('item_type', $itemType);
                                @endphp

                                @if ($typeItems->isNotEmpty())
                                    <div class="col-12">
                                        <h5 class="fs-16 mb-2">
                                            <i class="ti ti-table text-primary"></i>
                                            {{ $itemType }} 
                                        </h5>

                                        <div class="table-responsive">
                                            <table class="table text-nowrap table-hover last-border-none mb-0">
                                                <thead>
                                                    <th scope="col" style="width: 40%">Material</th>
                                                    <th scope="col" style="width: 15%">Quantity</th>
                                                    <th scope="col" style="width: 10%">Unit</th>
                                                    <th scope="col" style="width: 10%">Scrap %</th>
                                                    <th scope="col" style="width: 25%" class="text-end">Cost</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($typeItems as $item)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $item->material_name }}</strong>
                                                                <br>
                                                                <small class="text-muted">{{ $item->material_code }}</small>
                                                            </td>

                                                            <td>
                                                                <code>{{ number_format($item->quantity_required, 4) }}</code>
                                                            </td>

                                                            <td>
                                                                <span class="badge bg-light-transparent text-dark">
                                                                    {{ $item->uom_name }}
                                                                </span>
                                                            </td>

                                                            <td>
                                                                @if($item->scrap_percentage > 0)
                                                                    <span class="badge bg-warning-transparent">
                                                                        {{ $item->scrap_percentage }}%
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>

                                                            <td class="text-end">
                                                                <div class="fw-semibold">
                                                                    Rp {{ number_format($item->item_cost, 2, ',', '.') }}
                                                                </div>
                                                                <small class="text-muted">
                                                                    @ Rp {{ number_format($item->standard_cost, 2, ',', '.') }}
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <tr class="table-active">
                                                        <td colspan="4" class="text-end fw-semibold">
                                                            {{ $itemType }} Subtotal:
                                                        </td>
                                                        <td class="text-end fw-semibold">
                                                            Rp {{ number_format($typeItems->sum('item_cost'), 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-1">Total BOM Cost</h5>
                            <p class="text-muted mb-0 small">
                                Estimated cost for {{ $items->count() }} materials
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <code class="fs-24 text-primary">
                                Rp {{ number_format($totalCost, 2, ',', '.') }}
                            </code>
                        </div>
                    </div>
                </div>
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

    @vite(['resources/assets/js/erp/bom-init.js'])

@endsection