<?php

namespace App\Repositories;

use App\Models\Guarantee;
use App\Models\GuaranteeReview;
use App\Repositories\Interfaces\GuaranteeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GuaranteeRepository implements GuaranteeRepositoryInterface
{
    /**
     * Get all guarantees with pagination.
     */

     protected $guaranteeRepository;


     public function getAllWithPagination(int $perPage = 10): LengthAwarePaginator
    {
        return Guarantee::with(['creator', 'updater'])->latest()->paginate($perPage);
    }
    
    /**
     * Get all guarantees.
     */
    public function getAll(): Collection
    {
        return Guarantee::with(['creator', 'updater'])->latest()->get();
    }
    
   /**
 * Find a guarantee by ID.
 *
 * @param int $id
 * @return \App\Models\Guarantee|null
 */
public function findById(int $id): ?Guarantee
{
    // Get the model directly using find, which returns a model instance or null
    $model = Guarantee::find($id);
    
    // If we found the model, load the relations
    if ($model instanceof Guarantee) {
        $model->load(['creator', 'updater', 'reviews.reviewer']);
    }
    
    return $model;
}

    /**
     * Find a guarantee by corporate reference number.
     */
    public function findByCorporateReferenceNumber(string $referenceNumber): ?Guarantee
    {
        return Guarantee::where('corporate_reference_number', $referenceNumber)->first();
    }
    
    /**
     * Create a new guarantee.
     */
    public function create(array $data): Guarantee
    {
        return Guarantee::create($data);
    }
    
    /**
     * Update a guarantee.
     */
    public function update(Guarantee $guarantee, array $data): bool
    {
        // Corporate reference number is immutable
        if (isset($data['corporate_reference_number'])) {
            unset($data['corporate_reference_number']);
        }
        
        return $guarantee->update($data);
    }
    
    /**
     * Delete a guarantee.
     */
    public function delete(Guarantee $guarantee): bool
    {
        return $guarantee->delete();
    }
    
    /**
     * Submit a guarantee for review.
     */
    public function submitForReview(Guarantee $guarantee, int $userId): bool
    {
        return DB::transaction(function () use ($guarantee, $userId) {
            $success = $guarantee->update([
                'status' => 'Under Review',
                'updated_by' => $userId,
            ]);
            
            if ($success) {
                GuaranteeReview::create([
                    'guarantee_id' => $guarantee->id,
                    'status' => 'Pending',
                ]);
            }
            
            return $success;
        });
    }
    
    /**
     * Apply for a guarantee.
     */
    public function applyForGuarantee(Guarantee $guarantee, int $userId): bool
    {
        return $guarantee->update([
            'status' => 'Applied',
            'updated_by' => $userId,
        ]);
    }
    
    /**
     * Issue a guarantee.
     */
    public function issueGuarantee(Guarantee $guarantee, int $userId): bool
    {
        return DB::transaction(function () use ($guarantee, $userId) {
            // Find the latest review
            $review = $guarantee->reviews()->latest()->first();
            
            if ($review && $review->isPending()) {
                $review->approve($userId);
            }
            
            return $guarantee->update([
                'status' => 'Issued',
                'updated_by' => $userId,
            ]);
        });
    }
    
    /**
     * Reject a guarantee.
     */
    public function rejectGuarantee(Guarantee $guarantee, int $userId, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($guarantee, $userId, $reason) {
            // Find the latest review
            $review = $guarantee->reviews()->latest()->first();
            
            if ($review && $review->isPending()) {
                $review->reject($userId, $reason);
            }
            
            return $guarantee->update([
                'status' => 'Rejected',
                'updated_by' => $userId,
            ]);
        });
    }
    
    /**
     * Create guarantees in bulk.
     */
    public function createBulk(array $guarantees, int $userId): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];
        
        foreach ($guarantees as $guaranteeData) {
            try {
                // Check if guarantee with this reference number already exists
                $existingGuarantee = $this->findByCorporateReferenceNumber($guaranteeData['corporate_reference_number']);
                
                if ($existingGuarantee) {
                    // Skip if it already exists
                    $results['failed']++;
                    $results['errors'][] = "Guarantee with corporate reference number {$guaranteeData['corporate_reference_number']} already exists.";
                    continue;
                }
                
                // Add user ID as creator
                $guaranteeData['created_by'] = $userId;
                
                // Create guarantee
                $this->create($guaranteeData);
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Error processing guarantee with corporate reference number {$guaranteeData['corporate_reference_number']}: {$e->getMessage()}";
            }
        }
        
        return $results;
    }
}