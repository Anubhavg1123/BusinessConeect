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
        if (!$business) {
            return redirect()->route('business.profile.edit')->with('info', 'Please set up your business profile before managing services.');
        }

        $services = Service::where('business_id', $business->id)->latest()->get();
        return view('business.services', compact('services'));
    }

    public function store(Request $request)
    {
        $business = Auth::user()->business;
        if (!$business) {
            return redirect()->route('business.profile.edit')->with('error', 'Please set up your business profile first.');
        }

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
        $business = Auth::user()->business;
        $service = Service::findOrFail($id);

        if (!$business || $service->business_id !== $business->id) {
            abort(403, 'Unauthorized action: You do not own this service.');
        }

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
        $business = Auth::user()->business;
        $service = Service::findOrFail($id);

        if (!$business || $service->business_id !== $business->id) {
            abort(403, 'Unauthorized action: You do not own this service.');
        }

        $service->delete();

        return back()->with('success', 'Service deleted!');
    }
}
