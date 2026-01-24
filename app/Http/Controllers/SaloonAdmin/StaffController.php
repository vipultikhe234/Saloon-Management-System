<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    private function getSaloon()
    {
        $user = auth()->user();
        $saloon = $user->saloons()->first();
        
        if (!$saloon) {
            abort(404, 'Saloon profile not found. Please create your saloon profile first.');
        }
        
        return $saloon;
    }

    private function authorizeStaff($staff)
    {
        $saloon = $this->getSaloon();
        if ($staff->saloon_id !== $saloon->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $saloon = $this->getSaloon();
        $staff = Staff::where('saloon_id', $saloon->id)->latest()->paginate(10);
        return view('saloon-admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('saloon-admin.staff.create');
    }

    public function store(Request $request)
    {
        $saloon = $this->getSaloon();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email',
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['saloon_id'] = $saloon->id;
        $data['is_active'] = true;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff_photos', 'public');
        }

        Staff::create($data);

        return redirect()->route('saloon-admin.staff.index')->with('success', 'Staff member added successfully.');
    }

    public function edit(Staff $staff)
    {
        $this->authorizeStaff($staff);
        return view('saloon-admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $this->authorizeStaff($staff);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email,' . $staff->id,
            'phone' => 'required|string|max:20',
            'gender' => 'required|in:male,female,other',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($staff->photo) {
                Storage::disk('public')->delete($staff->photo);
            }
            $data['photo'] = $request->file('photo')->store('staff_photos', 'public');
        }

        $staff->update($data);

        return redirect()->route('saloon-admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $this->authorizeStaff($staff);
        
        if ($staff->photo) {
            Storage::disk('public')->delete($staff->photo);
        }
        
        $staff->delete();

        return redirect()->route('saloon-admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    public function toggleStatus(Staff $staff)
    {
        $this->authorizeStaff($staff);
        $staff->is_active = !$staff->is_active;
        $staff->save();

        return back()->with('success', 'Staff status updated successfully.');
    }
}
