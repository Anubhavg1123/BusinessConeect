@extends('layouts.app')

@section('title', 'Manage Products – Business Owner')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Business Portal</h6>
                <div class="nav flex-column">
                    <a href="{{ route('business.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('business.profile.edit') }}" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Business Profile</a>
                    <a href="{{ route('business.products') }}" class="nav-link active"><i class="bi bi-box-seam me-2"></i> Products</a>
                    <a href="{{ route('business.services') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                    <a href="{{ route('business.inquiries') }}" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Products Catalog</h3>
                <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="bi bi-plus-lg me-1"></i> Add Product</button>
            </div>
            
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Stock</th>
                                <th>Availability</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $p)
                                <tr>
                                    <td class="fw-semibold">{{ $p->name }}</td>
                                    <td class="text-primary fw-bold">${{ number_format($p->price, 2) }}</td>
                                    <td>{{ $p->discount }}%</td>
                                    <td>{{ $p->stock }}</td>
                                    <td><span class="badge bg-success-subtle text-success">{{ $p->availability }}</span></td>
                                    <td><span class="badge bg-primary">{{ $p->status }}</span></td>
                                    <td>
                                        <form action="{{ route('business.products.destroy', $p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger confirm-delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No products cataloged yet. Click Add Product to start.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Product -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content glass-card p-3 border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('business.products.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Product Name</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="e.g. Enterprise CRM License">
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
                            <label class="form-label small fw-semibold">Discount (%)</label>
                            <input type="number" step="0.01" name="discount" value="0" class="form-control rounded-3">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Stock Quantity</label>
                            <input type="number" name="stock" value="10" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Availability</label>
                            <select name="availability" class="form-select rounded-3">
                                <option value="in_stock">In Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="pre_order">Pre-Order</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
