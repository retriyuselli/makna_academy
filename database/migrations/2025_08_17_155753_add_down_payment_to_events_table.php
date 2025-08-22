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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('has_down_payment')->default(false)->after('is_free');
            $table->decimal('down_payment_amount', 15, 2)->nullable()->after('has_down_payment');
            $table->integer('down_payment_percentage')->nullable()->after('down_payment_amount');
            $table->enum('down_payment_type', ['amount', 'percentage'])->default('percentage')->after('down_payment_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['has_down_payment', 'down_payment_amount', 'down_payment_percentage', 'down_payment_type']);
        });
    }
};
