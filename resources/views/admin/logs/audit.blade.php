@extends('layouts.app', [
    'title' => 'Audit Logs'
])

@section('styles')
<style>
    .audit-card {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }
    .audit-card:hover {
        background-color: #f8f9fa;
        transform: translateX(3px);
    }
    .audit-card.create { border-left-color: #198754; }
    .audit-card.update { border-left-color: #ffc107; }
    .audit-card.delete { border-left-color: #dc3545; }
    .audit-card.login { border-left-color: #0d6efd; }
    .audit-card.logout { border-left-color: #6c757d; }
    
    .action-badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.6rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .ip-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h2 class="mb-1">
                        <i class="bi bi-shield-lock-fill text-primary me-2"></i>
                        Audit Logs
                    </h2>
                    <p class="text-muted mb-0">Comprehensive audit trail for all system changes</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.logs.audit.statistics') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up me-2"></i>Statistics
                    </a>
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="bi bi-download me-2"></i>Export CSV
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearModal">
                        <i class="bi bi-trash me-2"></i>Clear Old Logs
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Audits</h6>
                            <h3 class="mb-0">{{ number_format($logs->total()) }}</h3>
                        </div>
                        <div class="stats-icon bg-primary bg-opacity-10">
                            <i class="bi bi-file-earmark-text text-primary fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Active Users</h6>
                            <h3 class="mb-0">{{ $users->count() }}</h3>
                        </div>
                        <div class="stats-icon bg-success bg-opacity-10">
                            <i class="bi bi-people text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tables Tracked</h6>
                            <h3 class="mb-0">{{ $tables->count() }}</h3>
                        </div>
                        <div class="stats-icon bg-warning bg-opacity-10">
                            <i class="bi bi-table text-warning fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Action Types</h6>
                            <h3 class="mb-0">{{ $actionTypes->count() }}</h3>
                        </div>
                        <div class="stats-icon bg-danger bg-opacity-10">
                            <i class="bi bi-lightning text-danger fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Filters Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel me-2"></i>Filters
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.logs.audit.index') }}">
                        
                        {{-- Date Range --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date Range</label>
                            <input type="date" 
                                   class="form-control mb-2" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}">
                            <input type="date" 
                                   class="form-control" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>

                        {{-- User Filter --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">User</label>
                            <select class="form-select" name="user_id">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->full_name }} ({{ $user->log_count }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Action Type Filter --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Action Type</label>
                            <select class="form-select" name="action_type">
                                <option value="">All Actions</option>
                                @foreach($actionTypes as $type => $count)
                                <option value="{{ $type }}" {{ request('action_type') === $type ? 'selected' : '' }}>
                                    {{ $type }} ({{ $count }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Module Filter --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Module</label>
                            <select class="form-select" name="module_name">
                                <option value="">All Modules</option>
                                @foreach($modules as $module => $count)
                                <option value="{{ $module }}" {{ request('module_name') === $module ? 'selected' : '' }}>
                                    {{ $module }} ({{ $count }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Table Filter --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Table</label>
                            <select class="form-select" name="table_name">
                                <option value="">All Tables</option>
                                @foreach($tables as $table => $count)
                                <option value="{{ $table }}" {{ request('table_name') === $table ? 'selected' : '' }}>
                                    {{ $table }} ({{ $count }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Record ID --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Record ID</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="record_id" 
                                   value="{{ request('record_id') }}"
                                   placeholder="Enter record ID...">
                        </div>

                        {{-- IP Address --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">IP Address</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="ip_address" 
                                   value="{{ request('ip_address') }}"
                                   placeholder="e.g., 192.168.1.1">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.logs.audit.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Quick Actions --}}
                <div class="card-footer bg-white">
                    <h6 class="mb-3"><i class="bi bi-lightning me-2"></i>Quick Filters</h6>
                    <div class="d-flex flex-column gap-2">
                        @foreach(['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT'] as $action)
                        <a href="{{ route('admin.logs.audit.index', ['action_type' => $action]) }}" 
                           class="btn btn-sm btn-outline-{{ $action === 'CREATE' ? 'success' : ($action === 'DELETE' ? 'danger' : ($action === 'UPDATE' ? 'warning' : 'info')) }}">
                            {{ $action }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Audit Logs List --}}
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Audit Trail
                            <span class="badge bg-primary">{{ $logs->total() }}</span>
                        </h5>
                        <div class="text-muted small">
                            Showing {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    
                    @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 15%">Timestamp</th>
                                    <th style="width: 15%">User</th>
                                    <th style="width: 10%">Action</th>
                                    <th style="width: 15%">Module / Table</th>
                                    <th style="width: 10%">Record</th>
                                    <th style="width: 15%">IP Address</th>
                                    <th style="width: 10%" class="text-center">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $index => $log)
                                <tr class="audit-card {{ strtolower($log->action_type) }}">
                                    <td class="text-muted small">
                                        {{ $logs->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ \Carbon\Carbon::parse($log->action_timestamp)->format('d M Y') }}</div>
                                            <code class="text-muted">{{ \Carbon\Carbon::parse($log->action_timestamp)->format('H:i:s') }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold small">{{ $log->full_name }}</div>
                                            <code class="text-muted" style="font-size: 0.7rem;">{{ $log->username }}</code>
                                            <div>
                                                <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $log->role_name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="action-badge badge 
                                            @if($log->action_type === 'CREATE') bg-success
                                            @elseif($log->action_type === 'UPDATE') bg-warning text-dark
                                            @elseif($log->action_type === 'DELETE') bg-danger
                                            @elseif($log->action_type === 'LOGIN') bg-info
                                            @else bg-secondary
                                            @endif">
                                            {{ $log->action_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-bold">{{ $log->module_name }}</div>
                                            <code class="text-muted">{{ $log->table_name }}</code>
                                        </div>
                                    </td>
                                    <td>
                                        @if($log->record_id)
                                        <span class="badge bg-light text-dark">
                                            #{{ $log->record_id }}
                                        </span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="ip-badge">{{ $log->ip_address ?? '-' }}</code>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.logs.audit.show', $log->audit_id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No Audit Logs Found</h5>
                        <p class="text-muted">Try adjusting your filters</p>
                    </div>
                    @endif

                </div>
                @if($logs->hasPages())
                <div class="card-footer bg-white">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@section('modals')
{{-- Export Modal --}}
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-download me-2"></i>Export Audit Logs
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('admin.logs.audit.export') }}">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Export will include current filters (max 10,000 records)
                    </div>

                    {{-- Pass current filters --}}
                    @foreach(request()->query() as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="mb-3">
                        <h6>Export Summary:</h6>
                        <ul class="mb-0">
                            <li>Total Records: <strong>{{ min($logs->total(), 10000) }}</strong></li>
                            <li>Filters Applied: <strong>{{ collect(request()->query())->count() }}</strong></li>
                            <li>Format: <strong>CSV</strong></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-download me-2"></i>Download CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Clear Logs Modal --}}
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-trash me-2"></i>Clear Old Audit Logs
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.logs.audit.clear') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Critical Warning!</strong> Audit logs are essential for compliance. Deletion is permanent and cannot be undone.
                    </div>

                    <div class="mb-3">
                        <label for="days" class="form-label">
                            Delete logs older than: <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="days" id="days" required>
                            <option value="">Select period...</option>
                            <option value="90">90 days (3 months)</option>
                            <option value="180">180 days (6 months)</option>
                            <option value="365">365 days (1 year)</option>
                        </select>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="bi bi-shield-exclamation me-2"></i>
                            <strong>Compliance Notice:</strong> Many regulations require keeping audit logs for 1+ years. Consult your compliance officer before deletion.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Clear Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    @vite(['resources/assets/js/erp/audit-log.js'])

@endsection