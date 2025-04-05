@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <!-- <span>{{ __('Uploaded Files') }}</span> -->
                    <a href="{{ route('files.create') }}" class="btn btn-primary btn-sm">Upload New File</a>
                </div>
<h1>reh</h1>
                <!-- <div class="card-body">
                    @if ($files->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Status</th>
                                        <th>Uploaded By</th>
                                        <th>Upload Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($files as $file)
                                        <tr>
                                            <td>{{ $file->original_filename }}</td>
                                            <td>{{ strtoupper(pathinfo($file->original_filename, PATHINFO_EXTENSION)) }}</td>
                                            <td>{{ round($file->size / 1024, 2) }} KB</td>
                                            <td>
                                                <span class="badge 
                                                    @if ($file->status == 'Pending') bg-warning
                                                    @elseif ($file->status == 'Processed') bg-success
                                                    @elseif ($file->status == 'Failed') bg-danger
                                                    @endif">
                                                    {{ $file->status }}
                                                </span>
                                            </td>
                                            <td>{{ $file->uploader->name ?? 'N/A' }}</td>
                                            <td>{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-info">Download</a>
                                                    
                                                    @if ($file->status == 'Pending')
                                                        <form action="{{ route('files.process', $file) }}" method="POST" style="display: inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Process</button>
                                                        </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('files.destroy', $file) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $files->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            No files uploaded yet. <a href="{{ route('files.create') }}">Upload a new file</a>.
                        </div>
                    @endif
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection