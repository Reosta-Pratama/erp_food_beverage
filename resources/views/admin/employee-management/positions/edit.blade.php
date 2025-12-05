@extends('layouts.app', [
    'title' => 'Edit Position'
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
                    <li class="breadcrumb-item active" aria-current="page">Edit Position</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit Positions</h2>

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
    <form  method="POST" action="{{ route('hrm.positions.update', $position->position_code) }}" 
        id="positionForm" class="row">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Position Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Position Code (Read-only) -->
                        <div class="col-12">
                            <label class="form-label">Position Code</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $position->position_code }}"
                                   readonly
                                   disabled>
                            <small class="form-text text-muted">
                                Auto-generated and cannot be changed
                            </small>
                        </div>

                        <!-- Position Name -->
                        <div class="col-12">
                            <label for="position_name" class="form-label">
                                Position Name <span class="text-danger">*</span>
                            </label>
                            
                            <input type="text" 
                                   class="form-control @error('position_name') is-invalid @enderror" 
                                   id="position_name" 
                                   name="position_name" 
                                   value="{{ old('position_name', $position->position_name) }}"
                                   placeholder="e.g., Senior Software Engineer"
                                   required
                                   autofocus>

                            @error('position_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="col-12">
                            <label for="department_id" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('department_id') is-invalid @enderror" 
                                    id="department_id" 
                                    name="department_id"
                                    required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}" 
                                            {{ old('department_id', $position->department_id) == $department->department_id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Description -->
                        <div class="mb-3">
                            <label for="job_description" class="form-label">Job Description</label>
                            
                            <textarea class="form-control @error('job_description') is-invalid @enderror" 
                                      id="job_description" 
                                      name="job_description" 
                                      rows="6"
                                      placeholder="Enter job description...">{{ old('job_description', $position->job_description) }}</textarea>
                            
                            @error('job_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('hrm.positions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> 
                            Cancel
                        </a>
                        <div>
                            <a href="{{ route('hrm.positions.show', $position->position_code) }}" 
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
                                <span class="text-muted">{{ $position->position_code }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Created</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($position->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Last Updated</strong></span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($position->updated_at)->format('d M Y, H:i') }}</span>
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
                                    Position code cannot be changed
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
                                    Department can be changed
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-warning">
                                        <i class="ti ti-exclamation-circle"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Changes affect all employees in this position
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item"> 
                            <div class="d-flex align-items-center">
                                <div>
                                    <span class="fs-15 text-warning">
                                        <i class="ti ti-exclamation-circle"></i>
                                    </span>
                                </div>
                                <div class="ms-2">
                                    Moving to another department may affect reporting
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

    @vite(['resources/assets/js/erp/position-init.js'])

@endsection