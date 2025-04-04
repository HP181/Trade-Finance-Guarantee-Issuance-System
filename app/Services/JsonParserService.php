<?php

namespace App\Services;

use Exception;

class JsonParserService
{
    /**
     * Parse JSON content into an array of guarantees.
     */
    public function parse(string $content): array
    {
        try {
            // Decode JSON
            $data = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON format: " . json_last_error_msg());
            }
            
            // If single object, convert to array
            if (isset($data['corporate_reference_number'])) {
                $data = [$data];
            }
            
            $guarantees = [];
            
            foreach ($data as $item) {
                // Map JSON fields to guarantee fields
                $guarantee = $this->mapJsonToGuarantee($item);
                
                // Validate required fields
                $this->validateGuarantee($guarantee);
                
                $guarantees[] = $guarantee;
            }
            
            return $guarantees;
        } catch (Exception $e) {
            throw new Exception("Error parsing JSON: {$e->getMessage()}");
        }
    }

    /**
     * Map JSON item to guarantee array.
     */
    protected function mapJsonToGuarantee(array $item): array
    {
        // Map JSON fields to guarantee fields
        return [
            'corporate_reference_number' => $item['corporate_reference_number'] ?? null,
            'guarantee_type' => $item['guarantee_type'] ?? null,
            'nominal_amount' => $item['nominal_amount'] ?? null,
            'nominal_amount_currency' => $item['nominal_amount_currency'] ?? null,
            'expiry_date' => $item['expiry_date'] ?? null,
            'applicant_name' => $item['applicant']['name'] ?? ($item['applicant_name'] ?? null),
            'applicant_address' => $item['applicant']['address'] ?? ($item['applicant_address'] ?? null),
            'beneficiary_name' => $item['beneficiary']['name'] ?? ($item['beneficiary_name'] ?? null),
            'beneficiary_address' => $item['beneficiary']['address'] ?? ($item['beneficiary_address'] ?? null),
            'status' => 'Draft', // Default status for imported guarantees
        ];
    }

    /**
     * Validate required fields in a guarantee.
     */
    protected function validateGuarantee(array $guarantee): void
    {
        $requiredFields = [
            'corporate_reference_number',
            'guarantee_type',
            'nominal_amount',
            'nominal_amount_currency',
            'expiry_date',
            'applicant_name',
            'applicant_address',
            'beneficiary_name',
            'beneficiary_address',
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($guarantee[$field])) {
                throw new Exception("Missing required field: {$field}");
            }
        }
        
        // Validate guarantee type
        $validTypes = ['Bank', 'Bid Bond', 'Insurance', 'Surety'];
        if (!in_array($guarantee['guarantee_type'], $validTypes)) {
            throw new Exception("Invalid guarantee type: {$guarantee['guarantee_type']}");
        }
        
        // Validate expiry date
        $expiryDate = date('Y-m-d', strtotime($guarantee['expiry_date']));
        if ($expiryDate < date('Y-m-d')) {
            throw new Exception("Expiry date must be in the future: {$guarantee['expiry_date']}");
        }
        
        // Validate nominal amount
        if (!is_numeric($guarantee['nominal_amount'])) {
            throw new Exception("Nominal amount must be a number: {$guarantee['nominal_amount']}");
        }
    }
}