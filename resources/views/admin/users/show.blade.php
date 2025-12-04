@extends('layouts.app', [
    'title' => 'User Details - ' . $user->username
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
                    <li class="breadcrumb-item active" aria-current="page">User Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">User Details</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.users.edit', $user->user_id) }}"
                class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i>
                Edit User
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center fade show mb-3" role="alert">
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
        <div class="col-lg-4">
            <div class="row">
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-body text-center">
                            <span class="initial logo-company-size mx-auto mb-4">
                                <span>{{ strtoupper(substr($user->full_name, 0, 1)) }}</span>
                            </span>

                            <h5 class="mb-1">{{ $user->full_name }}</h5>
                            <p class="text-muted mb-3">@<span>{{ $user->username }}</span></p>
                            
                            @if($user->is_active)
                                <span class="badge bg-success px-3 py-2">Active</span>
                            @else
                                <span class="badge bg-danger px-3 py-2">Inactive</span>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                <button type="button" 
                                    class="btn btn-outline-primary btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#resetPasswordModal">
                                    <i class="ti ti-key me-2"></i>
                                    Reset Password
                                </button>
                                <form action="{{ route('admin.users.toggle-status', $user->user_id) }}" 
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }} btn-sm w-100">
                                        <i class="bi bi-toggle-{{ $user->is_active ? 'off' : 'on' }} me-2"></i>
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }} Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Role & Access</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tr>
                                        <td class="text-muted">Role</td>
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
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Description</td>
                                        <td>
                                            <p class="mb-0 small">{{ $user->role_description }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Contact Information</div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Email Address</label>
                                    <div>
                                        <a href="mailto:{{ $user->email }}" 
                                            class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover text-decoration-underline">
                                            {{ $user->email }}
                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small">Phone Number</label>
                                    <div>
                                        @if($user->phone)
                                            <a href="tel:{{ $user->phone }}" 
                                                class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover text-decoration-underline">
                                                {{ $user->phone }}
                                            </a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($user->employee_code)
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Employee Statistics</div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">Employee Code</label>
                                        <div>
                                            <strong>{{ $user->employee_code }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Employee Name</label>
                                        <div>{{ $user->employee_name }}</div>
                                    </div>
                                    @if($user->employee_email)
                                        <div class="col-md-6">
                                            <label class="text-muted small">Employee Email</label>
                                            <div>
                                                <a href="mailto:{{ $user->employee_email }}"
                                                    class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover text-decoration-underline">
                                                    {{ $user->employee_email }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Account Statistics</div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">Last Login</label>
                                    <div>
                                        @if($user->last_login)
                                            {{ \Carbon\Carbon::parse($user->last_login)->format('d M Y - H:i') }}
                                            <br>
                                            <small class="text-muted">
                                                ({{ \Carbon\Carbon::parse($user->last_login)->diffForHumans() }})
                                            </small>
                                        @else
                                            <span class="text-muted">Never logged in</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Account Created</label>
                                    <div>
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y - H:i') }}
                                        <br>
                                        <small class="text-muted">
                                            ({{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }})
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Last Updated</label>
                                    <div>
                                        {{ \Carbon\Carbon::parse($user->updated_at)->format('d M Y - H:i') }}
                                        <br>
                                        <small class="text-muted">
                                            ({{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }})
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">Email Verified</label>
                                    <div>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y') }}
                                            </small>
                                        @else
                                            <span class="badge bg-warning">Not Verified</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Recent Activity</div>
                        </div>
                        <div class="card-body p-0">
                            @if($recentActivities->count() > 0)
                                <div class="table-responsive">
                                    <table class="table text-nowrap table-borderless mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">Module</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentActivities as $activity)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $type = strtoupper($activity->activity_type);
                                                            $colors = [
                                                                'VIEWED' => 'info',
                                                                'CREATE' => 'success',
                                                                'UPDATE' => 'warning',
                                                                'DELETE' => 'danger',
                                                            ];
                                                        @endphp

                                                        <span class="badge bg-{{ $colors[$type] ?? 'secondary' }}">
                                                            {{ $activity->activity_type }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <small>{{ $activity->module_name }}</small>
                                                    </td>
                                                    
                                                    <td>
                                                        <small>{{ $activity->description }}</small>
                                                    </td>

                                                    <td>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($activity->activity_timestamp)->diffForHumans() }}
                                                        </small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-center text-muted py-3 mb-0">
                                    <i class="ti ti-database-off display-1 d-block mb-2"></i>
                                    No recent activity
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('modals')

    {{-- Reset Password Modal --}}
    <div class="modal fade" id="resetPasswordModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="resetPasswordModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.users.reset-password', $user->user_id) }}" method="POST"
                class="modal-content">
                @csrf

                <div class="modal-header d-flex justify-content-between bg-primary">
                    <h6 class="modal-title text-white">
                        Reset Password
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center gap-2">
                        <i class="ti ti-exclamation-circle"></i>
                        You are about to reset the password for 
                        <strong>{{ $user->username }}</strong>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                            class="form-control" 
                            id="new_password" 
                            name="new_password" 
                            required
                            minlength="8">
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">
                            Confirm New Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" 
                            class="form-control" 
                            id="new_password_confirmation" 
                            name="new_password_confirmation" 
                            required
                            minlength="8">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-circle-check me-2"></i>
                        Reset Password
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
@endsection