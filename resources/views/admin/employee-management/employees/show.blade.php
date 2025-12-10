@extends('layouts.app', [
    'title' => 'Employee Details'
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
                        <a href="javascript:void(0);">Employee Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('hrm.employees.index') }}">Employees</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Employee Profile</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Employee Profile</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hrm.employees.edit', $employee->employee_code) }}" 
               class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i> 
                Edit
            </a>
            <button type="button" class="btn btn-danger" 
                data-bs-toggle="modal" data-bs-target="#terminateModal">
                <i class="ti ti-user-x me-2"></i> 
                Terminate
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
                <div class="card-body text-center">
                    <div class="avatar avatar-xl avatar-font text-white rounded-circle 
                        d-inline-flex align-items-center justify-content-center mb-3">
                        <span>{{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}</span>
                    </div>
                    <h4 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                    <p class="text-muted mb-3">{{ $employee->position_name }}</p>
                    @if($employee->employment_status === 'Permanent')
                        <span class="badge bg-success mb-2">Permanent</span>
                    @elseif($employee->employment_status === 'Probation')
                        <span class="badge bg-warning mb-2">Probation</span>
                    @elseif($employee->employment_status === 'Contract')
                        <span class="badge bg-info mb-2">Contract</span>
                    @elseif($employee->employment_status === 'Intern')
                        <span class="badge bg-primary mb-2">Intern</span>
                    @else
                        <span class="badge bg-danger mb-2">Resigned</span>
                    @endif
                </div>
                <div class="card-footer p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tr>
                                <td class="text-muted">Department Name:</td>
                                <td class="fw-bold">{{ $employee->department_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Position Code:</td>
                                <td><code>{{ $employee->position_code }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Employee Code:</td>
                                <td><code>{{ $employee->employee_code }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td>{{ $employee->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone:</td>
                                <td>{{ $employee->phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Statistic</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-user-check text-success fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $attendanceSummary->present_days ?? 0 }}</h4>
                                <small class="text-muted">Present Days</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="text-center">
                                <div class="bg-danger bg-opacity-10 rounded p-3 mb-2">
                                    <i class="ti ti-user-x text-danger fs-3"></i>
                                </div>
                                <h4 class="mb-0">{{ $attendanceSummary->absent_days ?? 0 }}</h4>
                                <small class="text-muted">Absent Days</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Personal Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Date of Birth</label>
                            <div>{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d F Y') : 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Gender</label>
                            <div>{{ $employee->gender }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">ID Number</label>
                            <div>{{ $employee->id_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Join Date</label>
                            <div>{{ \Carbon\Carbon::parse($employee->join_date)->format('d F Y') }}</div>
                        </div>
                        @if($employee->resign_date)
                        <div class="col-md-6">
                            <label class="text-muted small">Resign Date</label>
                            <div class="text-danger">{{ \Carbon\Carbon::parse($employee->resign_date)->format('d F Y') }}</div>
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="text-muted small">Address</label>
                            <div>{{ $employee->address ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection