@extends('layouts.app', [
    'title' => 'Tax Rates Management'
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
                    <li class="breadcrumb-item active" aria-current="page">Tax Rates</li>
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
            <h2 class="fs-22 mb-1">Tax Rates Management</h2>
            <p class="text-muted mb-0"></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.settings.tax-rates.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-2"></i>
                Create New Tax Rate
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card custom">
                <div class="card-header">
                    <div class="card-title">Tax Rate List</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Tax Code</th>
                                    <th scope="col">Tax Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Percentage</th>
                                    <th scope="col">Effective Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Activate/Deactivate</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($taxRates as $tax)
                                    <tr>
                                        <td>
                                            <code class="text-primary">{{ $tax->tax_code }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $tax->tax_name }}</strong>
                                        </td>
                                        <td>
                                            @if($tax->tax_type === 'VAT')
                                                <span class="badge bg-primary">{{ $tax->tax_type }}</span>
                                            @elseif($tax->tax_type === 'Sales Tax')
                                                <span class="badge bg-success">{{ $tax->tax_type }}</span>
                                            @elseif($tax->tax_type === 'Service Tax')
                                                <span class="badge bg-info">{{ $tax->tax_type }}</span>
                                            @elseif($tax->tax_type === 'Withholding Tax')
                                                <span class="badge bg-warning">{{ $tax->tax_type }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $tax->tax_type }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($tax->tax_percentage, 2) }}%</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($tax->effective_date)->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($tax->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="d-grid gap-2 d-md-block">
                                                <form action="{{ route('admin.settings.tax-rates.toggle-status', $tax->tax_code) }}" 
                                                    method="POST" 
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-{{ $tax->is_active ? 'success' : 'danger' }}" 
                                                            title="{{ $tax->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="bi bi-toggle-{{ $tax->is_active ? 'on' : 'off' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-grid gap-2 d-md-block">
                                                <a href="{{ route('admin.settings.tax-rates.show', $tax->tax_code) }}" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="View Detail">
                                                    <i class="ti ti-eye"></i>
                                                </a>

                                                <a href="{{ route('admin.settings.tax-rates.edit', $tax->tax_code) }}" 
                                                    class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>

                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"
                                                        onclick="confirmDelete('{{ $tax->tax_code }}', '{{ $tax->tax_name }}')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="ti ti-database-off display-1 d-block mb-2"></i>
                                            No tax rates found. Add your first tax rate to get started.
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

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        function confirmDelete(code, name) {
            Swal.fire({
                icon: 'warning',
                title: `Delete tax rate "${name}"?`,
                text: "This action cannot be undone.",
                showCancelButton: true,
                confirmButtonColor: "#985ffd",
                cancelButtonColor: "#faf8fd",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/admin/settings/tax-rates/${code}`;
                    form.submit();
                }
            });

            return false;
        }
    </script>

@endsection