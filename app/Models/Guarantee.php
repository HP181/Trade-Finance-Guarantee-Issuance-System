<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guarantee extends Model
{
    use HasFactory;

    protected $fillable = [
        'corporate_reference_number',
        'guarantee_type',
        'nominal_amount',
        'nominal_amount_currency',
        'expiry_date',
        'applicant_name',
        'applicant_address',
        'beneficiary_name',
        'beneficiary_address',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'nominal_amount' => 'decimal:2',
    ];

    public const GUARANTEE_TYPES = [
        'Bank' => 'Bank',
        'Bid Bond' => 'Bid Bond',
        'Insurance' => 'Insurance',
        'Surety' => 'Surety',
    ];

    public const STATUSES = [
        'Draft' => 'Draft',
        'Under Review' => 'Under Review',
        'Applied' => 'Applied',
        'Issued' => 'Issued',
        'Expired' => 'Expired',
        'Rejected' => 'Rejected',
    ];

    /**
     * Get the user who created the guarantee.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the guarantee.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the reviews for the guarantee.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(GuaranteeReview::class);
    }

    /**
     * Get the current review for the guarantee.
     */
    public function currentReview()
    {
        return $this->reviews()->latest()->first();
    }

    /**
     * Scope a query to only include guarantees that have not expired.
     */
    public function scopeActive($query)
    {
        return $query->where('expiry_date', '>=', now())->where('status', '!=', 'Expired');
    }

    /**
     * Scope a query to only include guarantees that have expired.
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())->orWhere('status', 'Expired');
    }

    /**
     * Check if the guarantee is under review.
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'Under Review';
    }

    /**
     * Check if the guarantee has been applied for.
     */
    public function isApplied(): bool
    {
        return $this->status === 'Applied';
    }

    /**
     * Check if the guarantee has been issued.
     */
    public function isIssued(): bool
    {
        return $this->status === 'Issued';
    }

    /**
     * Check if the guarantee has been rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    /**
     * Check if the guarantee is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'Expired' || $this->expiry_date->isPast();
    }
}