<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    private function getSaloon()
    {
        $saloon = auth()->user()->saloons()->first();
        if (!$saloon) {
            abort(403, 'You must create a saloon profile first.');
        }
        return $saloon;
    }

    public function index()
    {
        $saloon = $this->getSaloon();
        $services = Service::where('saloon_id', $saloon->id)
            ->with(['category'])
            ->latest()
            ->paginate(10);
            
        return view('saloon-admin.services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('saloon-admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $saloon = $this->getSaloon();

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|lt:price',
            'duration_minutes' => 'required|integer|min:1',
            'gender' => 'required|in:male,female,unisex',
            'description' => 'nullable|string',
        ]);

        $service = new Service($request->all());
        $service->saloon_id = $saloon->id;
        $service->slug = Str::slug($request->name) . '-' . Str::random(6); // Ensure uniqueness
        $service->is_active = true;
        $service->save();

        return redirect()->route('saloon-admin.services.index')->with('success', 'Service added successfully.');
    }

    public function show(Service $service)
    {
        $this->authorizeService($service);
        return redirect()->route('saloon-admin.services.edit', $service->id);
    }

    public function edit(Service $service)
    {
        $this->authorizeService($service);
        $categories = Category::all();
        return view('saloon-admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $this->authorizeService($service);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|lt:price',
            'duration_minutes' => 'required|integer|min:1',
            'gender' => 'required|in:male,female,unisex',
            'description' => 'nullable|string',
        ]);

        $service->update($request->all());

        return redirect()->route('saloon-admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->authorizeService($service);
        $service->delete();
        return redirect()->route('saloon-admin.services.index')->with('success', 'Service deleted successfully.');
    }

    public function toggleStatus(Service $service)
    {
        $this->authorizeService($service);
        $service->is_active = !$service->is_active;
        $service->save();
        return back()->with('success', 'Service status updated.');
    }

    private function authorizeService(Service $service)
    {
        $saloon = $this->getSaloon();
        if ($service->saloon_id !== $saloon->id) {
            abort(403);
        }
    }
}
