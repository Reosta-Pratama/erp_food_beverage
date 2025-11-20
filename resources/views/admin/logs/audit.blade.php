@extends('layouts.app', [
    'title' => 'Audit Logs'
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
                        <a href="javascript:void(0);">User Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Logs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Audit Log</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    @if(session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center fade show mb-3" role="alert">
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

    <!-- Container -->
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-primary bg-primary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.5 5.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 11.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 17.5l1.5 1.5l2.5 -2.5" /><path d="M11 6l9 0" /><path d="M11 12l9 0" /><path d="M11 18l9 0" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-primary fs-24 mb-1">{{ number_format($logs->total()) }}</h4>
                        <span class="fs-base fw-semibold">Total Audits</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-success bg-success bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-success fs-24 mb-1">{{ $users->count() }}</h4>
                        <span class="fs-base fw-semibold">Active Users</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-secondary bg-secondary bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-table"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-14z" /><path d="M3 10h18" /><path d="M10 3v18" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-secondary fs-24 mb-1">{{ $actionTypes->count() }}</h4>
                        <span class="fs-base fw-semibold">Action Types</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bolt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-danger fs-24 mb-1">{{ $actionTypes->count() }}</h4>
                        <span class="fs-base fw-semibold">Activity Types</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1"> Audit Logs</h2>
            <p class="text-muted mb-0">Comprehensive audit trail for all system changes.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.logs.audit.statistics') }}"
                target="_blank" rel="noopener noreferrer" 
                class="btn btn-outline-info">
                <i class="ti ti-graph me-2"></i>
                Statistics
            </a>
             <button type="button" class="btn btn-outline-success" 
                data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="ti ti-download me-2"></i>
                Export CSV
            </button>
            <button type="button" class="btn btn-outline-danger" 
                data-bs-toggle="modal" data-bs-target="#clearModal">
                <i class="ti ti-trash me-2"></i>
                Clear Old Logs
            </button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card custom sticky-card">
                <div class="card-header">
                    <div class="card-title">Filters</div>
                </div>

                <form method="GET" action="{{ route('admin.logs.audit.index') }}" id="filterForm"
                    class="card-body">

                    {{-- Date Range --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date Range</label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-text text-muted"> 
                                    <i class="ti ti-calendar"></i> 
                                </div>
                                <input name="daterange"
                                    date_from=@json(request('date_from'))
                                    date_to=@json(request('date_to'))
                                    type="text" class="form-control daterange" placeholder="Date range...">
                            </div>
                        </div>
                    </div>

                    {{-- User Filter --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">User</label>
                        <select
                            class="form-control single-select"
                            name="user_id">
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
                        <select 
                            class="form-control single-select" 
                            name="action_type">
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
                        <select 
                            class="form-control single-select" 
                            name="module_name">
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
                        <select 
                            class="form-control single-select" 
                            name="table_name">
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
                                placeholder="Enter record ID..."
                                autocomplete="off">
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

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.logs.audit.index') }}" 
                            class="btn btn-outline-danger">
                            <i class="ti ti-rotate-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>

                </form>

                <div class="card-footer">
                    <h6 class="mb-3">
                        <i class="ti ti-list me-2"></i>
                        Quick Filter
                    </h6>

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
        
        <div class="col-lg-9">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        Audit Trail
                        <span class="badge bg-primary ms-2">{{ $logs->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table text-nowrap table-borderless mb-0">
                                <thead>
                                    <th scope="col">#</th>
                                    <th scope="col">Timestamp</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Module / Table</th>
                                    <th scope="col">Record</th>
                                    <th scope="col">IP Address</th>
                                    <th scope="col">Details</th>
                                </thead>
                                <tbody>
                                    @foreach($logs as $index => $log)
                                        <tr>
                                            <td>{{ $logs->firstItem() + $index }}</td>
                                            <td>
                                                <div class="small">
                                                    <div>{{ \Carbon\Carbon::parse($log->action_timestamp)->format('d M Y') }}</div>
                                                    <code class="text-muted">{{ \Carbon\Carbon::parse($log->action_timestamp)->format('H:i:s') }}</code>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold small">{{ $log->full_name }}</div>
                                                    <code class="text-muted fs-12">{{ $log->username }}</code>
                                                    <div>
                                                        <span class="badge bg-primary fs-10">{{ $log->role_name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge 
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
                                                <code>{{ $log->ip_address ?? '-' }}</code>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-primary btn-wave"
                                                    href="{{ route('admin.logs.audit.show', $log->audit_id) }}">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ti ti-file-x text-muted fs-40"></i>
                                <h5 class="text-muted mt-3">No Activity Logs Found</h5>
                                <p class="text-muted">Try adjusting your filters or check back later</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($logs->hasPages())
                    <div class="card-footer d-flex justify-content-center">
                        {{ $logs->links('pagination.default') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('modals')

    {{-- Export Modal --}}
    <div class="modal fade" id="exportModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="exportModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="GET" action="{{ route('admin.logs.audit.export') }}"
                class="modal-content">
                <div class="modal-header d-flex justify-content-between bg-success">
                    <h6 class="modal-title text-white">
                        Export Audit Logs
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Export will include current filter settings and up to 10,000 records.
                    </div>

                    {{-- Pass current filters --}}
                    @foreach(request()->query() as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="mb-3">
                        <label class="form-label fs-13">Export Format</label>
                        <select class="form-select" disabled>
                            <option>CSV (Comma Separated Values)</option>
                        </select>
                        <small class="text-muted">More formats coming soon</small>
                    </div>

                    <div>
                        <h6 class="fs-13">Export Summary:</h6>

                        <ul class="mb-1 list-group list-group-horizontal">
                            <li class="list-group-item w-50">Total Records</li>
                            <li class="list-group-item w-25"><strong>{{ min($logs->total(), 10000) }}</strong></li>
                        </ul>

                        <ul class="mb-1 list-group list-group-horizontal">
                            <li class="list-group-item w-50">Filters Applied</li>
                            <li class="list-group-item w-25"><strong>{{ collect(request()->query())->count() }}</strong></li>
                        </ul>

                        <ul class="mb-0 list-group list-group-horizontal">
                            <li class="list-group-item w-50">File Format</li>
                            <li class="list-group-item w-25"><strong>CSV</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-download me-2"></i>
                        Download CSV
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Clear Logs Modal --}}
    <div class="modal fade" id="clearModal" 
        data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="clearModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.logs.audit.clear') }}"
                class="modal-content">
                @csrf

                <div class="modal-header d-flex justify-content-between bg-danger">
                    <h6 class="modal-title text-white">
                        Clear Old Activity Logs
                    </h6>

                    <button type="button" class="btn btn-icon btn-white-transparent" 
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-exclamation-circle me-2"></i>
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>

                    <div class="mb-3">
                        <label for="days" class="form-label">
                            Delete logs older than: <span class="text-danger">*</span>
                        </label>
                        <select
                            class="form-control single-select"
                            id="days" name="days">
                            <option value="">Select period...</option>
                            <option value="90">90 days (3 months)</option>
                            <option value="180">180 days (6 months)</option>
                            <option value="365">365 days (1 year)</option>
                        </select>
                    </div>

                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Compliance Notice:</strong> Many regulations require keeping audit logs for 1+ years. Consult your compliance officer before deletion.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-2"></i>
                        Clear Logs
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('scripts')

    <!-- FlatPickr JS -->
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.min.js') }}"></script>

    @vite(['resources/assets/js/erp/audit-log.js'])

@endsection