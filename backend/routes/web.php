<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Product;
use App\Models\Service;
use App\Models\Review;

/*
|--------------------------------------------------------------------------
| Web Routes - Business Connect Platform
|--------------------------------------------------------------------------
*/

// Public Landing & Directory Routes
Route::get('/', function () {
    $categories = BusinessCategory::where('status', 'active')->withCount('businesses')->get();
    $featuredBusinesses = Business::where('status', 'approved')->with(['category', 'reviews'])->take(6)->get();
    $latestProducts = Product::where('status', 'active')->with('business')->take(6)->get();
    $stats = [
        'businesses' => Business::where('status', 'approved')->count(),
        'products' => Product::count(),
        'services' => Service::count(),
        'reviews' => Review::count(),
    ];
    return view('public.index', compact('categories', 'featuredBusinesses', 'latestProducts', 'stats'));
})->name('home');

Route::get('/businesses', [SearchController::class, 'search'])->name('businesses.index');
Route::get('/businesses/{id}', function ($id) {
    $business = Business::with(['category', 'products', 'services', 'reviews.customer', 'gallery'])->findOrFail($id);
    $business->increment('views_count');
    return view('public.business-detail', compact('business'));
})->name('businesses.show');

Route::get('/products', function () {
    $products = Product::where('status', 'active')->with('business')->paginate(12);
    return view('public.products', compact('products'));
})->name('products.index');

Route::get('/services', function () {
    $services = Service::where('availability', 'available')->with('business')->paginate(12);
    return view('public.services', compact('services'));
})->name('services.index');

Route::get('/contact', function () {
    return view('public.contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string',
    ]);
    \App\Models\ContactMessage::create($request->all());
    return back()->with('success', 'Message sent! We will contact you soon.');
})->name('contact.store');

// Search Autosuggest API
Route::get('/api/search/suggestions', [SearchController::class, 'suggestions']);

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register/business', [AuthController::class, 'showRegisterBusiness'])->name('register.business');
    Route::post('/register/business', [AuthController::class, 'registerBusiness']);
    Route::get('/register/customer', [AuthController::class, 'showRegisterCustomer'])->name('register.customer');
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Inquiry & Review Submissions
Route::post('/inquiry/send', [InquiryController::class, 'store'])->name('inquiry.store');
Route::post('/review/send', [ReviewController::class, 'store'])->name('review.store')->middleware('auth');
Route::post('/review/{id}/like', [ReviewController::class, 'like'])->name('review.like');
Route::post('/review/{id}/report', [ReviewController::class, 'report'])->name('review.report');

// Customer Dashboard Routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/favorites', [CustomerController::class, 'favorites'])->name('favorites');
    Route::post('/favorite/toggle/{businessId}', [CustomerController::class, 'toggleFavorite'])->name('favorite.toggle');
    Route::get('/inquiries', [CustomerController::class, 'inquiries'])->name('inquiries');
});

// Business Owner Dashboard Routes
Route::middleware(['auth'])->prefix('business')->name('business.')->group(function () {
    Route::get('/dashboard', [BusinessController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [BusinessController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [BusinessController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/inquiries', function () {
        $business = Auth::user()->business;
        $inquiries = $business ? $business->inquiries()->latest()->paginate(10) : collect();
        return view('business.inquiries', compact('inquiries'));
    })->name('inquiries');

    Route::post('/inquiries/{id}/reply', [InquiryController::class, 'reply'])->name('inquiries.reply');
});

// Admin Panel Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/businesses', [AdminController::class, 'businesses'])->name('businesses');
    Route::post('/businesses/{id}/approve', [AdminController::class, 'approveBusiness'])->name('businesses.approve');
    Route::post('/businesses/{id}/reject', [AdminController::class, 'rejectBusiness'])->name('businesses.reject');
    Route::delete('/businesses/{id}', [AdminController::class, 'deleteBusiness'])->name('businesses.delete');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');
});
