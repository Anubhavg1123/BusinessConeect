@extends('layouts.app')

@section('title', 'Platform Reports & Analytics – Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Admin Panel</h6>
                <div class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('admin.businesses') }}" class="nav-link"><i class="bi bi-building me-2"></i> Businesses</a>
                    <a href="{{ route('admin.users') }}" class="nav-link"><i class="bi bi-people me-2"></i> Users</a>
                    <a href="{{ route('admin.categories') }}" class="nav-link"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link active"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h3 class="fw-bold mb-4">Platform Data Reports</h3>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="glass-card p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-excel text-success me-2"></i>Export Businesses Data</h5>
                        <p class="text-muted small">Download complete list of approved and pending business records in Excel / CSV format.</p>
                        <a href="{{ route('admin.reports.export', ['type' => 'businesses']) }}" class="btn btn-success rounded-pill px-4"><i class="bi bi-download me-1"></i> Export Businesses CSV</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="glass-card p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-person text-primary me-2"></i>Export Users Data</h5>
                        <p class="text-muted small">Download user account details, roles, contact numbers, and status logs.</p>
                        <a href="{{ route('admin.reports.export', ['type' => 'users']) }}" class="btn btn-primary rounded-pill px-4"><i class="bi bi-download me-1"></i> Export Users CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
