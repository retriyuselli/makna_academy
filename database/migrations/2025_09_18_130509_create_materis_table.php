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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            
            // Basic information
            $table->string('title', 255);
            $table->text('description')->nullable();
            
            // File information
            $table->enum('type', [
                'pdf', 'video', 'audio', 'image', 'document', 
                'archive', 'source_code', 'presentation'
            ])->default('document');
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('file_extension', 10)->nullable();
            
            // Material categorization
            $table->enum('category', [
                'presentation', 'handout', 'source_code', 'video_recording',
                'additional_resources', 'exercise', 'certificate_template'
            ])->default('handout');
            
            // Access control
            $table->enum('access_level', [
                'public', 'registered', 'completed', 'premium'
            ])->default('completed');
            
            // Status and tracking
            $table->boolean('is_active')->default(true);
            $table->integer('download_count')->default(0);
            $table->integer('sort_order')->default(0);
            
            // Timestamps
            $table->timestamp('upload_date')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable(); // Store additional file info, thumbnails, etc.
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['event_id', 'is_active']);
            $table->index(['category', 'type']);
            $table->index(['access_level', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
