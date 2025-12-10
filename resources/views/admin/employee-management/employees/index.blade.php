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

                <div class="card-body">
                    <form method="GET" action="{{ route('hrm.employees.index') }}"
                        id="filterForm" class="row g-3">

                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label" for="search">Search</label>
                            <input type="text" 
                                id="search"
                                name="search" 
                                class="form-control" 
                                placeholder="Name, code, email, or phone..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Department -->
                        <div class="col-md-3">
                            <label class="form-label" for="department_id">Department</label>
                            <select id="department_id" name="department_id" 
                                class="form-select single-select">
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
                        <div class="col-md-3">
                            <label class="form-label" for="position_id">Position</label>
                            <select id="position_id" name="position_id" 
                                class="form-select single-select">
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
                            <label class="form-label" for="employment_status">Employment Status</label>
                            <select id="employment_status" name="employment_status" 
                                class="form-select single-select">
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
                        <div class="col-md-3">
                            <label class="form-label" for="gender">Gender</label>
                            <select id="gender" name="gender" 
                                class="form-select single-select">
                                <option value="">All</option>
                                <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Join Date Range -->
                        <div class="col-md-3">
                            <label class="form-label" for="daterange">Join Date</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> 
                                        <i class="ti ti-calendar"></i> 
                                    </div>
                                    <input class="form-control daterange" 
                                        id="daterange"
                                        name="daterange"
                                        join_from=@json(request('join_date_from'))
                                        join_to=@json(request('join_date_to'))
                                        type="text" 
                                        placeholder="Join date range...">
                                </div>
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <label class="form-label" for="sort_by">Sort By</label>
                            <select id="sort_by" name="sort_by" 
                                class="form-select single-select">
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
                        <div class="col-md-3">
                            <label class="form-label" for="sort_order">Order</label>
                            <select id="sort_order" name="sort_order" 
                                class="form-select single-select">
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" 
                                class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> 
                                Apply Filters
                            </button>
                            <a href="{{ route('hrm.employees.index') }}" 
                                class="btn btn-danger">
                                <i class="ti ti-x me-1"></i> 
                                Clear Filters
                            </a>
                        </div>

                    </form>
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

                <div class="card-body p-0">
                    @if ($employees->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless table-hover mb-0">
                                <thead>
                                    <th scope="col">Code</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Join Date</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $employee->employee_code }}</span>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm avatar-font 
                                                        bg-primary text-white rounded-circle 
                                                        d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div>{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                                        <small class="text-muted">
                                                            <i class="ti ti-gender-{{ strtolower($employee->gender) }}"></i>
                                                            {{ $employee->gender }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @if($employee->email)
                                                    <div class="small">
                                                        <i class="ti ti-mail me-1"></i> 
                                                        {{ $employee->email }}
                                                    </div>
                                                @endif
                                                @if($employee->phone)
                                                    <div class="small">
                                                        <i class="ti ti-phone me-1"></i> 
                                                        {{ $employee->phone }}
                                                    </div>
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
                                                <div class="d-grid gap-2 d-md-block">
                                                    @hasPermission('employees.manage')
                                                        <a href="{{ route('hrm.employees.show', $employee->employee_code) }}" 
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="View Details">
                                                                <i class="ti ti-eye"></i>
                                                        </a>

                                                        <a href="{{ route('hrm.employees.edit', $employee->employee_code) }}" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Edit">
                                                                <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $employee->employee_code }}', '{{ $employee->first_name }} {{ $employee->last_name }}')"
                                                                title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endhasPermission
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
                            <h5 class="text-muted mt-3">No employees Found</h5>
                            <p class="text-muted">Try adjusting your filters or check back later</p>
                            <a href="{{ route('hrm.employees.index') }}" 
                                class="btn btn-danger">
                                <i class="ti ti-x me-1"></i> 
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </div>

                @if($employees->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $employees->links('pagination.default') }}
                    </div>
                @endif
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