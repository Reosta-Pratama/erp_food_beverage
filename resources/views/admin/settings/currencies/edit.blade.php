@extends('layouts.app', [
    'title' => 'Edit Currency - ' . $currency->currency_code
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
                        <a href="javascript:void(0);">Currencies</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Currency</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Edit Currency - {{ $currency->currency_code }}</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.currencies.index') }}"
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

    <div class="row g-4">
        
        <form action="{{ route('admin.settings.currencies.update', $currency->currency_code) }}" method="POST"
            id="currencyForm" class="col-lg-8">
            @csrf
            @method('PUT')

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="currency_code" class="form-label">
                                Currency Code <span class="text-danger">*</span>
                            </label>

                            <input type="text" 
                                    class="form-control text-uppercase @error('currency_code') is-invalid @enderror" 
                                    id="currency_code" 
                                    name="currency_code" 
                                    value="{{ old('currency_code', $currency->currency_code) }}"
                                    required
                                    maxlength="3"
                                    placeholder="e.g., USD, EUR, IDR"
                                    autocomplete="off">

                            @error('currency_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">3 uppercase letters (ISO 4217 standard)</small>
                        </div>

                        <div class="col-md-6">
                            <label for="currency_name" class="form-label">
                                Currency Name <span class="text-danger">*</span>
                            </label>
                            
                            <input type="text" 
                                    class="form-control @error('currency_name') is-invalid @enderror" 
                                    id="currency_name" 
                                    name="currency_name" 
                                    value="{{ old('currency_name', $currency->currency_name) }}"
                                    required
                                    placeholder="e.g., US Dollar"
                                    autocomplete="off">

                            @error('currency_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="symbol" class="form-label">Currency Symbol</label>

                            <input type="text" 
                                    class="form-control @error('symbol') is-invalid @enderror" 
                                    id="symbol" 
                                    name="symbol" 
                                    value="{{ old('symbol', $currency->symbol) }}"
                                    maxlength="10"
                                    placeholder="e.g., $, €, Rp"
                                    autocomplete="off">

                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Optional</small>
                        </div>

                        <div class="col-md-6">
                            <label for="exchange_rate" class="form-label">
                                Exchange Rate <span class="text-danger">*</span>
                            </label>

                            <input type="number" 
                                    class="form-control @error('exchange_rate') is-invalid @enderror" 
                                    id="exchange_rate" 
                                    name="exchange_rate" 
                                    value="{{ old('exchange_rate', $currency->exchange_rate) }}"
                                    required
                                    step="0.000001"
                                    min="0.000001"
                                    placeholder="1.000000"
                                    autocomplete="off">

                            @error('exchange_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="text-muted">Exchange rate relative to base currency</small>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                                type="checkbox" 
                                id="is_base_currency" 
                                name="is_base_currency" 
                                value="1"
                                {{ old('is_base_currency', $currency->is_base_currency) ? 'checked' : '' }}>

                        <label class="form-check-label" for="is_base_currency">
                            <strong>Set as Base Currency</strong><br>
                            <small class="text-muted">Base currency will have exchange rate of 1.0</small>
                        </label>
                    </div>

                    @if($currency->is_base_currency)
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="ti ti-info-circle me-2"></i>
                            This is currently the base currency. Exchange rate is locked to 1.0
                        </div>
                    @endif
                </div>
            </div>

            <div class="card custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.settings.currencies.index') }}" 
                            class="btn btn-outline-secondary">
                            <i class="ti ti-x me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-circle-check me-2"></i>
                            Update Currency
                        </button>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Metadata</div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Created At</label>
                            <div>
                                {{ \Carbon\Carbon::parse($currency->created_at)->format('d M Y, H:i') }}
                                <br><small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($currency->created_at)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Last Updated</label>
                            <div>
                                {{ \Carbon\Carbon::parse($currency->updated_at)->format('d M Y, H:i') }}
                                <br><small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($currency->updated_at)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Currency ID</label>
                            <div><code>{{ $currency->currency_id }}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Quick Actions</div>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.settings.currencies.show', $currency->currency_code) }}" 
                            class="btn btn-outline-primary">
                            <i class="ti ti-eye me-2"></i>
                            Currency Details
                        </a>
                        
                        @if(!$currency->is_base_currency)
                            <form action="{{ route('admin.settings.currencies.set-base', $currency->currency_code) }}" 
                                method="POST"
                                id="setBaseForm">
                                @csrf
                                @method('PATCH')

                                <button type="button" 
                                        class="btn btn-outline-success w-100"
                                        id="btnSetBase"
                                        data-code="{{ $currency->currency_code }}">
                                    <i class="ti ti-star me-2"></i>
                                    Set as Base Currency
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Status</div>
                </div>
                <div class="card-body">
                    @if ($currency->is_base_currency)
                        <div class="text-center">
                            <i class="bi bi-star-fill d-block text-warning fs-1 mb-2"></i>
                            <strong class="text-success">Base Currency</strong>
                            <p class="text-muted small mb-0 mt-2">
                                This is the reference currency for all exchange rates
                            </p>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="bi bi-currency-exchange d-block text-secondary fs-1 mb-2"></i>
                            <strong>Secondary Currency</strong>
                            <p class="text-muted small mb-0 mt-2">
                                Exchange rate relative to base currency
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency Guidlines</div>
                </div>
                <div class="card-body p-0">
                    <ol class="list-group list-group-flush list-group-numbered small">
                        <li class="list-group-item">Use ISO 4217 standard currency codes (3 letters)</li>
                        <li class="list-group-item">Only one currency can be set as base currency</li>
                        <li class="list-group-item">Base currency always has exchange rate of 1.0</li>
                        <li class="list-group-item">Exchange rates should be updated regularly</li>
                        <li class="list-group-item">Currency symbol is optional but recommended</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    @vite(['resources/assets/js/erp/create-new-currency.js'])

@endsection