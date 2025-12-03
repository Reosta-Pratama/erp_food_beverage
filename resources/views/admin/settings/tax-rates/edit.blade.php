@extends('layouts.app', [
    'title' => 'Edit Tax Rate'
])

@section('styles')

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
                        <a href="javascript:void(0);">Settings</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Tax Rates</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{ $taxRate->tax_name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit Tax Rate - {{ $taxRate->tax_name }}</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.tax-rates.show', $taxRate->tax_code) }}" 
                class="btn btn-primary">
                <i class="ti ti-eye me-2"></i>
                View Detail
            </a>

            <a href="{{ route('admin.settings.tax-rates.index') }}"
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
    <div class="row">
        <div class="col-lg-8"></div>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tax Rate Guidelines</div>
                </div>
                <div class="card-body p-0">
                    <ol class="list-group list-group-flush list-group-numbered small">
                        <li class="list-group-item">Tax code is auto-generate</li>
                        <li class="list-group-item">Tax percentage should be between 0-100%</li>
                        <li class="list-group-item">Effective date determines when tax becomes applicable</li>
                        <li class="list-group-item">Only active tax rates can be used in transactions</li>
                        <li class="list-group-item">Multiple tax rates can exist for different types</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->




<div class="container-fluid">

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.settings.tax-rates.update', $taxRate->tax_code) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Tax Rate Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Tax Code:</strong> <code>{{ $taxRate->tax_code }}</code> (Auto-generated, cannot be changed)
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tax_name" class="form-label">
                                    Tax Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('tax_name') is-invalid @enderror" 
                                       id="tax_name" 
                                       name="tax_name" 
                                       value="{{ old('tax_name', $taxRate->tax_name) }}"
                                       required>
                                @error('tax_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="tax_type" class="form-label">
                                    Tax Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('tax_type') is-invalid @enderror" 
                                        id="tax_type" 
                                        name="tax_type" 
                                        required>
                                    <option value="">Select Tax Type</option>
                                    <option value="VAT" {{ old('tax_type', $taxRate->tax_type) == 'VAT' ? 'selected' : '' }}>VAT (Value Added Tax)</option>
                                    <option value="Sales Tax" {{ old('tax_type', $taxRate->tax_type) == 'Sales Tax' ? 'selected' : '' }}>Sales Tax</option>
                                    <option value="Service Tax" {{ old('tax_type', $taxRate->tax_type) == 'Service Tax' ? 'selected' : '' }}>Service Tax</option>
                                    <option value="Withholding Tax" {{ old('tax_type', $taxRate->tax_type) == 'Withholding Tax' ? 'selected' : '' }}>Withholding Tax</option>
                                    <option value="Excise Tax" {{ old('tax_type', $taxRate->tax_type) == 'Excise Tax' ? 'selected' : '' }}>Excise Tax</option>
                                    <option value="Import Tax" {{ old('tax_type', $taxRate->tax_type) == 'Import Tax' ? 'selected' : '' }}>Import Tax</option>
                                    <option value="Other" {{ old('tax_type', $taxRate->tax_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('tax_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tax_percentage" class="form-label">
                                    Tax Percentage <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           class="form-control @error('tax_percentage') is-invalid @enderror" 
                                           id="tax_percentage" 
                                           name="tax_percentage" 
                                           value="{{ old('tax_percentage', $taxRate->tax_percentage) }}"
                                           required
                                           step="0.01"
                                           min="0"
                                           max="100">
                                    <span class="input-group-text">%</span>
                                    @error('tax_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Enter percentage value (0-100)</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="effective_date" class="form-label">
                                    Effective Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('effective_date') is-invalid @enderror" 
                                       id="effective_date" 
                                       name="effective_date" 
                                       value="{{ old('effective_date', $taxRate->effective_date) }}"
                                       required>
                                @error('effective_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Date when this tax rate becomes effective</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $taxRate->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active Status</strong><br>
                                <small class="text-muted">Active tax rates can be used in transactions</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Tax Rate
                        </button>
                        <a href="{{ route('admin.settings.tax-rates.show', $taxRate->tax_code) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>

           
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-clock-history text-muted me-2"></i>Last Modified
                    </h6>
                    <small class="text-muted d-block mb-2">
                        <strong>Created:</strong><br>
                        {{ \Carbon\Carbon::parse($taxRate->created_at)->format('d M Y, H:i') }}
                    </small>
                    <small class="text-muted d-block">
                        <strong>Updated:</strong><br>
                        {{ \Carbon\Carbon::parse($taxRate->updated_at)->format('d M Y, H:i') }}
                        ({{ \Carbon\Carbon::parse($taxRate->updated_at)->diffForHumans() }})
                    </small>
                </div>
            </div>

            <div class="card mb-3 bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-primary me-2"></i>Important Notes
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Tax code cannot be changed after creation</li>
                        <li class="mb-2">Changes apply to new transactions only</li>
                        <li class="mb-2">Existing transactions retain original tax rates</li>
                        <li class="mb-2">Inactive tax rates don't appear in forms</li>
                        <li>Regular updates recommended for accuracy</li>
                    </ul>
                </div>
            </div>

            <!-- Quick Calculator -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-calculator text-success me-2"></i>Quick Calculator
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        See how this tax rate affects amounts in real-time
                    </p>
                    <div class="mb-3">
                        <label for="calc_base" class="form-label small">Base Amount</label>
                        <input type="number" 
                               class="form-control form-control-sm" 
                               id="calc_base" 
                               value="1000"
                               step="1"
                               min="0">
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Base Amount:</span>
                        <strong id="calc_base_display">1,000.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Tax (<span id="calc_percentage">{{ number_format($taxRate->tax_percentage, 2) }}</span>%):</span>
                        <strong class="text-success" id="calc_tax">
                            {{ number_format(1000 * ($taxRate->tax_percentage / 100), 2) }}
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top">
                        <span class="small"><strong>Total Amount:</strong></span>
                        <strong class="text-primary" id="calc_total">
                            {{ number_format(1000 + (1000 * ($taxRate->tax_percentage / 100)), 2) }}
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Current Status -->
            <div class="card mt-3">
                <div class="card-body text-center">
                    <label class="text-muted small">Current Status</label>
                    <div class="mt-2">
                        @if($taxRate->is_active)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Active
                            </span>
                        @else
                            <span class="badge bg-secondary fs-6">
                                <i class="bi bi-x-circle me-1"></i>Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-tax-rate.js'])

@endsection