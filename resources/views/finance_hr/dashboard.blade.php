@extends('layouts.app')

@section('title', 'Dashboard Finance & HR')
@section('page-title', 'Dashboard Finance & HR')

@section('sidebar')
<li class="nav-item">
    <a class="nav-link active" href="{{ route('finance_hr.dashboard') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-building"></i> HRM
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-cash-stack"></i> Finance
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-cart"></i> Purchase (View Only)
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-people"></i> Customers
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-megaphone"></i> Announcements
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="bi bi-graph-up"></i> Reports
    </a>
</li>
@endsection

@section('content')
<div class="alert alert-success">
    <i class="bi bi-info-circle-fill"></i>
    <strong>Selamat Datang, {{ auth()->user()->full_name }}!</strong><br>
    Anda login sebagai <strong>Finance & HR Staff</strong>. Anda memiliki akses ke modul Finance, HRM, dan Administrative.
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-people text-info" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Employees</h5>
                <h2 class="text-info">{{ \App\Models\HRM\Employee::where('employment_status', '!=', 'Resigned')->count() }}</h2>
                <p class="text-muted">Active employees</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-cash text-success" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Payroll</h5>
                <h2 class="text-success">0</h2>
                <p class="text-muted">Pending payroll</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-receipt text-warning" style="font-size: 3rem;"></i>
                <h5 class="mt-3">AP Unpaid</h5>
                <h2 class="text-warning">0</h2>
                <p class="text-muted">Accounts payable</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-wallet2 text-danger" style="font-size: 3rem;"></i>
                <h5 class="mt-3">AR Unpaid</h5>
                <h2 class="text-danger">0</h2>
                <p class="text-muted">Accounts receivable</p>
            </div>
        </div>
    </div>
</div>
@endsection