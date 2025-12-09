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








<div class="container-fluid">
    <!-- Page Header -->
    <div class="mb-4">
        <h1 class="h3 mb-1 text-gray-800">Add New Employee</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">HRM</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hrm.employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form method="POST" action="{{ route('hrm.employees.store') }}" id="employeeForm">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="m-0"><i class="bi bi-person me-1"></i> Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name') }}"
                                       required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name') }}"
                                       required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}"
                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">
                                    Gender <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" 
                                        name="gender"
                                        required>
                                    <option value="">-- Select --</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
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

                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="m-0"><i class="bi bi-briefcase me-1"></i> Employment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-4 mb-3">
                                <label for="join_date" class="form-label">
                                    Join Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('join_date') is-invalid @enderror" 
                                       id="join_date" 
                                       name="join_date" 
                                       value="{{ old('join_date', date('Y-m-d')) }}"
                                       required>
                                @error('join_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="employment_status" class="form-label">
                                    Employment Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('employment_status') is-invalid @enderror" 
                                        id="employment_status" 
                                        name="employment_status"
                                        required>
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

                            <div class="col-md-4 mb-3">
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

                <!-- Action Buttons -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hrm.employees.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Create Employee
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-left-primary mb-3">
                    <div class="card-header bg-light">
                        <h6 class="m-0 text-primary"><i class="bi bi-info-circle me-1"></i> Required Fields</h6>
                    </div>
                    <div class="card-body">
                        <p class="small mb-2">Fields marked with <span class="text-danger">*</span> are required:</p>
                        <ul class="small mb-0">
                            <li>First Name & Last Name</li>
                            <li>Gender</li>
                            <li>Department & Position</li>
                            <li>Join Date</li>
                            <li>Employment Status</li>
                        </ul>
                    </div>
                </div>

                <div class="card shadow-sm border-left-info">
                    <div class="card-header bg-light">
                        <h6 class="m-0 text-info"><i class="bi bi-lightbulb me-1"></i> Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0">
                            <li class="mb-2">Employee code will be auto-generated</li>
                            <li class="mb-2">Email should be unique if provided</li>
                            <li class="mb-2">Date of birth must be at least 18 years ago</li>
                            <li class="mb-2">Base salary can be set later in payroll</li>
                            <li>New employees typically start on Probation status</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/employee-init.js'])

@endsection