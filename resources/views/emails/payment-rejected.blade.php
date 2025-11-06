<x-mail::message>
# Payment Rejected - Reference #{{ $schedule->reference_number }}

Hello {{ $schedule->user->name }},

We regret to inform you that your payment for the emission test appointment has been rejected. Please find the details below:

<x-mail::panel>
## Appointment Details
- **Reference Number:** {{ $schedule->reference_number }}
- **Test Type:** {{ $schedule->test_type }}
- **Vehicle:** {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }} ({{ $schedule->vehicle->plate_number }})
- **Scheduled Date:** {{ $schedule->test_date->format('F d, Y') }}
- **Scheduled Time:** {{ $schedule->time_slot }}
- **Rejection Reason:** 

{{ $rejectionReason }}
</x-mail::panel>

## What's Next?
1. Please review the rejection reason above
2. If you believe this was a mistake, please contact our support team immediately
3. You may need to submit a new payment with the correct information

## Need Help?
If you have any questions or need assistance, please don't hesitate to contact our support team at {{ config('mail.from.address') }} or call us at (123) 456-7890.

<x-mail::button :url="route('schedules.show', $schedule)" color="red">
    View Appointment Details
</x-mail::button>

Thank you for your understanding.

Best regards,<br>
{{ config('app.name') }}

---
<small>This is an automated message. Please do not reply to this email.</small>
</x-mail::message>
