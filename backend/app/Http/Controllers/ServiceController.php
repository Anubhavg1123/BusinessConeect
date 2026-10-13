<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $business = Auth::user()->business;
        $services = Service::where('business_id', $business->id)->latest()->get();
        return view('business.services', compact('services'));
    }

    public function store(Request $request)
    {
        $business = Auth::user()->business;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string',
            'availability' => 'required|in:available,unavailable',
        ]);

        $validated['business_id'] = $business->id;

        Service::create($validated);

        return back()->with('success', 'Service added successfully!');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string',
            'availability' => 'required|in:available,unavailable',
        ]);

        $service->update($validated);

        return back()->with('success', 'Service updated successfully!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return back()->with('success', 'Service deleted!');
    }
}
