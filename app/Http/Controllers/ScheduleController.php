<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\ScheduleConfirmation;
use Illuminate\Support\Facades\Mail;

class ScheduleController extends Controller
{
    // Base pricing for smoke emission tests by vehicle type
    private $vehicleTypePricing = [
        'motorcycle' => [
            'initial' => 800.00,    // Motorcycle Initial: ₱800
            'renewal' => 600.00,    // Motorcycle Renewal: ₱600
            'retest' => 400.00      // Motorcycle Retest: ₱400
        ],
        'tricycle' => [
            'initial' => 900.00,    // Tricycle Initial: ₱900
            'renewal' => 700.00,    // Tricycle Renewal: ₱700
            'retest' => 500.00      // Tricycle Retest: ₱500
        ],
        'auto' => [
            'initial' => 1200.00,   // Auto Initial: ₱1,200
            'renewal' => 900.00,    // Auto Renewal: ₱900
            'retest' => 600.00      // Auto Retest: ₱600
        ],
        'suv' => [
            'initial' => 1800.00,   // SUV Initial: ₱1,800
            'renewal' => 1400.00,   // SUV Renewal: ₱1,400
            'retest' => 900.00      // SUV Retest: ₱900
        ],
        'truck' => [
            'initial' => 2500.00,   // Truck Initial: ₱2,500
            'renewal' => 2000.00,   // Truck Renewal: ₱2,000
            'retest' => 1200.00     // Truck Retest: ₱1,200
        ],
        'bus' => [
            'initial' => 3500.00,   // Bus Initial: ₱3,500
            'renewal' => 2800.00,   // Bus Renewal: ₱2,800
            'retest' => 1800.00     // Bus Retest: ₱1,800
        ]
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Auth::user()->schedules()->with('vehicle')->latest()->get();
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Auth::user()->vehicles;
        
        // Get booked time slots for the next 30 days
        $bookedTimeSlots = $this->getBookedTimeSlots();
        
        return view('schedules.create', compact('vehicles', 'bookedTimeSlots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'test_date' => 'required|date|after:today',
            'test_time' => 'required|date_format:H:i',
            'test_type' => 'required|in:initial,renewal,retest',
            'notes' => 'nullable|string|max:500',
            'mobile_number' => 'required|regex:/^09[0-9]{9}$/'
        ]);

        // Check if the time slot is already booked
        if ($this->isTimeSlotBooked($request->test_date, $request->test_time)) {
            return back()->withErrors(['test_time' => 'This time slot is not available. Please choose another time.'])->withInput();
        }

        // Ensure the vehicle belongs to the authenticated user
        $vehicle = Auth::user()->vehicles()->findOrFail($request->vehicle_id);

        // Calculate pricing based on vehicle type and test type
        $totalAmount = $this->vehicleTypePricing[$vehicle->vehicle_type][$request->test_type];
        $downpaymentAmount = $totalAmount * 0.5; // 50% downpayment

        // Handle file upload
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $paymentProofPath = $file->storeAs('payment_proofs', $fileName, 'public');
        }

        $schedule = Schedule::create([
            'user_id' => Auth::id(),
            'vehicle_id' => $vehicle->id,
            'test_date' => $request->test_date,
            'test_time' => $request->test_date . ' ' . $request->test_time,
            'test_type' => $request->test_type,
            'status' => 'pending', // Pending admin confirmation
            'notes' => $request->notes,
            'total_amount' => $totalAmount,
            'downpayment_amount' => $downpaymentAmount,
            'downpayment_status' => 'pending', // Pending admin verification
            'mobile_number' => $request->mobile_number
        ]);

        // Send confirmation email
        try {
            Mail::to($schedule->user->email)->send(new ScheduleConfirmation($schedule));
        } catch (\Exception $e) {
            // Log error but don't break the user experience
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }

        // Redirect to step 2: pay downpayment (payment info upload)
        return redirect()->route('schedules.payDownpayment', $schedule->id)
            ->with('success', 'Please complete your downpayment to finalize your booking.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Auth::user()->schedules()->with('vehicle')->findOrFail($id);
        return view('schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = Auth::user()->schedules()->findOrFail($id);
        $vehicles = Auth::user()->vehicles;
        
        // Get booked time slots for the next 30 days
        $bookedTimeSlots = $this->getBookedTimeSlots($schedule->id);
        
        return view('schedules.edit', compact('schedule', 'vehicles', 'bookedTimeSlots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Auth::user()->schedules()->findOrFail($id);
        
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'test_date' => 'required|date|after:today',
            'test_time' => 'required|date_format:H:i',
            'test_type' => 'required|in:initial,renewal,retest',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if the new time slot is already booked (excluding current schedule)
        if ($this->isTimeSlotBooked($request->test_date, $request->test_time, $schedule->id)) {
            return back()->withErrors(['test_time' => 'This time slot is not available. Please choose another time.'])->withInput();
        }

        $schedule->update([
            'vehicle_id' => $request->vehicle_id,
            'test_date' => $request->test_date,
            'test_time' => $request->test_date . ' ' . $request->test_time,
            'test_type' => $request->test_type,
            'notes' => $request->notes
        ]);

        return redirect()->route('schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Auth::user()->schedules()->findOrFail($id);
        
        // Check if downpayment was paid
        if ($schedule->isDownpaymentPaid()) {
            // Keep the downpayment (50% of total amount)
            $schedule->update([
                'status' => 'cancelled',
                'downpayment_status' => 'refunded',
                'payment_notes' => 'Schedule cancelled. Downpayment retained as per policy.'
            ]);
            
            return redirect()->route('schedules.index')
                ->with('success', 'Schedule cancelled. Your 50% downpayment has been retained as per our cancellation policy.');
        } else {
            // No downpayment paid, just delete
            $schedule->delete();
            
            return redirect()->route('schedules.index')
                ->with('success', 'Schedule cancelled successfully!');
        }
    }

    /**
     * Process downpayment payment
     */
    public function processDownpayment(Request $request, string $id)
    {
        $schedule = Auth::user()->schedules()->findOrFail($id);
        
        if (!$schedule->isDownpaymentRequired()) {
            return back()->withErrors(['payment' => 'Downpayment has already been processed.']);
        }

        $transactionId = $request->input('transaction_id');

        // Update downpayment status
        $schedule->update([
            'downpayment_status' => 'paid',
            'downpayment_paid_at' => now(),
            'payment_notes' => 'Downpayment received via ' . $schedule->payment_method . ' on ' . now()->format('M d, Y g:i A'),
            'transaction_id' => $transactionId
        ]);

        return redirect()->route('schedules.show', $schedule)
            ->with('success', 'Downpayment received! Your booking is now secured. Please arrive 15 minutes before your scheduled time.');
    }

    /**
     * Confirm payment for a schedule (Admin only)
     */
    public function confirmPayment(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        
        $schedule->update([
            'status' => 'confirmed',
            'downpayment_status' => 'paid',
            'downpayment_paid_at' => now(),
            'payment_notes' => 'Payment confirmed by admin on ' . now()->format('M d, Y g:i A')
        ]);

        return redirect()->route('admin.schedules.show', $schedule)
            ->with('success', 'Payment confirmed successfully! Schedule is now confirmed.');
    }

    /**
     * Reject payment for a schedule (Admin only)
     */
    public function rejectPayment(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $schedule = Schedule::findOrFail($id);
        
        $schedule->update([
            'status' => 'cancelled',
            'downpayment_status' => 'refunded',
            'payment_notes' => 'Payment rejected by admin on ' . now()->format('M d, Y g:i A') . '. Reason: ' . $request->rejection_reason
        ]);

        return redirect()->route('admin.schedules.show', $schedule)
            ->with('success', 'Payment rejected successfully! Schedule has been cancelled.');
    }

    public function payDownpayment(Schedule $schedule)
    {
        $this->authorize('view', $schedule);
        
        // If downpayment is already paid, redirect to show page
        if ($schedule->downpayment_status === 'paid') {
            return redirect()->route('schedules.show', $schedule)
                ->with('info', 'Your downpayment has already been processed.');
        }
        
        return view('schedules.pay-downpayment', compact('schedule'));
    }

    public function submitDownpayment(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        $request->validate([
            'payment_method' => 'required|in:gcash',
            'transaction_id' => 'required|string|max:255',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $paymentProofPath = $file->storeAs('payment_proofs', $fileName, 'public');
        }

        // Update the schedule with payment details and mark as pending admin confirmation
        $schedule->update([
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_proof' => $paymentProofPath,
            'downpayment_status' => 'pending',
            'status' => 'pending',
            'payment_notes' => 'Payment proof submitted via ' . $request->payment_method . ' on ' . now()->format('M d, Y g:i A') . ' - Awaiting admin confirmation',
        ]);

        // Send notification email to admin (you'll need to implement this)
        try {
            // You can implement admin notification here if needed
            // Mail::to(env('ADMIN_EMAIL'))->send(new AdminPaymentNotification($schedule));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification: ' . $e->getMessage());
        }

        return redirect()->route('schedules.show', $schedule)
            ->with('success', 'Payment submitted! Your booking is pending admin confirmation.');
    }

    /**
     * Get booked time slots for the next 30 days
     */
    private function getBookedTimeSlots($excludeScheduleId = null)
    {
        $query = Schedule::where('test_date', '>=', Carbon::today())
            ->where('test_date', '<=', Carbon::today()->addDays(30))
            ->whereIn('status', ['pending', 'confirmed', 'completed']);

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $schedules = $query->get();
        
        $bookedTimeSlots = [];
        
        // Define working hours (8:00 AM to 5:00 PM)
        $workingHours = [];
        for ($i = 8; $i <= 16; $i++) {
            $workingHours[] = sprintf('%02d:00', $i);
        }
        
        foreach ($schedules as $schedule) {
            $date = $schedule->test_date->format('Y-m-d');
            $time = $schedule->test_time;
            
            if (!isset($bookedTimeSlots[$date])) {
                $bookedTimeSlots[$date] = [];
            }
            
            // Add the booked time slot
            if (!in_array($time, $bookedTimeSlots[$date])) {
                $bookedTimeSlots[$date][] = $time;
            }
        }
        
        // Sort the time slots for each date
        foreach ($bookedTimeSlots as $date => $times) {
            usort($times, function($a, $b) {
                return strtotime($a) - strtotime($b);
            });
            $bookedTimeSlots[$date] = $times;
        }
        
        return $bookedTimeSlots;
    }

    /**
     * Check if a specific time slot is already booked
     */
    private function isTimeSlotBooked($date, $time, $excludeScheduleId = null)
    {
        $query = Schedule::where('test_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'completed']);

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        $schedules = $query->get();
        $requestedTime = Carbon::parse($time);
        $testDurationMinutes = 90; // 1.5 hours in minutes
        $requestedEnd = $requestedTime->copy()->addMinutes($testDurationMinutes);
        
        // Check for exact time match first (simplest case)
        foreach ($schedules as $schedule) {
            $scheduleTime = Carbon::parse($schedule->test_time);
            $scheduleEnd = $scheduleTime->copy()->addMinutes($testDurationMinutes);
            
            // If the exact time is already taken, return true
            if ($scheduleTime->format('H:i') === $requestedTime->format('H:i')) {
                return true;
            }
            
            // Check for any overlap between the requested time slot and existing schedule
            if ($requestedTime->between($scheduleTime, $scheduleEnd, true) ||  // Requested start is during an existing schedule
                $requestedEnd->between($scheduleTime, $scheduleEnd, true) ||    // Requested end is during an existing schedule
                ($requestedTime->lte($scheduleTime) && $requestedEnd->gte($scheduleEnd))) { // Requested time completely contains an existing schedule
                return true;
            }
        }
        
        return false; // No conflicts
    }
}
