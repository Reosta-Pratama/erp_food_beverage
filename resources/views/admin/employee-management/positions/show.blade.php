@extends('layouts.app', [
    'title' => 'Position Details'
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
                        <a href="{{ route('hrm.positions.index') }}">Positions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Position Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Position Details</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hrm.positions.edit', $position->position_code) }}" 
               class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i> 
                Edit
            </a>
            <button type="button" 
                    class="btn btn-danger"
                    onclick="confirmDelete('{{ $position->position_code }}', '{{ $position->position_name }}')">
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
                    <div class="card-title">Position Information</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Position Code:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $position->position_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Position Name:</td>
                                    <td class="fw-bold">{{ $position->position_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Department:</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-primary text-white rounded-circle 
                                                d-flex align-items-center justify-content-center me-2">
                                                <i class="ti ti-building"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $position->department_name }}</div>
                                                <small class="text-muted">{{ $position->department_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @if ($position->job_description)
                                    <tr>
                                        <td class="text-muted">Job Description:</td>
                                        <td>
                                            <p class="mb-0" style="white-space: pre-line;">{{ $position->job_description }}</p>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Created Date:</td>
                                    <td>{{ \Carbon\Carbon::parse($position->created_at)->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Updated:</td>
                                    <td>{{ \Carbon\Carbon::parse($position->updated_at)->format('d M Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Statistics</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-primary bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-users text-primary fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $employees->count() }}</h4>
                                <small class="text-muted"> Total Employees </small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-user-check text-success fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $employees->where('employment_status', 'Active')->count() }}</h4>
                                <small class="text-muted">Active</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-danger bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-user-exclamation text-danger fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $employees->where('employment_status', 'Probation')->count() }}</h4>
                                <small class="text-muted"> Probation </small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-secondary bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-cash text-secondary fs-3"></i>
                                </div>
                                <h4 class="mb-0">
                                    @if($employees->where('base_salary', '>', 0)->count() > 0)
                                        Rp {{ number_format($employees->where('base_salary', '>', 0)->avg('base_salary'), 0, ',', '.') }}
                                    @else
                                        N/A
                                    @endif
                                </h4>
                                <small class="text-muted"> Avg. Salary </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title">Employee List</div>

                    @if($employees->count() > 0)
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active" 
                                onclick="showView('grid')">
                                <i class="ti ti-layout-grid"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                onclick="showView('list')">
                                <i class="ti ti-list"></i>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if ($employees->count() > 0)
                        <!-- Grid View -->
                        <div id="gridView" class="row g-3">
                            @foreach ($employees as $employee)
                                <div class="col-md-6">
                                    <div class="card custom mb-0">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start gap-2">
                                                <div class="avatar avatar-lg bg-primary text-white rounded-circle 
                                                    d-flex align-items-center justify-content-center me-3">
                                                    <span class="h4 mb-0">{{ substr($employee->first_name, 0, 1) }}</span>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h6>
                                                    <p class="text-muted small mb-2">{{ $employee->employee_code }}</p>
                                                    
                                                    @if($employee->employment_status === 'Active')
                                                        <span class="badge bg-success mb-2">Active</span>
                                                    @elseif($employee->employment_status === 'Probation')
                                                        <span class="badge bg-warning mb-2">Probation</span>
                                                    @else
                                                        <span class="badge bg-secondary mb-2">{{ $employee->employment_status }}</span>
                                                    @endif

                                                    @if($employee->email)
                                                        <div class="small text-muted">
                                                            <i class="ti ti-mail me-1"></i> {{ $employee->email }}
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="small text-muted mt-1">
                                                        <i class="ti ti-calendar me-1"></i> 
                                                        Joined {{ \Carbon\Carbon::parse($employee->join_date)->format('d M Y') }}
                                                    </div>

                                                    @if($employee->base_salary && $employee->base_salary > 0)
                                                        <div class="small text-muted mt-1">
                                                            <i class="ti ti-cash me-1"></i> 
                                                            Rp {{ number_format($employee->base_salary, 0, ',', '.') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Grid View -->
                        
                        <!-- List View (Hidden by default) -->
                        <div id="listView" class="d-none">
                            <div class="table-responsive">
                                <table class="table text-nowrap table-hover last-border-none mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Join Date</th>
                                            <th scope="col">Contact</th>
                                            <th scope="col">Salary</th>
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

                                                <td>
                                                    @if($employee->base_salary && $employee->base_salary > 0)
                                                        <small>Rp {{ number_format($employee->base_salary, 0, ',', '.') }}</small>
                                                    @else
                                                        <small class="text-muted">N/A</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- List View (Hidden by default) -->
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-database-off text-muted display-1"></i>
                            <h5 class="text-muted mt-3">No employees assigned to this position</h5>
                            <p class="text-muted">Employees will appear here once they are assigned to this position</p>
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
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        function showView(viewType) {
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const buttons = document.querySelectorAll('.btn-group button');
            
            if (viewType === 'grid') {
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
                buttons[0].classList.add('active');
                buttons[1].classList.remove('active');
            } else {
                gridView.classList.add('d-none');
                listView.classList.remove('d-none');
                buttons[0].classList.remove('active');
                buttons[1].classList.add('active');
            }
        }
    </script>

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