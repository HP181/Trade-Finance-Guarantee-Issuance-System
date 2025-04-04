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

                    <h3 class="mb-4">{{ __('Trade Finance Guarantee System') }}</h3>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Manage Guarantees</h5>
                                    <p class="card-text">Create, review, and manage guarantees</p>
                                    <a href="{{ route('guarantees.index') }}" class="btn btn-primary">View Guarantees</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Create New Guarantee</h5>
                                    <p class="card-text">Create a new guarantee manually</p>
                                    <a href="{{ route('guarantees.create') }}" class="btn btn-success">Create Guarantee</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Upload Guarantees</h5>
                                    <p class="card-text">Bulk upload guarantees via CSV, JSON, or XML</p>
                                    <a href="{{ route('files.create') }}" class="btn btn-info">Upload Files</a>
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