@extends('layouts.app', [
    'title' => 'Create New User'
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
                        <a href="javascript:void(0);">Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New User</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New User</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.index') }}"
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
    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm"
        class="row">
        @csrf

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Account Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- Username --}}
                        <div class="col-md-6">
                            <label for="username" class="form-label">
                                Username <span class="text-danger">*</span>
                            </label>
                            
                            <input type="text" 
                                    class="form-control @error('username') is-invalid @enderror" 
                                    id="username" 
                                    name="username" 
                                    value="{{ old('username') }}" 
                                    placeholder="e.g., john_doe"
                                    autocomplete="off"
                                    required>
                            
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Only letters, numbers, dashes and underscores allowed</small>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>

                            <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="user@example.com">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>

                            <input type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    required
                                    minlength="8">

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Minimum 8 characters</small>
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password <span class="text-danger">*</span>
                            </label>

                            <input type="password" 
                                    class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                    minlength="8">

                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row g-3">
                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control @error('full_name') is-invalid @enderror" 
                                    id="full_name" 
                                    name="full_name" 
                                    value="{{ old('full_name') }}"
                                    required
                                    placeholder="John Doe">

                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>

                            <input type="text" 
                                    class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone') }}"
                                    placeholder="+62812345678">
                            
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Role & Access</div>
                </div>
                <div class="card-body">
                    {{-- Role --}}
                    <div class="mb-0">
                        <label for="role_id" class="form-label">
                            User Role <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select single-select @error('role_id') is-invalid @enderror"
                            id="role_id" name="role_id">
                            <option value="">Select role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}" 
                                    {{ old('role_id') == $role->role_id ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Determines user permissions</small>
                    </div>

                    {{-- Employee --}}
                    <div class="mb-0">
                        <label for="employee_id" class="form-label">
                            Link to Employee
                        </label>

                        <select class="form-select single-select @error('employee_id') is-invalid @enderror" 
                            id="employee_id" name="employee_id">
                            <option value="">No Employee Link</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}" 
                                        {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->employee_code }} - {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Optional: Link user to employee record</small>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Status</strong><br>
                                <small class="text-muted">Active users can login to the system</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-circle-plus me-2"></i>
                            Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-circle-x me-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->

@endsection

@section('scripts')

    @vite(['resources/assets/js/erp/create-new-user.js'])

@endsection