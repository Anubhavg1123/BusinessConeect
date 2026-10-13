@extends('layouts.app')

@section('title', 'Edit Business Profile – Business Owner')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Business Portal</h6>
                <div class="nav flex-column">
                    <a href="{{ route('business.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('business.profile.edit') }}" class="nav-link active"><i class="bi bi-person-lines-fill me-2"></i> Business Profile</a>
                    <a href="{{ route('business.products') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                    <a href="{{ route('business.services') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                    <a href="{{ route('business.inquiries') }}" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h3 class="fw-bold mb-4">Manage Business Profile</h3>
            
            <form action="{{ route('business.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Basic Business Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Business Name</label>
                            <input type="text" name="name" class="form-control rounded-3" value="{{ $business->name ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Owner Name</label>
                            <input type="text" name="owner_name" class="form-control rounded-3" value="{{ $business->owner_name ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Category</label>
                            <select name="category_id" class="form-select rounded-3" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ ($business->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">GST Number</label>
                            <input type="text" name="gst_number" class="form-control rounded-3" value="{{ $business->gst_number ?? '' }}" placeholder="27ABCDE1234F1Z5">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Business Description</label>
                            <textarea name="description" rows="3" class="form-control rounded-3" required>{{ $business->description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Contact & Social Links</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3" value="{{ $business->email ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control rounded-3" value="{{ $business->phone ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control rounded-3" value="{{ $business->whatsapp ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Website URL</label>
                            <input type="url" name="website" class="form-control rounded-3" value="{{ $business->website ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Google Maps Embed URL</label>
                            <input type="text" name="google_maps_embed" class="form-control rounded-3" value="{{ $business->google_maps_embed ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Location & Timings</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Opening Time</label>
                            <input type="time" name="opening_time" class="form-control rounded-3" value="{{ $business->opening_time ?? '09:00' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Closing Time</label>
                            <input type="time" name="closing_time" class="form-control rounded-3" value="{{ $business->closing_time ?? '18:00' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">State</label>
                            <input type="text" name="state" class="form-control rounded-3" value="{{ $business->state ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">City</label>
                            <input type="text" name="city" class="form-control rounded-3" value="{{ $business->city ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Address</label>
                            <input type="text" name="address" class="form-control rounded-3" value="{{ $business->address ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">PIN Code</label>
                            <input type="text" name="pin_code" class="form-control rounded-3" value="{{ $business->pin_code ?? '' }}" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Save Profile Details</button>
            </form>
        </div>
    </div>
</div>
@endsection
