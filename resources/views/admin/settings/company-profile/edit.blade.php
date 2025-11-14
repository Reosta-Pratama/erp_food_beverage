@extends('layouts.app', [
    'title' => ($profile ? 'Edit' : 'Create') . ' Company Profile'
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
                        <a href="javascript:void(0);">Settings</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Company Profile</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit Company Profile</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.company-profile.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible d-flex align-items-center fade show">
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

    <!-- Container -->
    <form action="{{ route('admin.settings.company-profile.update') }}" method="post"
        enctype="multipart/form-data"
        id="companyProfileForm"
        class="row g-4 mb-4">
        @csrf
        @method('PUT')

        <div class="col-lg-4">
            <div class="card custom sticky-card">
                <div class="card-header">
                    <div class="card-title">Company Logo</div>
                </div>

                {{-- Current Logo --}}
                @if($profile && $profile->logo_path)
                    <div class="card-body">
                        <label class="form-label fw-bold">Current Logo</label>
                        <div class="current-logo-container">
                            <img src="{{ Storage::url($profile->logo_path) }}" 
                                alt="Current Logo" 
                                class="img-thumbnail">
                        </div>
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="confirmDeleteLogo()"
                                title="Delete logo">
                            <i class="ti ti-trash"></i>
                            Delete Logo
                        </button>
                    </div>
                @endif

                <div class="card-footer">
                    {{-- Logo Upload --}}
                    <label class="form-label fw-bold">
                        {{ $profile && $profile->logo_path ? 'Change Logo' : 'Upload Logo' }}
                    </label>
                    
                    <div class="logo-preview-container mb-3" onclick="document.getElementById('logo').click()">
                        <div id="logoPreview" class="image-placeholder">
                            <i class="ti ti-upload"></i>
                            <span class="small">Click to upload</span>
                            <span class="small">or drag and drop</span>
                        </div>
                    </div>

                    <input type="file" 
                            class="form-control @error('logo') is-invalid @enderror" 
                            id="logo" 
                            name="logo" 
                            accept="image/jpeg,image/png,image/jpg"
                            style="display: none;">
                    
                    @error('logo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="alert alert-info mt-3 mb-0">
                        <small>
                            <strong>Requirements:</strong><br>
                            • Max size: 2MB<br>
                            • Format: JPG, JPEG, PNG<br>
                            • Recommended: 500x500px
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Basic Information</div>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Company Name --}}
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">
                                        Company Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                        class="form-control @error('company_name') is-invalid @enderror" 
                                        id="company_name" 
                                        name="company_name" 
                                        value="{{ old('company_name', $profile->company_name ?? '') }}" 
                                        autocomplete="off"
                                        required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Legal Name --}}
                                <div class="col-md-6">
                                    <label for="legal_name" class="form-label">Legal Name</label>
                                    <input type="text" 
                                        class="form-control @error('legal_name') is-invalid @enderror" 
                                        id="legal_name" 
                                        name="legal_name" 
                                        value="{{ old('legal_name', $profile->legal_name ?? '') }}"
                                        autocomplete="off">
                                    @error('legal_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Tax ID --}}
                                <div class="col-md-6">
                                    <label for="tax_id" class="form-label">Tax ID / NPWP</label>
                                    <input type="text" 
                                        class="form-control @error('tax_id') is-invalid @enderror" 
                                        id="tax_id" 
                                        name="tax_id" 
                                        value="{{ old('tax_id', $profile->tax_id ?? '') }}"
                                        placeholder="e.g., 01.234.567.8-901.000"
                                        autocomplete="off">
                                    @error('tax_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Contact Information</div>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Phone --}}
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" 
                                        class="form-control @error('phone') is-invalid @enderror" 
                                        id="phone" 
                                        name="phone" 
                                        value="{{ old('phone', $profile->phone ?? '') }}"
                                        placeholder="+62 xxx xxxx xxxx">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        id="email" 
                                        name="email" 
                                        value="{{ old('email', $profile->email ?? '') }}"
                                        placeholder="info@company.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- Website --}}
                                <div class="col-md-6">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" 
                                        class="form-control @error('website') is-invalid @enderror" 
                                        id="website" 
                                        name="website" 
                                        value="{{ old('website', $profile->website ?? '') }}"
                                        placeholder="https://www.company.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Address Information</div>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Address --}}
                                <div class="col-12">
                                    <label for="address" class="form-label">Street Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                            id="address" 
                                            name="address" 
                                            rows="3"
                                            placeholder="Enter full street address">{{ old('address', $profile->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- City --}}
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" 
                                        class="form-control @error('city') is-invalid @enderror" 
                                        id="city" 
                                        name="city" 
                                        value="{{ old('city', $profile->city ?? '') }}"
                                        placeholder="e.g., Jakarta">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                {{-- Postal Code --}}
                                <div class="col-md-4">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" 
                                        class="form-control @error('postal_code') is-invalid @enderror" 
                                        id="postal_code" 
                                        name="postal_code" 
                                        value="{{ old('postal_code', $profile->postal_code ?? '') }}"
                                        placeholder="e.g., 12345">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Country --}}
                                <div class="col-md-4">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" 
                                        class="form-control @error('country') is-invalid @enderror" 
                                        id="country" 
                                        name="country" 
                                        value="{{ old('country', $profile->country ?? 'Indonesia') }}"
                                        placeholder="e.g., Indonesia">
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.settings.company-profile.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check me-2"></i>
                            Save Company Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->

@endsection

@section('modals')
{{-- Delete Logo Confirmation Modal --}}
<div class="modal fade" id="deleteLogoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-trash me-2"></i>Delete Company Logo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.company-profile.delete-logo') }}" method="POST" id="deleteLogoForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Are you sure you want to delete the company logo?
                    </div>
                    <p class="mb-0">This action cannot be undone. The logo will be permanently removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete Logo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        // Delete logo confirmation
        function confirmDeleteLogo() {
            const modal = new bootstrap.Modal(document.getElementById('deleteLogoModal'));
            modal.show();
        }
    </script>

    @vite(['resources/assets/js/erp/company-profile.js'])

@endsection