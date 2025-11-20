@extends('layouts.app', [
    'title' => 'Create New Permission'
])

@section('styles')

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.css') }}">

    <style>
        .crud-checkbox-card {
            transition: all 300ms ease;
            cursor: pointer;
        }
        .crud-checkbox-card:hover {
            border-color: var(--primary);
        }
        .crud-checkbox-card.checked {
            border-color: var(--primary);
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
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Permissions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Permission</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Permission</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.permissions.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
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
    @endif

    <!-- Container -->
    <form action="{{ route('admin.permissions.store') }}" method="POST" id="permissionForm">
        @csrf

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card custom">
                    <div class="card-header">
                        <div class="card-title">Permission Information</div>
                    </div>

                    <div class="card-body">
                        {{-- Permission Name --}}
                        <div class="mb-3">
                            <label for="permission_name" class="form-label">
                                Permission Name <span class="text-danger">*</span>
                            </label>
                            
                            <input type="text" 
                                   class="form-control @error('permission_name') is-invalid @enderror" 
                                   id="permission_name" 
                                   name="permission_name" 
                                   value="{{ old('permission_name') }}" 
                                   placeholder="e.g., Manage Users"
                                   autocomplete="off"
                                   required>
                            
                            @error('permission_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Descriptive name for the permission</small>
                        </div>

                        {{-- Permission Code --}}
                        <div class="mb-3">
                            <label for="permission_code" class="form-label">
                                Permission Code <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                   class="form-control @error('permission_code') is-invalid @enderror" 
                                   id="permission_code" 
                                   name="permission_code" 
                                   value="{{ old('permission_code') }}" 
                                   placeholder="e.g., users.manage"
                                   required>

                            @error('permission_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Unique identifier (lowercase, dots/underscores allowed)</small>
                        </div>

                        {{-- Module Name --}}
                        <div class="mb-3">
                            <label for="module_name" class="form-label">
                                Module <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('module_name') is-invalid @enderror" 
                                       id="module_name" 
                                       name="module_name" 
                                       value="{{ old('module_name') }}" 
                                       list="modulesList"
                                       placeholder="e.g., User Management"
                                       required>

                                <button class="btn btn-outline-secondary dropdown-toggle" 
                                        type="button" 
                                        data-bs-toggle="dropdown">
                                    Existing
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    @foreach($modules as $module)
                                        <li>
                                            <a class="dropdown-item module-option" href="#" data-module="{{ $module }}">
                                                {{ $module }}
                                            </a>
                                        </li>
                                    @endforeach

                                    @if($modules->isEmpty())
                                        <li><span class="dropdown-item text-muted">No modules yet</span></li>
                                    @endif
                                </ul>
                            </div>

                            @error('module_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Select existing or create new module</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card custom">
                    <div class="card-header">
                        <div class="card-title">CRUD Access Permissions</div>
                    </div>

                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Select which operations this permission allows
                        </p>

                        <div class="row g-3">
                            {{-- Create --}}
                            <div class="col-6">
                                <label class="card custom crud-checkbox-card text-center p-3 mb-0" 
                                    for="can_create">
                                    <input type="checkbox" 
                                           class="form-check-input crud-checkbox d-none" 
                                           id="can_create" 
                                           name="can_create"
                                           {{ old('can_create') ? 'checked' : '' }}>
                                    <i class="ti ti-plus text-success fs-36"></i>
                                    <h6 class="mb-1">Create</h6>
                                    <small class="text-muted">Add new records</small>
                                </label>
                            </div>

                            {{-- Read --}}
                            <div class="col-6">
                                <label class="card custom crud-checkbox-card text-center p-3 mb-0" for="can_read">
                                    <input type="checkbox" 
                                           class="form-check-input crud-checkbox d-none" 
                                           id="can_read" 
                                           name="can_read"
                                           {{ old('can_read') ? 'checked' : '' }}>
                                    <i class="ti ti-eye text-info fs-36"></i>
                                    <h6 class="mb-1">Read</h6>
                                    <small class="text-muted">View records</small>
                                </label>
                            </div>

                            {{-- Update --}}
                            <div class="col-6">
                                <label class="card custom crud-checkbox-card text-center p-3 mb-0" for="can_update">
                                    <input type="checkbox" 
                                           class="form-check-input crud-checkbox d-none" 
                                           id="can_update" 
                                           name="can_update"
                                           {{ old('can_update') ? 'checked' : '' }}>
                                    <i class="ti ti-pencil text-warning fs-36"></i>
                                    <h6 class="mb-1">Update</h6>
                                    <small class="text-muted">Modify records</small>
                                </label>
                            </div>

                            {{-- Delete --}}
                            <div class="col-6">
                                <label class="card custom crud-checkbox-card text-center p-3 mb-0" for="can_delete">
                                    <input type="checkbox" 
                                           class="form-check-input crud-checkbox d-none" 
                                           id="can_delete" 
                                           name="can_delete"
                                           {{ old('can_delete') ? 'checked' : '' }}>
                                    <i class="ti ti-trash text-danger fs-36"></i>
                                    <h6 class="mb-1">Delete</h6>
                                    <small class="text-muted">Remove records</small>
                                </label>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <small>
                                        <i class="bi bi-info-circle me-2"></i>
                                        Select at least one permission type
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.permissions.index') }}" 
                        class="btn btn-outline-secondary">
                        <i class="ti ti-x me-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-2"></i>
                        Create Permission
                    </button>
                </div>
            </div>
        </div>
    </form>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-permission.js'])

@endsection