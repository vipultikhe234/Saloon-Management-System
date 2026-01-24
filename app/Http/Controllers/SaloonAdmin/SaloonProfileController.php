<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaloonProfileController extends Controller
{
    public function create()
    {
        // Check if user already has a saloon
        if (auth()->user()->saloons()->exists()) {
            return redirect()->route('saloon-admin.dashboard')
                ->with('warning', 'You already have a saloon profile.');
        }

        return view('saloon-admin.saloon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        $saloon = new Saloon();
        $saloon->owner_id = auth()->id();
        $saloon->name = $request->name;
        $saloon->slug = Str::slug($request->name);
        $saloon->description = $request->description;
        $saloon->email = $request->email;
        $saloon->phone = $request->phone;
        $saloon->address = $request->address;
        $saloon->city = $request->city;
        $saloon->state = $request->state;
        $saloon->zip_code = $request->zip_code;
        $saloon->country = 'India'; // Default
        $saloon->opening_time = $request->opening_time;
        $saloon->closing_time = $request->closing_time;
        $saloon->working_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']; // Default
        $saloon->is_active = true;
        // is_verified is false by default until Super Admin verifies it
        $saloon->save();

        return redirect()->route('saloon-admin.dashboard')
            ->with('success', 'Saloon profile created successfully!');
    }

    public function edit()
    {
        $saloon = auth()->user()->saloons()->firstOrFail();
        return view('saloon-admin.saloon.edit', compact('saloon'));
    }

    public function update(Request $request, Saloon $saloon)
    {
        // Ensure user owns this saloon
        if ($saloon->owner_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'opening_time' => 'required',
            'closing_time' => 'required',
        ]);

        $saloon->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
        ]);

        return redirect()->route('saloon-admin.dashboard')
            ->with('success', 'Saloon profile updated successfully!');
    }
}
