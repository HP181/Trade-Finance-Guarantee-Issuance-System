<?php

namespace App\Repositories\Interfaces;

use App\Models\Guarantee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface GuaranteeRepositoryInterface
{
    /**
     * Get all guarantees with pagination.
     */
    public function getAllWithPagination(int $perPage = 10): LengthAwarePaginator;
    
    /**
     * Get all guarantees.
     */
    public function getAll(): Collection;
    
    /**
     * Find a guarantee by ID.
     */
    public function findById(int $id): ?Guarantee;
    
    /**
     * Find a guarantee by corporate reference number.
     */
    public function findByCorporateReferenceNumber(string $referenceNumber): ?Guarantee;
    
    /**
     * Create a new guarantee.
     */
    public function create(array $data): Guarantee;
    
    /**
     * Update a guarantee.
     */
    public function update(Guarantee $guarantee, array $data): bool;
    
    /**
     * Delete a guarantee.
     */
    public function delete(Guarantee $guarantee): bool;
    
    /**
     * Submit a guarantee for review.
     */
    public function submitForReview(Guarantee $guarantee, int $userId): bool;
    
    /**
     * Apply for a guarantee.
     */
    public function applyForGuarantee(Guarantee $guarantee, int $userId): bool;
    
    /**
     * Issue a guarantee.
     */
    public function issueGuarantee(Guarantee $guarantee, int $userId): bool;
    
    /**
     * Reject a guarantee.
     */
    public function rejectGuarantee(Guarantee $guarantee, int $userId, ?string $reason = null): bool;
    
    /**
     * Create guarantees in bulk.
     */
    public function createBulk(array $guarantees, int $userId): array;
}