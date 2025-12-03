@extends('layouts.app', [
    'title' => 'Tax Rate Details' 
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
                        <a href="javascript:void(0);">Tax Rates</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $taxRate->tax_name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fs-22 mb-0">Tax Rate Details - {{ $taxRate->tax_name }}</h2>

        <div class="d-flex align-items-center gap-2">
            @canUpdate('settings')
                <a href="{{ route('admin.settings.tax-rates.edit', $taxRate->tax_code) }}" 
                    class="btn btn-warning">
                    <i class="ti ti-pencil me-2"></i>
                    Edit
                </a>
            @endcanUpdate

            <a href="{{ route('admin.settings.tax-rates.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tax Rate Information</div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small">Tax Code</label>
                            <div>
                                <code class="fs-5 text-primary">{{ $taxRate->tax_code }}</code>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="text-muted small">Tax Name</label>
                            <div>
                                <strong class="fs-5">{{ $taxRate->tax_name }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Tax Type</label>
                            <div>
                                @if($taxRate->tax_type === 'VAT')
                                    <span class="badge bg-primary fs-6">{{ $taxRate->tax_type }}</span>
                                @elseif($taxRate->tax_type === 'Sales Tax')
                                    <span class="badge bg-success fs-6">{{ $taxRate->tax_type }}</span>
                                @elseif($taxRate->tax_type === 'Service Tax')
                                    <span class="badge bg-info fs-6">{{ $taxRate->tax_type }}</span>
                                @elseif($taxRate->tax_type === 'Withholding Tax')
                                    <span class="badge bg-warning fs-6">{{ $taxRate->tax_type }}</span>
                                @else
                                    <span class="badge bg-secondary fs-6">{{ $taxRate->tax_type }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Tax Percentage</label>
                            <div>
                                <h3 class="mb-0 text-success">{{ number_format($taxRate->tax_percentage, 2) }}%</h3>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small">Effective Date</label>
                            <div>
                                <strong>{{ \Carbon\Carbon::parse($taxRate->effective_date)->format('d F Y') }}</strong>
                                <br>
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($taxRate->effective_date)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="text-muted small">Status</label>
                            <div>
                                @if($taxRate->is_active)
                                    <span class="badge bg-success fs-6">
                                        <i class="ti ti-circle-check me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6">
                                        <i class="ti ti-circle-x me-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title">Tax Calculation Example</div>
                    <p class="mb-0">
                        Based on <strong>{{ number_format($taxRate->tax_percentage, 2) }}%</strong> tax rate:
                    </p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Base Amount</th>
                                    <th scope="col">Tax Amount</th>
                                    <th scope="col">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $amounts = [100, 500, 1000, 5000, 10000];
                                @endphp
                                @foreach($amounts as $amount)
                                @php
                                    $taxAmount = $amount * ($taxRate->tax_percentage / 100);
                                    $totalAmount = $amount + $taxAmount;
                                @endphp
                                <tr>
                                    <td>{{ number_format($amount, 0) }}</td>
                                    <td class="text-primary">{{ number_format($taxAmount, 2) }}</td>
                                    <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                                {{ \Carbon\Carbon::parse($taxRate->created_at)->format('d M Y, H:i') }}
                                <br><small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($taxRate->created_at)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Last Updated</label>
                            <div>
                                {{ \Carbon\Carbon::parse($taxRate->updated_at)->format('d M Y, H:i') }}
                                <br><small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($taxRate->updated_at)->diffForHumans() }})
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="text-muted small">Currency ID</label>
                            <div><code>{{ $taxRate->tax_id }}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @canUpdate('settings')
                <div class="card custom">
                    <div class="card-header">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.settings.tax-rates.toggle-status', $taxRate->tax_code) }}" 
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-outline-{{ $taxRate->is_active ? 'danger' : 'success' }} w-100">
                                    <i class="bi bi-toggle-{{ $taxRate->is_active ? 'off' : 'on' }} me-2"></i>
                                    {{ $taxRate->is_active ? 'Deactivate' : 'Activate' }} Tax Rate
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcanUpdate

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
                    <div class="card-title">Tax Rate Guidlines</div>
                </div>
                <div class="card-body p-0">
                    <ol class="list-group list-group-flush list-group-numbered small">
                        <li class="list-group-item">Tax rate is applied to base amounts</li>
                        <li class="list-group-item">Multiple tax rates can exist for different types</li>
                        <li class="list-group-item">Effective date determines when tax becomes applicable</li>
                        <li class="list-group-item">Changes don't affect existing transactions</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection