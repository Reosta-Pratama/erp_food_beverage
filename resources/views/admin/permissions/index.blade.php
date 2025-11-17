@extends('layouts.app', [
    'title' => 'Permissions'
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
                        <a href="javascript:void(0);">User Management</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
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
    
    <!-- Container -->
    <div class="row g-4">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M4 13m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
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
        <div class="col-lg-3">
            <div class="card custom sticky-card">
                <div class="card-header">
                    <div class="card-title">Filters</div>
                </div>

                <form method="GET" action="{{ route('admin.permissions.index') }}"
                    class="card-body">

                    {{-- Search --}}
                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" 
                            class="form-control" 
                            id="search" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search permissions..."
                            autocomplete="off">
                    </div>

                    {{-- Module Filter --}}
                    <div class="mb-3">
                        <label for="module" class="form-label">Module</label>
                        <select class="form-select" id="module" name="module"
                            data-trigger="data-trigger">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                                    {{ $module }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" 
                            class="btn btn-outline-danger">
                            <i class="ti ti-rotate-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>
                </form>

                <div class="card-footer">
                    <h6 class="mb-3">
                        <i class="ti ti-list me-2"></i>
                        Quick Filter
                    </h6>
                    
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($modules as $module)
                            <a href="{{ route('admin.permissions.index', ['module' => $module]) }}" 
                                class="badge bg-{{ request('module') === $module ? 'success' : 'secondary' }} text-decoration-none">
                                {{ $module }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        Permissions List
                        <span class="badge bg-primary ms-2">{{ $permissions->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $permissions->firstItem() ?? 0 }} - {{ $permissions->lastItem() ?? 0 }} of {{ $permissions->total() }}
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($permissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Permission</th>
                                        <th scope="col">Module</th>
                                        <th scope="col" class="text-center">CRUD Access</th>
                                        <th scope="col" class="text-center">Roles</th>
                                        <th scope="col" class="text-center">Created</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $index => $permission)
                                        <tr>
                                            <td>
                                                {{ $permissions->firstItem() + $index }}
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ $permission->permission_name }}</div>
                                                    <code class="small text-muted">{{ $permission->permission_code }}</code>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $permission->module_name }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 flex-wrap">
                                                    @if($permission->can_create)
                                                        <span class="badge bg-success">C</span>
                                                    @endif
                                                    @if($permission->can_read)
                                                        <span class="badge bg-info">R</span>
                                                    @endif
                                                    @if($permission->can_update)
                                                        <span class="badge bg-warning">U</span>
                                                    @endif
                                                    @if($permission->can_delete)
                                                        <span class="badge bg-danger">D</span>
                                                    @endif
                                                    @if(!$permission->can_create && !$permission->can_read && !$permission->can_update && !$permission->can_delete)
                                                        <span class="badge bg-secondary">None</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($permission->roles_count > 0)
                                                    {{ $permission->roles_count }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td class="text-center text-muted small">
                                                {{ \Carbon\Carbon::parse($permission->created_at)->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    <a class="btn btn-sm btn-primary btn-wave"
                                                        href="{{ route('admin.permissions.show', $permission->permission_id) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-success btn-wave"
                                                        href="{{ route('admin.permissions.edit', $permission->permission_id) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.permissions.destroy', $permission->permission_id) }}" 
                                                        method="POST" 
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <button class="btn btn-sm btn-danger btn-wave"
                                                            type="submit"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="ti ti-trash"></i>
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
                            <i class="ti ti-file-x text-muted fs-40"></i>
                            <p class="text-muted mt-3">No permissions found</p>
                            <a href="{{ route('admin.permissions.create') }}" 
                                class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>
                                Create First Permission
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-footer d-flex justify-content-center">
                    {{ $permissions->links('pagination.default') }}
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <script>
        const moduleSelectElement = document.getElementById('module');
        if (moduleSelectElement) {
            const moduleChoices = new Choices(moduleSelectElement, {
                searchEnabled: true
            });
        }
    </script>

@endsection