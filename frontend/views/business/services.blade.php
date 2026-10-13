@extends('layouts.app')

@section('title', 'Manage Services – Business Owner')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Business Portal</h6>
                <div class="nav flex-column">
                    <a href="{{ route('business.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('business.profile.edit') }}" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Business Profile</a>
                    <a href="{{ route('business.products') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                    <a href="{{ route('business.services') }}" class="nav-link active"><i class="bi bi-tools me-2"></i> Services</a>
                    <a href="{{ route('business.inquiries') }}" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Offered Services</h3>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="bi bi-plus-lg me-1"></i> Add Service</button>
            </div>
            
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Service Name</th>
                                <th>Price</th>
                                <th>Duration</th>
                                <th>Availability</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $s)
                                <tr>
                                    <td class="fw-semibold">{{ $s->name }}</td>
                                    <td class="text-success fw-bold">${{ number_format($s->price, 2) }}</td>
                                    <td>{{ $s->duration }}</td>
                                    <td><span class="badge bg-success-subtle text-success">{{ $s->availability }}</span></td>
                                    <td>
                                        <form action="{{ route('business.services.destroy', $s->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger confirm-delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No services cataloged yet. Click Add Service to start.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Service -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-card p-3 border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('business.services.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Service Name</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Cloud Infrastructure Audit">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" rows="3" class="form-control rounded-3" required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Duration</label>
                            <input type="text" name="duration" class="form-control rounded-3" placeholder="e.g. 1 Hour, 2 Days">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
