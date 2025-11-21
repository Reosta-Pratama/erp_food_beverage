@extends('layouts.app', [
    'title' => 'Audit Log Details'
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
                        <a href="javascript:void(0);">User Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Logs</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);">Audit Log</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Audit #{{ $auditLog->audit_id }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fs-22 mb-1">
                <span class="badge 
                    @if($auditLog->action_type === 'CREATE') bg-success
                    @elseif($auditLog->action_type === 'UPDATE') bg-warning
                    @elseif($auditLog->action_type === 'DELETE') bg-danger
                    @else bg-info
                    @endif">
                    {{ $auditLog->action_type }}
                </span>
                Audit Log #{{ $auditLog->audit_id }}
            </h2>
            <p class="text-muted mb-0">
                {{ $auditLog->module_name }} - {{ $auditLog->table_name }}
                @if($auditLog->record_id)
                    (Record #{{ $auditLog->record_id }})
                @endif
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.logs.audit.index') }}"
                class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-2"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="row">
                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">Audit Information</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tr>
                                        <td class="text-muted">Audit ID:</td>
                                        <td class="fw-bold">#{{ $auditLog->audit_id }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Action Type:</td>
                                        <td>
                                            <span class="badge 
                                                @if($auditLog->action_type === 'CREATE') bg-success
                                                @elseif($auditLog->action_type === 'UPDATE') bg-warning 
                                                @elseif($auditLog->action_type === 'DELETE') bg-danger
                                                @else bg-info
                                                @endif">
                                                {{ $auditLog->action_type }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Module:</td>
                                        <td class="fw-bold">{{ $auditLog->module_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Table</td>
                                        <td>
                                            <code>{{ $auditLog->table_name }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Record ID:</td>
                                        <td>
                                            @if($auditLog->record_id)
                                                <span class="badge bg-light text-dark">#{{ $auditLog->record_id }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Timestamp:</td>
                                        <td class="small">
                                            {{ \Carbon\Carbon::parse($auditLog->action_timestamp)->format('d M Y - H:i:s') }}
                                            <div class="text-muted">
                                                ({{ \Carbon\Carbon::parse($auditLog->action_timestamp)->diffForHumans() }})
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">IP Address:</td>
                                        <td>
                                            <code>{{ $auditLog->ip_address ?? 'N/A' }}</code>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card custom">
                        <div class="card-header">
                            <div class="card-title">User Details</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tr>
                                        <td class="text-muted">Full Name:</td>
                                        <td class="fw-bold">{{ $auditLog->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Username:</td>
                                        <td>
                                            <code>{{ $auditLog->username }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email:</td>
                                        <td>{{ $auditLog->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Role:</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $auditLog->role_name }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($relatedLogs->count() > 0)
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Related Audits</div>
                                <span class="badge bg-primary ms-2">
                                    {{ $relatedLogs->count() }}
                                </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($relatedLogs as $related)
                                        <a href="{{ route('admin.logs.audit.show', $related->audit_id) }}" 
                                            class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge 
                                                        @if($related->action_type === 'CREATE') bg-success
                                                        @elseif($related->action_type === 'UPDATE') bg-warning
                                                        @elseif($related->action_type === 'DELETE') bg-danger
                                                        @else bg-info
                                                        @endif">
                                                        {{ $related->action_type }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($related->action_timestamp)->format('d M Y - H:i') }}
                                                </small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="row">
                @if($auditLog->old_data || $auditLog->new_data)
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">Data Changes</div>
                            </div>

                            <div class="card-body">

                                @if($auditLog->action_type === 'UPDATE' && $auditLog->old_data && $auditLog->new_data)
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="card custom card-bg-light border-danger mb-0">
                                                <div class="card-header">
                                                    <div class="card-title text-danger">
                                                        <i class="ti ti-circle-minus me-2"></i>
                                                        Old Data
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Field</th>
                                                                    <th scope="col">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($auditLog->old_data as $key => $value)
                                                                    <tr class="{{ isset($auditLog->new_data[$key]) && $auditLog->new_data[$key] != $value ? 'text-danger' : '' }}">
                                                                        <td>
                                                                            <strong>{{ $key }}</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card custom card-bg-light border-success mb-0">
                                                <div class="card-header">
                                                    <div class="card-title text-success">
                                                        <i class="ti ti-circle-plus me-2"></i>
                                                        New Data
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Field</th>
                                                                    <th scope="col">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($auditLog->new_data as $key => $value)
                                                                    <tr class="{{ isset($auditLog->old_data[$key]) && $auditLog->old_data[$key] != $value ? 'text-success' : '' }}">
                                                                        <td>
                                                                            <strong>{{ $key }}</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-info d-flex align-items-center gap-2">
                                                <strong>
                                                    <i class="ti ti-info-circle me-2"></i>
                                                    Changes Summary:
                                                </strong>

                                                @php
                                                    $changes = 0;
                                                    foreach($auditLog->new_data as $key => $value) {
                                                        if(isset($auditLog->old_data[$key]) && $auditLog->old_data[$key] != $value) {
                                                            $changes++;
                                                        }
                                                    }
                                                @endphp

                                                <span class="badge bg-warning text-dark">
                                                    {{ $changes }}
                                                </span> 
                                                field(s) modified
                                            </div>
                                        </div>
                                    </div>
                                @elseif($auditLog->action_type === 'CREATE' && $auditLog->new_data)
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card custom">
                                                <div class="card-header">
                                                    <div class="card-title text-success">
                                                        <i class="ti ti-circle-plus me-2"></i>
                                                        Created Data
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Field</th>
                                                                    <th scope="col">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($auditLog->new_data as $key => $value)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $key }}</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($auditLog->action_type === 'DELETE' && $auditLog->old_data)
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card custom">
                                                <div class="card-header">
                                                    <div class="card-title text-danger">
                                                        <i class="ti ti-circle-minus me-2"></i>
                                                        Deleted Data
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Field</th>
                                                                    <th scope="col">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($auditLog->old_data as $key => $value)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $key }}</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(in_array($auditLog->action_type, ['LOGIN', 'LOGOUT']) && $auditLog->new_data)
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="card custom">
                                                <div class="card-header">
                                                    <div class="card-title text-info">
                                                        <i class="ti ti-login me-2"></i>
                                                        Authentication
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table text-nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Field</th>
                                                                    <th scope="col">Value</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($auditLog->new_data as $key => $value)
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $key }}</strong>
                                                                        </td>
                                                                        <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header">
                                <div class="card-title">No Data Changes</div>
                            </div>

                            <div class="card-body text-center py-5">
                                <i class="ti ti-database-off text-muted display-1"></i>
                                <h5 class="text-muted mt-3">No Data Changes Recorded</h5>
                                <p class="text-muted mb-0">This audit entry does not contain detailed data changes</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($auditLog->old_data || $auditLog->new_data)
                    <div class="col-12">
                        <div class="card custom">
                            <div class="card-header justify-content-between align-items-center gap-2">
                                <div class="card-title">Raw JSON Data</div>
                                <button class="btn btn-sm btn-outline-secondary" 
                                    onclick="copyJSON()">
                                    <i class="ti ti-copy me-1"></i>
                                    Copy JSON
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <ul class="nav nav-tabs flex-column nav-style-5" role="tablist">
                                            @if($auditLog->old_data)
                                                <li class="nav-item">
                                                    <a
                                                        class="nav-link active"
                                                        data-bs-toggle="tab"
                                                        role="tab"
                                                        href="#oldJson">
                                                        Old Data
                                                    </a>
                                                </li>
                                            @endif

                                            @if($auditLog->new_data)
                                                <li class="nav-item">
                                                    <a
                                                        class="nav-link {{ !$auditLog->old_data ? 'active' : '' }}"
                                                        data-bs-toggle="tab"
                                                        role="tab"
                                                        href="#newJson">
                                                        New Data
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="col-lg-9">
                                        <div class="tab-content mt-2 mt-xl-0">

                                            @if($auditLog->old_data)
                                                <div class="tab-pane show active text-muted" id="oldJson" role="tabpanel">
                                                    <pre class="json-viewer mb-0" id="oldJsonData"><code>{{ json_encode($auditLog->old_data, JSON_PRETTY_PRINT) }}</code></pre>
                                                </div>
                                            @endif

                                            @if($auditLog->new_data)
                                                <div class="tab-pane text-muted {{ !$auditLog->old_data ? 'show active' : '' }}" id="newJson" role="tabpanel">
                                                    <pre class="json-viewer mb-0" id="newJsonData"><code>{{ json_encode($auditLog->new_data, JSON_PRETTY_PRINT) }}</code></pre>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')

    <!-- Sweetalerts JS -->
    <script src="{{ asset('assets/plugin/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        function copyJSON() {
            const activeTab = document.querySelector('.tab-pane.active pre code');

            if (activeTab) {
                navigator.clipboard.writeText(activeTab.textContent).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'JSON copied!',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false,    
                        allowEscapeKey: false,         
                        allowEnterKey: false           
                    });
                });
            }
        }
    </script>

@endsection