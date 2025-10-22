@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('sidebar')
<li class="nav-item">
    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.users.index') }}">
        <i class="bi bi-people"></i> Manajemen User
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-building"></i> HRM
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-box-seam"></i> Inventory
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-gear-fill"></i> Produksi
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-cash-stack"></i> Finance
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-graph-up"></i> Reports
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-gear"></i> Settings
    </a>
</li>
@endsection

@section('content')

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
@endsection