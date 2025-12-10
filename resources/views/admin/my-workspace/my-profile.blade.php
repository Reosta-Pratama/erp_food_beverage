@extends('layouts.app', [
    'title' => 'My Profile'
])

@section('styles')
<style>
    .profile-cover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 220px;
        border-radius: 12px 12px 0 0;
        position: relative;
        overflow: hidden;
    }
    
    .profile-cover::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="rgba(255,255,255,0.1)"/></svg>') no-repeat bottom;
        background-size: cover;
    }
    
    .profile-avatar {
        width: 140px;
        height: 140px;
        border: 5px solid white;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .info-card {
        transition: all 0.3s;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .info-item {
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: start;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        min-width: 180px;
        flex-shrink: 0;
    }
    
    .info-value {
        color: #212529;
        flex-grow: 1;
    }
    
    .cert-card, .training-card {
        transition: all 0.3s;
        border-left: 4px solid transparent;
    }
    
    .cert-card:hover {
        border-left-color: #0d6efd;
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .training-card:hover {
        border-left-color: #198754;
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stats-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')




<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}"><i class="bi bi-house-door"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">My Profile</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-4">
            <!-- Profile Card -->
            <div class="card border-0 shadow-sm">
                <div class="profile-cover"></div>
                <div class="card-body text-center" style="margin-top: -70px;">
                    <div class="profile-avatar rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                        <span class="text-white" style="font-size: 3rem; font-weight: 700;">
                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h4 class="mb-1 fw-bold">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                    <p class="text-muted mb-2">{{ $employee->position_name }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if($employee->employment_status === 'Active')
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>{{ $employee->employment_status }}
                            </span>
                        @elseif($employee->employment_status === 'Probation')
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                <i class="bi bi-hourglass-split me-1"></i>{{ $employee->employment_status }}
                            </span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $employee->employment_status }}</span>
                        @endif
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square me-2"></i>Edit Contact Info
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-award-fill text-info fs-5"></i>
                            </div>
                            <span class="text-muted">Certifications</span>
                        </div>
                        <span class="badge bg-info rounded-pill fs-6">{{ $certifications->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-mortarboard-fill text-success fs-5"></i>
                            </div>
                            <span class="text-muted">Training Programs</span>
                        </div>
                        <span class="badge bg-success rounded-pill fs-6">{{ $trainings->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            </div>
                            <span class="text-muted">Completed</span>
                        </div>
                        <span class="badge bg-primary rounded-pill fs-6">{{ $trainings->where('status', 'Completed')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card border-0 shadow-sm info-card mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-vcard-fill me-2 text-primary"></i>Personal Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-hash me-2"></i>Employee Code</span>
                        <span class="info-value fw-semibold">{{ $employee->employee_code }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-person me-2"></i>Full Name</span>
                        <span class="info-value">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-envelope me-2"></i>Email</span>
                        <span class="info-value">{{ $employee->email ?? 'Not set' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-telephone me-2"></i>Phone</span>
                        <span class="info-value">{{ $employee->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-calendar-event me-2"></i>Date of Birth</span>
                        <span class="info-value">
                            {{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('F j, Y') : 'Not set' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-gender-ambiguous me-2"></i>Gender</span>
                        <span class="info-value">{{ $employee->gender }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-credit-card me-2"></i>ID Number</span>
                        <span class="info-value">{{ $employee->id_number ?? 'Not set' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-geo-alt me-2"></i>Address</span>
                        <span class="info-value">{{ $employee->address ?? 'Not set' }}</span>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="card border-0 shadow-sm info-card mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-briefcase-fill me-2 text-primary"></i>Employment Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-building me-2"></i>Department</span>
                        <span class="info-value">
                            {{ $employee->department_name }}
                            <span class="badge bg-primary ms-2">{{ $employee->department_code }}</span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-briefcase me-2"></i>Position</span>
                        <span class="info-value">
                            {{ $employee->position_name }}
                            <span class="badge bg-primary ms-2">{{ $employee->position_code }}</span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-calendar-check me-2"></i>Join Date</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($employee->join_date)->format('F j, Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-clock-history me-2"></i>Years of Service</span>
                        <span class="info-value fw-semibold text-primary">
                            {{ \Carbon\Carbon::parse($employee->join_date)->diffInYears(now()) }} years
                            ({{ \Carbon\Carbon::parse($employee->join_date)->diffInMonths(now()) % 12 }} months)
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="bi bi-info-circle me-2"></i>Status</span>
                        <span class="info-value">
                            @if($employee->employment_status === 'Active')
                                <span class="badge bg-success px-3 py-2">{{ $employee->employment_status }}</span>
                            @elseif($employee->employment_status === 'Probation')
                                <span class="badge bg-warning text-dark px-3 py-2">{{ $employee->employment_status }}</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">{{ $employee->employment_status }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Certifications -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-award-fill me-2 text-info"></i>Certifications
                    </h5>
                    <span class="badge bg-info rounded-pill">{{ $certifications->count() }}</span>
                </div>
                <div class="card-body">
                    @if($certifications->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-award display-1 opacity-25"></i>
                            <p class="mt-3 mb-0">No certifications available</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($certifications as $cert)
                            <div class="col-12">
                                <div class="cert-card bg-light p-3 rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-2 fw-bold">{{ $cert->certification_name }}</h6>
                                            <p class="text-muted mb-2 small">
                                                <i class="bi bi-building me-1"></i>{{ $cert->issuing_authority }}
                                            </p>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    Issued: {{ \Carbon\Carbon::parse($cert->issue_date)->format('M d, Y') }}
                                                </span>
                                                @if($cert->expiry_date)
                                                    @php
                                                        $expiryDate = \Carbon\Carbon::parse($cert->expiry_date);
                                                        $daysUntilExpiry = $expiryDate->diffInDays(now());
                                                    @endphp
                                                    <span class="badge bg-secondary">
                                                        Expires: {{ $expiryDate->format('M d, Y') }}
                                                    </span>
                                                    @if($expiryDate->isPast())
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Expired</span>
                                                    @elseif($daysUntilExpiry <= 30)
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>Expires in {{ $daysUntilExpiry }} days
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success"><i class="bi bi-infinity me-1"></i>Lifetime</span>
                                                @endif
                                            </div>
                                            @if($cert->certificate_number)
                                                <p class="text-muted mb-0 small mt-2">
                                                    <i class="bi bi-hash me-1"></i>Certificate No: {{ $cert->certificate_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Training History -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-mortarboard-fill me-2 text-success"></i>Training History
                    </h5>
                    <span class="badge bg-success rounded-pill">{{ $trainings->count() }}</span>
                </div>
                <div class="card-body">
                    @if($trainings->isEmpty())
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-mortarboard display-1 opacity-25"></i>
                            <p class="mt-3 mb-0">No training history available</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($trainings as $training)
                            <div class="col-12">
                                <div class="training-card bg-light p-3 rounded-3 border">
                                    <h6 class="mb-2 fw-bold">{{ $training->training_name }}</h6>
                                    <p class="text-muted mb-2 small">
                                        <i class="bi bi-calendar-range me-1"></i>
                                        {{ \Carbon\Carbon::parse($training->start_date)->format('M d, Y') }} - 
                                        {{ \Carbon\Carbon::parse($training->end_date)->format('M d, Y') }}
                                    </p>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($training->status === 'Completed')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>{{ $training->status }}
                                            </span>
                                        @elseif($training->status === 'Registered')
                                            <span class="badge bg-primary">{{ $training->status }}</span>
                                        @elseif($training->status === 'Ongoing')
                                            <span class="badge bg-info">{{ $training->status }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $training->status }}</span>
                                        @endif
                                        
                                        @if($training->score)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i>Score: {{ $training->score }}
                                            </span>
                                        @endif
                                        
                                        @if($training->is_certified)
                                            <span class="badge bg-success">
                                                <i class="bi bi-patch-check-fill me-1"></i>Certified
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')



<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('employee.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary bg-gradient text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Edit Contact Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0">
                        <i class="bi bi-info-circle me-2"></i>
                        You can only update your contact information. For other changes, please contact HR department.
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               value="{{ old('email', $employee->email) }}" placeholder="your.email@company.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $employee->phone) }}" placeholder="+62 xxx xxxx xxxx">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Complete Address</label>
                        <textarea name="address" rows="4" class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Your complete address...">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection