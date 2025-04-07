@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ __('File Details') }}</span>
                        <div>
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">Back to List</a>

                            @if (Auth::user()->isAdmin() && $file->isUploaded())
                                <form method="POST" action="{{ route('files.process', $file->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Are you sure you want to process this file?')">
                                        Process File
                                    </button>
                                </form>
                            @endif

                            @if (Auth::user()->isAdmin() || $file->user_id == Auth::id())
                                <form method="POST" action="{{ route('files.destroy', $file->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this file? This action cannot be undone.')">
                                        Delete File
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h4>File Information</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Filename:</th>
                                        <td>{{ $file->filename }}</td>
                                    </tr>
                                    <tr>
                                        <th>File Type:</th>
                                        <td>{{ strtoupper($file->file_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span
                                                class="badge bg-{{ $file->status == 'uploaded' ? 'warning' : ($file->status == 'processed' ? 'success' : 'danger') }}">
                                                {{ ucfirst($file->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Uploaded By:</th>
                                        <td>{{ $file->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Upload Date:</th>
                                        <td>{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>Processing Information</h4>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Processing Status:</th>
                                        <td>
                                            @if ($file->isUploaded())
                                                <span class="text-warning">Pending Processing</span>
                                            @elseif ($file->isProcessed())
                                                <span class="text-success">Processed Successfully</span>
                                            @else
                                                <span class="text-danger">Processing Failed</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Processing Notes:</th>
                                        <td>{{ $file->processing_notes ?: 'No processing notes available' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $file->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- In the File Preview section of your show.blade.php file -->
                        <!-- In the File Preview section of your show.blade.php file -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>File Preview</h4>

                                <!-- Add the buttons here -->
                                <div class="mb-3">
                                    <a href="{{ route('files.view-content', $file->id) }}" target="_blank"
                                        class="btn btn-primary">
                                        <i class="fas fa-eye"></i> View Full Content
                                    </a>
                                    <a href="{{ route('files.download-content', $file->id) }}" class="btn btn-success">
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                </div>

                                <div class="border p-3 bg-light">
                                    @php
                                        // Only show preview for text-based files
                                        $isTextFile = in_array(strtolower($file->file_type), ['csv', 'json', 'xml', 'txt']);
                                        if ($isTextFile) {
                                            // Convert binary to text for preview
                                            $preview = mb_substr($file->file_contents, 0, 1000, 'UTF-8');
                                            $hasMore = mb_strlen($file->file_contents, 'UTF-8') > 1000;
                                        } else {
                                            $preview = "[Binary file content - preview not available]";
                                            $hasMore = false;
                                        }
                                    @endphp
                                    <pre>{{ $preview }}{{ $hasMore ? '...' : '' }}</pre>
                                </div>
                                <div class="small text-muted mt-2">
                                    Note: Preview may be truncated for large files. Use the buttons above to view or
                                    download the full content.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection