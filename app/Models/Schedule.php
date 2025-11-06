<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'test_date',
        'test_time',
        'test_type',
        'status',
        'notes',
        'total_amount',
        'downpayment_amount',
        'downpayment_status',
        'payment_method',
        'downpayment_paid_at',
        'payment_notes',
        'transaction_id',
        'payment_proof',
        'mobile_number'
    ];

    protected $casts = [
        'test_date' => 'date',
        'test_time' => 'datetime:H:i',
        'total_amount' => 'decimal:2',
        'downpayment_amount' => 'decimal:2',
        'downpayment_paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function test_center()
    {
        return $this->belongsTo(TestCenter::class, 'test_center_id');
    }

    /**
     * Check if downpayment is required
     */
    public function isDownpaymentRequired()
    {
        return $this->downpayment_status === 'pending';
    }

    /**
     * Check if downpayment is paid
     */
    public function isDownpaymentPaid()
    {
        return $this->downpayment_status === 'paid';
    }

    /**
     * Calculate remaining balance
     */
    public function getRemainingBalance()
    {
        return $this->total_amount - $this->downpayment_amount;
    }

    /**
     * Check if schedule can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->status === 'pending' && $this->downpayment_status === 'paid';
    }
}
