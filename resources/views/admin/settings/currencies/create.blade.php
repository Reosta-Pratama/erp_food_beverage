@extends('layouts.app', [
    'title' => 'Create Currency'
])

@section('styles')
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
                    <li class="breadcrumb-item active" aria-current="page">Create New Currency</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Create New Currency</h2>

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

    <!-- Container -->
    <form action="{{ route('admin.settings.currencies.store') }}" method="POST"
        id="currencyForm" class="row g-4">
        @csrf

        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency Information</div>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency Guidlines</div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Common Currency</div>
                </div>
            </div>
        </div>

    </form>
    <!-- Container -->




    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.settings.currencies.store') }}" method="POST">
                @csrf
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Currency Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="currency_code" class="form-label">
                                    Currency Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control text-uppercase @error('currency_code') is-invalid @enderror" 
                                       id="currency_code" 
                                       name="currency_code" 
                                       value="{{ old('currency_code') }}"
                                       required
                                       maxlength="3"
                                       placeholder="e.g., USD, EUR, IDR"
                                       style="text-transform: uppercase;">
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
                                       value="{{ old('currency_name') }}"
                                       required
                                       placeholder="e.g., US Dollar">
                                @error('currency_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="symbol" class="form-label">Currency Symbol</label>
                                <input type="text" 
                                       class="form-control @error('symbol') is-invalid @enderror" 
                                       id="symbol" 
                                       name="symbol" 
                                       value="{{ old('symbol') }}"
                                       maxlength="10"
                                       placeholder="e.g., $, €, Rp">
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
                                       value="{{ old('exchange_rate', '1.000000') }}"
                                       required
                                       step="0.000001"
                                       min="0.000001"
                                       placeholder="1.000000">
                                @error('exchange_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Exchange rate relative to base currency</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_base_currency" 
                                   name="is_base_currency" 
                                   value="1"
                                   {{ old('is_base_currency') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_base_currency">
                                <strong>Set as Base Currency</strong><br>
                                <small class="text-muted">Base currency will have exchange rate of 1.0</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Currency
                        </button>
                        <a href="{{ route('admin.settings.currencies.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle text-primary me-2"></i>Currency Guidelines
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">Use ISO 4217 standard currency codes (3 letters)</li>
                        <li class="mb-2">Only one currency can be set as base currency</li>
                        <li class="mb-2">Base currency always has exchange rate of 1.0</li>
                        <li class="mb-2">Exchange rates should be updated regularly</li>
                        <li>Currency symbol is optional but recommended</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-cash-stack text-success me-2"></i>Common Currencies
                    </h6>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>USD</strong> - US Dollar</span>
                            <span class="text-muted">$</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>EUR</strong> - Euro</span>
                            <span class="text-muted">€</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>IDR</strong> - Indonesian Rupiah</span>
                            <span class="text-muted">Rp</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><strong>GBP</strong> - British Pound</span>
                            <span class="text-muted">£</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><strong>JPY</strong> - Japanese Yen</span>
                            <span class="text-muted">¥</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    @vite(['resources/assets/js/erp/create-new-currency.js'])

@endsection