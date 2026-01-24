<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Saloon;
use App\Models\Appointment;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function join(Request $request, Saloon $saloon)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        if (!$saloon->isSubscriptionActive()) {
            return back()->with('error', 'This saloon is currently not accepting new queue entries due to expired subscription.');
        }

        $user = auth()->user();

        // Check if already in queue for today
        $existing = Appointment::where('user_id', $user->id)
            ->where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existing) {
            return redirect()->route('user.dashboard')->with('error', 'You are already in the queue for this saloon today.');
        }

        // Generate Token
        $lastToken = Appointment::where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->max('token_number') ?? 0;
        
        $newToken = $lastToken + 1;

        // Calculate Estimated Time
        // Count pending people
        $pendingCount = Appointment::where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->count();
            
        $avgWait = $saloon->avg_wait_time_per_customer ?? 20; // minutes
        $minsToWait = $pendingCount * $avgWait;
        
        $estimatedTime = Carbon::now()->addMinutes($minsToWait);

        // Create "Appointment" as a Queue Ticket
        $appointment = new Appointment();
        $appointment->appointment_number = 'TOK-' . $newToken . '-' . strtoupper(uniqid());
        $appointment->user_id = $user->id;
        $appointment->saloon_id = $saloon->id;
        $appointment->service_id = $request->service_id;
        $appointment->appointment_date = Carbon::today();
        $appointment->appointment_time = $estimatedTime->format('H:i:s'); // Storing estimated time
        $appointment->token_number = $newToken;
        $appointment->duration_minutes = $avgWait;
        $appointment->status = 'pending';
        
        // Price logic
        $service = \App\Models\Service::find($request->service_id);
        $appointment->total_amount = $service->discounted_price ?? $service->price;
        $appointment->final_amount = $appointment->total_amount;
        
        $appointment->save();

        return redirect()->route('user.dashboard')->with('success', 'Joined Queue! Your Token Number is ' . $newToken);
    }

    // View Live Status
    public function status(Appointment $appointment)
    {
        if ($appointment->user_id !== auth()->id()) {
            abort(403);
        }

        $saloon = $appointment->saloon;
        
        // Calculate dynamic position
        $position = Appointment::where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('token_number', '<', $appointment->token_number)
            ->count();
            
        $currentServing = Appointment::where('saloon_id', $saloon->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'in_progress')
            ->value('token_number');

        return view('user.queue.status', compact('appointment', 'position', 'currentServing'));
    }
}
