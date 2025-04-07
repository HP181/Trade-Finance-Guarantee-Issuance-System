@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Upload New File') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="file" class="col-md-4 col-form-label text-md-end">{{ __('File') }}</label>

                            <div class="col-md-6">
                                <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" required>

                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="text-muted">Supported formats: CSV, JSON, XML. Maximum size: 10MB.</small>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Upload File') }}
                                </button>
                                <a href="{{ route('files.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Sample Files Section -->
                    <div class="mt-5">
                        <h5 class="mb-3">Sample Files</h5>
                        <p>You can download these sample files to see the expected format:</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('samples.csv') }}" target="_blank" class="btn btn-outline-primary">CSV Sample</a>
                            <a href="{{ route('samples.json') }}" target="_blank" class="btn btn-outline-primary">JSON Sample</a>
                            <a href="{{ route('samples.xml') }}" target="_blank" class="btn btn-outline-primary">XML Sample</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection