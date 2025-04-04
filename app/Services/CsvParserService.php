<?php

namespace App\Services;

use Exception;
use League\Csv\Reader;

class CsvParserService
{
    /**
     * Parse CSV content into an array of guarantees.
     */
    public function parse(string $content): array
    {
        try {
            // Create a CSV reader
            $csv = Reader::createFromString($content);
            $csv->setHeaderOffset(0);
            
            $guarantees = [];
            $records = $csv->getRecords();
            
            foreach ($records as $record) {
                // Map CSV fields to guarantee fields
                $guarantee = $this->mapCsvToGuarantee($record);
                
                // Validate required fields
                $this->validateGuarantee($guarantee);
                
                $guarantees[] = $guarantee;
            }
            
            return $guarantees;
        } catch (Exception $e) {
            throw new Exception("Error parsing CSV: {$e->getMessage()}");
        }
    }

    /**
     * Map CSV record to guarantee array.
     */
    protected function mapCsvToGuarantee(array $record): array
    {
        // Map CSV fields to guarantee fields
        // The mapping should match the expected structure in the sample file
        return [
            'corporate_reference_number' => $record['corporate_reference_number'] ?? null,
            'guarantee_type' => $record['guarantee_type'] ?? null,
            'nominal_amount' => $record['nominal_amount'] ?? null,
            'nominal_amount_currency' => $record['nominal_amount_currency'] ?? null,
            'expiry_date' => $record['expiry_date'] ?? null,
            'applicant_name' => $record['applicant_name'] ?? null,
            'applicant_address' => $record['applicant_address'] ?? null,
            'beneficiary_name' => $record['beneficiary_name'] ?? null,
            'beneficiary_address' => $record['beneficiary_address'] ?? null,
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