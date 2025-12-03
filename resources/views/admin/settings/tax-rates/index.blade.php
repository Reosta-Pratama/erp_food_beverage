@extends('layouts.app', [
    'title' => 'Tax Rates Management'
])

@section('styles')
@endsection

@section('content')



<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Tax Rates Management</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Settings</a></li>
                    <li class="breadcrumb-item active">Tax Rates</li>
                </ol>
            </nav>
        </div>
        @canCreate('settings')
        <div>
            <a href="{{ route('admin.settings.tax-rates.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Tax Rate
            </a>
        </div>
        @endcanCreate
    </div>

    <!-- Tax Rates Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tax Code</th>
                            <th>Tax Name</th>
                            <th>Type</th>
                            <th>Percentage</th>
                            <th>Effective Date</th>
                            <th>Status</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxRates as $tax)
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
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @canRead('settings')
                                    <a href="{{ route('admin.settings.tax-rates.show', $tax->tax_code) }}" 
                                       class="btn btn-outline-primary" 
                                       title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endcanRead
                                    
                                    @canUpdate('settings')
                                    <form action="{{ route('admin.settings.tax-rates.toggle-status', $tax->tax_code) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-outline-{{ $tax->is_active ? 'warning' : 'success' }}" 
                                                title="{{ $tax->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-toggle-{{ $tax->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('admin.settings.tax-rates.edit', $tax->tax_code) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcanUpdate
                                    
                                    @canDelete('settings')
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            title="Delete"
                                            onclick="confirmDelete('{{ $tax->tax_code }}', '{{ $tax->tax_name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endcanDelete
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
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

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')

<script>
    function confirmDelete(code, name) {
        if (confirm(`Are you sure you want to delete tax rate "${name}"?\n\nThis action cannot be undone.`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/settings/tax-rates/${code}`;
            form.submit();
        }
    }
</script>

@endsection