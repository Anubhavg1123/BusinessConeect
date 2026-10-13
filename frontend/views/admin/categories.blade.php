@extends('layouts.app')

@section('title', 'Manage Categories – Admin Panel')

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
                    <a href="{{ route('admin.categories') }}" class="nav-link active"><i class="bi bi-tags me-2"></i> Categories</a>
                    <a href="{{ route('admin.reports') }}" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Business Categories</h3>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="bi bi-plus-lg me-1"></i> Add Category</button>
            </div>
            
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Icon</th>
                                <th>Category Name</th>
                                <th>Slug</th>
                                <th>Listed Businesses</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $cat)
                                <tr>
                                    <td><i class="bi {{ $cat->icon }} fs-4 text-primary"></i></td>
                                    <td class="fw-semibold">{{ $cat->name }}</td>
                                    <td><code>{{ $cat->slug }}</code></td>
                                    <td><span class="badge bg-secondary rounded-pill">{{ $cat->businesses_count }}</span></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-card p-3 border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category Name</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Healthcare & Medical">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Icon Class (Bootstrap Icons)</label>
                        <input type="text" name="icon" class="form-control rounded-3" placeholder="bi-heart-pulse">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="2" class="form-control rounded-3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
