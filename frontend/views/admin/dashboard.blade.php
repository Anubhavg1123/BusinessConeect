@extends('layouts.app')

@section('title', 'Admin Analytics Dashboard – Business Connect')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Admin Panel</h6>
                <div class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('admin.businesses') }}" class="nav-link"><i class="bi bi-building me-2"></i> Businesses</a>
                    <a href="{{ route('admin.users') }}" class="nav-link"><i class="bi bi-people me-2"></i> Users</a>
                    <a href="{{ route('admin.categories') }}" class="nav-link"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">System Analytics Dashboard</h3>
                    <p class="text-muted small mb-0">Overview of platform metrics, growth, and approval requests</p>
                </div>
                <a href="{{ route('admin.reports.export') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm"><i class="bi bi-download me-1"></i> Export Platform Report</a>
            </div>

            <!-- Stats Cards Grid -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-primary text-white rounded-3 p-3 me-3"><i class="bi bi-building fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['total_businesses'] ?? 42 }}</h4>
                            <small class="text-muted">Total Businesses</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-success text-white rounded-3 p-3 me-3"><i class="bi bi-people fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['total_customers'] ?? 158 }}</h4>
                            <small class="text-muted">Total Customers</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-warning text-white rounded-3 p-3 me-3"><i class="bi bi-clock-history fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0 text-warning">{{ $stats['pending_approvals'] ?? 5 }}</h4>
                            <small class="text-muted">Pending Approvals</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-info text-white rounded-3 p-3 me-3"><i class="bi bi-box-seam fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['total_products'] ?? 120 }}</h4>
                            <small class="text-muted">Listed Products</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics Charts -->
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold mb-3">Business Growth & Registrations</h5>
                        <canvas id="growthChart" height="110"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card p-4 h-100">
                        <h5 class="fw-bold mb-3">Category Distribution</h5>
                        <canvas id="categoryDistributionChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Approvals Table -->
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3">Recent Business Registration Requests</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Business Name</th>
                                <th>Owner</th>
                                <th>Category</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRegistrations as $user)
                                @if($user->business)
                                    <tr>
                                        <td class="fw-semibold">{{ $user->business->name }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->business->category->name ?? 'General' }}</td>
                                        <td>{{ $user->business->city ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->business->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($user->business->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending Approval</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.businesses.approve', $user->business->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success rounded-circle me-1" title="Approve"><i class="bi bi-check"></i></button>
                                            </form>
                                            <form action="{{ route('admin.businesses.reject', $user->business->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-danger rounded-circle" title="Reject"><i class="bi bi-x"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No pending business approvals.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
