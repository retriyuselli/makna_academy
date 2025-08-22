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
        Schema::table('event_registrations', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('event_registrations', 'remaining_amount')) {
                $table->decimal('remaining_amount', 15, 2)->nullable()->after('down_payment_amount');
            }
            if (!Schema::hasColumn('event_registrations', 'down_payment_date')) {
                $table->timestamp('down_payment_date')->nullable()->after('payment_date');
            }
            if (!Schema::hasColumn('event_registrations', 'down_payment_proof')) {
                $table->string('down_payment_proof')->nullable()->after('bukti_pembayaran');
            }
            if (!Schema::hasColumn('event_registrations', 'full_payment_date')) {
                $table->timestamp('full_payment_date')->nullable()->after('down_payment_date');
            }
            if (!Schema::hasColumn('event_registrations', 'full_payment_proof')) {
                $table->string('full_payment_proof')->nullable()->after('down_payment_proof');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $columns = [
                'remaining_amount', 
                'down_payment_date', 
                'down_payment_proof',
                'full_payment_date',
                'full_payment_proof'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('event_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
