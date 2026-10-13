@extends('layouts.app')

@section('title', 'My Favorites – Business Connect')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Saved Favorite Businesses</h3>

    <div class="row g-4">
        @forelse($favorites as $fav)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card p-4 h-100 d-flex flex-column">
                    <h5 class="fw-bold mb-1">{{ $fav->business->name }}</h5>
                    <p class="text-muted small">{{ Str::limit($fav->business->description, 90) }}</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                        <a href="{{ route('businesses.show', $fav->business->id) }}" class="btn btn-primary rounded-pill btn-sm">View Profile</a>
                        <form action="{{ route('customer.favorite.toggle', $fav->business->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">No favorite businesses saved yet.</div>
        @endforelse
    </div>
</div>
@endsection
