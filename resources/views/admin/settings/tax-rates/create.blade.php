@extends('layouts.app', [
    'title' => 'Add Tax Rate'
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
                    <li class="breadcrumb-item active" aria-current="page">Create New Tax Rate</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Tax Rate</h2>

        <div class="d-flex align-items-center gap-2">
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
    <form action="{{ route('admin.settings.tax-rates.store') }}" method="POST"
        id="taxRateForm" class="row">
        @csrf

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tax Rate Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tax_name" class="form-label">
                                Tax Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                    class="form-control @error('tax_name') is-invalid @enderror" 
                                    id="tax_name" 
                                    name="tax_name" 
                                    value="{{ old('tax_name') }}"
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
                                <option value="VAT" {{ old('tax_type') == 'VAT' ? 'selected' : '' }}>VAT (Value Added Tax)</option>
                                <option value="Sales Tax" {{ old('tax_type') == 'Sales Tax' ? 'selected' : '' }}>Sales Tax</option>
                                <option value="Service Tax" {{ old('tax_type') == 'Service Tax' ? 'selected' : '' }}>Service Tax</option>
                                <option value="Withholding Tax" {{ old('tax_type') == 'Withholding Tax' ? 'selected' : '' }}>Withholding Tax</option>
                                <option value="Excise Tax" {{ old('tax_type') == 'Excise Tax' ? 'selected' : '' }}>Excise Tax</option>
                                <option value="Import Tax" {{ old('tax_type') == 'Import Tax' ? 'selected' : '' }}>Import Tax</option>
                                <option value="Other" {{ old('tax_type') == 'Other' ? 'selected' : '' }}>Other</option>
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
                                        class="form-control @error('tax_percentage') is-invalid @enderror" 
                                        id="tax_percentage" 
                                        name="tax_percentage" 
                                        value="{{ old('tax_percentage') }}"
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
                                date=@json(old('effective_date', date('Y-m-d')))
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
                                {{ old('is_active') ? 'checked' : '' }}>

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
                            Create Tax Rate
                        </button>
                    </div>
                </div>
            </div>
        </div>
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

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Common Tax Rates</div>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>VAT Standard</strong></span>
                                <span class="text-muted">10-15%</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>VAT Reduced</strong></span>
                                <span class="text-muted">5-7%</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Sales Tax</strong></span>
                                <span class="text-muted">5-10%</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Service Tax</strong></span>
                                <span class="text-muted">6-10%</span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><strong>Withholding</strong></span>
                                <span class="text-muted">2-5%</span>
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

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-tax-rate.js'])

@endsection