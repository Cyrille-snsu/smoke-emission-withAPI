<?php

namespace App\Mail;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    /**
     * Create a new message instance.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule->load(['user', 'vehicle']);
        
        // Load test_center if the relationship exists
        if (method_exists($schedule, 'test_center')) {
            $this->schedule->load('test_center');
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Emission Test Schedule Confirmation #' . $this->schedule->id)
                    ->view('emails.schedule-confirmation');
    }
}
