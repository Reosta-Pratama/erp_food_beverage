@extends('layouts.app', [
    'title' => 'Company Profile'
])

@section('styles')
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Header -->
    <div
        class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        <div>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Settings</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Company Profile</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center fade show mb-3" role="alert">
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Company Profile</h2>
            <p class="text-muted mb-0">Manage your company information and branding.</p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.settings.company-profile.edit') }}" class="btn btn-primary">
                <i class="ti ti-pencil me-2"></i>
                Edit Profile
            </a>
        </div>
    </div>

    @if($profile)
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Company Logo</div>
                            </div>

                            <div class="card-body text-center">
                                @if($profile->logo_path)
                                    <span class="avatar logo-company-size">
                                        <img
                                            src="{{ Storage::url($profile->logo_path) }}"
                                            alt="{{ $profile->company_name }}">
                                    </span>
                                @else
                                    <span class="initial logo-company-size mx-auto">
                                        <span>
                                            {{ strtoupper(substr($profile->company_name, 0, 2)) }}
                                        </span>
                                    </span>
                                @endif
                            </div>

                            <div class="card-footer">
                                @if($profile->logo_path)
                                    <div class="d-grid">
                                        <a href="{{ Storage::url($profile->logo_path) }}" 
                                            class="btn btn-sm btn-outline-primary" 
                                            target="_blank">
                                            <i class="bi bi-eye me-2"></i>
                                            View Full Size
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
                    </div>

                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Quick Info</div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tr>
                                            <td class="text-muted">Status</td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Last Updated</td>
                                            <td>
                                                {{ $profile->updated_at ? \Carbon\Carbon::parse($profile->updated_at)->format('d M Y - H:i') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Created</td>
                                            <td>
                                                {{ $profile->created_at ? \Carbon\Carbon::parse($profile->created_at)->format('d M Y') : '-' }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
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
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tr>
                                            <td class="text-muted w-25">Company Name</td>
                                            <td class="fw-bold">
                                                {{ $profile->company_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">Legal Name</td>
                                            <td>
                                                {{ $profile->legal_name ?? '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">Tax ID / NPWP</td>
                                            <td>
                                                @if($profile->tax_id)
                                                    <code class="badge bg-light text-dark">{{ $profile->tax_id }}</code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Contact Information</div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tr>
                                            <td class="text-muted w-25">Phone</td>
                                            <td>
                                                @if($profile->phone)
                                                    <a href="tel:{{ $profile->phone }}">
                                                        <i class="ti ti-phone me-2"></i>
                                                        {{ $profile->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">Email</td>
                                            <td>
                                                @if($profile->email)
                                                    <a href="mailto:{{ $profile->email }}">
                                                        <i class="ti ti-mail me-2"></i>
                                                        {{ $profile->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">Website</td>
                                            <td>
                                                @if($profile->website)
                                                    <a href="{{ $profile->website }}" target="_blank">
                                                        <i class="ti ti-world-www me-2"></i>
                                                        {{ $profile->website }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Address Information</div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tr>
                                            <td class="text-muted w-25">Street Address</td>
                                            <td>
                                                @if($profile->address)
                                                    {{ $profile->address }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">City</td>
                                            <td>
                                                @if($profile->city)
                                                    {{ $profile->city }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">Postal Code</td>
                                            <td>
                                                @if($profile->postal_code)
                                                    {{ $profile->postal_code }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted w-25">Country</td>
                                            <td>
                                                @if($profile->country)
                                                    {{ $profile->country }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
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
                <div class="card custom border-0">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-building text-muted fs-40"></i>
                        <h3 class="text-muted mt-4">
                            No Company Profile Found
                        </h3>
                        <p class="text-muted mb-4">
                            Set up your company profile to get started
                        </p>
                        <a href="{{ route('admin.settings.company-profile.edit') }}" 
                            class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>
                            Create Company Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Container -->

</div>
@endsection

@section('scripts')
@endsection