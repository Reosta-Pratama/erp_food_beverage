@extends('layouts.app', [
    'title' => 'Permissions'
])

@section('styles')
    <style>
        .permission-row {
            transition: all 0.2s ease;
        }
        .permission-row:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        .module-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .crud-badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.4rem;
            font-weight: 600;
        }
        .filter-card {
            border-left: 3px solid #0d6efd;
        }
        .stats-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">User Management</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="ti ti-circle-check fs-18 me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert">
                <i class="ti ti-x"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="ti ti-exclamation-circle fs-18 me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert">
                <i class="ti ti-x"></i>
            </button>
        </div>
    @endif
    
    <!-- Container -->
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-primary bg-primary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shield-lock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" /><path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M12 12l0 2.5" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-primary fs-24 mb-1">{{ $permissions->total() }}</h4>
                        <span class="fs-base fw-semibold">Total Permissions</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-success bg-success bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-table"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" /><path d="M3 10h18" /><path d="M10 3v18" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-success fs-24 mb-1">{{ $modules->count() }}</h4>
                        <span class="fs-base fw-semibold">Modules</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-secondary bg-secondary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-secondary fs-24 mb-1">{{ DB::table('roles')->count() }}</h4>
                        <span class="fs-base fw-semibold">Active Roles</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-link"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464" /><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-danger fs-24 mb-1">{{ $permissions->sum('roles_count') }}</h4>
                        <span class="fs-base fw-semibold">Assigned</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Permissions Management</h2>
            <p class="text-muted mb-0">Define and manage system permissions for access control.</p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Permission
            </a>
        </div>
    </div>

    <div class="row g-4">

    </div>
    <!-- Container -->

    <div class="container-fluid">
        <div class="row">
            {{-- Filters --}}
            <div class="col-lg-3 mb-4">
                <div class="card filter-card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filters</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.permissions.index') }}">
                            
                            {{-- Search --}}
                            <div class="mb-3">
                                <label class="form-label">Search</label>
                                <input type="text" 
                                    class="form-control" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Search permissions...">
                            </div>

                            {{-- Module Filter --}}
                            <div class="mb-3">
                                <label class="form-label">Module</label>
                                <select class="form-select" name="module">
                                    <option value="">All Modules</option>
                                    @foreach($modules as $module)
                                    <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                                        {{ $module }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Module List --}}
                    <div class="card-footer bg-white">
                        <h6 class="mb-3"><i class="bi bi-list-ul me-2"></i>Quick Filter</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($modules as $module)
                            <a href="{{ route('admin.permissions.index', ['module' => $module]) }}" 
                            class="badge bg-{{ request('module') === $module ? 'primary' : 'secondary' }} text-decoration-none">
                                {{ $module }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Permissions Table --}}
            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-list-check me-2"></i>
                                Permissions List
                                <span class="badge bg-primary">{{ $permissions->total() }}</span>
                            </h5>
                            <div class="text-muted small">
                                Showing {{ $permissions->firstItem() ?? 0 }} - {{ $permissions->lastItem() ?? 0 }} of {{ $permissions->total() }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($permissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 25%">Permission Name</th>
                                        <th style="width: 15%">Module</th>
                                        <th style="width: 20%">CRUD Access</th>
                                        <th style="width: 10%" class="text-center">Roles</th>
                                        <th style="width: 15%" class="text-center">Created</th>
                                        <th style="width: 10%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $index => $permission)
                                    <tr class="permission-row">
                                        <td class="text-muted">
                                            {{ $permissions->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold">{{ $permission->permission_name }}</div>
                                                <code class="small text-muted">{{ $permission->permission_code }}</code>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="module-badge badge bg-info">
                                                {{ $permission->module_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                @if($permission->can_create)
                                                <span class="crud-badge badge bg-success">C</span>
                                                @endif
                                                @if($permission->can_read)
                                                <span class="crud-badge badge bg-info">R</span>
                                                @endif
                                                @if($permission->can_update)
                                                <span class="crud-badge badge bg-warning">U</span>
                                                @endif
                                                @if($permission->can_delete)
                                                <span class="crud-badge badge bg-danger">D</span>
                                                @endif
                                                @if(!$permission->can_create && !$permission->can_read && !$permission->can_update && !$permission->can_delete)
                                                <span class="crud-badge badge bg-secondary">None</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($permission->roles_count > 0)
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $permission->roles_count }}
                                            </span>
                                            @else
                                            <span class="badge bg-secondary rounded-pill">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ \Carbon\Carbon::parse($permission->created_at)->format('d M Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.permissions.show', $permission->permission_id) }}" 
                                                class="btn btn-outline-primary" 
                                                title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.permissions.edit', $permission->permission_id) }}" 
                                                class="btn btn-outline-warning" 
                                                title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.permissions.destroy', $permission->permission_id) }}" 
                                                    method="POST" 
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger" 
                                                            title="Delete"
                                                            {{ $permission->roles_count > 0 ? 'disabled' : '' }}>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No permissions found</p>
                            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create First Permission
                            </a>
                        </div>
                        @endif
                    </div>
                    @if($permissions->hasPages())
                        <div class="card-footer bg-white">
                            {{ $permissions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
@endsection