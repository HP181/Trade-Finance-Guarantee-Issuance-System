@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Review Guarantee') }}</span>
                    <a href="{{ route('admin.pending-reviews') }}" class="btn btn-secondary">Back to Pending Reviews</a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h4>Guarantee Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Reference Number:</th>
                                    <td>{{ $guarantee->corporate_reference_number }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $guarantee->guarantee_type }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal Amount:</th>
                                    <td>{{ number_format($guarantee->nominal_amount, 2) }} {{ $guarantee->nominal_amount_currency }}</td>
                                </tr>
                                <tr>
                                    <th>Expiry Date:</th>
                                    <td>{{ $guarantee->expiry_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-info">Review</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Submitted By:</th>
                                    <td>{{ $guarantee->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $guarantee->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Parties Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Applicant Name:</th>
                                    <td>{{ $guarantee->applicant_name }}</td>
                                </tr>
                                <tr>
                                    <th>Applicant Address:</th>
                                    <td>{{ $guarantee->applicant_address }}</td>
                                </tr>
                                <tr>
                                    <th>Beneficiary Name:</th>
                                    <td>{{ $guarantee->beneficiary_name }}</td>
                                </tr>
                                <tr>
                                    <th>Beneficiary Address:</th>
                                    <td>{{ $guarantee->beneficiary_address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Review Actions</h4>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('guarantees.apply', $guarantee->id) }}" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to apply this guarantee?')">
                                        Approve & Apply Guarantee
                                    </button>
                                </form>
                                
                                <!-- Reject Button Trigger Modal -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    Reject Guarantee
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('guarantees.reject', $guarantee->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Guarantee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="review_notes" class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="review_notes" name="review_notes" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Guarantee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection