<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $business = Auth::user()->business;
        $products = Product::where('business_id', $business->id)->latest()->paginate(10);
        return view('business.products', compact('products'));
    }

    public function store(Request $request)
    {
        $business = Auth::user()->business;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:product_categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'availability' => 'required|in:in_stock,out_of_stock,pre_order',
            'status' => 'required|in:active,draft,inactive',
        ]);

        $validated['business_id'] = $business->id;

        Product::create($validated);

        return back()->with('success', 'Product created successfully!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'availability' => 'required|in:in_stock,out_of_stock,pre_order',
            'status' => 'required|in:active,draft,inactive',
        ]);

        $product->update($validated);

        return back()->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }
}
