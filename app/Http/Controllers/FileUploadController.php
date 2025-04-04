<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Repositories\Interfaces\UploadedFileRepositoryInterface;
use App\Services\FileParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class FileUploadController extends Controller
{
    protected $uploadedFileRepository;
    protected $fileParserService;

    public function __construct(
        UploadedFileRepositoryInterface $uploadedFileRepository,
        FileParserService $fileParserService = null
    ) {
        $this->uploadedFileRepository = $uploadedFileRepository;
        $this->fileParserService = $fileParserService;
    }

    public function index()
    {
        try {
            // Create an empty paginator if there are no files
            $files = new LengthAwarePaginator([], 0, 10);
            
            // Try to get files from repository if it exists
            if (method_exists($this->uploadedFileRepository, 'getAllWithPagination')) {
                $files = $this->uploadedFileRepository->getAllWithPagination();
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in FileUploadController@index: ' . $e->getMessage());
            $files = new LengthAwarePaginator([], 0, 10);
        }
        
        return view('files.index', compact('files'));
    }

    public function create()
    {
        return view('files.upload');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,json,xml|max:10240',
            ]);
            
            $file = $request->file('file');
            $uploadedFile = $this->uploadedFileRepository->store($file, Auth::id());
            
            return redirect()->route('files.index')
                ->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            \Log::error('Error in FileUploadController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error uploading file: ' . $e->getMessage());
        }
    }

    /**
     * Process an uploaded file.
     */
    public function process(UploadedFile $file)
    {
        $result = $this->fileParserService->processFile($file);
        
        if ($result['success']) {
            return redirect()->route('files.index')
                ->with('success', 'File processed successfully. Created ' . $result['results']['success'] . ' guarantees.');
        } else {
            return redirect()->route('files.index')
                ->with('error', 'Failed to process file: ' . $result['error']);
        }
    }

    /**
     * Download an uploaded file.
     */
    public function download(UploadedFile $file)
    {
        $content = base64_decode($file->content);
        
        return response($content)
            ->header('Content-Type', $file->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $file->original_filename . '"');
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy(UploadedFile $file)
    {
        $this->uploadedFileRepository->delete($file);
        
        return redirect()->route('files.index')
            ->with('success', 'File deleted successfully.');
    }
}