<?php

namespace App\Http\Controllers\SaloonAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class QueueController extends Controller
{
    private function getSaloon()
    {
        return auth()->user()->saloons()->firstOrFail();
    }

    public function index()
    {
        $saloon = $this->getSaloon();

        // Get Today's Active Queue
        $queue = Appointment::where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->with('user', 'service')
            ->orderBy('token_number', 'asc')
            ->get();

        // Get Completed Today (History)
        $history = Appointment::where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['completed', 'cancelled'])
            ->with('user', 'service')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('saloon-admin.queue.index', compact('queue', 'history'));
    }

    public function markDone(Appointment $appointment)
    {
        $this->authorizeAction($appointment);

        $appointment->status = 'completed';
        $appointment->completed_at = now();
        $appointment->save();

        return back()->with('success', 'Token #' . $appointment->token_number . ' marked as Done.');
    }

    public function startServing(Appointment $appointment)
    {
        $this->authorizeAction($appointment);

        $appointment->status = 'in_progress';
        $appointment->save();

        return back()->with('success', 'Serving Token #' . $appointment->token_number);
    }
    
    public function cancel(Appointment $appointment) 
    {
        $this->authorizeAction($appointment);
        $appointment->status = 'cancelled';
        $appointment->save();
        return back()->with('success', 'Token cancelled');
    }

    private function authorizeAction($appointment)
    {
        if ($appointment->saloon_id !== $this->getSaloon()->id) {
            abort(403);
        }
    }
}
