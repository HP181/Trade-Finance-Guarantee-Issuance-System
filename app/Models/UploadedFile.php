<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'content',
        'status',
        'processing_result',
        'uploaded_by',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public const STATUSES = [
        'Pending' => 'Pending',
        'Processed' => 'Processed',
        'Failed' => 'Failed',
    ];

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Check if the file is pending processing.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if the file has been processed.
     */
    public function isProcessed(): bool
    {
        return $this->status === 'Processed';
    }

    /**
     * Check if processing the file failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'Failed';
    }

    /**
     * Get file content as a string.
     */
    public function getContentAsString(): string
    {
        return $this->content;
    }

    /**
     * Get file extension.
     */
    public function getExtension(): string
    {
        return pathinfo($this->original_filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if the file is a CSV.
     */
    public function isCsv(): bool
    {
        return strtolower($this->getExtension()) === 'csv' || $this->mime_type === 'text/csv';
    }

    /**
     * Check if the file is a JSON.
     */
    public function isJson(): bool
    {
        return strtolower($this->getExtension()) === 'json' || $this->mime_type === 'application/json';
    }

    /**
     * Check if the file is an XML.
     */
    public function isXml(): bool
    {
        return strtolower($this->getExtension()) === 'xml' || $this->mime_type === 'application/xml' || $this->mime_type === 'text/xml';
    }

    /**
     * Mark file as processed.
     */
    public function markAsProcessed(?string $result = null): bool
    {
        $this->status = 'Processed';
        $this->processing_result = $result;
        $this->processed_at = now();
        
        return $this->save();
    }

    /**
     * Mark file as failed.
     */
    public function markAsFailed(string $error): bool
    {
        $this->status = 'Failed';
        $this->processing_result = $error;
        $this->processed_at = now();
        
        return $this->save();
    }
}