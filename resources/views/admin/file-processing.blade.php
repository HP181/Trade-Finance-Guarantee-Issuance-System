@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('File Processing') }}</span>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>

                <div class="card-body">
                    @if ($pendingFiles->isEmpty())
                        <div class="alert alert-info">No files pending processing.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Type</th>
                                        <th>Uploaded By</th>
                                        <th>Upload Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingFiles as $file)
                                        <tr>
                                            <td>{{ $file->filename }}</td>
                                            <td>{{ strtoupper($file->file_type) }}</td>
                                            <td>{{ $file->user->name }}</td>
                                            <td>{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('files.show', $file->id) }}" class="btn btn-sm btn-info">View</a>
                                                    <form method="POST" action="{{ route('files.process', $file->id) }}" class="ms-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to process this file?')">
                                                            Process
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
