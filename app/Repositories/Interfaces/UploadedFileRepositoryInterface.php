<?php

namespace App\Repositories\Interfaces;

use App\Models\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

interface UploadedFileRepositoryInterface
{
    /**
     * Get all uploaded files with pagination.
     */
    public function getAllWithPagination(int $perPage = 10): LengthAwarePaginator;
    
    /**
     * Find an uploaded file by ID.
     */
    public function findById(int $id): ?UploadedFile;
    
    /**
     * Store an uploaded file.
     */
    public function store(HttpUploadedFile $file, int $userId): UploadedFile;
    
    /**
     * Delete an uploaded file.
     */
    public function delete(UploadedFile $file): bool;
    
    /**
     * Get files that are pending processing.
     */
    public function getPendingFiles(): Collection;
    
    /**
     * Mark a file as processed.
     */
    public function markAsProcessed(UploadedFile $file, ?string $result = null): bool;
    
    /**
     * Mark a file as failed.
     */
    public function markAsFailed(UploadedFile $file, string $error): bool;
}