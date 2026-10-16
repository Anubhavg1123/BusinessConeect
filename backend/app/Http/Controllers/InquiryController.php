<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'preferred_contact_time' => 'nullable|string',
        ]);

        if (Auth::check()) {
            $validated['customer_id'] = Auth::id();
        }

        $inquiry = Inquiry::create($validated);

        // Notify Business Owner
        $business = $inquiry->business;
        if ($business && $business->user_id) {
            Notification::create([
                'user_id' => $business->user_id,
                'title' => 'New Customer Inquiry',
                'message' => "You received a new inquiry from {$inquiry->name} regarding '{$inquiry->subject}'.",
                'type' => 'inquiry',
                'link' => route('business.inquiries'),
            ]);
        }

        return back()->with('success', 'Your inquiry has been sent successfully! The business owner will get back to you shortly.');
    }

    public function reply(Request $request, $id)
    {
        $business = Auth::user()->business;
        $inquiry = Inquiry::findOrFail($id);

        if (!$business || $inquiry->business_id !== $business->id) {
            abort(403, 'Unauthorized action: You do not have permission to reply to this inquiry.');
        }

        $request->validate(['reply_message' => 'required|string']);

        $inquiry->update([
            'reply_message' => $request->reply_message,
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        if ($inquiry->customer_id) {
            Notification::create([
                'user_id' => $inquiry->customer_id,
                'title' => 'Inquiry Replied',
                'message' => "{$inquiry->business->name} replied to your inquiry: '{$inquiry->subject}'.",
                'type' => 'inquiry',
                'link' => route('customer.inquiries'),
            ]);
        }

        return back()->with('success', 'Reply sent successfully!');
    }
}
