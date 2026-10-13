@extends('layouts.app')

@section('title', 'Manage Businesses – Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Admin Panel</h6>
                <div class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('admin.businesses') }}" class="nav-link active"><i class="bi bi-building me-2"></i> Businesses</a>
                    <a href="{{ route('admin.users') }}" class="nav-link"><i class="bi bi-people me-2"></i> Users</a>
                    <a href="{{ route('admin.categories') }}" class="nav-link"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h3 class="fw-bold mb-4">Manage Business Listings</h3>
            
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Business Name</th>
                                <th>Owner</th>
                                <th>Email</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($businesses as $b)
                                <tr>
                                    <td class="fw-semibold">{{ $b->name }}</td>
                                    <td>{{ $b->owner_name }}</td>
                                    <td>{{ $b->email }}</td>
                                    <td>{{ $b->city ?? 'N/A' }}</td>
                                    <td>
                                        @if($b->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($b->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.businesses.approve', $b->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success me-1">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.businesses.reject', $b->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning me-1">Reject</button>
                                        </form>
                                        <form action="{{ route('admin.businesses.delete', $b->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger confirm-delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $businesses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
