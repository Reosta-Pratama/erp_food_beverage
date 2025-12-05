@extends('layouts.app', [
    'title' => 'Department Details'
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
                        <a href="javascript:void(0);">Employee Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('hrm.departments.index') }}">Departments</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Department Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Department Details</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hrm.departments.edit', $department->department_code) }}" 
               class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i> 
                Edit
            </a>
            <button type="button" 
                    class="btn btn-danger"
                    onclick="confirmDelete('{{ $department->department_code }}', '{{ $department->department_name }}')">
                <i class="ti ti-trash me-2"></i> 
                Delete
            </button>
        </div>
    </div>

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
    <div class="row">
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Department Information</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Department Code:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $department->department_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Department Code:</td>
                                    <td class="fw-bold">{{ $department->department_name }}</td>
                                </tr>
                                @if ($department->description)
                                    <tr>
                                        <td class="text-muted">Description:</td>
                                        <td>{{ $department->description }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Created Date:</td>
                                    <td>{{ \Carbon\Carbon::parse($department->created_at)->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td>{{ \Carbon\Carbon::parse($department->updated_at)->format('d M Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Department Manager</div>
                </div>
                <div class="card-body">
                    @if ($department->manager_name)
                        <div class="text-center">
                            <div class="avatar avatar-lg bg-primary text-white rounded-circle 
                                d-inline-flex align-items-center justify-content-center mb-3">
                                <span class="fs-22 mb-0">
                                    {{ substr($department->manager_name, 0, 1) }}
                                </span>
                            </div>
                            <h5 class="mb-1">{{ $department->manager_name }}</h5>
                            <p class="text-muted mb-2">{{ $department->manager_code }}</p>
                            @if($department->manager_email)
                                <p class="text-muted small mb-0">
                                    <i class="ti ti-mail me-1"></i> 
                                    {{ $department->manager_email }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="ti ti-database-off text-muted display-3"></i>
                            <h5 class="text-muted mt-3">No manager assigned</h5>
                            <button type="button" 
                                    class="btn btn-sm btn-primary mt-2"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#assignManagerModal">
                                <i class="ti ti-plus me-1"></i> 
                                Assign Manager
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card custom">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar avatar-xxl svg-primary bg-primary bg-opacity-10 rounded-circle border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                            </div>
                            <div class="ms-3">
                                <h4 class="text-primary fs-24 mb-1">{{ $employees->count() }}</h4>
                                <span class="fs-base fw-semibold">Employees</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card custom">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar avatar-xxl svg-success bg-success bg-opacity-10 rounded-circle border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                            </div>
                            <div class="ms-3">
                                <h4 class="text-success fs-24 mb-1">{{ $positions->count() }}</h4>
                                <span class="fs-base fw-semibold">Positions</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card custom">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg>
                            </div>
                            <div class="ms-3">
                                <h4 class="text-danger fs-24 mb-1">{{ $employees->where('employment_status', 'Active')->count() }}</h4>
                                <span class="fs-base fw-semibold">Active Staff</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Employees</div>
                </div>
                <div class="card-body p-0">
                    @if ($employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover last-border-none text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Employee</th>
                                        <th scope="col">Position</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Join Data</th>
                                        <th scope="col">Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle 
                                                        d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($employee->first_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">
                                                            {{ $employee->first_name }} {{ $employee->last_name }}
                                                        </div>
                                                        <small class="text-muted">{{ $employee->employee_code }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>{{ $employee->position_name }}</td>

                                            <td>
                                                @if($employee->employment_status === 'Active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($employee->employment_status === 'Probation')
                                                    <span class="badge bg-warning">Probation</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $employee->employment_status }}</span>
                                                @endif
                                            </td>

                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</small>
                                            </td>
                                            
                                            <td>
                                                @if($employee->email)
                                                    <small>{{ $employee->email }}</small>
                                                @else
                                                    <small class="text-muted">No email</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-database-off text-muted display-1"></i>
                            <h5 class="text-muted mt-3">No employees in this department</h5>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Positions</div>
                </div>
                <div class="card-body p-0">
                    @if ($positions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover last-border-none text-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Position Code</th>
                                        <th scope="col">Position Name</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($positions as $position)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $position->position_code }}
                                                </span>
                                            </td>
                                            
                                            <td>
                                                <strong>{{ $position->position_name }}</strong>
                                            </td>

                                            <td>
                                                @if($position->job_description)
                                                    {{ Str::limit($position->job_description, 80) }}
                                                @else
                                                    <span class="text-muted">No description</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-database-off text-muted display-1"></i>
                            <h5 class="text-muted mt-3">No positions defined for this department</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!-- Delete Confirmation Form -->

@endsection

@section('modals')

    {{-- Assigned Manager --}}
    <div class="modal fade" id="assignManagerModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="assignManagerModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('hrm.departments.assign-manager', $department->department_code) }}"
                id="assignedManagerForm" class="modal-content">
                @csrf

                <div class="modal-header d-flex justify-content-between bg-primary">
                    <h6 class="modal-title text-white">
                        Assign Department Manager
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_manager_id" class="form-label">Select Manager</label>
                        <select class="form-select single-select" 
                            id="modal_manager_id" name="manager_id">
                            <option value="">-- Select Manager --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}" 
                                        {{ $department->manager_id == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="ti ti-circle-info me-2"></i>
                        Only active employees from this department are shown
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-2"></i>
                        Assign Manager
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/department-init.js'])

    <script>
        function confirmDelete(departmentCode, departmentName) {
            Swal.fire({
                icon: 'warning',
                title: `Delete department "${departmentName}"?`,
                text: "This action cannot be undone.",
                showCancelButton: true,
                confirmButtonColor: "#985ffd",
                cancelButtonColor: "#faf8fd",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/hrm/departments/${departmentCode}`;
                    form.submit();
                }
            });

            return false;
        }
    </script>

@endsection