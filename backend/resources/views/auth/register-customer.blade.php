@extends('layouts.app')

@section('title', 'Register Customer Account – Business Connect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="glass-card p-5">
                <div class="text-center mb-4">
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 mb-2">Customer Account</span>
                    <h3 class="fw-bold mb-1">Join Business Connect</h3>
                    <p class="text-muted small">Connect with verified businesses and send direct inquiries</p>
                </div>

                <form action="{{ route('register.customer') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2" required placeholder="David Miller">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3 py-2" required placeholder="david@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control rounded-3 py-2" required placeholder="+1 (555) 019-2834">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                    </div>
                    <button type="submit" class="btn btn-secondary text-white w-100 rounded-pill py-2.5 fw-semibold mb-3">Create Customer Account</button>
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
