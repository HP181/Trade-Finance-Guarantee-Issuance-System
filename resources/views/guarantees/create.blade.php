@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Create New Guarantee') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('guarantees.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="corporate_reference_number" class="form-label">Corporate Reference Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('corporate_reference_number') is-invalid @enderror" id="corporate_reference_number" name="corporate_reference_number" value="{{ old('corporate_reference_number') }}" required>
                                @error('corporate_reference_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">Unique identifier for this guarantee. Cannot be changed after creation.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="guarantee_type" class="form-label">Guarantee Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('guarantee_type') is-invalid @enderror" id="guarantee_type" name="guarantee_type" required>
                                    <option value="">Select a type</option>
                                    @foreach ($guaranteeTypes as $type)
                                        <option value="{{ $type }}" {{ old('guarantee_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('guarantee_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nominal_amount" class="form-label">Nominal Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('nominal_amount') is-invalid @enderror" id="nominal_amount" name="nominal_amount" value="{{ old('nominal_amount') }}" required>
                                @error('nominal_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nominal_amount_currency" class="form-label">Currency <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nominal_amount_currency') is-invalid @enderror" id="nominal_amount_currency" name="nominal_amount_currency" value="{{ old('nominal_amount_currency', 'USD') }}" required maxlength="3">
                                @error('nominal_amount_currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">Three-letter ISO currency code (e.g., USD, EUR, GBP)</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expiry_date" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" required min="{{ date('Y-m-d') }}">
                                @error('expiry_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">Must be today or a future date</div>
                            </div>
                        </div>

                        <h4 class="mt-4 mb-3">Applicant Information</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="applicant_name" class="form-label">Applicant Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" required>
                                @error('applicant_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="applicant_address" class="form-label">Applicant Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('applicant_address') is-invalid @enderror" id="applicant_address" name="applicant_address" rows="3" required>{{ old('applicant_address') }}</textarea>
                                @error('applicant_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4 mb-3">Beneficiary Information</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="beneficiary_name" class="form-label">Beneficiary Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('beneficiary_name') is-invalid @enderror" id="beneficiary_name" name="beneficiary_name" value="{{ old('beneficiary_name') }}" required>
                                @error('beneficiary_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="beneficiary_address" class="form-label">Beneficiary Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('beneficiary_address') is-invalid @enderror" id="beneficiary_address" name="beneficiary_address" rows="3" required>{{ old('beneficiary_address') }}</textarea>
                                @error('beneficiary_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('guarantees.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Guarantee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection