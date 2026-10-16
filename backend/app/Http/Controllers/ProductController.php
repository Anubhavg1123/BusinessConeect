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
        if (!$business) {
            return redirect()->route('business.profile.edit')->with('info', 'Please set up your business profile before managing products.');
        }

        $products = Product::where('business_id', $business->id)->latest()->paginate(10);
        return view('business.products', compact('products'));
    }

    public function store(Request $request)
    {
        $business = Auth::user()->business;
        if (!$business) {
            return redirect()->route('business.profile.edit')->with('error', 'Please set up your business profile first.');
        }

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
        $business = Auth::user()->business;
        $product = Product::findOrFail($id);

        if (!$business || $product->business_id !== $business->id) {
            abort(403, 'Unauthorized action: You do not own this product.');
        }
        
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
        $business = Auth::user()->business;
        $product = Product::findOrFail($id);

        if (!$business || $product->business_id !== $business->id) {
            abort(403, 'Unauthorized action: You do not own this product.');
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }
}
