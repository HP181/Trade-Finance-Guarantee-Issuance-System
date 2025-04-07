@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Guarantees') }}</span>
                    <a href="{{ route('guarantees.create') }}" class="btn btn-primary">Create New Guarantee</a>
                </div>

                <div class="card-body">
                    @if ($guarantees->isEmpty())
                        <p>No guarantees found.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Reference Number</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guarantees as $guarantee)
                                        <tr>
                                            <td>{{ $guarantee->corporate_reference_number }}</td>
                                            <td>{{ $guarantee->guarantee_type }}</td>
                                            <td>{{ number_format($guarantee->nominal_amount, 2) }}</td>
                                            <td>{{ $guarantee->nominal_amount_currency }}</td>
                                            <td>{{ $guarantee->expiry_date->format('Y-m-d') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $guarantee->status == 'draft' ? 'secondary' : ($guarantee->status == 'review' ? 'info' : ($guarantee->status == 'applied' ? 'primary' : ($guarantee->status == 'issued' ? 'success' : 'danger'))) }}">
                                                    {{ ucfirst($guarantee->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('guarantees.show', $guarantee->id) }}" class="btn btn-sm btn-info">View</a>
                                                    @if ($guarantee->isDraft() && (Auth::user()->isAdmin() || $guarantee->user_id == Auth::id()))
                                                        <a href="{{ route('guarantees.edit', $guarantee->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                    @endif
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