@extends('layouts.app')

@section('title', 'Manage Inquiries – Business Owner')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="sidebar p-3 glass-card rounded-4">
                <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Business Portal</h6>
                <div class="nav flex-column">
                    <a href="{{ route('business.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="{{ route('business.profile.edit') }}" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Business Profile</a>
                    <a href="{{ route('business.products') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                    <a href="{{ route('business.services') }}" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                    <a href="{{ route('business.inquiries') }}" class="nav-link active"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h3 class="fw-bold mb-4">Customer Inquiries Inbox</h3>
            
            <div class="glass-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inq)
                                <tr>
                                    <td class="fw-semibold">{{ $inq->name }}</td>
                                    <td>{{ $inq->email }}</td>
                                    <td>{{ $inq->phone }}</td>
                                    <td>{{ $inq->subject }}</td>
                                    <td>
                                        @if($inq->status === 'replied')
                                            <span class="badge bg-success">Replied</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#replyModal{{ $inq->id }}">View & Reply</button>

                                        <!-- Reply Modal -->
                                        <div class="modal fade" id="replyModal{{ $inq->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content glass-card p-3 border-0">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-bold">Inquiry from {{ $inq->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('business.inquiries.reply', $inq->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p class="small text-muted mb-1"><strong>Subject:</strong> {{ $inq->subject }}</p>
                                                            <div class="p-3 bg-body-tertiary rounded-3 mb-3 small">{{ $inq->message }}</div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold">Your Response</label>
                                                                <textarea name="reply_message" rows="3" class="form-control rounded-3" placeholder="Type your reply to the customer..." required>{{ $inq->reply_message }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-success rounded-pill px-4">Send Reply</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted">No customer inquiries received yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $inquiries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
