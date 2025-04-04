<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuaranteeReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'guarantee_id',
        'status',
        'comments',
        'reviewer_id',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public const STATUSES = [
        'Pending' => 'Pending',
        'Approved' => 'Approved',
        'Rejected' => 'Rejected',
    ];

    /**
     * Get the guarantee that the review belongs to.
     */
    public function guarantee(): BelongsTo
    {
        return $this->belongsTo(Guarantee::class);
    }

    /**
     * Get the user who reviewed the guarantee.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Check if the review is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if the review is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'Approved';
    }

    /**
     * Check if the review is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'Rejected';
    }

    /**
     * Scope a query to only include pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Set the review as approved.
     */
    public function approve(int $reviewerId, ?string $comments = null): bool
    {
        $this->status = 'Approved';
        $this->reviewer_id = $reviewerId;
        $this->comments = $comments;
        $this->reviewed_at = now();
        
        return $this->save();
    }

    /**
     * Set the review as rejected.
     */
    public function reject(int $reviewerId, ?string $comments = null): bool
    {
        $this->status = 'Rejected';
        $this->reviewer_id = $reviewerId;
        $this->comments = $comments;
        $this->reviewed_at = now();
        
        return $this->save();
    }
}