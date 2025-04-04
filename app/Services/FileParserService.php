<?php

namespace App\Services;

use App\Models\UploadedFile;
use App\Repositories\Interfaces\GuaranteeRepositoryInterface;
use App\Repositories\Interfaces\UploadedFileRepositoryInterface;
use Exception;

class FileParserService
{
    protected $guaranteeRepository;
    protected $uploadedFileRepository;
    protected $csvParserService;
    protected $jsonParserService;
    protected $xmlParserService;

    public function __construct(
        GuaranteeRepositoryInterface $guaranteeRepository,
        UploadedFileRepositoryInterface $uploadedFileRepository,
        CsvParserService $csvParserService,
        JsonParserService $jsonParserService,
        XmlParserService $xmlParserService
    ) {
        $this->guaranteeRepository = $guaranteeRepository;
        $this->uploadedFileRepository = $uploadedFileRepository;
        $this->csvParserService = $csvParserService;
        $this->jsonParserService = $jsonParserService;
        $this->xmlParserService = $xmlParserService;
    }

    /**
     * Process an uploaded file.
     */
    public function processFile(UploadedFile $file): array
    {
        try {
            // Parse the file based on its type
            $guarantees = $this->parseFile($file);
            
            // Create guarantees in bulk
            $results = $this->guaranteeRepository->createBulk($guarantees, $file->uploaded_by);
            
            // Mark the file as processed
            $this->uploadedFileRepository->markAsProcessed($file, json_encode($results));
            
            return [
                'success' => true,
                'results' => $results,
            ];
        } catch (Exception $e) {
            // Mark the file as failed
            $this->uploadedFileRepository->markAsFailed($file, $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Parse a file based on its extension.
     */
    protected function parseFile(UploadedFile $file): array
    {
        // Get file content
        $content = base64_decode($file->content);
        
        // Parse based on file type
        if ($file->isCsv()) {
            return $this->csvParserService->parse($content);
        } elseif ($file->isJson()) {
            return $this->jsonParserService->parse($content);
        } elseif ($file->isXml()) {
            return $this->xmlParserService->parse($content);
        } else {
            throw new Exception("Unsupported file type: {$file->mime_type}");
        }
    }
}