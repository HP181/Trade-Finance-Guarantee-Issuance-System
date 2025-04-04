@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Upload Guarantee Data File') }}</div>

                <div class="card-body">
                <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-2">
                                <label for="file" class="form-label">Select File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                                @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-text">
                                    <p>Accepted file types: CSV, JSON, XML (Max size: 10MB)</p>
                                    <p>The file should contain guarantee data in the appropriate format. Please make sure it follows the required structure.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8 offset-md-2">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>File Format Examples</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="formatTabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="csv-tab" data-bs-toggle="tab" data-bs-target="#csv" type="button" role="tab" aria-controls="csv" aria-selected="true">CSV</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="json-tab" data-bs-toggle="tab" data-bs-target="#json" type="button" role="tab" aria-controls="json" aria-selected="false">JSON</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="xml-tab" data-bs-toggle="tab" data-bs-target="#xml" type="button" role="tab" aria-controls="xml" aria-selected="false">XML</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="formatTabsContent">
                                            <div class="tab-pane fade show active" id="csv" role="tabpanel" aria-labelledby="csv-tab">
                                                <div class="mt-3">
                                                    <h6>CSV Format</h6>
                                                    <pre class="bg-light p-3 border rounded mt-2" style="font-size: 12px; overflow-x: auto;">
corporate_reference_number,guarantee_type,nominal_amount,nominal_amount_currency,expiry_date,applicant_name,applicant_address,beneficiary_name,beneficiary_address
GB12345678,Bank,50000,USD,2023-12-31,ACME Inc.,123 Main St London UK,XYZ Corp,456 Park Ave New York USA
GB87654321,Bid Bond,75000,EUR,2023-11-30,ABC Ltd.,789 Oxford St London UK,DEF GmbH,101 Berlin St Berlin Germany</pre>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="json" role="tabpanel" aria-labelledby="json-tab">
                                                <div class="mt-3">
                                                    <h6>JSON Format</h6>
                                                    <pre class="bg-light p-3 border rounded mt-2" style="font-size: 12px; overflow-x: auto;">
[
  {
    "corporate_reference_number": "GB12345678",
    "guarantee_type": "Bank",
    "nominal_amount": 50000,
    "nominal_amount_currency": "USD",
    "expiry_date": "2023-12-31",
    "applicant": {
      "name": "ACME Inc.",
      "address": "123 Main St London UK"
    },
    "beneficiary": {
      "name": "XYZ Corp",
      "address": "456 Park Ave New York USA"
    }
  },
  {
    "corporate_reference_number": "GB87654321",
    "guarantee_type": "Bid Bond",
    "nominal_amount": 75000,
    "nominal_amount_currency": "EUR",
    "expiry_date": "2023-11-30",
    "applicant": {
      "name": "ABC Ltd.",
      "address": "789 Oxford St London UK"
    },
    "beneficiary": {
      "name": "DEF GmbH",
      "address": "101 Berlin St Berlin Germany"
    }
  }
]</pre>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="xml" role="tabpanel" aria-labelledby="xml-tab">
                                                <div class="mt-3">
                                                    <h6>XML Format</h6>
                                                    <pre class="bg-light p-3 border rounded mt-2" style="font-size: 12px; overflow-x: auto;">
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;guarantees&gt;
  &lt;guarantee&gt;
    &lt;corporate_reference_number&gt;GB12345678&lt;/corporate_reference_number&gt;
    &lt;guarantee_type&gt;Bank&lt;/guarantee_type&gt;
    &lt;nominal_amount&gt;50000&lt;/nominal_amount&gt;
    &lt;nominal_amount_currency&gt;USD&lt;/nominal_amount_currency&gt;
    &lt;expiry_date&gt;2023-12-31&lt;/expiry_date&gt;
    &lt;applicant&gt;
      &lt;name&gt;ACME Inc.&lt;/name&gt;
      &lt;address&gt;123 Main St London UK&lt;/address&gt;
    &lt;/applicant&gt;
    &lt;beneficiary&gt;
      &lt;name&gt;XYZ Corp&lt;/name&gt;
      &lt;address&gt;456 Park Ave New York USA&lt;/address&gt;
    &lt;/beneficiary&gt;
  &lt;/guarantee&gt;
  &lt;guarantee&gt;
    &lt;corporate_reference_number&gt;GB87654321&lt;/corporate_reference_number&gt;
    &lt;guarantee_type&gt;Bid Bond&lt;/guarantee_type&gt;
    &lt;nominal_amount&gt;75000&lt;/nominal_amount&gt;
    &lt;nominal_amount_currency&gt;EUR&lt;/nominal_amount_currency&gt;
    &lt;expiry_date&gt;2023-11-30&lt;/expiry_date&gt;
    &lt;applicant&gt;
      &lt;name&gt;ABC Ltd.&lt;/name&gt;
      &lt;address&gt;789 Oxford St London UK&lt;/address&gt;
    &lt;/applicant&gt;
    &lt;beneficiary&gt;
      &lt;name&gt;DEF GmbH&lt;/name&gt;
      &lt;address&gt;101 Berlin St Berlin Germany&lt;/address&gt;
    &lt;/beneficiary&gt;
  &lt;/guarantee&gt;
&lt;/guarantees&gt;</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('files.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Upload File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection