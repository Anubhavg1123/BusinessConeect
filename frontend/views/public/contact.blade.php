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
                    <input type="text" name="name" class="form-control rounded-3 py-2" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control rounded-3 py-2" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Phone Number</label>
                    <input type="text" name="phone" class="form-control rounded-3 py-2">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Subject</label>
                    <input type="text" name="subject" class="form-control rounded-3 py-2" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Message</label>
                    <textarea name="message" rows="4" class="form-control rounded-3" required></textarea>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Send Message</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
