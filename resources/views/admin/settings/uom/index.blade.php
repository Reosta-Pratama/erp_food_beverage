@extends('layouts.app', [
    'title' => 'Units of Measure'
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
                    <li class="breadcrumb-item active" aria-current="page">Units of Measure</li>
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
        @php
            $types = $uoms->groupBy('uom_type');
            $stats = [
                [
                    'type' => 'Weight', 
                    'count' => $types->get('Weight', collect())->count(), 
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar-popular"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20h14" /></svg>', 
                    'color' => 'primary'
                ],
                [
                    'type' => 'Volume', 
                    'count' => $types->get('Volume', collect())->count(), 
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-flask"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 3l6 0" /><path d="M10 9l4 0" /><path d="M10 3v6l-4 11a.7 .7 0 0 0 .5 1h11a.7 .7 0 0 0 .5 -1l-4 -11v-6" /></svg>', 
                    'color' => 'success'
                ],
                [
                    'type' => 'Length', 
                    'count' => $types->get('Length', collect())->count(), 
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ruler"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h14a1 1 0 0 1 1 1v5a1 1 0 0 1 -1 1h-7a1 1 0 0 0 -1 1v7a1 1 0 0 1 -1 1h-5a1 1 0 0 1 -1 -1v-14a1 1 0 0 1 1 -1" /><path d="M4 8l2 0" /><path d="M4 12l3 0" /><path d="M4 16l2 0" /><path d="M8 4l0 2" /><path d="M12 4l0 3" /><path d="M16 4l0 2" /></svg>', 
                    'color' => 'secondary'
                ],
                [
                    'type' => 'Quantity', 
                    'count' => $types->get('Quantity', collect())->count(), 
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-number-123"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 10l2 -2v8" /><path d="M9 8h3a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-2a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h3" /><path d="M17 8h2.5a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1 -1.5 1.5h-1.5h1.5a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1 -1.5 1.5h-2.5" /></svg>', 
                    'color' => 'danger'
                ],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="col-md-6 col-lg-3">
                <div class="card custom">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar avatar-xxl svg-{{ $stat['color'] }} bg-{{ $stat['color'] }} bg-opacity-10 rounded-circle border-0">
                            {!! $stat['icon'] !!}
                        </div>
                        <div class="ms-3">
                            <h4 class="text-{{ $stat['color'] }} fs-24 mb-1">{{ $stat['count'] }}</h4>
                            <span class="fs-base fw-semibold">{{ $stat['type'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Units of Measure</h2>
            <p class="text-muted mb-0">Manage measurement units for products and materials.</p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.settings.uom.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Role
            </a>
        </div>
    </div>

    @if($uoms->count() > 0)
        @foreach ($types as $type => $typeUoms)
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                    <h3 class="fs-20 mb-0">
                        <i class="ti ti-ruler text-primary"></i>
                        <span>{{ $type }}</span>
                        <span class="text-muted small ms-1">({{ $typeUoms->count() }} units)</span>
                    </h3>
                </div>
                <div class="row">
                    @foreach ($typeUoms as $uom)
                        <div class="col-md-6 col-lg-4">
                            <div class="card custom">
                                <div class="card-header justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title mb-1">{{ $uom->uom_name }}</h5>
                                        <code class="text-muted small">{{ $uom->uom_code }}</code>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.settings.uom.show', $uom->uom_code) }}">
                                                    <i class="ti ti-eye me-2"></i>
                                                    View Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.settings.uom.edit', $uom->uom_code) }}">
                                                    <i class="ti ti-pencil me-2"></i>
                                                    Edit Role
                                                </a>
                                            </li>
                                            @if($uom->products_count == 0)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.settings.uom.destroy', $uom->uom_code) }}" 
                                                        method="POST" 
                                                        onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ti ti-trash me-2"></i>
                                                            Delete 
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="bg-light p-2 rounded text-center">
                                                <div class="text-primary fw-bold fs-5">{{ $type }}</div>
                                                <div class="text-muted small">Type</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-light p-2 rounded text-center">
                                                <div class="text-success fw-bold fs-5">{{ $uom->products_count }} products</div>
                                                <div class="text-muted small">Used by</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.settings.uom.show', $uom->uom_code) }}" 
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-eye me-2"></i>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span class="text-muted small">
                                        <i class="ti ti-calendar me-1"></i>
                                        Created {{ \Carbon\Carbon::parse($uom->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="card custom">
            <div class="card-body text-center p-5">
                <i class="ti ti-ruler text-muted fs-40"></i>
                <h5 class="text-muted mt-3">No Units of Measure</h5>
                <p class="text-muted mb-4">Create your first unit to get started</p>
                <a href="{{ route('admin.settings.uom.create') }}" 
                    class="btn btn-primary">
                    <i class="ti ti-circle-plus me-2"></i>
                    Add First Unit
                </a>
            </div>
        </div>
    @endif
    <!-- Container -->

@endsection

@section('scripts')
@endsection