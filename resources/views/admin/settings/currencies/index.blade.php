@extends('layouts.app', [
    'title' => 'Currencies Management'
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
                    <li class="breadcrumb-item active" aria-current="page">Currencies</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center d-flex align-items-center fade show mb-3" role="alert">
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Currencies Management</h2>
            <p class="text-muted mb-0">
                Manage all system currencies, including codes, symbols, and exchange settings.
            </p>

        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.settings.currencies.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Currency
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row g-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="ti ti-info-circle me-2"></i>
                <strong>Base Currency:</strong> The base currency has an exchange rate of 1.0 and is used as the reference for all other currencies.
            </div>
        </div>

        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Currency List</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Currency Code</th>
                                    <th scope="col">Currency Name</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Exchange Rate</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($currencies as $currency)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $currency->currency_code }}</strong>
                                        </td>

                                        <td>{{ $currency->currency_name }}</td>

                                        <td>
                                            @if($currency->symbol)
                                                <span class="badge bg-light text-dark">{{ $currency->symbol }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ number_format($currency->exchange_rate, 6) }}</code>
                                        </td>
                                        <td>
                                            @if($currency->is_base_currency)
                                                <span class="badge bg-success">
                                                    <i class="ti ti-star-filled me-1"></i>
                                                    Base Currency
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Secondary</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-grid gap-2 d-md-block">
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('admin.settings.currencies.show', $currency->currency_code) }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                
                                                @canUpdate('settings')
                                                    {{-- @if (!$currency->is_base_currency)
                                                        <form action="{{ route('admin.settings.currencies.set-base', $currency->currency_code) }}" 
                                                            method="POST" 
                                                            class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            
                                                            <button class="btn btn-sm btn-primary"
                                                                type="submit"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Set as Base Currency">
                                                                <i class="ti ti-star"></i>
                                                            </button>
                                                        </form>
                                                    @endif --}}

                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('admin.settings.currencies.edit', $currency->currency_code) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                @endcanUpdate

                                                @canDelete('settings')
                                                    @if (!$currency->is_base_currency)
                                                        <form action="{{ route('admin.settings.currencies.destroy', $currency->currency_code) }}" 
                                                            method="POST" 
                                                            class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this currency?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            
                                                            <button class="btn btn-sm btn-danger"
                                                                type="submit"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-danger"
                                                            type="button"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Cannot delete base currency">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    @endif
                                                @endcanDelete
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="ti ti-database-off display-1 d-block mb-2"></i>
                                            No currencies found. Add your first currency to get started.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection