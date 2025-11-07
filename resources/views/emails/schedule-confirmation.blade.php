<!DOCTYPE html>
<html>
<head>
    <title>Emission Test Schedule Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #3B82F6; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; border: 1px solid #e2e8f0; border-top: none; border-radius: 0 0 5px 5px; }
        .details { margin: 20px 0; padding: 15px; background-color: #f8fafc; border-radius: 5px; }
        .footer { margin-top: 20px; font-size: 12px; color: #64748b; text-align: center; }
        .button {
            display: inline-block; padding: 10px 20px; background-color: #3B82F6; 
            color: white; text-decoration: none; border-radius: 5px; margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                @if($schedule->status === 'pending')
                    SCHEDULE CREATED SUCCESSFULLY
                @elseif($schedule->status === 'confirmed')
                    SCHEDULE CONFIRMED SUCCESSFULLY, PAYMENT CONFIRMED
                @else
                    Emission Test Schedule Update
                @endif
            </h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $schedule->user->name }},</p>
            
            @if($schedule->status === 'pending')
                <p>Your emission test has been scheduled and is pending payment confirmation. Here are the details:</p>
            @elseif($schedule->status === 'confirmed')
                <p>Great news! Your emission test has been confirmed. Here are the details:</p>
            @else
                <p>Your emission test has been updated. Here are the details:</p>
            @endif
            
            <div class="details">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($schedule->test_date)->format('F j, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($schedule->test_time)->format('g:i A') }}</p>
                <p><strong>Vehicle:</strong> {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }} ({{ $schedule->vehicle->plate_number }})</p>
                <p><strong>Test Type:</strong> {{ ucfirst($schedule->test_type) }}</p>
            </div>

            <p>Please arrive 15 minutes before your scheduled time.</p>
            
            @if($schedule->test_center)
            <p><strong>Test Center:</strong> {{ $schedule->test_center->name }}<br>
            {{ $schedule->test_center->address }}</p>
            @endif
            
            <p>If you need to reschedule or have any questions, please contact us.</p>
            
            <p>Thank you for choosing our service!</p>
            
            <p>Regards,<br>
            Super Carry Emission Testing Co</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
