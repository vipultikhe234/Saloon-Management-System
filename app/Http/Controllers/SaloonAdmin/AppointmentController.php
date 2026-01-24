<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    private function getSaloon()
    {
        $user = auth()->user();
        $saloon = $user->saloons()->first();
        
        if (!$saloon) {
            abort(404, 'Saloon profile not found.');
        }
        
        return $saloon;
    }

    private function authorizeAppointment($appointment)
    {
        $saloon = $this->getSaloon();
        if ($appointment->saloon_id !== $saloon->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index(Request $request)
    {
        $saloon = $this->getSaloon();
        $query = Appointment::where('saloon_id', $saloon->id)
            ->with(['user', 'service', 'staff']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        } else {
            // Default to showing upcoming/recent first
            $query->orderBy('appointment_date', 'desc')
                  ->orderBy('appointment_time', 'asc');
        }

        $appointments = $query->paginate(15);
        
        return view('saloon-admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);
        return view('saloon-admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show',
        ]);

        $appointment->status = $request->status;
        
        if ($request->status == 'completed') {
            $appointment->completed_at = now();
            // Update payment status if needed
        } elseif ($request->status == 'cancelled') {
            $appointment->cancelled_at = now();
            $appointment->cancellation_reason = 'Cancelled by Saloon Admin';
        }

        $appointment->save();

        return back()->with('success', 'Appointment status updated successfully.');
    }
}
