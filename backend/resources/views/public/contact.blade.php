@extends('layouts.app')

@section('title', 'Contact Support – Business Connect')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    <div class="glass-card p-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold mb-2">Get in Touch</h2>
            <p class="text-muted">Have a question or need platform support? Send us a message.</p>
        </div>

        <form action="{{ route('contact.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Your Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" required placeholder="John Doe">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3 py-2 @error('email') is-invalid @enderror" required placeholder="john@example.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Phone Number (Optional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control rounded-3 py-2 @error('phone') is-invalid @enderror" placeholder="+1 (555) 000-0000">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="form-control rounded-3 py-2 @error('subject') is-invalid @enderror" required placeholder="Inquiry about platform services">
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Message</label>
                    <textarea name="message" rows="4" class="form-control rounded-3 @error('message') is-invalid @enderror" required placeholder="Type your message here...">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Send Message</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
