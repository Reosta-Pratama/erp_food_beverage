@extends('layouts.app', [
    'title' => 'Edit Department'
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
                    <li class="breadcrumb-item active" aria-current="page">Edit Department</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit Department</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hrm.departments.index') }}"
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
    <form method="POST" action="{{ route('hrm.departments.update', $department->department_code) }}" 
        id="departmentForm" class="row">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Department Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Department Code (Read-only) -->
                        <div class="col-12">
                            <label class="form-label">Department Code</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $department->department_code }}"
                                   readonly
                                   disabled>
                            <small class="form-text text-muted">
                                Auto-generated and cannot be changed
                            </small>
                        </div>

                        <!-- Department Name -->
                        <div class="col-12">
                            <label for="department_name" class="form-label">
                                Department Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control @error('department_name') is-invalid @enderror" 
                                    id="department_name" 
                                    name="department_name" 
                                    value="{{ old('department_name', $department->department_name) }}"
                                    placeholder="e.g., Human Resources"
                                    required
                                    autofocus>

                            @error('department_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Enter a unique department name (max 150 characters)
                            </small>
                        </div>

                        <!-- Manager -->
                        <div class="col-12">
                            <label for="manager_id" class="form-label">Department Manager</label>
                            
                            <div>
                                <select class="form-select single-select @error('manager_id') is-invalid @enderror" 
                                        id="manager_id" 
                                        name="manager_id">
                                    <option value="">-- Select Manager (Optional) --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->employee_id }}" 
                                                {{ old('manager_id', $department->manager_id) == $employee->employee_id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->employee_code }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <small class="form-text text-muted">
                                You can assign a manager later if not available now
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Enter department description, responsibilities, or notes...">{{ old('description', $department->description) }}</textarea>
                            
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('hrm.departments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> 
                            Cancel
                        </a>
                        <div>
                            <a href="{{ route('hrm.departments.show', $department->department_code) }}" 
                                class="btn btn-info me-2">
                                <i class="ti ti-eye me-1"></i> 
                                View Details
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-circle-check me-1"></i> 
                                Update Department
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Current Information</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Code</strong></span>
                                <span class="text-muted">{{ $department->department_code }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Created</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($department->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Last Updated</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($department->updated_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Update Guidlines</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                     Department code cannot be changed 
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                     Name must remain unique 
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                     Manager can be changed or removed 
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-success">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                     Changes affect all related employees 
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/department-init.js'])

@endsection