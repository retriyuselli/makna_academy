<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hanya jalankan perubahan enum pada MySQL. SQLite tidak mendukung ALTER ENUM.
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            return;
        }
        // First, expand the enum to include new values while keeping old ones
        DB::statement("ALTER TABLE event_registrations MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'free', 'down_payment_paid', 'fully_paid', 'waiting_verification') DEFAULT 'pending'");
        
        // Now update existing 'paid' status to 'fully_paid'
        DB::statement("UPDATE event_registrations SET payment_status = 'fully_paid' WHERE payment_status = 'paid'");
        
        // Finally, remove the old 'paid' and 'failed' from enum, keeping only the new structure
        DB::statement("ALTER TABLE event_registrations MODIFY COLUMN payment_status ENUM('pending', 'down_payment_paid', 'fully_paid', 'waiting_verification', 'free') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() !== 'mysql') {
            return;
        }
        // First expand enum to include old values
        DB::statement("ALTER TABLE event_registrations MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'free', 'down_payment_paid', 'fully_paid', 'waiting_verification') DEFAULT 'pending'");
        
        // Revert data changes
        DB::statement("UPDATE event_registrations SET payment_status = 'paid' WHERE payment_status = 'fully_paid'");
        
        // Restore original enum
        DB::statement("ALTER TABLE event_registrations MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'free') DEFAULT 'pending'");
    }
};
