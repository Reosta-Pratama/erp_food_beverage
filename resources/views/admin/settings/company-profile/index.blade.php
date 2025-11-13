@extends('layouts.app', [
    'title' => 'Company Profile'
])

@section('styles')
<style>
    .company-logo-container {
        width: 200px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px dashed #dee2e6;
        border-radius: 12px;
        background: #f8f9fa;
        overflow: hidden;
        position: relative;
    }
    .company-logo-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .company-logo-container.no-logo {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .company-initials {
        font-size: 4rem;
        font-weight: bold;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    .info-card {
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .info-row {
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
        min-width: 150px;
    }
    .info-value {
        color: #212529;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Company Profile</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-building text-primary me-2"></i>
                        Company Profile
                    </h2>
                    <p class="text-muted mb-0">Manage your company information and branding</p>
                </div>
                <a href="{{ route('admin.settings.company-profile.edit') }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($profile)
    <div class="row">
        {{-- Left Column: Logo & Quick Info --}}
        <div class="col-lg-4 mb-4">
            
            {{-- Company Logo --}}
            <div class="card info-card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-image me-2"></i>Company Logo
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="company-logo-container mx-auto mb-3 {{ $profile->logo_path ? '' : 'no-logo' }}">
                        @if($profile->logo_path)
                            <img src="{{ Storage::url($profile->logo_path) }}" alt="{{ $profile->company_name }}">
                        @else
                            <div class="company-initials">
                                {{ strtoupper(substr($profile->company_name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    
                    @if($profile->logo_path)
                    <div class="d-grid gap-2">
                        <a href="{{ Storage::url($profile->logo_path) }}" 
                           class="btn btn-sm btn-outline-primary" 
                           target="_blank">
                            <i class="bi bi-eye me-2"></i>View Full Size
                        </a>
                    </div>
                    @else
                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="bi bi-info-circle me-2"></i>
                            No logo uploaded yet
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Quick Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Last Updated</small>
                        <div class="small">
                            {{ $profile->updated_at ? \Carbon\Carbon::parse($profile->updated_at)->format('d M Y H:i') : '-' }}
                        </div>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1">Created</small>
                        <div class="small">
                            {{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column: Company Details --}}
        <div class="col-lg-8">
            
            {{-- Basic Information --}}
            <div class="card info-card shadow-sm border-0 mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-info-square me-2 text-primary"></i>
                        Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="info-row d-flex">
                        <div class="info-label">Company Name:</div>
                        <div class="info-value flex-grow-1">
                            <strong class="fs-5">{{ $profile->company_name }}</strong>
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">Legal Name:</div>
                        <div class="info-value flex-grow-1">
                            {{ $profile->legal_name ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">Tax ID / NPWP:</div>
                        <div class="info-value flex-grow-1">
                            @if($profile->tax_id)
                                <code class="bg-light p-1 rounded">{{ $profile->tax_id }}</code>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- Contact Information --}}
            <div class="card info-card shadow-sm border-0 mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-telephone me-2 text-success"></i>
                        Contact Information
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="info-row d-flex">
                        <div class="info-label">Phone:</div>
                        <div class="info-value flex-grow-1">
                            @if($profile->phone)
                                <a href="tel:{{ $profile->phone }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-2"></i>{{ $profile->phone }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">Email:</div>
                        <div class="info-value flex-grow-1">
                            @if($profile->email)
                                <a href="mailto:{{ $profile->email }}" class="text-decoration-none">
                                    <i class="bi bi-envelope me-2"></i>{{ $profile->email }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">Website:</div>
                        <div class="info-value flex-grow-1">
                            @if($profile->website)
                                <a href="{{ $profile->website }}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-globe me-2"></i>{{ $profile->website }}
                                    <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            {{-- Address Information --}}
            <div class="card info-card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-geo-alt me-2 text-danger"></i>
                        Address Information
                    </h5>
                </div>
                <div class="card-body">
                    
                    <div class="info-row d-flex">
                        <div class="info-label">Street Address:</div>
                        <div class="info-value flex-grow-1">
                            {{ $profile->address ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">City:</div>
                        <div class="info-value flex-grow-1">
                            {{ $profile->city ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">Postal Code:</div>
                        <div class="info-value flex-grow-1">
                            {{ $profile->postal_code ?? '-' }}
                        </div>
                    </div>

                    <div class="info-row d-flex">
                        <div class="info-label">Country:</div>
                        <div class="info-value flex-grow-1">
                            {{ $profile->country ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @else
    {{-- No Profile Yet --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-building text-muted" style="font-size: 5rem;"></i>
                    <h3 class="text-muted mt-4">No Company Profile Found</h3>
                    <p class="text-muted mb-4">Set up your company profile to get started</p>
                    <a href="{{ route('admin.settings.company-profile.edit') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle me-2"></i>Create Company Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endsection