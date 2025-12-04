<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EventRegistration extends Model
{
    use HasFactory;
    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            self::PAYMENT_STATUS_PENDING => 'Menunggu Pembayaran',
            self::PAYMENT_STATUS_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::PAYMENT_STATUS_DOWN_PAYMENT_PAID => 'DP Terbayar',
            self::PAYMENT_STATUS_FULLY_PAID => 'Lunas',
            self::PAYMENT_STATUS_FREE => 'Gratis',
            default => 'Status Tidak Diketahui',
        };
    }

    // Payment status constants
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_WAITING_VERIFICATION = 'waiting_verification';
    public const PAYMENT_STATUS_DOWN_PAYMENT_PAID = 'down_payment_paid';
    public const PAYMENT_STATUS_FULLY_PAID = 'fully_paid';
    public const PAYMENT_STATUS_FREE = 'free';

    // Payment method constants
    public const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';
    public const PAYMENT_METHOD_CREDIT_CARD = 'credit_card';
    public const PAYMENT_METHOD_E_WALLET = 'e_wallet';

    // Get all available payment methods
    public static function getPaymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_BANK_TRANSFER => 'Transfer Bank',
            self::PAYMENT_METHOD_CREDIT_CARD => 'Kartu Kredit',
            self::PAYMENT_METHOD_E_WALLET => 'E-Wallet'
        ];
    }

    // Get active payment methods (currently only bank transfer)
    public static function getActivePaymentMethods(): array
    {
        return [
            self::PAYMENT_METHOD_BANK_TRANSFER => 'Transfer Bank'
        ];
    }

    // Get invoice number
    public function getInvoiceNumberAttribute(): string
    {
        return $this->attributes['invoice_number'] ?? ('INV' . date('Ym') . str_pad($this->id, 4, '0', STR_PAD_LEFT));
    }

    // Get certificate number
    public function getCertificateNumberAttribute(): ?string
    {
        if (!empty($this->attributes['certificate_number'])) {
            return $this->attributes['certificate_number'];
        }
        
        if ($this->is_attended && $this->completed_at) {
            return 'CERT/' . date('Y') . '/' . 
                   strtoupper(substr($this->event->title ?? 'EVENT', 0, 3)) . '/' . 
                   str_pad($this->id, 5, '0', STR_PAD_LEFT);
        }
        
        return null;
    }

    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'position',
        'experience_level',
        'motivation',
        'special_needs',
        'registration_status',
        'payment_status',
        'payment_method',
        'package_type',
        'payment_amount',
        'down_payment_amount',
        'remaining_amount',
        'payment_date',
        'down_payment_date',
        'full_payment_date',
        'payment_notes',
        'invoice_number',
        'bukti_pembayaran',
        'down_payment_proof',
        'full_payment_proof',
        'registration_date',
        'confirmation_code',
        'is_attended',
        'completed_at',
        'certificate_number',
        'certificate_issued_at',
        'certificate_path',
        'certificate_status',
        'certificate_metadata'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'payment_date' => 'datetime',
        'down_payment_date' => 'datetime',
        'full_payment_date' => 'datetime',
        'payment_amount' => 'decimal:2',
        'down_payment_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'is_attended' => 'boolean',
        'completed_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'certificate_metadata' => 'array'
    ];

    /**
     * Get the event that owns the registration
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that owns the registration
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the registration is eligible for certificate
     */
    public function isEligibleForCertificate(): bool
    {
        return $this->isPaid() &&
               $this->is_attended &&
               $this->event->end_date < now();
    }

    public function getCertificateStatusDetails(): array
    {
        return [
            'payment_status_ok' => $this->payment_status === 'paid',
            'payment_status' => $this->payment_status,
            'attendance_ok' => $this->is_attended,
            'is_attended' => $this->is_attended,
            'event_ended_ok' => $this->event->end_date < now(),
            'event_end_date' => $this->event->end_date,
            'current_time' => now(),
        ];
    }

    /**
     * Check if certificate is available
     */
    public function hasCertificate(): bool
    {
        // Jika sudah memenuhi syarat tapi belum ada sertifikat, generate otomatis
        if ($this->isEligibleForCertificate() && 
            ($this->certificate_status === 'pending' || empty($this->certificate_number))) {
            try {
                // Generate nomor sertifikat
                $this->certificate_number = 'CERT/' . date('Y') . '/' . 
                    strtoupper(substr($this->event->title ?? 'EVENT', 0, 3)) . '/' . 
                    str_pad($this->id ?? '0', 5, '0', STR_PAD_LEFT);
                
                // Set metadata sertifikat
                $this->certificate_status = 'issued';
                $this->certificate_issued_at = now();
                $this->certificate_metadata = [
                    'issued_at' => now()->toIso8601String(),
                    'event_title' => $this->event->title,
                    'is_digital' => true,
                    'verification_url' => route('certificate.verify', ['number' => $this->certificate_number]),
                    'issuer' => 'System Administrator',
                    'issuer_position' => 'Event Certificate Administrator'
                ];
                $this->save();
            } catch (\Exception $e) {
                Log::error('Failed to generate certificate: ' . $e->getMessage());
                return false;
            }
        }

        return $this->certificate_status === 'issued' && 
               !empty($this->certificate_number) && 
               $this->certificate_issued_at !== null;
    }

    /**
     * Generate confirmation code
     */
    public static function generateConfirmationCode(): string
    {
        return 'REG-' . strtoupper(uniqid());
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        return 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    /**
     * Check if registration is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->registration_status === 'confirmed';
    }

    /**
     * Check if payment is completed
     */
    public function isPaid(): bool
    {
        return in_array($this->payment_status, [
            self::PAYMENT_STATUS_FULLY_PAID,
            self::PAYMENT_STATUS_FREE
        ]);
    }

    /**
     * Check if down payment is made
     */
    public function isDownPaymentPaid(): bool
    {
        return in_array($this->payment_status, [
            self::PAYMENT_STATUS_DOWN_PAYMENT_PAID,
            self::PAYMENT_STATUS_FULLY_PAID
        ]);
    }

    /**
     * Check if full payment is required
     */
    public function requiresRemainingPayment(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_DOWN_PAYMENT_PAID 
            && $this->remaining_amount > 0;
    }

    /**
     * Generate certificate for eligible registration
     */
    public function generateCertificate(): void
    {
        if (!$this->isEligibleForCertificate()) {
            return;
        }

        try {
            // Generate certificate number
            $certificateNumber = 'CERT/' . date('Y') . '/' . 
                               strtoupper(substr($this->event->title ?? 'EVENT', 0, 3)) . '/' . 
                               str_pad($this->id, 5, '0', STR_PAD_LEFT);

            // Generate PDF
            $pdf = app('dompdf.wrapper')->loadView('certificates.template', [
                'name' => $this->name,
                'event_title' => $this->event->title,
                'completion_date' => $this->completed_at ? $this->completed_at->format('d F Y') : now()->format('d F Y'),
                'certificate_number' => $certificateNumber,
                'qr_code' => app('qrcode')->format('png')
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate(route('certificates.verify', $certificateNumber)),
                'issued_date' => now()->format('d F Y')
            ]);

            // Save PDF to storage
            $pdfPath = 'certificates/' . $certificateNumber . '.pdf';
            Storage::disk('public')->put($pdfPath, $pdf->output());

            // Update registration record
            $this->update([
                'certificate_number' => $certificateNumber,
                'certificate_path' => $pdfPath,
                'certificate_issued_at' => now(),
                'certificate_status' => 'issued'
            ]);

        } catch (\Exception $e) {
            Log::error('Certificate generation failed', [
                'registration_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_PENDING;
    }

    /**
     * Check if payment is waiting for verification
     */
    public function isWaitingVerification(): bool
    {
        return $this->payment_status === self::PAYMENT_STATUS_WAITING_VERIFICATION;
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabel(): string
    {
        return match($this->payment_status) {
            self::PAYMENT_STATUS_PENDING => 'Menunggu Pembayaran',
            self::PAYMENT_STATUS_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::PAYMENT_STATUS_DOWN_PAYMENT_PAID => 'DP Terbayar',
            self::PAYMENT_STATUS_FULLY_PAID => 'Lunas',
            self::PAYMENT_STATUS_FREE => 'Gratis',
            default => 'Status Tidak Diketahui',
        };
    }
}
