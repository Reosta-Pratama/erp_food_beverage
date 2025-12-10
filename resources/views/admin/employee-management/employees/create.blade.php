@extends('layouts.app', [
    'title' => 'Create Employee'
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('hrm.employees.index') }}">Employees</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New Employee</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Employee</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('hrm.employees.index') }}"
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
    <form method="POST" action="{{ route('hrm.employees.store') }}" 
        id="employeeForm" class="row">
        @csrf

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Personal Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">
                                First Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control @error('first_name') is-invalid @enderror" 
                                    id="first_name" 
                                    name="first_name" 
                                    data-name="First Name"
                                    value="{{ old('first_name') }}"
                                    placeholder="e.g., Reosta"
                                    required>

                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">
                                Last Name <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control @error('last_name') is-invalid @enderror" 
                                    id="last_name" 
                                    name="last_name"
                                    data-name="Last Name" 
                                    value="{{ old('last_name') }}"
                                    placeholder="e.g., Pane"
                                    required>

                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>

                            <input type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="e.g., reosta.pane@gmail.com">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>

                            <input type="text" 
                                    class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone') }}"
                                    placeholder="e.g., 082169076600">

                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">
                                Date of Birth <span class="text-danger">*</span>
                            </label>

                            @php
                                $inputDob = old('date_of_birth', date('Y-m-d'));
                                $maxDob = date('Y-m-d', strtotime('-18 years'));

                                if ($inputDob > $maxDob) {
                                    $inputDob = $maxDob;
                                }
                            @endphp

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> 
                                        <i class="ti ti-calendar"></i> 
                                    </div>
                                    <input class="form-control single-date @error('date_of_birth') is-invalid @enderror"
                                        date=@json($inputDob)
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        type="text"  
                                        required>
                                </div>
                            </div>

                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="gender" class="form-label">
                                Gender <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('gender') is-invalid @enderror" 
                                    id="gender" 
                                    name="gender"
                                    data-name="Gender">
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>

                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="id_number" class="form-label">ID Number (KTP)</label>

                            <input type="text" 
                                    class="form-control @error('id_number') is-invalid @enderror" 
                                    id="id_number" 
                                    name="id_number" 
                                    value="{{ old('id_number') }}"
                                    placeholder="e.g., 3275011234567890">

                            @error('id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>

                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                        id="address" 
                                        name="address" 
                                        rows="3"
                                        placeholder="Enter full address (street, city, province, postal code)">{{ old('address') }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Employment Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">
                                Department <span class="text-danger">*</span>
                            </label>

                            <select class="form-select @error('department_id') is-invalid @enderror" 
                                    id="department_id" 
                                    name="department_id"
                                    required
                                    onchange="filterPositions()">
                                <option value="">-- Select Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->department_id }}" 
                                            {{ old('department_id') == $dept->department_id ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="position_id" class="form-label">
                                Position <span class="text-danger">*</span>
                            </label>

                            <select class="form-select @error('position_id') is-invalid @enderror" 
                                    id="position_id" 
                                    name="position_id"
                                    required>
                                <option value="">-- Select Position --</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->position_id }}" 
                                            data-department="{{ $position->department_name }}"
                                            {{ old('position_id') == $position->position_id ? 'selected' : '' }}>
                                        {{ $position->position_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('position_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="join_date" class="form-label">
                                Join Date <span class="text-danger">*</span>
                            </label>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> 
                                        <i class="ti ti-calendar"></i> 
                                    </div>
                                    <input class="form-control single-date @error('join_date') is-invalid @enderror"
                                        date=@json(old('join_date', date('Y-m-d')))
                                        id="join_date"
                                        name="join_date"
                                        data-name="Join Date"
                                        type="text"  
                                        required>
                                </div>
                            </div>
                            
                            @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="employment_status" class="form-label">
                                Employment Status <span class="text-danger">*</span>
                            </label>

                            <select class="form-select single-select @error('employment_status') is-invalid @enderror" 
                                    id="employment_status" 
                                    name="employment_status"
                                    data-name="Employment Status">
                                <option value="">-- Select --</option>
                                <option value="Probation" {{ old('employment_status') === 'Probation' ? 'selected' : '' }}>Probation</option>
                                <option value="Permanent" {{ old('employment_status') === 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                <option value="Contract" {{ old('employment_status') === 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Intern" {{ old('employment_status') === 'Intern' ? 'selected' : '' }}>Intern</option>
                            </select>

                            @error('employment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="base_salary" class="form-label">Base Salary (IDR)</label>
                            
                            <input type="number" 
                                    class="form-control @error('base_salary') is-invalid @enderror" 
                                    id="base_salary" 
                                    name="base_salary" 
                                    value="{{ old('base_salary') }}"
                                    min="0"
                                    step="1000">

                            @error('base_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Optional - can be set later</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('hrm.employees.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-circle-check me-2"></i>
                            Create Employee
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">
                        Employee Guidlines
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
                                    Employee code will be auto-generated
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
                                    Email should be unique if provided
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
                                    Date of birth must be at least 18 years ago
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
                                    Base salary can be set later in payroll
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
                                    New employees typically start on Probation status
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

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/employee-init.js'])

@endsection