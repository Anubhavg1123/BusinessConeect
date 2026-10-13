<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('reviews', 'public');
            }
        }

        $review = Review::create([
            'customer_id' => Auth::id(),
            'business_id' => $validated['business_id'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'images' => $imagePaths,
            'status' => 'approved',
        ]);

        // Notify Business Owner
        $business = $review->business;
        if ($business && $business->user_id) {
            Notification::create([
                'user_id' => $business->user_id,
                'title' => 'New Review Received',
                'message' => "A customer rated your business {$review->rating} stars.",
                'type' => 'review',
            ]);
        }

        return back()->with('success', 'Thank you for your review!');
    }

    public function like($id)
    {
        $review = Review::findOrFail($id);
        $review->increment('likes_count');
        return back()->with('success', 'Review liked!');
    }

    public function report($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['reported' => true]);
        return back()->with('info', 'Review has been reported to administrators.');
    }
}
