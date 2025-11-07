<!DOCTYPE html>
<html>
<head>
    <title>Payment Rejected - Emission Test Schedule</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #EF4444; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 5px 5px; }
        .details { margin: 20px 0; padding: 15px; background-color: #fef2f2; border-radius: 5px; border-left: 4px solid #EF4444; }
        .footer { margin-top: 20px; font-size: 12px; color: #64748b; text-align: center; }
        .button {
            display: inline-block; padding: 10px 20px; background-color: #EF4444; 
            color: white; text-decoration: none; border-radius: 5px; margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PAYMENT REJECTED - REFERENCE #{{ $schedule->reference_number }}</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $schedule->user->name }},</p>
            
            <p>We regret to inform you that your payment has been rejected. Here are the details:</p>
            
            <div class="details">
                <p><strong>Rejection Reason:</strong><br>
                {{ $rejectionReason }}</p>
                
                <p><strong>Reference Number:</strong> {{ $schedule->reference_number }}</p>
                <p><strong>Vehicle:</strong> {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }} ({{ $schedule->vehicle->plate_number }})</p>
                <p><strong>Test Type:</strong> {{ ucfirst($schedule->test_type) }}</p>
                <p><strong>Scheduled Date:</strong> {{ $schedule->test_date->format('F d, Y') }}</p>
                <p><strong>Scheduled Time:</strong> {{ $schedule->time_slot }}</p>
            </div>

            <p>Please review the rejection reason above. If you believe this was a mistake or have any questions, please contact our support team immediately.</p>
            
            <p>You may need to submit a new payment with the correct information.</p>
            
            <p>Thank you for your understanding.</p>
            
            <p>Regards,<br>
            Super Carry Emission Testing Co</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
