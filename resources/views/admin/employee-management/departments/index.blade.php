@extends('layouts.app', [
    'title' => 'Departments'
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
                    <li class="breadcrumb-item active" aria-current="page">Departments</li>
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
            <h2 class="fs-22 mb-1">Departments</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('hrm.departments.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Department
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row">
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Filter & Search
                    </div>
                </div>

                <div class="card-body">
                    <form method="GET" action="{{ route('hrm.departments.index') }}" 
                        id="filterForm" class="row g-3">

                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" 
                                name="search" 
                                class="form-control" 
                                placeholder="Department name, code, or manager..."
                                value="{{ request('search') }}">
                        </div>

                        <!-- Manager Filter -->
                        <div class="col-md-3">
                            <label class="form-label">Manager Status</label>
                            <select name="manager" class="form-select single-select">
                                <option value="">All Departments</option>
                                <option value="assigned" {{ request('manager') === 'assigned' ? 'selected' : '' }}>
                                    With Manager
                                </option>
                                <option value="unassigned" {{ request('manager') === 'unassigned' ? 'selected' : '' }}>
                                    Without Manager
                                </option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort_by" class="form-select single-select">
                                <option value="department_name" {{ request('sort_by') === 'department_name' ? 'selected' : '' }}>
                                    Department Name
                                </option>
                                <option value="department_code" {{ request('sort_by') === 'department_code' ? 'selected' : '' }}>
                                    Department Code
                                </option>
                                <option value="manager_name" {{ request('sort_by') === 'manager_name' ? 'selected' : '' }}>
                                    Manager Name
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
                        <div class="col-md-2">
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
                            <a href="{{ route('hrm.departments.index') }}" 
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
                        Department List
                        <span class="badge bg-primary ms-2">{{ $departments->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $departments->firstItem() ?? 0 }} - {{ $departments->lastItem() ?? 0 }} of {{ $departments->total() }}
                    </div>
                </div>

                <div class="card-body p-0">
                    @if ($departments->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless table-hover mb-0">
                                <thead>
                                    <th scope="col">Code</th>
                                    <th scope="col">Department Name</th>
                                    <th scope="col">Manager</th>
                                    <th scope="col">Employees</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $department->department_code }}</span>
                                            </td>

                                            <td>
                                                <strong>{{ $department->department_name }}</strong>
                                                @if($department->description)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($department->description, 50) }}</small>
                                                @endif
                                            </td>

                                            <td>
                                                @if($department->manager_name)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm avatar-font
                                                            bg-primary text-white rounded-circle 
                                                            d-flex align-items-center justify-content-center me-2">
                                                            {{ substr($department->manager_name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div>{{ $department->manager_name }}</div>
                                                            <small class="text-muted">{{ $department->manager_code }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="ti ti-exclamation-circle me-1"></i> 
                                                        No Manager
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="ti ti-affiliate me-1"></i> {{ $department->employees_count }}
                                                </span>
                                            </td>

                                            <td>
                                                <small>{{ \Carbon\Carbon::parse($department->created_at)->format('d M Y') }}</small>
                                            </td>

                                            <td class="text-center">
                                                <div class="d-grid gap-2 d-md-block">
                                                    @hasPermission('employees.manage')
                                                        <a href="{{ route('hrm.departments.show', $department->department_code) }}" 
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="View Details">
                                                                <i class="ti ti-eye"></i>
                                                        </a>

                                                        <a href="{{ route('hrm.departments.edit', $department->department_code) }}" 
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Edit">
                                                                <i class="ti ti-pencil"></i>
                                                        </a>

                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete('{{ $department->department_code }}', '{{ $department->department_name }}')"
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
                            <h5 class="text-muted mt-3">No Department Found</h5>
                            <p class="text-muted">Try adjusting your filters or check back later</p>
                            <a href="{{ route('hrm.departments.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i> 
                                Create First Department
                            </a>
                        </div>
                    @endif
                </div>

                @if($departments->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $departments->links('pagination.default') }}
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