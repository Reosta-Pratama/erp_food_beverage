@extends('layouts.app', [
    'title' => 'Permission Details - ' . $permission->permission_name
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
                        <a href="javascript:void(0);">Permissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Permission Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">{{ $permission->permission_name }}</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.permissions.edit', $permission->permission_id) }}" 
                class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i>
                Edit Permission
            </a>
            <a href="{{ route('admin.permissions.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Permission Information</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tr>
                                        <td class="text-muted">Permission Name:</td>
                                        <td class="fw-bold">{{ $permission->permission_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Permission Code:</td>
                                        <td><code>{{ $permission->permission_code }}</code></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Module:</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $permission->module_name }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Created:</td>
                                        <td class="small">{{ \Carbon\Carbon::parse($permission->created_at)->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Last Updated:</td>
                                        <td class="small">{{ \Carbon\Carbon::parse($permission->updated_at)->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Assigned Roles:</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">{{ $permission->roles_count }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">CRUD Access</div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="access-icon {{ $permission->can_create ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 mx-auto mb-2">
                                            <i class="ti ti-plus {{ $permission->can_create ? 'text-success' : 'text-secondary' }}"></i>
                                        </div>
                                        <h6 class="mb-0">Create</h6>
                                        <small class="{{ $permission->can_create ? 'text-success' : 'text-muted' }}">
                                            {{ $permission->can_create ? 'Allowed' : 'Denied' }}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="access-icon {{ $permission->can_read ? 'bg-info' : 'bg-secondary' }} bg-opacity-10 mx-auto mb-2">
                                            <i class="ti ti-eye {{ $permission->can_read ? 'text-info' : 'text-secondary' }}"></i>
                                        </div>
                                        <h6 class="mb-0">Read</h6>
                                        <small class="{{ $permission->can_read ? 'text-info' : 'text-muted' }}">
                                    {{ $permission->can_read ? 'Allowed' : 'Denied' }}
                                </small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="access-icon {{ $permission->can_update ? 'bg-warning' : 'bg-secondary' }} bg-opacity-10 mx-auto mb-2">
                                            <i class="ti ti-pencil {{ $permission->can_update ? 'text-warning' : 'text-secondary' }}"></i>
                                        </div>
                                        <h6 class="mb-0">Update</h6>
                                        <small class="{{ $permission->can_update ? 'text-warning' : 'text-muted' }}">
                                            {{ $permission->can_update ? 'Allowed' : 'Denied' }}
                                        </small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="access-icon {{ $permission->can_delete ? 'bg-danger' : 'bg-secondary' }} bg-opacity-10 mx-auto mb-2">
                                            <i class="ti ti-trash {{ $permission->can_delete ? 'text-danger' : 'text-secondary' }}"></i>
                                        </div>
                                        <h6 class="mb-0">Delete</h6>
                                        <small class="{{ $permission->can_delete ? 'text-danger' : 'text-muted' }}">
                                            {{ $permission->can_delete ? 'Allowed' : 'Denied' }}
                                        </small>
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
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                        <h5 class="fs-16 mb-0">
                            <i class="ti ti-table text-primary"></i>
                            Assigned to Roles
                        </h5>
                    </div>
                </div>
                @if($roles->count() > 0)
                    @foreach($roles as $role)
                    <div class="col-md-6">
                        <div class="card custom">
                            <div class="card-header justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1">{{ $role->role_name }}</h5>
                                    <code class="text-muted small">{{ $role->role_code }}</code>
                                </div>

                                @if(in_array($role->role_code, ['admin', 'operator', 'finance_hr']))
                                    <span class="badge bg-info ms-2">System</span>
                                @endif
                            </div>

                            <div class="card-body">
                                <p class="text-muted small mb-3">
                                    {{ $role->description ?? 'No description available' }}
                                </p>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.roles.show', $role->role_code) }}" 
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-eye me-2"></i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-body">
                                <div class="text-center py-5">
                                    <i class="ti ti-inbox text-muted fs-40"></i>
                                    <p class="text-muted mt-3 mb-0">
                                        This permission is not assigned to any roles yet.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection