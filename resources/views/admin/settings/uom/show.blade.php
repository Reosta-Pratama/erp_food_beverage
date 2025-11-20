@extends('layouts.app', [
    'title' => 'Unit Details - ' . $uom->uom_name
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
                        <a href="javascript:void(0);">Settings</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Units of Measure</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Unit Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">{{ $uom->uom_name }}</h2>
            <p class="text-muted mb-0">{{ $uom->uom_code }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.uom.edit', $uom->uom_code) }}" 
                class="btn btn-warning">
                <i class="ti ti-pencil me-2"></i>
                Edit Unit
            </a>
            @if($productsCount == 0)
                <button type="button" class="btn btn-danger" 
                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="ti ti-trash me-2"></i>
                    Delete
                </button>
            @endif
            <a href="{{ route('admin.settings.uom.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Unit Information
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Unit Name:</td>
                                    <td class="fw-bold">{{ $uom->uom_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit Code:</td>
                                    <td><code>{{ $uom->uom_code }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit Type:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $uom->uom_type }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Created:</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($uom->created_at)->format('d M Y - H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($uom->updated_at)->format('d M Y - H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Usage Statistics
                    </div>
                </div>
                <div class="card-body">
                    <div class="bg-primary bg-opacity-10 text-center p-3 mb-3" 
                        style="border-radius: var(--default-radius)">
                        <div class="display-4 text-primary fw-bold">{{ $productsCount }}</div>
                        <div class="text-muted">Products Using This Unit</div>
                    </div>

                    @if($productsCount > 0)
                        <div class="alert alert-warning mb-0">
                            <small>
                                <i class="ti ti-exclamation-circle me-2"></i>
                                This unit is currently being used and cannot be deleted
                            </small>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="ti ti-info-circle me-2"></i>
                                This unit is not being used by any products
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Common Usage Examples for {{ $uom->uom_type }} Units
                    </div>
                </div>

                <div class="card-body">
                    @php
                        $examples = [
                            'Weight' => [
                                'Manufacturing' => ['Raw materials', 'Finished products', 'Packaging materials'],
                                'Food & Beverage' => ['Ingredients', 'Portions', 'Batch sizes'],
                                'Logistics' => ['Shipping weight', 'Load capacity'],
                            ],
                            'Volume' => [
                                'Liquids' => ['Beverages', 'Oils', 'Chemicals'],
                                'Storage' => ['Tank capacity', 'Container volume'],
                                'Production' => ['Batch volume', 'Mixing quantities'],
                            ],
                            'Length' => [
                                'Materials' => ['Fabric', 'Wire', 'Tubing'],
                                'Dimensions' => ['Product size', 'Packaging'],
                                'Construction' => ['Measurements', 'Specifications'],
                            ],
                            'Quantity' => [
                                'Counting' => ['Individual items', 'Pieces', 'Units'],
                                'Packaging' => ['Boxes', 'Cartons', 'Pallets'],
                                'Sales' => ['Sets', 'Bundles', 'Lots'],
                            ],
                        ];
                        
                        $typeExamples = $examples[$uom->uom_type] ?? [];
                    @endphp

                    @if($typeExamples)
                        <div class="row g-4">
                            @foreach ($typeExamples as $category => $items)
                                <div class="col-md-4">
                                    <h6 class="text-primary">{{ $category }}</h6>
                                    <ul class="list-group small">
                                        @foreach ($items as $item)
                                            <li class="list-group-item">{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            No specific examples available for this unit type.
                        </p>
                    @endif
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Quick Actions 
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-grid d-md-flex gap-2">
                        <a href="{{ route('admin.settings.uom.edit', $uom->uom_code) }}" 
                           class="btn btn-outline-warning flex-fill">
                            <i class="ti ti-pencil me-2"></i>
                            Edit Unit
                        </a>
                        <a href="{{ route('admin.settings.uom.create') }}" 
                           class="btn btn-outline-primary flex-fill">
                            <i class="ti ti-circle-plus me-2"></i>
                            Create Similar Unit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('modals')

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="exportModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.settings.uom.destroy', $uom->uom_code) }}" method="POST"
                class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-header d-flex justify-content-between bg-danger">
                    <h6 class="modal-title text-white">
                        Delete Unit of Measure
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-exclamation-circle me-2"></i>
                        Are you sure you want to delete this unit?
                    </div>

                    <p>You are about to delete:</p>

                    <div class="table-responsive">
                        <table class="table table-nowrap table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Unit Name:</td>
                                    <td class="fw-bold">{{ $uom->uom_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit Code:</td>
                                    <td><code>{{ $uom->uom_code }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit Type:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $uom->uom_type }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-2"></i>
                        Delete Unit
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
@endsection