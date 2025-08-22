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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('image')->nullable();
            $table->string('location');
            $table->string('venue')->nullable();
            $table->string('city');
            $table->string('province')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('price', 10, 2)->default(0); // 0 for free events
            $table->boolean('is_free')->default(false);
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('tags')->nullable(); // JSON array of tags
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('organizer_name')->nullable();
            $table->string('pembicara')->nullable(); // Speaker name
            $table->text('requirements')->nullable(); // Event requirements/prerequisites
            $table->text('benefits')->nullable(); // What participants will get
            $table->json('schedule')->nullable(); // JSON array of event schedule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
