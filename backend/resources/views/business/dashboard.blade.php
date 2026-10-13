@extends('layouts.app')

@section('title', 'Business Owner Dashboard – Business Connect')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Business Portal</h6>
                <div class="nav flex-column">
                    <a href="{{ route('business.dashboard') }}" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('business.profile.edit') }}" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Business Profile</a>
                    <a href="{{ route('business.products') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                    <a href="{{ route('business.services') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                    <a href="{{ route('business.inquiries') }}" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Welcome, {{ $business->name ?? Auth::user()->name }}</h3>
                    <p class="text-muted small mb-0">Track customer engagement, manage products, and reply to inquiries</p>
                </div>
                <a href="{{ route('business.profile.edit') }}" class="btn btn-primary rounded-pill px-4"><i class="bi bi-pencil me-1"></i> Edit Profile</a>
            </div>

            <!-- Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-primary text-white rounded-3 p-3 me-3"><i class="bi bi-eye fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['views'] ?? 1420 }}</h4>
                            <small class="text-muted">Total Page Views</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-success text-white rounded-3 p-3 me-3"><i class="bi bi-chat-left-text fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['inquiries_count'] ?? 18 }}</h4>
                            <small class="text-muted">Customer Inquiries</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-warning text-white rounded-3 p-3 me-3"><i class="bi bi-star fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['avg_rating'] ?? '4.9' }}</h4>
                            <small class="text-muted">Average Rating</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-3 d-flex align-items-center">
                        <div class="bg-info text-white rounded-3 p-3 me-3"><i class="bi bi-box-seam fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['products_count'] ?? 5 }}</h4>
                            <small class="text-muted">Active Products</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Inquiries Table -->
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3">Recent Customer Inquiries</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Subject</th>
                                <th>Preferred Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInquiries as $inq)
                                <tr>
                                    <td class="fw-semibold">{{ $inq->name }}</td>
                                    <td>{{ $inq->subject }}</td>
                                    <td>{{ $inq->preferred_contact_time ?? 'Anytime' }}</td>
                                    <td>
                                        @if($inq->status === 'replied')
                                            <span class="badge bg-success">Replied</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending Reply</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('business.inquiries') }}" class="btn btn-sm btn-outline-primary rounded-pill">View & Reply</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No inquiries received yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
