<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Add user_id foreign key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Add vehicle_id foreign key
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            
            // Add test scheduling fields
            $table->date('test_date');
            $table->time('test_time');
            $table->enum('test_type', ['initial', 'renewal', 'retest']);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            
            // Add notes field
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn([
                'user_id',
                'vehicle_id',
                'test_date',
                'test_time',
                'test_type',
                'status',
                'notes'
            ]);
        });
    }
};
