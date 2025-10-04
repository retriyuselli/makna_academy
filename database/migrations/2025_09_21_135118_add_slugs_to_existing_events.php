<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Event;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing events to have slugs
        Event::whereNull('slug')->orWhere('slug', '')->chunk(100, function ($events) {
            foreach ($events as $event) {
                $baseSlug = Str::slug($event->title);
                $slug = $baseSlug;
                $counter = 1;
                
                // Check for duplicate slugs and add counter if needed
                while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $event->update(['slug' => $slug]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse, slugs can remain
    }
};
