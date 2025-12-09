@extends('layouts.app', [
    'title' => 'Positions'
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
                    <li class="breadcrumb-item active" aria-current="page">Positions</li>
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
            <h2 class="fs-22 mb-1">Positions</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('hrm.positions.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Position
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
                    <form method="GET" action="{{ route('hrm.positions.index') }}"
                        id="filterForm" class="row g-3">
                    
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" 
                                name="search" 
                                class="form-control" 
                                placeholder="Position name, code, or department..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Department Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-select single-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_id }}" 
                                            {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Employee Status Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="employee_status" class="form-select single-select">
                                <option value="">All Positions</option>
                                <option value="filled" {{ request('employee_status') === 'filled' ? 'selected' : '' }}>
                                    With Employees
                                </option>
                                <option value="empty" {{ request('employee_status') === 'empty' ? 'selected' : '' }}>
                                    No Employees
                                </option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-4">
                            <label class="form-label">Sort By</label>
                            <select name="sort_by" class="form-select single-select">
                                <option value="department_name" {{ request('sort_by') === 'department_name' ? 'selected' : '' }}>
                                    Department
                                </option>
                                <option value="position_name" {{ request('sort_by') === 'position_name' ? 'selected' : '' }}>
                                    Position Name
                                </option>
                                <option value="position_code" {{ request('sort_by') === 'position_code' ? 'selected' : '' }}>
                                    Position Code
                                </option>
                                <option value="employees_count" {{ request('sort_by') === 'employees_count' ? 'selected' : '' }}>
                                    Employee Count
                                </option>
                                <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>
                                    Created Date
                                </option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-4">
                            <label class="form-label">Order</label>
                            <select name="sort_order" class="form-select single-select">
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>
                                    Ascending
                                </option>
                                <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>
                                    Descending
                                </option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" 
                                class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> 
                                Apply Filters
                            </button>
                            <a href="{{ route('hrm.positions.index') }}" 
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
                        Position List
                        <span class="badge bg-primary ms-2">{{ $positions->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $positions->firstItem() ?? 0 }} - {{ $positions->lastItem() ?? 0 }} of {{ $positions->total() }}
                    </div>
                </div>

                <div class="card-body p-0">
                    @if ($positions->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless table-hover mb-0">
                                <thead>
                                    <th scope="col">Code</th>
                                    <th scope="col">Position Name</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Employees</th>
                                    <th scope="col">Job Description</th>
                                    <th scope="col">Created</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($positions as $position)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $position->position_code }}</span>
                                            </td>

                                            <td>
                                                <strong>{{ $position->position_name }}</strong>
                                            </td>

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm avatar-font
                                                        bg-primary text-white rounded-circle 
                                                        d-flex align-items-center justify-content-center me-2">
                                                        <i class="ti ti-building"></i>
                                                    </div>
                                                    <div>
                                                        <div>{{ $position->department_name }}</div>
                                                        <small class="text-muted">{{ $position->department_code }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                @if($position->employees_count > 0)
                                                    <span class="badge bg-success">
                                                        <i class="ti ti-users me-1"></i> 
                                                        {{ $position->employees_count }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="ti ti-database-off me-1"></i> 
                                                        Empty
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($position->job_description)
                                                    <small class="text-muted">{{ Str::limit($position->job_description, 60) }}</small>
                                                @else
                                                    <small class="text-muted fst-italic">No description</small>
                                                @endif
                                            </td>

                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($position->created_at)->format('d M Y') }}</small>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    @hasPermission('employees.manage')
                                                        <a href="{{ route('hrm.positions.show', $position->position_code) }}"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="View Details">
                                                                <i class="ti ti-eye"></i>
                                                        </a>

                                                        <a href="{{ route('hrm.positions.edit', $position->position_code) }}" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Edit">
                                                                <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $position->position_code }}', '{{ $position->position_name }}')"
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
                            <h5 class="text-muted mt-3">No Position Found</h5>
                            <p class="text-muted">Try adjusting your filters or check back later</p>
                            <a href="{{ route('hrm.positions.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> 
                                Create First Position
                            </a>
                        </div>
                    @endif
                </div>

                @if($positions->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $positions->links('pagination.default') }}
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

    @vite(['resources/assets/js/erp/position-init.js'])

    <script>
        function confirmDelete(positionCode, positionName) {
            Swal.fire({
                icon: 'warning',
                title: `Delete position "${positionName}"?`,
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
                    form.action = `/hrm/positions/${positionCode}`;
                    form.submit();
                }
            });

            return false;
        }
    </script>

@endsection