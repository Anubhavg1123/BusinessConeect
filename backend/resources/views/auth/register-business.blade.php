@extends('layouts.app')

@section('title', 'Register Business Owner – Business Connect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="glass-card p-5">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">Business Owner Account</span>
                    <h3 class="fw-bold mb-1">Promote Your Enterprise</h3>
                    <p class="text-muted small">Create a Business Owner account to list products & services</p>
                </div>

                <form action="{{ route('register.business') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" required placeholder="Alex Carter" autocomplete="name" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3 py-2 @error('email') is-invalid @enderror" required placeholder="alex@nexustech.com" autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control rounded-3 py-2 @error('phone') is-invalid @enderror" required placeholder="+1 (555) 014-7890" autocomplete="tel">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control rounded-3 py-2 @error('password') is-invalid @enderror" required placeholder="••••••••" autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimum 8 characters.</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control rounded-3 py-2" required placeholder="••••••••" autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-semibold mb-3">Create Business Account</button>
                </form>

                <div class="text-center border-top pt-3">
                    <small class="text-muted">Already registered?</small>
                    <a href="{{ route('login') }}" class="small fw-bold text-primary ms-1">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
