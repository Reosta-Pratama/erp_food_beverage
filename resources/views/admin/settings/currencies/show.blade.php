@extends('layouts.app', [
    'title' => 'Currency Details - ' . $currency->currency_code
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
                    <li class="breadcrumb-item active" aria-current="page">Currency Details</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Currency Details - {{ $currency->currency_code }}</h2>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.settings.currencies.edit', $currency->currency_code) }}"
                class="btn btn-warning">
                <i class="ti ti-pencil me-2"></i>
                Edit Currency
            </a>

            <a href="{{ route('admin.settings.currencies.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>


<div class="container-fluid">

    <div class="row">
        <div class="col-lg-8">
            <!-- Currency Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Currency Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Currency Code</label>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0 text-primary">{{ $currency->currency_code }}</h3>
                                @if($currency->is_base_currency)
                                    <span class="badge bg-success ms-2">
                                        <i class="bi bi-star-fill me-1"></i>Base
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Currency Name</label>
                            <div><strong>{{ $currency->currency_name }}</strong></div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Symbol</label>
                            <div>
                                @if($currency->symbol)
                                    <span class="badge bg-secondary fs-5">{{ $currency->symbol }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Exchange Rate</label>
                            <div>
                                <code class="fs-5">{{ number_format($currency->exchange_rate, 6) }}</code>
                                @if($currency->is_base_currency)
                                    <br><small class="text-muted">(Base currency rate)</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($currency->is_base_currency)
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        This is the <strong>base currency</strong> for your system. All other currencies are calculated relative to this one.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Usage Example -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calculator me-2"></i>Conversion Example
                    </h5>
                </div>
                <div class="card-body">
                    @if($currency->is_base_currency)
                        <p class="mb-3">
                            As the base currency, <strong>1.00 {{ $currency->currency_code }}</strong> equals <strong>1.00</strong> in the system.
                        </p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ $currency->currency_code }}</th>
                                        <th>System Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.00</td>
                                        <td>1.00</td>
                                    </tr>
                                    <tr>
                                        <td>10.00</td>
                                        <td>10.00</td>
                                    </tr>
                                    <tr>
                                        <td>100.00</td>
                                        <td>100.00</td>
                                    </tr>
                                    <tr>
                                        <td>1,000.00</td>
                                        <td>1,000.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-3">
                            <strong>1.00 Base Currency</strong> = 
                            <strong>{{ number_format($currency->exchange_rate, 2) }} {{ $currency->currency_code }}</strong>
                        </p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Base Currency</th>
                                        <th>{{ $currency->currency_code }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.00</td>
                                        <td>{{ number_format($currency->exchange_rate, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>10.00</td>
                                        <td>{{ number_format($currency->exchange_rate * 10, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>100.00</td>
                                        <td>{{ number_format($currency->exchange_rate * 100, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>1,000.00</td>
                                        <td>{{ number_format($currency->exchange_rate * 1000, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Conversions are approximate and may vary based on current exchange rates.
                        </p>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Metadata</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Created At</label>
                            <div>
                                {{ \Carbon\Carbon::parse($currency->created_at)->format('d M Y, H:i') }}
                                <br><small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($currency->created_at)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Last Updated</label>
                            <div>
                                {{ \Carbon\Carbon::parse($currency->updated_at)->format('d M Y, H:i') }}
                                <br><small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($currency->updated_at)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="text-muted small">Currency ID</label>
                            <div><code>{{ $currency->currency_id }}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            @canUpdate('settings')
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.settings.currencies.edit', $currency->currency_code) }}" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil me-2"></i>Edit Currency
                        </a>
                        
                        @if(!$currency->is_base_currency)
                        <form action="{{ route('admin.settings.currencies.set-base', $currency->currency_code) }}" 
                              method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn btn-outline-success w-100"
                                    onclick="return confirm('Set {{ $currency->currency_code }} as base currency?\n\nThis will change the exchange rate to 1.0 and unset the current base currency.')">
                                <i class="bi bi-star me-2"></i>Set as Base Currency
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endcanUpdate

            <!-- Status Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    @if($currency->is_base_currency)
                        <div class="text-center py-3">
                            <i class="bi bi-star-fill text-warning fs-1 d-block mb-2"></i>
                            <strong class="text-success">Base Currency</strong>
                            <p class="text-muted small mb-0 mt-2">
                                This is the reference currency for all exchange rates in the system
                            </p>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-currency-exchange fs-1 d-block mb-2 text-primary"></i>
                            <strong>Secondary Currency</strong>
                            <p class="text-muted small mb-0 mt-2">
                                Exchange rate is relative to the base currency
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-lightbulb text-warning me-2"></i>Currency Information
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">ISO 4217 standard code format</li>
                        <li class="mb-2">Used across all financial transactions</li>
                        <li class="mb-2">Exchange rates can be updated anytime</li>
                        <li class="mb-2">Changes don't affect past transactions</li>
                        <li>Regular updates recommended for accuracy</li>
                    </ul>
                </div>
            </div>

            <!-- Usage Statistics (Optional - can be expanded) -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Usage Info
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Currency ID:</span>
                        <strong><code>{{ $currency->currency_id }}</code></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Code Length:</span>
                        <strong>{{ strlen($currency->currency_code) }} chars</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Has Symbol:</span>
                        <strong>
                            @if($currency->symbol)
                                <span class="text-success"><i class="bi bi-check-circle"></i> Yes</span>
                            @else
                                <span class="text-muted"><i class="bi bi-x-circle"></i> No</span>
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection