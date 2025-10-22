@extends('layouts.app')

@section('title', 'Dashboard Operator')
@section('page-title', 'Dashboard Operator')

@section('sidebar')
<li class="nav-item">
    <a class="nav-link active" href="{{ route('operator.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
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
        <i class="bi bi-clipboard-check"></i> Quality Control
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-tools"></i> Maintenance
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-truck"></i> Delivery (View Only)
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-graph-up"></i> Reports
    </a>
</li>
@endsection

@section('content')
<div class="alert alert-info">
    <i class="bi bi-info-circle-fill"></i>
    <strong>Selamat Datang, {{ auth()->user()->full_name }}!</strong><br>
    Anda login sebagai <strong>Operator</strong>. Anda memiliki akses ke modul Production, Inventory, QC, dan Maintenance.
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-gear-fill text-primary" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Work Orders</h5>
                <h2 class="text-primary">0</h2>
                <p class="text-muted">Active work orders</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-box-seam text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Inventory</h5>
                <h2 class="text-success">0</h2>
                <p class="text-muted">Low stock alerts</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-clipboard-check text-warning" style="font-size: 3rem;"></i>
                <h5 class="mt-3">QC Pending</h5>
                <h2 class="text-warning">0</h2>
                <p class="text-muted">Awaiting inspection</p>
            </div>
        </div>
    </div>
</div>
@endsection