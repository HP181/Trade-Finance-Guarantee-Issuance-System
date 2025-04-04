<?php

namespace App\Repositories;

use App\Models\UploadedFile;
use App\Repositories\Interfaces\UploadedFileRepositoryInterface;
use App\Services\FileParserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class UploadedFileRepository implements UploadedFileRepositoryInterface
{
    /**
     * Get all uploaded files with pagination.
     */

     protected $uploadedFileRepository;
    protected $fileParserService;
    public function __construct(
        UploadedFileRepositoryInterface $uploadedFileRepository,
        FileParserService $fileParserService
    ) {
        // $this->middleware('auth');
        $this->uploadedFileRepository = $uploadedFileRepository;
        $this->fileParserService = $fileParserService;
    }
    
    public function getAllWithPagination(int $perPage = 10): LengthAwarePaginator
    {
        return UploadedFile::with('uploader')->latest()->paginate($perPage);
    }
    
    /**
 * Find an uploaded file by ID.
 *
 * @param int $id
 * @return \App\Models\UploadedFile|null
 */
public function findById(int $id): ?UploadedFile
{
    // First find the model
    $file = UploadedFile::find($id);
    
    // Then load relationships if the model exists
    if ($file instanceof UploadedFile) {
        $file->load('uploader');
    }
    
    return $file;
}
    
    /**
     * Store an uploaded file.
     */
    public function store(HttpUploadedFile $file, int $userId): UploadedFile
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $content = base64_encode(file_get_contents($file->getRealPath()));
        
        return UploadedFile::create([
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'content' => $content,
            'status' => 'Pending',
            'uploaded_by' => $userId,
        ]);
    }
    
    /**
     * Delete an uploaded file.
     */
    public function delete(UploadedFile $file): bool
    {
        return $file->delete();
    }
    
    /**
     * Get files that are pending processing.
     */
    public function getPendingFiles(): Collection
    {
        return UploadedFile::where('status', 'Pending')->get();
    }
    
    /**
     * Mark a file as processed.
     */
    public function markAsProcessed(UploadedFile $file, ?string $result = null): bool
    {
        return $file->markAsProcessed($result);
    }
    
    /**
     * Mark a file as failed.
     */
    public function markAsFailed(UploadedFile $file, string $error): bool
    {
        return $file->markAsFailed($error);
    }
}