<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    
    protected $appends = ['actual_participants'];
    
    protected $fillable = [
        'event_category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'image',
        'location',
        'venue',
        'city',
        'province',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'price',
        'price_gold',
        'price_platinum',
        'is_free',
        'has_down_payment',
        'down_payment_amount',
        'down_payment_percentage',
        'down_payment_type',
        'max_participants',
        'current_participants',
        'rating',
        'total_reviews',
        'is_featured',
        'is_trending',
        'is_active',
        'tags',
        'contact_email',
        'contact_phone',
        'organizer_name',
        'pembicara',
        'requirements',
        'benefits',
        'schedule',
        'payment_methods',
        'payment_instructions'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'payment_methods' => 'array',
        'end_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
        'price_gold' => 'decimal:2',
        'price_platinum' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'down_payment_percentage' => 'integer',
        'rating' => 'decimal:1',
        'is_free' => 'boolean',
        'has_down_payment' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
        'schedule' => 'array', // Array of schedule items
        // Field berikut menggunakan RichEditor, sehingga disimpan sebagai string HTML
        // 'requirements' => 'array',
        // 'benefits' => 'array',
    ];

    /**
     * Mutator untuk schedule - convert ke array simple
     */
    public function setScheduleAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['schedule'] = json_encode(array_filter($value));
        } elseif (is_string($value) && !empty($value)) {
            // Jika string, anggap sebagai JSON yang valid
            $this->attributes['schedule'] = $value;
        } else {
            $this->attributes['schedule'] = null;
        }
    }

    /**
     * Relasi ke kategori event
     */
    public function eventCategory()
    {
        return $this->belongsTo(\App\Models\EventCategory::class, 'event_category_id');
    }

    // Automatically generate slug when creating
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
        
        static::updating(function ($event) {
            if ($event->isDirty('title') && empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    /**
     * Get the category that owns the event
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->is_free || $this->price == 0) {
            return 'GRATIS';
        }
        
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateAttribute(): string
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        
        if ($start->isSameDay($end)) {
            return $start->format('d M Y');
        }
        
        return $start->format('d') . '-' . $end->format('d M Y');
    }

    /**
     * Get formatted time range
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return '';
        }
        
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        return $start->format('H:i') . ' - ' . $end->format('H:i');
    }

    /**
     * Check if event is upcoming
     */
    public function getIsUpcomingAttribute(): bool
    {
        return Carbon::now()->lt(Carbon::parse($this->start_date));
    }

    /**
     * Check if event is ongoing
     */
    public function getIsOngoingAttribute(): bool
    {
        $now = Carbon::now();
        return $now->gte(Carbon::parse($this->start_date)) && $now->lte(Carbon::parse($this->end_date));
    }

    /**
     * Check if event is past
     */
    public function getIsPastAttribute(): bool
    {
        return Carbon::now()->gt(Carbon::parse($this->end_date));
    }

    /**
     * Get remaining slots
     */
    public function getRemainingSlotAttribute(): int
    {
        if (!$this->max_participants) {
            return 999; // Unlimited
        }
        
        return max(0, $this->max_participants - $this->current_participants);
    }

    /**
     * Check if event is fully booked
     */
    public function getIsFullyBookedAttribute(): bool
    {
        if (!$this->max_participants) {
            return false;
        }
        
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Scope for active events
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for trending events
     */
    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    /**
     * Scope for events by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('event_category_id', $categoryId);
    }

    /**
     * Scope for events by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', '%' . $city . '%');
    }

    /**
     * Scope for free events
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true)->orWhere('price', 0);
    }

    /**
     * Scope for paid events
     */
    public function scopePaid($query)
    {
        return $query->where('is_free', false)->where('price', '>', 0);
    }

    /**
     * Get the registrations for the event
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the actual number of participants
     */
    public function getActualParticipantsAttribute(): int
    {
        return $this->registrations()
            ->whereNotIn('registration_status', ['cancelled'])
            ->count();
    }

    /**
     * Get remaining slots
     */
    public function getRemainingSlots(): int
    {
        return max(0, $this->max_participants - $this->actual_participants);
    }

    /**
     * Calculate down payment amount for a given price
     */
    public function calculateDownPayment($totalPrice = null): ?float
    {
        if (!$this->has_down_payment) {
            return null;
        }

        $price = $totalPrice ?? $this->price;
        
        if ($this->down_payment_type === 'amount') {
            return $this->down_payment_amount;
        } else {
            return $price * ($this->down_payment_percentage / 100);
        }
    }

    /**
     * Calculate remaining amount after down payment
     */
    public function calculateRemainingAmount($totalPrice = null): ?float
    {
        if (!$this->has_down_payment) {
            return null;
        }

        $price = $totalPrice ?? $this->price;
        $downPayment = $this->calculateDownPayment($price);
        
        return $price - $downPayment;
    }

    /**
     * Get down payment amount for specific package
     */
    public function getPackageDownPayment($packageType = 'regular'): ?float
    {
        if (!$this->has_down_payment) {
            return null;
        }

        $price = match($packageType) {
            'gold' => $this->price_gold,
            'platinum' => $this->price_platinum,
            default => $this->price
        };

        return $this->calculateDownPayment($price);
    }

    /**
     * Get remaining amount for specific package
     */
    public function getPackageRemainingAmount($packageType = 'regular'): ?float
    {
        if (!$this->has_down_payment) {
            return null;
        }

        $price = match($packageType) {
            'gold' => $this->price_gold,
            'platinum' => $this->price_platinum,
            default => $this->price
        };

        return $this->calculateRemainingAmount($price);
    }

    /**
     * Get confirmed registrations
     */
    public function confirmedRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class)->where('registration_status', 'confirmed');
    }
}
