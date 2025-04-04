@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Guarantee Details') }}</span>
                    <a href="{{ route('guarantees.index') }}" class="btn btn-primary btn-sm">Back to List</a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4 class="mb-3">Guarantee Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 40%">Corporate Reference Number</th>
                                    <td>{{ $guarantee->corporate_reference_number }}</td>
                                </tr>
                                <tr>
                                    <th>Guarantee Type</th>
                                    <td>{{ $guarantee->guarantee_type }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal Amount</th>
                                    <td>{{ number_format($guarantee->nominal_amount, 2) }} {{ $guarantee->nominal_amount_currency }}</td>
                                </tr>
                                <tr>
                                    <th>Expiry Date</th>
                                    <td>{{ $guarantee->expiry_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
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
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4 class="mb-3">Parties Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Applicant Name</th>
                                    <td>{{ $guarantee->applicant_name }}</td>
                                </tr>
                                <tr>
                                    <th>Applicant Address</th>
                                    <td>{{ $guarantee->applicant_address }}</td>
                                </tr>
                                <tr>
                                    <th>Beneficiary Name</th>
                                    <td>{{ $guarantee->beneficiary_name }}</td>
                                </tr>
                                <tr>
                                    <th>Beneficiary Address</th>
                                    <td>{{ $guarantee->beneficiary_address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="mb-3">Audit Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 20%">Created By</th>
                                    <td>{{ $guarantee->creator->name ?? 'N/A' }}</td>
                                    <th style="width: 20%">Created At</th>
                                    <td>{{ $guarantee->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated By</th>
                                    <td>{{ $guarantee->updater->name ?? 'N/A' }}</td>
                                    <th>Last Updated At</th>
                                    <td>{{ $guarantee->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($guarantee->reviews->count() > 0)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h4 class="mb-3">Review History</h4>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Reviewer</th>
                                            <th>Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($guarantee->reviews->sortByDesc('created_at') as $review)
                                            <tr>
                                                <td>{{ $review->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    <span class="badge 
                                                        @if ($review->status == 'Pending') bg-warning
                                                        @elseif ($review->status == 'Approved') bg-success
                                                        @elseif ($review->status == 'Rejected') bg-danger
                                                        @endif">
                                                        {{ $review->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $review->reviewer->name ?? 'N/A' }}</td>
                                                <td>{{ $review->comments ?? 'No comments' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mt-4">
                        @if ($guarantee->status == 'Draft')
                            <a href="{{ route('guarantees.edit', $guarantee) }}" class="btn btn-primary me-2">Edit</a>
                            
                            <form action="{{ route('guarantees.submit-for-review', $guarantee) }}" method="POST" class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-info">Submit for Review</button>
                            </form>
                            
                            <form action="{{ route('guarantees.destroy', $guarantee) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this guarantee?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @elseif ($guarantee->status == 'Under Review')
                            <form action="{{ route('guarantees.reject', $guarantee) }}" method="POST" class="me-2">
                                @csrf
                                <input type="text" name="reason" placeholder="Rejection reason (optional)" class="form-control d-inline-block me-2" style="width: 300px;">
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </form>
                            
                            <form action="{{ route('guarantees.apply', $guarantee) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve & Apply</button>
                            </form>
                        @elseif ($guarantee->status == 'Applied')
                            <form action="{{ route('guarantees.issue', $guarantee) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Issue Guarantee</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection