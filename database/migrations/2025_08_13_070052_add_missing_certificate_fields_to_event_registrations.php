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
            // Tambahkan field yang masih kurang
            if (!Schema::hasColumn('event_registrations', 'is_attended')) {
                $table->boolean('is_attended')->default(false)->after('confirmation_code');
            }
            if (!Schema::hasColumn('event_registrations', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('is_attended');
            }
            if (!Schema::hasColumn('event_registrations', 'certificate_number')) {
                $table->string('certificate_number')->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('event_registrations', 'certificate_issued_at')) {
                $table->timestamp('certificate_issued_at')->nullable()->after('certificate_number');
            }
            if (!Schema::hasColumn('event_registrations', 'certificate_file')) {
                $table->string('certificate_file')->nullable()->after('certificate_issued_at');
            }
            if (!Schema::hasColumn('event_registrations', 'certificate_status')) {
                $table->enum('certificate_status', ['pending', 'issued', 'revoked'])->default('pending')->after('certificate_file');
            }
            if (!Schema::hasColumn('event_registrations', 'certificate_metadata')) {
                $table->json('certificate_metadata')->nullable()->after('certificate_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'is_attended',
                'completed_at',
                'certificate_number',
                'certificate_issued_at',
                'certificate_file',
                'certificate_status',
                'certificate_metadata'
            ]);
        });
    }
};
