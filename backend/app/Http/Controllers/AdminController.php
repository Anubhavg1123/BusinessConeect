<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use App\Models\Inquiry;
use App\Models\BusinessCategory;
use App\Models\Review;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_businesses' => Business::count(),
            'total_customers' => User::whereHas('role', fn($q) => $q->where('name', 'customer'))->count(),
            'total_products' => Product::count(),
            'total_services' => Service::count(),
            'pending_approvals' => Business::where('status', 'pending')->count(),
            'approved_businesses' => Business::where('status', 'approved')->count(),
            'rejected_businesses' => Business::where('status', 'rejected')->count(),
        ];

        $recentRegistrations = User::latest()->take(5)->get();
        $recentInquiries = Inquiry::with('business')->latest()->take(5)->get();
        $latestProducts = Product::with('business')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentRegistrations', 'recentInquiries', 'latestProducts'));
    }

    public function businesses(Request $request)
    {
        $query = Business::with(['category', 'owner']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $businesses = $query->latest()->paginate(10);
        return view('admin.businesses', compact('businesses'));
    }

    public function approveBusiness($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => 'approved']);
        return back()->with('success', 'Business approved successfully!');
    }

    public function rejectBusiness($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => 'rejected']);
        return back()->with('success', 'Business rejected!');
    }

    public function deleteBusiness($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        return back()->with('success', 'Business deleted.');
    }

    public function users()
    {
        $users = User::with('role')->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function categories()
    {
        $categories = BusinessCategory::withCount('businesses')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        BusinessCategory::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'icon' => $request->icon ?? 'bi-shop',
            'description' => $request->description,
        ]);

        return back()->with('success', 'Category created!');
    }
}
