<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Inquiry;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $favorites = Favorite::with('business.category')->where('customer_id', $user->id)->get();
        $inquiries = Inquiry::with('business')->where('customer_id', $user->id)->latest()->take(5)->get();
        $notifications = Notification::where('user_id', $user->id)->latest()->take(5)->get();

        return view('customer.dashboard', compact('favorites', 'inquiries', 'notifications'));
    }

    public function toggleFavorite(Request $request, $businessId)
    {
        $customerId = Auth::id();
        $existing = Favorite::where('customer_id', $customerId)->where('business_id', $businessId)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('info', 'Removed from favorites.');
        } else {
            Favorite::create(['customer_id' => $customerId, 'business_id' => $businessId]);
            return back()->with('success', 'Added to favorites!');
        }
    }

    public function favorites()
    {
        $favorites = Favorite::with('business.category')->where('customer_id', Auth::id())->paginate(12);
        return view('customer.favorites', compact('favorites'));
    }

    public function inquiries()
    {
        $inquiries = Inquiry::with('business')->where('customer_id', Auth::id())->latest()->paginate(10);
        return view('customer.inquiries', compact('inquiries'));
    }
}
