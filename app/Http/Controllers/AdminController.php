<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Mail\ScheduleConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Schedule::with(['user', 'vehicle'])->latest();
        if ($status) {
            $query->where('status', $status);
        }
        $schedules = $query->paginate(10);

        $counts = [
            'pending' => Schedule::where('status', 'pending')->count(),
            'confirmed' => Schedule::where('status', 'confirmed')->count(),
            'completed' => Schedule::where('status', 'completed')->count(),
            'cancelled' => Schedule::where('status', 'cancelled')->count(),
        ];

        return view('admin.index', compact('schedules', 'counts', 'status'));
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['user', 'vehicle']);
        return view('admin.show', compact('schedule'));
    }

    public function confirm(Schedule $schedule)
    {
        // Update the schedule status and payment status
        $schedule->update([
            'status' => 'confirmed',
            'downpayment_status' => 'paid',
            'downpayment_paid_at' => now(),
            'payment_notes' => trim(($schedule->payment_notes ? $schedule->payment_notes.'\n' : '').'[Admin] Payment confirmed and booking approved on '.now()->format('M d, Y g:i A')),
        ]);

        // Refresh the schedule to ensure we have the latest data
        $schedule->refresh();
        
        // Log the email sending attempt
        \Log::info('Attempting to send confirmation email for schedule #' . $schedule->id . ' to ' . $schedule->user->email);

        // Send confirmation email
        try {
            Mail::to($schedule->user->email)->send(new ScheduleConfirmation($schedule));
            \Log::info('Confirmation email sent successfully for schedule #' . $schedule->id);
            $message = 'Payment confirmed and schedule approved. Notification email has been sent to the customer.';
        } catch (\Exception $e) {
            $errorMsg = 'Failed to send confirmation email: ' . $e->getMessage();
            \Log::error($errorMsg);
            $message = 'Payment confirmed and schedule approved, but failed to send confirmation email: ' . $e->getMessage();
        }

        return redirect()->route('admin.schedules.show', $schedule)
            ->with('status', $message);
    }

    public function reject(Schedule $schedule, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Log the time slot being freed up before updating
        \Log::info('Time slot freed up after rejection', [
            'schedule_id' => $schedule->id,
            'original_date' => $schedule->test_date,
            'original_time' => $schedule->test_time,
            'rejection_reason' => $validated['rejection_reason']
        ]);
        
        // Update the schedule status to cancelled
        $schedule->update([
            'status' => 'cancelled',
            'downpayment_status' => 'refunded',
            'payment_notes' => trim(($schedule->payment_notes ? $schedule->payment_notes.'\n' : '').'[Admin] Payment rejected on '.now()->format('M d, Y g:i A').' - Reason: '.$validated['rejection_reason']),
        ]);

        // Send rejection email to customer
        try {
            // Refresh the schedule to ensure we have the latest data
            $schedule->refresh();
            
            // Log the email sending attempt
            \Log::info('Sending rejection email for schedule #' . $schedule->id . ' to ' . $schedule->user->email);
            
            // Send rejection email
            Mail::to($schedule->user->email)->send(new \App\Mail\PaymentRejected($schedule, $validated['rejection_reason']));
            \Log::info('Rejection email sent successfully for schedule #' . $schedule->id);
            $message = 'Payment rejected and schedule cancelled. Notification email has been sent to the customer.';
        } catch (\Exception $e) {
            $errorMsg = 'Failed to send rejection email: ' . $e->getMessage();
            \Log::error($errorMsg);
            $message = 'Payment rejected and schedule cancelled, but failed to send notification email: ' . $e->getMessage();
        }

        return redirect()->route('admin.schedules.show', $schedule)
            ->with('status', 'Payment rejected and schedule cancelled. The customer has been notified.');
    }

    /**
     * Mark a schedule as completed
     */
    public function complete(Schedule $schedule)
    {
        // Only allow completing confirmed schedules
        if ($schedule->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Only confirmed schedules can be marked as completed.');
        }

        $schedule->update([
            'status' => 'completed',
            'payment_notes' => trim(($schedule->payment_notes ? $schedule->payment_notes.'\n' : '').'[Admin] Marked as completed on '.now()->format('M d, Y g:i A')),
        ]);

        // Send completion email to customer
        try {
            // Refresh the schedule to ensure we have the latest data
            $schedule->refresh();
            
            // Log the email sending attempt
            \Log::info('Sending completion email for schedule #' . $schedule->id . ' to ' . $schedule->user->email);
            
            // Send completion email (you'll need to create this Mailable)
            // Mail::to($schedule->user->email)->send(new \App\Mail\ScheduleCompleted($schedule));
            \Log::info('Completion email sent successfully for schedule #' . $schedule->id);
            $message = 'Schedule marked as completed. Notification email has been sent to the customer.';
        } catch (\Exception $e) {
            $errorMsg = 'Failed to send completion email: ' . $e->getMessage();
            \Log::error($errorMsg);
            $message = 'Schedule marked as completed, but failed to send notification email: ' . $e->getMessage();
        }

        return redirect()->route('admin.schedules.show', $schedule)
            ->with('status', $message);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.index')->with('status', 'Schedule deleted');
    }
}
