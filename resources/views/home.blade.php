@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>{{ __('Your Guarantees') }}</span>
                                    <a href="{{ route('guarantees.create') }}" class="btn btn-sm btn-primary">Create New</a>
                                </div>
                                <div class="card-body">
                                    @if ($guarantees->isEmpty())
                                        <p>No guarantees found.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Reference</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($guarantees->take(5) as $guarantee)
                                                        <tr>
                                                            <td>{{ $guarantee->corporate_reference_number }}</td>
                                                            <td>{{ $guarantee->guarantee_type }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $guarantee->status == 'draft' ? 'secondary' : ($guarantee->status == 'review' ? 'info' : ($guarantee->status == 'applied' ? 'primary' : ($guarantee->status == 'issued' ? 'success' : 'danger'))) }}">
                                                                    {{ ucfirst($guarantee->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('guarantees.show', $guarantee->id) }}" class="btn btn-sm btn-info">View</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($guarantees->count() > 5)
                                            <div class="text-center mt-3">
                                                <a href="{{ route('guarantees.index') }}" class="btn btn-outline-primary">View All</a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>{{ __('Your Files') }}</span>
                                    <a href="{{ route('files.create') }}" class="btn btn-sm btn-primary">Upload New</a>
                                </div>
                                <div class="card-body">
                                    @if ($files->isEmpty())
                                        <p>No files found.</p>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Filename</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($files->take(5) as $file)
                                                        <tr>
                                                            <td>{{ $file->filename }}</td>
                                                            <td>{{ strtoupper($file->file_type) }}</td>
                                                            <td>
                                                                <span class="badge bg-{{ $file->status == 'uploaded' ? 'warning' : ($file->status == 'processed' ? 'success' : 'danger') }}">
                                                                    {{ ucfirst($file->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('files.show', $file->id) }}" class="btn btn-sm btn-info">View</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($files->count() > 5)
                                            <div class="text-center mt-3">
                                                <a href="{{ route('files.index') }}" class="btn btn-outline-primary">View All</a>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection