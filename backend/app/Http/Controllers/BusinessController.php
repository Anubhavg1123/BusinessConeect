<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $business = $user->business;

        if (!$business) {
            return redirect()->route('business.profile.edit')->with('info', 'Please create your business profile.');
        }

        $stats = [
            'views' => $business->views_count,
            'products_count' => $business->products()->count(),
            'services_count' => $business->services()->count(),
            'reviews_count' => $business->reviews()->count(),
            'inquiries_count' => $business->inquiries()->count(),
            'avg_rating' => $business->averageRating(),
        ];

        $recentInquiries = $business->inquiries()->latest()->take(5)->get();
        $recentReviews = $business->reviews()->with('customer')->latest()->take(5)->get();

        return view('business.dashboard', compact('business', 'stats', 'recentInquiries', 'recentReviews'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $business = $user->business ?? new Business(['user_id' => $user->id, 'email' => $user->email, 'phone' => $user->phone]);
        $categories = BusinessCategory::all();

        return view('business.profile-edit', compact('business', 'categories'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $business = $user->business;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'category_id' => 'required|exists:business_categories,id',
            'description' => 'required|string',
            'gst_number' => 'nullable|string|max:50',
            'website' => 'nullable|url',
            'email' => 'required|email',
            'phone' => 'required|string',
            'whatsapp' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'location' => 'nullable|string',
            'google_maps_embed' => 'nullable|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'pin_code' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('businesses/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('businesses/covers', 'public');
        }

        if ($business) {
            $business->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            $validated['status'] = 'pending';
            Business::create($validated);
        }

        return redirect()->route('business.dashboard')->with('success', 'Business profile updated successfully!');
    }
}
