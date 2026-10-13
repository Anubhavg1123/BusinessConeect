@extends('layouts.app')

@section('title', 'Sign In – Business Connect')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="glass-card p-5">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex p-3 mb-2">
                        <i class="bi bi-box-arrow-in-right fs-3"></i>
                    </div>
                    <h3 class="fw-bold mb-1">Welcome Back</h3>
                    <p class="text-muted small">Sign in to your Business Connect account</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3 py-2" required placeholder="admin@businessconnect.com">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold mb-0">Password</label>
                            <a href="#" class="small text-decoration-none">Forgot Password?</a>
                        </div>
                        <input type="password" name="password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="rememberCheck">
                        <label class="form-check-label small" for="rememberCheck">Remember me on this device</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-semibold mb-3">Sign In</button>
                </form>

                <div class="text-center border-top pt-3">
                    <small class="text-muted">Don't have an account?</small><br>
                    <a href="{{ route('register.business') }}" class="small fw-bold text-primary me-2">Register Business</a> | 
                    <a href="{{ route('register.customer') }}" class="small fw-bold text-secondary ms-2">Register Customer</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
