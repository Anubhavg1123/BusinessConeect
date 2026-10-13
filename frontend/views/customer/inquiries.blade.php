@extends('layouts.app')

@section('title', 'Inquiry History – Business Connect')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Inquiry History & Responses</h3>

    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Business</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Sent Date</th>
                        <th>Reply</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inquiries as $inq)
                        <tr>
                            <td class="fw-semibold">{{ $inq->business->name }}</td>
                            <td>{{ $inq->subject }}</td>
                            <td><span class="badge {{ $inq->status === 'replied' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $inq->status }}</span></td>
                            <td>{{ $inq->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($inq->reply_message)
                                    <div class="p-2 bg-success-subtle rounded-3 small text-dark">{{ $inq->reply_message }}</div>
                                @else
                                    <span class="text-muted small">Awaiting owner response</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No inquiries submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
