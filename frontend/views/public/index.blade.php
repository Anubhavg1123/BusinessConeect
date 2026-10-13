@extends('layouts.app')

@section('title', 'Business Connect – Business Management & Networking Platform')

@section('content')
<!-- Hero Section -->
<section class="hero-wrapper py-5 text-center text-lg-start position-relative">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 animate-fade-in">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-semibold mb-3">
                    <i class="bi bi-stars me-1 text-warning"></i> Empowering Modern Enterprises
                </span>
                <h1 class="display-3 text-white fw-bold mb-3">Connect, Grow & Scale Your Business Online</h1>
                <p class="lead text-white-50 mb-4">Discover verified local and global businesses, explore premium products & services, send direct inquiries, and network seamlessly on Business Connect.</p>
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                    <a href="{{ route('businesses.index') }}" class="btn btn-secondary btn-lg rounded-pill px-4 shadow"><i class="bi bi-search me-2"></i> Explore Businesses</a>
                    <a href="{{ route('register.business') }}" class="btn btn-outline-light btn-lg rounded-pill px-4"><i class="bi bi-plus-circle me-2"></i> Register Business</a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="glass-card p-4 text-dark text-start shadow-lg">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3">
                            <i class="bi bi-search fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Quick Directory Search</h5>
                            <small class="text-muted">Find verified partners instantly</small>
                        </div>
                    </div>
                    <form action="{{ route('businesses.index') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Keywords</label>
                            <input type="text" name="q" class="form-control rounded-3" placeholder="e.g. NexusTech, Software, Hemp">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Category</label>
                            <select name="category" class="form-select rounded-3">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold"><i class="bi bi-funnel me-1"></i> Filter Directory</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Counter -->
<section class="py-5 bg-body-tertiary">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="glass-card p-4">
                    <h2 class="display-5 text-primary fw-bold mb-0">{{ number_format($stats['businesses'] ?? 150) }}+</h2>
                    <p class="text-muted mb-0 fw-semibold">Approved Businesses</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-4">
                    <h2 class="display-5 text-secondary fw-bold mb-0">{{ number_format($stats['products'] ?? 450) }}+</h2>
                    <p class="text-muted mb-0 fw-semibold">Listed Products</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-4">
                    <h2 class="display-5 text-success fw-bold mb-0">{{ number_format($stats['services'] ?? 280) }}+</h2>
                    <p class="text-muted mb-0 fw-semibold">Professional Services</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="glass-card p-4">
                    <h2 class="display-5 text-warning fw-bold mb-0">{{ number_format($stats['reviews'] ?? 1200) }}+</h2>
                    <p class="text-muted mb-0 fw-semibold">Verified Reviews</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Business Categories -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-category mb-2">Browse by Sector</span>
            <h2 class="display-6 fw-bold">Explore Business Categories</h2>
            <p class="text-muted">Find specialists and companies tailored to your industry requirements</p>
        </div>
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-4 col-lg-2-4">
                    <a href="{{ route('businesses.index', ['category' => $category->id]) }}" class="text-decoration-none text-dark">
                        <div class="glass-card p-4 text-center h-100">
                            <div class="bg-primary-subtle text-primary rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi {{ $category->icon }} fs-3"></i>
                            </div>
                            <h6 class="fw-bold mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->businesses_count ?? 12 }} Businesses</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Businesses -->
<section class="py-5 bg-body-tertiary">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div>
                <span class="badge badge-category mb-2">Verified Partners</span>
                <h2 class="display-6 fw-bold mb-0">Featured Businesses</h2>
            </div>
            <a href="{{ route('businesses.index') }}" class="btn btn-outline-primary rounded-pill mt-3 mt-md-0">View All Businesses <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @forelse($featuredBusinesses as $biz)
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card h-100 p-4 d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 55px; height: 55px;">
                                {{ substr($biz->name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-truncate" style="max-width: 200px;">{{ $biz->name }}</h5>
                                <span class="badge bg-secondary-subtle text-secondary small"><i class="bi bi-tag me-1"></i> {{ $biz->category->name ?? 'Business' }}</span>
                            </div>
                        </div>
                        <p class="text-muted small flex-grow-1">{{ Str::limit($biz->description, 110) }}</p>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="rating-stars small">
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="fw-bold text-dark me-1">{{ $biz->averageRating() }}</span>
                                <span class="text-muted">({{ $biz->reviews->count() }})</span>
                            </div>
                            <a href="{{ route('businesses.show', $biz->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">View Profile</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No businesses listed yet. Be the first to register!</p>
                    <a href="{{ route('register.business') }}" class="btn btn-primary rounded-pill">Register Your Business</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="py-5">
    <div class="container" style="max-width: 800px;">
        <div class="text-center mb-5">
            <span class="badge badge-category mb-2">Help Center</span>
            <h2 class="display-6 fw-bold">Frequently Asked Questions</h2>
        </div>
        <div class="accordion accordion-flush glass-card rounded-4 p-3 shadow-sm" id="faqAccordion">
            <div class="accordion-item bg-transparent">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        How does Business Connect verify business profiles?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">
                        Administrators inspect provided GST details, official business contact information, and operating licenses before granting approved status.
                    </div>
                </div>
            </div>
            <div class="accordion-item bg-transparent">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Can customers contact business owners directly?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted">
                        Yes! Registered and guest users can submit direct inquiries with their preferred contact times. Business owners get notified in real time.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
