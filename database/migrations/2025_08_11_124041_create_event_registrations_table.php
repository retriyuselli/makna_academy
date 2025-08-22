<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->enum('experience_level', ['beginner', 'intermediate', 'advanced']);
            $table->text('motivation');
            $table->string('dietary_requirements')->nullable();
            $table->string('special_needs')->nullable();
            $table->enum('registration_status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'free'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->timestamp('registration_date');
            $table->string('confirmation_code')->unique();
            $table->timestamps();
            
            $table->unique(['event_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
