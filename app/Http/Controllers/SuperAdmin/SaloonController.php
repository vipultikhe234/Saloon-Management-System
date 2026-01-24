<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Saloon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaloonController extends Controller
{
    public function index()
    {
        $saloons = Saloon::with('owner')->latest()->paginate(10);
        return view('superadmin.saloons.index', compact('saloons'));
    }

    public function create()
    {
        // Get users who are saloon_admins but don't have a saloon yet
        $owners = User::where('role', 'saloon_admin')
            ->whereDoesntHave('saloons')
            ->get();
            
        return view('superadmin.saloons.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'subscription_level' => 'nullable|in:silver,gold,platinum',
            'subscription_expires_at' => 'nullable|date',
        ]);

        $saloon = new Saloon($request->all());
        $saloon->slug = Str::slug($request->name);
        $saloon->country = 'India';
        $saloon->working_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $saloon->is_active = true;
        // Super admin creating it implies verification usually, but let's keep it explicit
        $saloon->is_verified = true;
        $saloon->save();

        return redirect()->route('admin.saloons.index')->with('success', 'Saloon created successfully.');
    }

    public function show(Saloon $saloon)
    {
        $saloon->load(['owner', 'services', 'staff']);
        return view('superadmin.saloons.show', compact('saloon'));
    }

    public function edit(Saloon $saloon)
    {
        $owners = User::where('role', 'saloon_admin')->get();
        return view('superadmin.saloons.edit', compact('saloon', 'owners'));
    }

    public function update(Request $request, Saloon $saloon)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'subscription_level' => 'nullable|in:silver,gold,platinum',
            'subscription_expires_at' => 'nullable|date',
        ]);

        $saloon->update($request->all());
        
        if ($request->has('name')) {
            $saloon->slug = Str::slug($request->name);
            $saloon->save();
        }

        return redirect()->route('admin.saloons.index')->with('success', 'Saloon updated successfully.');
    }

    public function destroy(Saloon $saloon)
    {
        $saloon->delete();
        return redirect()->route('admin.saloons.index')->with('success', 'Saloon deleted successfully.');
    }

    public function toggleStatus(Saloon $saloon)
    {
        $saloon->is_active = !$saloon->is_active;
        $saloon->save();
        return back()->with('success', 'Saloon status updated.');
    }

    public function verify(Saloon $saloon)
    {
        $saloon->is_verified = true;
        $saloon->save();
        return back()->with('success', 'Saloon verified successfully.');
    }

    public function loginAsOwner(Saloon $saloon)
    {
        if (!$saloon->owner) {
            return back()->with('error', 'This saloon has no assigned owner.');
        }

        auth()->login($saloon->owner);
        return redirect()->route('saloon-admin.dashboard')->with('success', 'Logged in as Saloon Owner: ' . $saloon->name);
    }
}
