@extends('layouts.app', [
    'title' => 'Create Department'
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
                    <li class="breadcrumb-item active" aria-current="page">Create New Department</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Department</h2>

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
    <form method="POST" action="{{ route('hrm.departments.store') }}" 
        id="departmentForm" class="row">
        @csrf

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Department Information
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <!-- Department Name -->
                        <div class="col-12">
                            <label for="department_name" class="form-label">
                                Department Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control @error('department_name') is-invalid @enderror" 
                                    id="department_name" 
                                    name="department_name" 
                                    value="{{ old('department_name') }}"
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
                                                {{ old('manager_id') == $employee->employee_id ? 'selected' : '' }}>
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
                                      placeholder="Enter department description, responsibilities, or notes...">{{ old('description') }}</textarea>
                            
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
                        <a href="{{ route('hrm.departments.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-circle-check me-2"></i>
                            Create Department
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Department Guidlines
                    </div>
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
                                    Department names must be unique 
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
                                    A unique code will be auto-generated
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
                                    Managers can be assigned later 
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
                                    Only active employees can be managers 
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Next Steps Information
                    </div>
                </div>
                <div class="card-body p-0">
                    <ol class="list-group list-group-flush list-group-numbered small">
                        <li class="list-group-item"> Create the department </li>
                        <li class="list-group-item"> Add positions to the department </li>
                        <li class="list-group-item"> Assign employees to positions </li>
                        <li class="list-group-item"> Assign or change manager if needed </li>
                    </ol>
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