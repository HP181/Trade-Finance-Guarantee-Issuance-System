<?php

namespace App\Services;

use Exception;
use SimpleXMLElement;

class XmlParserService
{
    /**
     * Parse XML content into an array of guarantees.
     */
    public function parse(string $content): array
    {
        try {
            // Parse XML
            $xml = new SimpleXMLElement($content);
            
            $guarantees = [];
            
            // Check if root element is 'guarantees' or 'guarantee'
            if ($xml->getName() === 'guarantees') {
                // Multiple guarantees
                foreach ($xml->guarantee as $item) {
                    $guarantee = $this->mapXmlToGuarantee($item);
                    $this->validateGuarantee($guarantee);
                    $guarantees[] = $guarantee;
                }
            } elseif ($xml->getName() === 'guarantee') {
                // Single guarantee
                $guarantee = $this->mapXmlToGuarantee($xml);
                $this->validateGuarantee($guarantee);
                $guarantees[] = $guarantee;
            } else {
                throw new Exception("Invalid XML format: Root element should be 'guarantees' or 'guarantee'");
            }
            
            return $guarantees;
        } catch (Exception $e) {
            throw new Exception("Error parsing XML: {$e->getMessage()}");
        }
    }

    /**
     * Map XML element to guarantee array.
     */
    protected function mapXmlToGuarantee(SimpleXMLElement $item): array
    {
        // Map XML fields to guarantee fields
        return [
            'corporate_reference_number' => (string) $item->corporate_reference_number ?? null,
            'guarantee_type' => (string) $item->guarantee_type ?? null,
            'nominal_amount' => (string) $item->nominal_amount ?? null,
            'nominal_amount_currency' => (string) $item->nominal_amount_currency ?? null,
            'expiry_date' => (string) $item->expiry_date ?? null,
            'applicant_name' => (string) ($item->applicant->name ?? $item->applicant_name ?? null),
            'applicant_address' => (string) ($item->applicant->address ?? $item->applicant_address ?? null),
            'beneficiary_name' => (string) ($item->beneficiary->name ?? $item->beneficiary_name ?? null),
            'beneficiary_address' => (string) ($item->beneficiary->address ?? $item->beneficiary_address ?? null),
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