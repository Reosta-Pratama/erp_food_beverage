@extends('layouts.app', [
    'title' => 'Employees Directory'
])

@section('styles')

    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.css') }}">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugin/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/flatpickr/flatpickr.min.css') }}">

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
                    <li class="breadcrumb-item active" aria-current="page">Employees</li>
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

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-primary bg-primary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-primary fs-24 mb-1">{{ number_format($employees->total()) }}</h4>
                        <span class="fs-base fw-semibold">Total Employees</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-success bg-success bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4" /><path d="M15 19l2 2l4 -4" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-success fs-24 mb-1">{{ $employees->where('employment_status', '!=', 'Resigned')->count() }}</h4>
                        <span class="fs-base fw-semibold">Active Users</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-secondary bg-secondary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-hourglass"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.5 7h11" /><path d="M6.5 17h11" /><path d="M6 20v-2a6 6 0 1 1 12 0v2a1 1 0 0 1 -1 1h-10a1 1 0 0 1 -1 -1z" /><path d="M6 4v2a6 6 0 1 0 12 0v-2a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1z" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-secondary fs-24 mb-1">{{ $employees->where('employment_status', 'Probation')->count() }}</h4>
                        <span class="fs-base fw-semibold">Probation</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3.5" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-danger fs-24 mb-1">{{ $employees->where('employment_status', 'Resigned')->count() }}</h4>
                        <span class="fs-base fw-semibold">Resigned</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Cards -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Employees</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('hrm.employees.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
               class="btn btn-outline-success me-2">
                <i class="ti ti-download me-2"></i> 
                Export CSV
            </a>
            <a href="{{ route('hrm.employees.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Employee
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row">
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Filter & Search</div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        Employee List
                        <span class="badge bg-primary ms-2">{{ $employees->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->







<div class="container-fluid">
    

    <!-- Filters Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-funnel me-1"></i> Filters & Search
                </h6>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="bi bi-chevron-down"></i> Toggle Filters
                </button>
            </div>
        </div>
        <div class="collapse show" id="filterCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('hrm.employees.index') }}" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Name, code, email, or phone..."
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Department -->
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->department_id }}" 
                                        {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                    {{ $dept->department_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Position -->
                        <div class="col-md-4">
                            <label class="form-label">Position</label>
                            <select name="position_id" class="form-select">
                                <option value="">All Positions</option>
                                @foreach($positions as $position)
                                <option value="{{ $position->position_id }}" 
                                        {{ request('position_id') == $position->position_id ? 'selected' : '' }}>
                                    {{ $position->position_name }} ({{ $position->department_name }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Employment Status -->
                        <div class="col-md-3">
                            <label class="form-label">Employment Status</label>
                            <select name="employment_status" class="form-select">
                                <option value="">All Status</option>
                                @foreach($employmentStatuses as $status)
                                <option value="{{ $status }}" 
                                        {{ request('employment_status') === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-2">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">All</option>
                                <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Join Date From -->
                        <div class="col-md-2">
                            <label class="form-label">Join From</label>
                            <input type="date" 
                                   name="join_date_from" 
                                   class="form-control" 
                                   value="{{ request('join_date_from') }}">
                        </div>

                        <!-- Join Date To -->
                        <div class="col-md-2">
                            <label class="form-label">Join To</label>
                            <input type="date" 
                                   name="join_date_to" 
                                   class="form-control" 
                                   value="{{ request('join_date_to') }}">
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-2">
                            <label class="form-label">Sort By</label>
                            <select name="sort_by" class="form-select">
                                <option value="first_name" {{ request('sort_by') === 'first_name' ? 'selected' : '' }}>First Name</option>
                                <option value="last_name" {{ request('sort_by') === 'last_name' ? 'selected' : '' }}>Last Name</option>
                                <option value="employee_code" {{ request('sort_by') === 'employee_code' ? 'selected' : '' }}>Employee Code</option>
                                <option value="department_name" {{ request('sort_by') === 'department_name' ? 'selected' : '' }}>Department</option>
                                <option value="position_name" {{ request('sort_by') === 'position_name' ? 'selected' : '' }}>Position</option>
                                <option value="employment_status" {{ request('sort_by') === 'employment_status' ? 'selected' : '' }}>Status</option>
                                <option value="join_date" {{ request('sort_by') === 'join_date' ? 'selected' : '' }}>Join Date</option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-1">
                            <label class="form-label">Order</label>
                            <select name="sort_order" class="form-select">
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>ASC</option>
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>DESC</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('hrm.employees.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Employees Table Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-people me-1"></i> Employees List
            </h6>
            <span class="badge bg-primary">{{ $employees->total() }} Total</span>
        </div>
        <div class="card-body">
            @if($employees->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Employee</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Join Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $employee->employee_code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        <small class="text-muted">
                                            <i class="bi bi-gender-{{ strtolower($employee->gender) }}"></i>
                                            {{ $employee->gender }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($employee->email)
                                <div class="small"><i class="bi bi-envelope me-1"></i> {{ $employee->email }}</div>
                                @endif
                                @if($employee->phone)
                                <div class="small"><i class="bi bi-telephone me-1"></i> {{ $employee->phone }}</div>
                                @endif
                                @if(!$employee->email && !$employee->phone)
                                <small class="text-muted">No contact</small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $employee->department_name }}</div>
                                <small class="text-muted">{{ $employee->department_code }}</small>
                            </td>
                            <td>
                                <div>{{ $employee->position_name }}</div>
                                <small class="text-muted">{{ $employee->position_code }}</small>
                            </td>
                            <td>
                                @if($employee->employment_status === 'Permanent')
                                <span class="badge bg-success">Permanent</span>
                                @elseif($employee->employment_status === 'Probation')
                                <span class="badge bg-warning">Probation</span>
                                @elseif($employee->employment_status === 'Contract')
                                <span class="badge bg-info">Contract</span>
                                @elseif($employee->employment_status === 'Intern')
                                <span class="badge bg-primary">Intern</span>
                                @else
                                <span class="badge bg-danger">Resigned</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    @canRead('hrm')
                                    <a href="{{ route('hrm.employees.show', $employee->employee_code) }}" 
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endcanRead

                                    @canUpdate('hrm')
                                    <a href="{{ route('hrm.employees.edit', $employee->employee_code) }}" 
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcanUpdate

                                    @canDelete('hrm')
                                    @if($employee->employment_status !== 'Resigned')
                                    <button type="button" 
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $employee->employee_code }}', '{{ $employee->first_name }} {{ $employee->last_name }}')"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                    @endcanDelete
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} 
                    of {{ $employees->total() }} entries
                </div>
                <div>
                    {{ $employees->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="text-muted mt-3">No employees found</p>
                @if(request()->hasAny(['search', 'department_id', 'position_id', 'employment_status']))
                <p class="text-muted">Try adjusting your filters</p>
                <a href="{{ route('hrm.employees.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Clear Filters
                </a>
                @else
                @canCreate('hrm')
                <a href="{{ route('hrm.employees.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add First Employee
                </a>
                @endcanCreate
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <!-- Delete Confirmation Form -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/employee-init.js'])

    <script>
        function confirmDelete(employeeCode, employeeName) {
            Swal.fire({
                icon: 'warning',
                title: `Delete employee "${employeeName}"?`,
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
                    form.action = `/hrm/employees/${employeeCode}`;
                    form.submit();
                }
            });

            return false;
        }
    </script>

@endsection