@extends('layouts.app', [
    'title' => 'Users Management'
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
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Users Management</h2>
            <p class="text-muted mb-0">Manage and oversee all user accounts and permissions.</p>
        </div>

        @canCreate('users')
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i>
                    Create New User
                </a>
            </div>
        @endcanCreate
    </div>

    <!-- Container -->
    <div class="row g-">
        <div class="col-lg-3">
            <div class="card custom sticky-card">
                <div class="card-header">
                    <div class="card-title">Filters</div>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}"
                    class="card-body">

                    {{-- Search --}}
                    <div class="mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" 
                            class="form-control" 
                            id="search" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Username, email, or name..."
                            autocomplete="off">
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select single-select" id="role" name="role"
                            data-trigger="data-trigger">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->role_code }}" 
                                        {{ request('role') == $role->role_code ? 'selected' : '' }}>
                                    {{ $role->role_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select single-select" id="status" name="status"
                            data-trigger="data-trigger">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="ti ti-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.users.index') }}" 
                            class="btn btn-outline-danger">
                            <i class="ti ti-rotate-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        User List
                        <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless mb-0">
                                <thead>
                                    <th scope="col">Username</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Last Login</th>
                                    <th scope="col">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->username }}</strong>
                                            </td>

                                            <td>{{ $user->full_name }}</td>

                                            <td>
                                                <a href="mailto:{{ $user->email }}" 
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover text-decoration-underline">
                                                    {{ $user->email }}
                                                </a>
                                            </td>

                                            <td>
                                                @if($user->role_code === 'admin')
                                                    <span class="badge bg-danger">{{ $user->role_name }}</span>
                                                @elseif($user->role_code === 'operator')
                                                    <span class="badge bg-primary">{{ $user->role_name }}</span>
                                                @elseif($user->role_code === 'finance_hr')
                                                    <span class="badge bg-success">{{ $user->role_name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $user->role_name }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($user->employee_name)
                                                    <span class="text-muted">{{ $user->employee_code }}</span><br>
                                                    <small>{{ $user->employee_name }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($user->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($user->last_login)
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    @canRead('users')
                                                        <a class="btn btn-sm btn-primary btn-wave"
                                                            href="{{ route('admin.users.show', $user->user_id) }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @endcanRead

                                                    @canUpdate('users')
                                                        <a class="btn btn-sm btn-success btn-wave"
                                                            href="{{ route('admin.users.edit', $user->user_id) }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    @endcanUpdate

                                                    @canDelete('users')
                                                        <form action="{{ route('admin.users.destroy', $user->user_id) }}" 
                                                            method="POST" 
                                                            data-username="{{ $user->username }}"
                                                            class="d-inline delete-user-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            
                                                            <button class="btn btn-sm btn-danger btn-wave"
                                                                type="submit"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcanDelete
                                                </div>
                                            </td>
                                        </tr>                                        
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-database-off text-muted display-1"></i>
                            <p class="text-muted mt-3">No users found</p>
                            <a href="{{ route('admin.users.create') }}" 
                                class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>
                                Create First User
                            </a>
                        </div>
                    @endif
                </div>

                @if($users->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $users->links('pagination.default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Container -->


@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        const dropdownElements = document.querySelectorAll('.single-select');
        if (dropdownElements.length > 0) {
            dropdownElements.forEach((dropdown) => {
                new Choices(dropdown, {searchEnabled: true});
            });
        }
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteForms = document.querySelectorAll('.delete-user-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 

                const username = form.getAttribute('data-username');

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
                        form.submit(); 
                    }
                });
            });
        });
    });
    </script>
  
@endsection