@extends('layouts.app', [
    'title' => 'Dashboard'
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
                        <a href="javascript:void(0);">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Admin</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header -->

    <!-- Container -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        {{-- <i class="bi bi-info-circle"></i> Selamat Datang, {{ auth()->user()->full_name }}! --}}
                    </h5>
                </div>
                <div class="card-body">
                    {{-- <p>Anda login sebagai <strong>{{ auth()->user()->role->role_name }}</strong>.</p> --}}
                    <p>Anda memiliki akses penuh ke semua module sistem ERP Food & Beverage.</p>
                    
                    <hr>
                    
                    <h6>Quick Links:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="bi bi-people"></i> Manajemen User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-success w-100 mb-2">
                                <i class="bi bi-building"></i> HRM Module
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-info w-100 mb-2">
                                <i class="bi bi-box-seam"></i> Inventory Module
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-warning w-100 mb-2">
                                <i class="bi bi-gear-fill"></i> Production Module
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container -->

@endsection

@section('scripts')
@endsection