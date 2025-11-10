@extends('layouts.app', [
    'title' => 'Roles'
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
                    <li class="breadcrumb-item active" aria-current="page">Roles</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->


    <div class="row">
        @if(session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-circle-check fs-18 me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-exclamation-circle fs-18 me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        @endif
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-primary bg-primary bg-opacity-10 rounded-circle border-0">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-primary fs-24 mb-1">{{ $roles->count() }}</h4>
                        <span class="fs-base fw-semibold">Total Roles</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-success bg-success bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-success fs-24 mb-1">{{ $roles->sum('users_count') }}</h4>
                        <span class="fs-base fw-semibold">Total Users</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-secondary bg-secondary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-settings-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-secondary fs-24 mb-1">{{ $roles->whereIn('role_code', ['admin', 'operator', 'finance_hr'])->count() }}</h4>
                        <span class="fs-base fw-semibold">System Roles</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-star"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-danger fs-24 mb-1">{{ $roles->whereNotIn('role_code', ['admin', 'operator', 'finance_hr'])->count() }}</h4>
                        <span class="fs-base fw-semibold">Custom Roles</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fs-16 mb-0">Role Management</h5>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-2"></i>Create New Role
            </a>
        </div>
    </div>

    <div class="row">
        @forelse ($roles as $role)
            <div class="col-md-6 col-lg-4">
                <div class="card custom">
                    <div class="card-header justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title mb-1">
                                {{ $role->role_name }}
                                @if(in_array($role->role_code, ['admin', 'operator', 'finance_hr']))
                                    <span class="badge bg-info ms-2">System</span>
                                @endif
                            </h5>
                            <code class="text-muted small">{{ $role->role_code }}</code>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.roles.show', $role->role_code) }}">
                                        <i class="ti ti-eye me-2"></i>View Details
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.roles.edit', $role->role_code) }}">
                                        <i class="ti ti-pencil me-2"></i>Edit Role
                                    </a>
                                </li>
                                @if(!in_array($role->role_code, ['admin', 'operator', 'finance_hr']))
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.roles.destroy', $role->role_code) }}" 
                                            method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ti ti-trash me-2"></i>
                                                Delete Role
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            {{ $role->description ?? 'No description available' }}
                        </p>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded text-center">
                                    <div class="text-primary fw-bold fs-5">{{ $role->users_count }}</div>
                                    <div class="text-muted small">Users</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded text-center">
                                    <div class="text-success fw-bold fs-5">{{ $role->permissions_count }}</div>
                                    <div class="text-muted small">Permissions</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.roles.show', $role->role_code) }}" 
                                class="btn btn-outline-primary btn-sm">
                                <i class="ti ti-eye me-2"></i>
                                View Details
                            </a>
                        </div>
                    </div>

                    <div class="card-footer">
                        <span class="text-muted small">
                            <i class="ti ti-calendar me-1"></i>
                            Created {{ \Carbon\Carbon::parse($role->created_at)->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="ti ti-info-circle me-2"></i>
                    No roles found. Create your first role to get started.
                </div>
            </div>
        @endforelse
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection