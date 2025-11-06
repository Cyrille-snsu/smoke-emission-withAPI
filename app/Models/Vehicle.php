<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'make',
        'model',
        'plate_number',
        'vehicle_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the display name for vehicle type
     */
    public function getVehicleTypeDisplayAttribute()
    {
        $types = [
            'motorcycle' => 'Motorcycle',
            'tricycle' => 'Tricycle',
            'auto' => 'Auto',
            'suv' => 'SUV',
            'truck' => 'Truck',
            'bus' => 'Bus'
        ];

        return $types[$this->vehicle_type] ?? $this->vehicle_type;
    }

    /**
     * Get the vehicle type with proper formatting
     */
    public function getFormattedVehicleTypeAttribute()
    {
        return strtoupper($this->vehicle_type);
    }
}


