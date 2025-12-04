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
                class="btn btn-primary">
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

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Currency Code</label>
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0 text-primary">{{ $currency->currency_code }}</h3>
                                @if($currency->is_base_currency)
                                    <span class="badge bg-success ms-2">
                                        <i class="ti ti-star-filled me-1"></i>Base
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small">Currency Name</label>
                            <div><strong>{{ $currency->currency_name }}</strong></div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Symbol</label>
                            <div>
                                @if($currency->symbol)
                                    <span class="badge bg-secondary fs-5">{{ $currency->symbol }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
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
                            <i class="ti ti-info-circle me-2"></i>
                            This is the <strong>base currency</strong> for your system. All other currencies are calculated relative to this one.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Conversion Example</div>
                </div>
                <div class="card-body p-0">
                    @if ($currency->is_base_currency)
                        <div class="table-responsive">
                            <table class="table last-border-none">
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
                        <div class="table-responsive">
                            <table class="table">
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

                        <p class="text-muted small p-3 mb-0">
                            <i class="ti ti-info-circle me-1"></i>
                            Conversions are approximate and may vary based on current exchange rates.
                        </p>
                    @endif
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
        </div>

        <div class="col-lg-4">
            @if(!$currency->is_base_currency)
                <div class="card custom">
                    <div class="card-header">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.settings.currencies.set-base', $currency->currency_code) }}" 
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-outline-success w-100"
                                        onclick="return confirm('Set {{ $currency->currency_code }} as base currency?\n\nThis will change the exchange rate to 1.0 and unset the current base currency.')">
                                    <i class="ti ti-star me-2"></i>
                                    Set as Base Currency
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Status</div>
                </div>
                <div class="card-body">
                    @if ($currency->is_base_currency)
                        <div class="text-center">
                            <i class="bi bi-star-fill d-block text-warning display-3 mb-2"></i>
                            <strong class="text-success">Base Currency</strong>
                            <p class="text-muted small mb-0 mt-2">
                                This is the reference currency for all exchange rates
                            </p>
                        </div>
                    @else
                        <div class="text-center">
                            <i class="bi bi-currency-exchange d-block text-secondary display-3 mb-2"></i>
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

            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency Guidlines</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table last-border-none mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Currency ID:</td>
                                    <td class="fw-bold"><code>{{ $currency->currency_id }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Code Length:</td>
                                    <td class="fw-bold">{{ strlen($currency->currency_code) }} chars</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Has Symbol:</td>
                                    <td class="fw-bold">
                                        @if($currency->symbol)
                                            <span class="text-success">
                                                <i class="ti ti-circle-check"></i> 
                                                Yes
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="ti ti-circle-x"></i> 
                                                No
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@endsection