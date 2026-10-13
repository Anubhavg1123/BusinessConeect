@extends('layouts.app')

@section('title', 'User Management – Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Admin Panel</h6>
                <div class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('admin.businesses') }}" class="nav-link"><i class="bi bi-building me-2"></i> Businesses</a>
                    <a href="{{ route('admin.users') }}" class="nav-link active"><i class="bi bi-people me-2"></i> Users</a>
                    <a href="{{ route('admin.categories') }}" class="nav-link"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h3 class="fw-bold mb-4">User Accounts Management</h3>
            
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td class="fw-semibold">{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ $u->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">{{ $u->role->label ?? 'User' }}</span>
                                    </td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td>
                                        <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="d-inline">
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
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
