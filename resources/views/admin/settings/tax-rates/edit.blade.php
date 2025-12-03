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
        <form action="{{ route('admin.settings.tax-rates.update', $taxRate->tax_code) }}" method="POST"
            id="taxRateForm" class="col-lg-8">
            @csrf
            @method('PUT')

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tax Rate Information</div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Tax Code:</strong> 
                        <code>{{ $taxRate->tax_code }}</code> 
                        (Auto-generated, cannot be changed)
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tax_name" class="form-label">
                                Tax Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                    class="form-control @error('tax_name') is-invalid @enderror" 
                                    id="tax_name" 
                                    name="tax_name" 
                                    value="{{ old('tax_name', $taxRate->tax_name) }}"
                                    required
                                    placeholder="e.g., VAT Standard Rate">
                            @error('tax_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tax_type" class="form-label">
                                Tax Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select single-select @error('tax_type') is-invalid @enderror" 
                                    id="tax_type" 
                                    name="tax_type">
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

                        <div class="col-md-6">
                            <label for="tax_percentage" class="form-label">
                                Tax Percentage <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                        class="form-control count_percentage @error('tax_percentage') is-invalid @enderror" 
                                        id="tax_percentage" 
                                        name="tax_percentage" 
                                        value="{{ old('tax_percentage', $taxRate->tax_percentage) }}"
                                        required
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        placeholder="0.00">
                                <span class="input-group-text">%</span>
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Enter percentage value (0-100)</small>
                        </div>

                        <div class="col-md-6">
                            <label for="flatpick-date" class="form-label">
                                Effective Date <span class="text-danger">*</span>
                            </label>

                            <input class="form-control flatDate @error('effective_date') is-invalid @enderror"
                                date=@json(old('effective_date', $taxRate->effective_date))
                                id="flatpick-date"
                                name="flatpick-date"
                                type="text"  
                                required>

                            @error('effective_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Date when this tax rate becomes effective</small>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
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

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.settings.tax-rates.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-circle-check me-2"></i>
                            Update Tax Rate
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Quick Calculator</div>
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
                </div>
                <div class="card-footer">
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
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Status</div>
                </div>
                <div class="card-body">
                    @if ($taxRate->is_active)
                        <div class="text-center">
                            <i class="ti ti-circle-check d-block text-success display-3 mb-2"></i>
                            <strong>Active</strong>
                            <p class="text-muted small mb-0 mt-2">
                                This tax rate can be used in transactions
                            </p>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="ti ti-circle-x d-block text-danger display-3 mb-2"></i>
                            <strong>Inactive</strong>
                            <p class="text-muted small mb-0 mt-2">
                                This tax rate is disabled and cannot be used
                            </p>
                        </div>
                    @endif
                </div>
            </div>
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

@endsection

@section('scripts')

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-tax-rate.js'])

@endsection