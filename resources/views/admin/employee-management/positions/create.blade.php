@extends('layouts.app', [
    'title' => 'Create Position'
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
                    <li class="breadcrumb-item active" aria-current="page">Create New Position</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Position</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hrm.positions.index') }}"
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
    <form method="POST" action="{{ route('hrm.positions.store') }}"
        id="positionForm" class="row">
        @csrf

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Position Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Position Name -->
                        <div class="col-12">
                            <label for="position_name" class="form-label">
                                Position Name <span class="text-danger">*</span>
                            </label>
                            
                            <input type="text" 
                                    class="form-control @error('position_name') is-invalid @enderror" 
                                    id="position_name" 
                                    name="position_name" 
                                    value="{{ old('position_name') }}"
                                    placeholder="e.g., Senior Software Engineer"
                                    required
                                    autofocus>

                            @error('position_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Enter a unique position name (max 150 characters)
                            </small>
                        </div>

                        <!-- Department -->
                        <div class="col-12">
                            <label for="department_id" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('department_id') is-invalid @enderror" 
                                    id="department_id" 
                                    name="department_id">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}" 
                                            {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Select the department this position belongs to
                            </small>
                        </div>

                        <!-- Job Description -->
                        <div class="col-12">
                            <label for="job_description" class="form-label">Job Description</label>
                            
                            <textarea class="form-control @error('job_description') is-invalid @enderror" 
                                      id="job_description" 
                                      name="job_description" 
                                      rows="6"
                                      placeholder="Enter job description, responsibilities, requirements, and qualifications...">{{ old('job_description') }}</textarea>
                            
                            @error('job_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Describe the role, responsibilities, and requirements (optional)
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('hrm.positions.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-circle-check me-2"></i>
                            Create Position
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Position Guidelines</div>
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
                                     Position names must be unique 
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
                                     Each position belongs to one department 
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
                                     Job description helps in recruitment 
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Example</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item ">
                            <div class="text-primary fw-medium fs-14 mb-2"> IT Department: </div>
                            <ol class="list-group list-group-numbered">
                                <li class="list-group-item">Software Engineer</li>
                                <li class="list-group-item">DevOps Engineer</li>
                                <li class="list-group-item">IT Support Specialist</li>
                            </ol>
                        </li>

                        <li class="list-group-item ">
                            <div class="text-success fw-medium fs-14 mb-2"> HR Department: </div>
                            <ol class="list-group list-group-numbered">
                                <li class="list-group-item">HR Manager</li>
                                <li class="list-group-item">Recruitment Specialistli>
                                <li class="list-group-item">Training Coordinator</li>
                            </ol>
                        </li>

                        <li class="list-group-item ">
                            <div class="text-danger fw-medium fs-14 mb-2"> Production: </div>
                            <ol class="list-group list-group-numbered">
                                <li class="list-group-item">Production Supervisor</li>
                                <li class="list-group-item">Quality Control Inspector</li>
                                <li class="list-group-item">Machine Operator</li>
                            </ol>
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

    @vite(['resources/assets/js/erp/position-init.js'])

@endsection