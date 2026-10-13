<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Business::where('status', 'approved')->with(['category', 'reviews']);

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%")
                  ->orWhere('state', 'like', "%{$keyword}%")
                  ->orWhereHas('products', fn($p) => $p->where('name', 'like', "%{$keyword}%"))
                  ->orWhereHas('services', fn($s) => $s->where('name', 'like', "%{$keyword}%"));
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'popular':
                    $query->orderBy('views_count', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $businesses = $query->paginate(12);
        $categories = BusinessCategory::all();

        return view('public.businesses', compact('businesses', 'categories'));
    }

    public function suggestions(Request $request)
    {
        $term = $request->get('term', '');
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $businesses = Business::where('status', 'approved')
            ->where('name', 'like', "%{$term}%")
            ->take(5)
            ->get(['id', 'name', 'city']);

        $products = Product::where('status', 'active')
            ->where('name', 'like', "%{$term}%")
            ->take(5)
            ->get(['id', 'name', 'price']);

        return response()->json([
            'businesses' => $businesses,
            'products' => $products,
        ]);
    }
}
