@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Guarantees') }}</span>
                    <a href="{{ route('guarantees.create') }}" class="btn btn-primary btn-sm">Create New Guarantee</a>
                </div>

                <div class="card-body">
                    @if ($guarantees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Reference Number</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guarantees as $guarantee)
                                        <tr>
                                            <td>{{ $guarantee->corporate_reference_number }}</td>
                                            <td>{{ $guarantee->guarantee_type }}</td>
                                            <td>{{ $guarantee->nominal_amount }} {{ $guarantee->nominal_amount_currency }}</td>
                                            <td>{{ $guarantee->expiry_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if ($guarantee->status == 'Draft') bg-secondary
                                                    @elseif ($guarantee->status == 'Under Review') bg-primary
                                                    @elseif ($guarantee->status == 'Applied') bg-info
                                                    @elseif ($guarantee->status == 'Issued') bg-success
                                                    @elseif ($guarantee->status == 'Expired') bg-warning
                                                    @elseif ($guarantee->status == 'Rejected') bg-danger
                                                    @endif">
                                                    {{ $guarantee->status }}
                                                </span>
                                            </td>
                                            <td>{{ $guarantee->creator->name ?? 'N/A' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('guarantees.show', $guarantee) }}" class="btn btn-sm btn-info">View</a>
                                                    @if ($guarantee->status == 'Draft')
                                                        <a href="{{ route('guarantees.edit', $guarantee) }}" class="btn btn-sm btn-primary">Edit</a>
                                                        <form action="{{ route('guarantees.destroy', $guarantee) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this guarantee?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $guarantees->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            No guarantees found. <a href="{{ route('guarantees.create') }}">Create a new one</a> or <a href="{{ route('files.create') }}">upload guarantees</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection