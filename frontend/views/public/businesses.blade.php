@extends('layouts.app')

@section('title', 'Business Directory – Business Connect')

@section('content')
<div class="bg-primary-subtle py-4 border-bottom">
    <div class="container">
        <h2 class="fw-bold mb-1">Business Directory</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Businesses</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel me-2 text-primary"></i>Filter Search</h5>
                <form action="{{ route('businesses.index') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Keywords</label>
                        <input type="text" name="q" class="form-control rounded-3" value="{{ request('q') }}" placeholder="Search by name, product...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category</label>
                        <select name="category" class="form-select rounded-3">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">City / Location</label>
                        <input type="text" name="city" class="form-control rounded-3" value="{{ request('city') }}" placeholder="e.g. San Francisco, Austin">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sort By</label>
                        <select name="sort" class="form-select rounded-3">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Listed</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold"><i class="bi bi-search me-1"></i> Apply Filters</button>
                </form>
            </div>
        </div>

        <!-- Business Listings Grid -->
        <div class="col-lg-9">
            <div class="row g-4">
                @forelse($businesses as $biz)
                    <div class="col-md-6">
                        <div class="glass-card h-100 p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 60px; height: 60px;">
                                    {{ substr($biz->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-truncate" style="max-width: 220px;">{{ $biz->name }}</h5>
                                    <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ $biz->city ?? 'Online' }}, {{ $biz->state }}</span>
                                </div>
                            </div>
                            <p class="text-muted small flex-grow-1">{{ Str::limit($biz->description, 120) }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="fw-bold text-dark me-1">{{ $biz->averageRating() }}</span>
                                    <span class="text-muted">({{ $biz->reviews->count() }} reviews)</span>
                                </div>
                                <a href="{{ route('businesses.show', $biz->id) }}" class="btn btn-primary rounded-pill px-3 btn-sm">View Business</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center">
                        <i class="bi bi-shop-window display-1 text-muted"></i>
                        <h4 class="mt-3 fw-bold">No Businesses Found</h4>
                        <p class="text-muted">Try adjusting your filters or search keywords.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $businesses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
