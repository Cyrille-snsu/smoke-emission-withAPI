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
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->decimal('downpayment_amount', 10, 2)->default(0.00)->after('total_amount');
            $table->enum('downpayment_status', ['pending', 'paid', 'refunded'])->default('pending')->after('downpayment_amount');
            $table->enum('payment_method', ['cash', 'gcash', 'paymaya', 'bank_transfer', 'credit_card'])->nullable()->after('downpayment_status');
            $table->timestamp('downpayment_paid_at')->nullable()->after('payment_method');
            $table->text('payment_notes')->nullable()->after('downpayment_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'total_amount',
                'downpayment_amount',
                'downpayment_status',
                'payment_method',
                'downpayment_paid_at',
                'payment_notes'
            ]);
        });
    }
};
