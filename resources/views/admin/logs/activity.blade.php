@extends('layouts.app', [
    'title' => 'Activity Logs'
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
                    <li class="breadcrumb-item active" aria-current="page">Activity Log</li>
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
                        <span class="fs-base fw-semibold">Total Activities</span>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-layout"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M4 13m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M14 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-secondary fs-24 mb-1">{{ $modules->count() }}</h4>
                        <span class="fs-base fw-semibold">Modules</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card custom">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar avatar-xxl svg-danger bg-danger bg-opacity-10 rounded-circle border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tag"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z" /></svg>
                    </div>
                    <div class="ms-3">
                        <h4 class="text-danger fs-24 mb-1">{{ $activityTypes->count() }}</h4>
                        <span class="fs-base fw-semibold">Activity Types</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">Activity Logs</h2>
            <p class="text-muted mb-0">Monitor and track all user activities in the system.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
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

                <form method="GET" action="{{ route('admin.logs.activity.index') }}" id="filterForm"
                    class="card-body">

                    {{-- Search --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Search</label>
                        <input type="text" 
                                class="form-control" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search in description...">
                    </div>

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

                    {{-- Activity Type Filter --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Activity Type</label>
                        <select
                            class="form-control single-select"
                            name="activity_type">
                            <option value="">All Types</option>
                            @foreach($activityTypes as $type => $count)
                                <option value="{{ $type }}" {{ request('activity_type') === $type ? 'selected' : '' }}>
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

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search me-2"></i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.logs.activity.index') }}" 
                            class="btn btn-outline-danger">
                            <i class="ti ti-rotate-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>
                </form>

                {{-- Quick Filters --}}
                <div class="card-footer">
                    <h6 class="mb-3">
                        <i class="ti ti-list me-2"></i>
                        Quick Filter
                    </h6>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.logs.activity.index', ['date_from' => today()->format('Y-m-d')]) }}" 
                           class="badge bg-primary text-decoration-none">
                            Today
                        </a>
                        <a href="{{ route('admin.logs.activity.index', ['date_from' => today()->subDays(7)->format('Y-m-d')]) }}" 
                           class="badge bg-info text-decoration-none">
                            Last 7 Days
                        </a>
                        <a href="{{ route('admin.logs.activity.index', ['date_from' => today()->subDays(30)->format('Y-m-d')]) }}" 
                           class="badge bg-success text-decoration-none">
                            Last 30 Days
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card custom">
                <div class="card-header justify-content-between align-items-center">
                    <div class="card-title d-flex align-items-center">
                        Activity Timeline
                        <span class="badge bg-primary ms-2">{{ $logs->total() }}</span>
                    </div>
                    <div class="text-muted small">
                        Showing {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }}
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        @if($logs->count() > 0)
                            @foreach($logs as $log)
                                <div class="col-12">
                                    <div class="card custom mb-0">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                {{-- Log Content --}}
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <span class="log-type-badge badge 
                                                                    @if(str_contains($log->activity_type, 'Create')) bg-success
                                                                    @elseif(str_contains($log->activity_type, 'Update')) bg-warning
                                                                    @elseif(str_contains($log->activity_type, 'Delete')) bg-danger
                                                                    @elseif(str_contains($log->activity_type, 'Login')) bg-info
                                                                    @else bg-secondary
                                                                    @endif">
                                                                    {{ $log->activity_type }}
                                                                </span>
                                                                <span class="badge bg-light text-dark">{{ $log->module_name }}</span>
                                                            </h6>
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="ti ti-clock me-1"></i>
                                                            {{ \Carbon\Carbon::parse($log->activity_timestamp)->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                    
                                                    <p class="mb-2">{{ $log->description }}</p>
                                                    
                                                    <div class="d-flex align-items-center text-muted small">
                                                        <i class="ti ti-user-circle fs-16 me-2"></i>
                                                        <strong>{{ $log->full_name }}</strong>
                                                        <span class="mx-2">•</span>
                                                        <code>{{ $log->username }}</code>
                                                        <span class="mx-2">•</span>
                                                        <span class="badge bg-light text-black">{{ $log->role_name }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($log->activity_timestamp)->format('d M Y - H:i:s') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="ti ti-inbox text-muted fs-40"></i>
                                    <h5 class="text-muted mt-3">No Activity Logs Found</h5>
                                    <p class="text-muted">Try adjusting your filters or check back later</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    {{ $logs->links('pagination.default') }}
                </div>
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
            <form action="{{ route('admin.logs.activity.export') }}" method="get"
                class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        Export Activity Logs
                    </h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Export will include current filter settings and up to 10,000 records.
                    </div>

                    {{-- Pass current filters --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('date_from'))
                        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    @endif
                    @if(request('date_to'))
                        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    @endif
                    @if(request('user_id'))
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    @endif
                    @if(request('activity_type'))
                        <input type="hidden" name="activity_type" value="{{ request('activity_type') }}">
                    @endif
                    @if(request('module_name'))
                        <input type="hidden" name="module_name" value="{{ request('module_name') }}">
                    @endif

                    <div class="mb-3">
                        <label class="form-label fs-13">Export Format</label>
                        <select class="form-select" disabled>
                            <option>CSV (Comma Separated Values)</option>
                        </select>
                        <small class="text-muted">More formats coming soon</small>
                    </div>

                    <div class="mb-0">
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
            <form method="POST" action="{{ route('admin.logs.activity.clear') }}"
                class="modal-content">
                @csrf

                <div class="modal-header">
                    <h6 class="modal-title">
                        Clear Old Activity Logs
                    </h6>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            name="days" required>
                            <option value="">Select period...</option>
                            <option value="30">30 days (1 month)</option>
                            <option value="60">60 days (2 months)</option>
                            <option value="90">90 days (3 months)</option>
                            <option value="180">180 days (6 months)</option>
                            <option value="365">365 days (1 year)</option>
                        </select>
                        <small class="text-muted">Only logs older than the selected period will be deleted</small>
                    </div>

                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="ti ti-info-circle me-2"></i>
                            It's recommended to keep at least 90 days of activity logs for audit purposes.
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

    @vite(['resources/assets/js/erp/activity-log.js'])

@endsection