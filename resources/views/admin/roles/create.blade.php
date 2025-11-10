@extends('layouts.app', [
    'title' => 'Create New Role' 
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
                        <a href="javascript:void(0);">User Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Roles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Role</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row g-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-18 mb-0">Create New Role</h2>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.roles.index') }}"
                        class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Error Messages --}}
        @if($errors->any())
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show">
                    <h6 class="d-flex align-items-center">
                        <i class="ti ti-exclamation-circle fs-18 me-2"></i>
                        Please fix the following errors:
                    </h6>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.roles.store') }}" method="POST" 
            id="roleForm" class="col-12">
            @csrf

            <div class="row">
                <div class="col-lg-4">
                    <div class="card custom" style="position: sticky; top: 68px">
                        <div class="card-header">
                            <div class="card-title fs-16">Role Information</div>
                        </div>
                        
                        <div class="card-body">
                            {{-- Role Name --}}
                            <div class="mb-3">
                                <label for="role_name" class="form-label">
                                    Role Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                    class="form-control @error('role_name') is-invalid @enderror" 
                                    id="role_name" 
                                    name="role_name" 
                                    value="{{ old('role_name') }}" 
                                    placeholder="e.g., Warehouse Manager"
                                    autocomplete="off"
                                    required>
                                
                                @error('role_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">Display name for the role</small>
                            </div>

                            {{-- Role Code --}}
                            <div class="mb-3">
                                <label for="role_code" class="form-label">
                                    Role Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                    class="form-control @error('role_code') is-invalid @enderror" 
                                    id="role_code" 
                                    name="role_code" 
                                    value="{{ old('role_code') }}" 
                                    placeholder="e.g., warehouse_manager"
                                    pattern="[a-z0-9_]+"
                                    autocomplete="off"
                                    required>

                                @error('role_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">Unique identifier (lowercase, no spaces)</small>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    Description
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                        id="description" 
                                        name="description" 
                                        rows="4"
                                        placeholder="Describe the role's responsibilities...">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Permission Summary --}}
                            <div class="alert alert-info mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-info-circle me-2"></i>
                                        Selected Permissions:
                                    </span>
                                    <strong id="permissionCount">0</strong>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-circle-check me-2"></i>
                                    Create Role
                                </button>

                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-circle-x me-2"></i>
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row">
                        @foreach($permissions as $moduleName => $modulePermissions)
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                                    <h5 class="fs-16 mb-0">
                                        <i class="ti ti-table text-primary"></i>
                                        {{ $moduleName }}
                                    </h5>

                                    <div class="form-check">
                                        <input class="form-check-input select-all-module" 
                                                type="checkbox" 
                                                id="selectModule_{{ Str::slug($moduleName) }}"
                                                data-module="{{ Str::slug($moduleName) }}">
                                        <label class="form-check-label select-all-module" 
                                                for="selectModule_{{ Str::slug($moduleName) }}">
                                                Select All
                                        </label>
                                    </div>
                                </div>

                                <div class="card custom">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <table class="table text-nowrap table-borderless mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Select Permission</th>
                                                        <th scope="col" class="text-center">Create</th>
                                                        <th scope="col" class="text-center">Read</th>
                                                        <th scope="col" class="text-center">Update</th>
                                                        <th scope="col" class="text-center">Delete</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($modulePermissions as $permission)
                                                        <tr>
                                                            <td>
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input module-permission" 
                                                                        type="checkbox" 
                                                                        name="permissions[]" 
                                                                        value="{{ $permission->permission_id }}"
                                                                        id="perm_{{ $permission->permission_id }}"
                                                                        data-module="{{ Str::slug($moduleName) }}"
                                                                        {{ in_array($permission->permission_id, old('permissions', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-bold" for="perm_{{ $permission->permission_id }}">
                                                                        {{ $permission->permission_name }}
                                                                    </label>
                                                                </div>
                                                                <small class="text-muted">
                                                                    <code>{{ $permission->permission_code }}</code>
                                                                </small>
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
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-role.js'])

@endsection
