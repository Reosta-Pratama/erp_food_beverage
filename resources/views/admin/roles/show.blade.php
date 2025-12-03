@extends('layouts.app', [
    'title' => 'Role Details - ' . $role->role_name
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
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Roles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Role Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">{{ $role->role_name }}</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.roles.edit', $role->role_code) }}" 
                class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i>
                Edit Role
            </a>
            <a href="{{ route('admin.roles.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>
    
    <!-- Container -->
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Role Information</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tr>
                                        <td class="text-muted">Role Name:</td>
                                        <td class="fw-bold">{{ $role->role_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Role Code:</td>
                                        <td><code>{{ $role->role_code }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Type:</td>
                                        <td>
                                            @if(in_array($role->role_code, ['admin', 'operator', 'finance_hr']))
                                            <span class="badge bg-info">System Role</span>
                                            @else
                                            <span class="badge bg-secondary">Custom Role</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Description:</td>
                                        <td>{{ $role->description ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Created:</td>
                                        <td>{{ \Carbon\Carbon::parse($role->created_at)->format('d M Y - H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Updated:</td>
                                        <td>{{ \Carbon\Carbon::parse($role->updated_at)->format('d M Y - H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Statistics</div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="bg-primary bg-opacity-10 rounded p-3 mb-2">
                                            <i class="ti ti-users text-primary fs-3"></i>
                                        </div>
                                        <h4 class="mb-0">{{ $role->users_count }}</h4>
                                        <small class="text-muted">Active Users</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="bg-success bg-opacity-10 rounded p-3 mb-2">
                                            <i class="ti ti-shield-check text-success fs-3"></i>
                                        </div>
                                        <h4 class="mb-0">{{ $permissions->flatten()->count() }}</h4>
                                        <small class="text-muted">Permissions</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="bg-danger bg-opacity-10 rounded p-3 mb-2">
                                            <i class="ti ti-layout text-danger fs-3"></i>
                                        </div>
                                        <h4 class="mb-0">{{ $permissions->count() }}</h4>
                                        <small class="text-muted">Modules</small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="bg-secondary bg-opacity-10 rounded p-3 mb-2">
                                            <i class="ti ti-circle-check text-secondary fs-3"></i>
                                        </div>
                                        <h4 class="mb-0">{{ $permissions->flatten()->where('can_create', 1)->count() }}</h4>
                                        <small class="text-muted">Create Access</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                @forelse($permissions as $moduleName => $modulePermissions)
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <h5 class="fs-16 mb-0">
                                <i class="ti ti-table text-primary"></i>
                                {{ $moduleName }}
                            </h5>
                        </div>

                        <div class="card custom">
                            <div class="card-body p-2">
                                <div class="table-responsive">
                                    <table class="table text-nowrap table-borderless table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Permission</th>
                                                <th scope="col" class="text-center">Create</th>
                                                <th scope="col" class="text-center">Read</th>
                                                <th scope="col" class="text-center">Update</th>
                                                <th scope="col" class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modulePermissions as $permission)
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>
                                                        <div>
                                                            <div class="fw-bold">{{ $permission->permission_name }}</div>
                                                            <code class="small text-muted">{{ $permission->permission_code }}</code>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($permission->can_create)
                                                            <span class="action-badge badge bg-success">
                                                                <i class="ti ti-check"></i>
                                                            </span>
                                                        @else
                                                            <span class="action-badge badge bg-danger">
                                                                <i class="ti ti-x"></i>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($permission->can_read)
                                                            <span class="action-badge badge bg-success">
                                                                <i class="ti ti-check"></i>
                                                            </span>
                                                        @else
                                                            <span class="action-badge badge bg-danger">
                                                                <i class="ti ti-x"></i>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($permission->can_update)
                                                            <span class="action-badge badge bg-success">
                                                                <i class="ti ti-check"></i>
                                                            </span>
                                                        @else
                                                            <span class="action-badge badge bg-danger">
                                                                <i class="ti ti-x"></i>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($permission->can_delete)
                                                            <span class="action-badge badge bg-success">
                                                                <i class="ti ti-check"></i>
                                                            </span>
                                                        @else
                                                            <span class="action-badge badge bg-danger">
                                                                <i class="ti ti-x"></i>
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">
                        <i class="ti ti-database-off me-2"></i>
                        No permissions assigned to this role yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection