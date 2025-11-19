@extends('layouts.app', [
    'title' => 'Edit User - ' . $user->username
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
                        <a href="javascript:void(0);">Users</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit User</h2>

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
    <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST" id="userForm"
        class="row g-4">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="row">
                <div class="col-12">
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
                                            value="{{ old('username', $user->username) }}" 
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
                                            value="{{ old('email', $user->email) }}"
                                            required
                                            placeholder="user@example.com">

                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        To change the password, use the <strong>Reset Password</strong> button on the user detail page.
                                    </div>
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
                                            value="{{ old('full_name', $user->full_name) }}"
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
                                            value="{{ old('phone', $user->phone) }}"
                                            placeholder="+62812345678">
                                    
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @canDelete('users')
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">
                                    Danger Zone
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Delete This User</h6>
                                        <p class="text-muted mb-0">
                                            Once you delete a user, there is no going back. Please be certain.
                                        </p>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            onclick="confirmDelete()">
                                        <i class="ti ti-trash me-2"></i>Delete User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcanDelete
            </div>
        </div>

        <div class="col-lg-4">
            <div class="row">
                <div class="col-12">
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
                                            {{ old('role_id', $user->role_id) == $role->role_id ? 'selected' : '' }}>
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
                                            {{ old('employee_id', $user->employee_id) == $employee->employee_id ? 'selected' : '' }}>
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
                                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Active Status</strong><br>
                                        <small class="text-muted">Active users can login to the system</small>
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="ti ti-circle-check me-2"></i>
                                    Update User
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

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-body">
                            <small class="text-muted d-block mb-2">
                                <i class="ti ti-clock me-1"></i>
                                Last updated: {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y - H:i') }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="ti ti-calendar me-1"></i>
                                Created: {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y - H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->

    <!-- Delete Confirmation Form -->
    @canDelete('users')
        <form id="deleteForm" 
            action="{{ route('admin.users.destroy', $user->user_id) }}" 
            method="POST" 
            style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endcanDelete
    <!-- Delete Confirmation Form -->

@endsection

@section('modals')
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/edit-user.js'])

    <script>
        function confirmDelete() {
            const username = '{{ $user->username }}';

            Swal.fire({
                title: `Delete user "${username}"?`,
                text: "This action cannot be undone. All associated data will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                reverseButtons: true, 
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    </script>

@endsection