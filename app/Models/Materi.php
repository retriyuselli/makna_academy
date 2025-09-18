<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'file_extension',
        'download_count',
        'is_active',
        'upload_date',
        'uploaded_by',
        'category',
        'sort_order',
        'access_level',
        'expires_at',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'upload_date' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'sort_order' => 'integer'
    ];

    // Material type constants
    public const TYPE_PDF = 'pdf';
    public const TYPE_VIDEO = 'video';
    public const TYPE_AUDIO = 'audio';
    public const TYPE_IMAGE = 'image';
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_ARCHIVE = 'archive';
    public const TYPE_SOURCE_CODE = 'source_code';
    public const TYPE_PRESENTATION = 'presentation';

    // Access level constants
    public const ACCESS_PUBLIC = 'public';
    public const ACCESS_REGISTERED = 'registered';
    public const ACCESS_COMPLETED = 'completed';
    public const ACCESS_PREMIUM = 'premium';

    // Category constants
    public const CATEGORY_PRESENTATION = 'presentation';
    public const CATEGORY_HANDOUT = 'handout';
    public const CATEGORY_SOURCE_CODE = 'source_code';
    public const CATEGORY_VIDEO_RECORDING = 'video_recording';
    public const CATEGORY_ADDITIONAL_RESOURCES = 'additional_resources';
    public const CATEGORY_EXERCISE = 'exercise';
    public const CATEGORY_CERTIFICATE_TEMPLATE = 'certificate_template';

    /**
     * Get all available material types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_PDF => 'Dokumen PDF',
            self::TYPE_VIDEO => 'Video',
            self::TYPE_AUDIO => 'Audio',
            self::TYPE_IMAGE => 'Gambar',
            self::TYPE_DOCUMENT => 'Dokumen',
            self::TYPE_ARCHIVE => 'Arsip/ZIP',
            self::TYPE_SOURCE_CODE => 'Kode Sumber',
            self::TYPE_PRESENTATION => 'Presentasi'
        ];
    }

    /**
     * Get all available categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_PRESENTATION => 'Slide Presentasi',
            self::CATEGORY_HANDOUT => 'Materi Handout',
            self::CATEGORY_SOURCE_CODE => 'Kode Sumber',
            self::CATEGORY_VIDEO_RECORDING => 'Rekaman Video',
            self::CATEGORY_ADDITIONAL_RESOURCES => 'Sumber Tambahan',
            self::CATEGORY_EXERCISE => 'File Latihan',
            self::CATEGORY_CERTIFICATE_TEMPLATE => 'Template Sertifikat'
        ];
    }

    /**
     * Relationship with Event
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship with User (uploader)
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get file size in human readable format
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get material icon SVG path based on type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_PDF => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            self::TYPE_VIDEO => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z',
            self::TYPE_AUDIO => 'M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M9 7H5a2 2 0 00-2 2v6a2 2 0 002 2h4l5 5V2L9 7z',
            self::TYPE_IMAGE => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            self::TYPE_ARCHIVE => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
            self::TYPE_SOURCE_CODE => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
            self::TYPE_PRESENTATION => 'M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h3a1 1 0 011 1v14a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1h3z',
            default => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        };
    }

    /**
     * Get material color based on type
     */
    public function getColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_PDF => 'red-500',
            self::TYPE_VIDEO => 'blue-500',
            self::TYPE_AUDIO => 'purple-500',
            self::TYPE_IMAGE => 'green-500',
            self::TYPE_ARCHIVE => 'yellow-500',
            self::TYPE_SOURCE_CODE => 'gray-500',
            self::TYPE_PRESENTATION => 'indigo-500',
            default => 'gray-400'
        };
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('materi.download', $this->id);
    }

    /**
     * Check if material is accessible by user
     */
    public function isAccessibleBy(User $user, $hasCompletedEvent = false): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return match($this->access_level) {
            self::ACCESS_PUBLIC => true,
            self::ACCESS_REGISTERED => $user !== null,
            self::ACCESS_COMPLETED => $hasCompletedEvent,
            self::ACCESS_PREMIUM => $user && method_exists($user, 'isPremium') ? $user->isPremium() : false,
            default => false
        };
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    /**
     * Scope for active materials
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for materials by event
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope for materials by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for materials by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for accessible materials by user
     */
    public function scopeAccessibleBy($query, User $user, $hasCompletedEvent = false)
    {
        return $query->where('is_active', true)
            ->where(function($q) use ($user, $hasCompletedEvent) {
                $q->where('access_level', self::ACCESS_PUBLIC)
                  ->orWhere(function($subQ) use ($user) {
                      if ($user) {
                          $subQ->where('access_level', self::ACCESS_REGISTERED);
                      }
                  })
                  ->orWhere(function($subQ) use ($hasCompletedEvent) {
                      if ($hasCompletedEvent) {
                          $subQ->where('access_level', self::ACCESS_COMPLETED);
                      }
                  });
            })
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }
}
