<?php
/**
 * LeadProof - CSV Parser Service
 * Responsible for safely reading CSV files
 */

declare(strict_types=1);

namespace App\Services;

use Exception;

class CsvParserService
{
    private string $filePath;
    private string $delimiter;
    private array $headers = [];

    public function __construct(string $filePath, string $delimiter = ',')
    {
        if (!file_exists($filePath)) {
            throw new Exception('CSV file not found');
        }

        $this->filePath  = $filePath;
        $this->delimiter = $delimiter;
    }

    public function parse(): iterable
    {
        $handle = fopen($this->filePath, 'r');

        if ($handle === false) {
            throw new Exception('Unable to open CSV file');
        }

        try {
            $rawHeaders = fgetcsv($handle, 0, $this->delimiter);

            if (!$rawHeaders || count($rawHeaders) === 0) {
                throw new Exception('CSV file has no headers');
            }

            $this->headers = $this->normalizeHeaders($rawHeaders);

            while (($row = fgetcsv($handle, 0, $this->delimiter)) !== false) {
                if ($this->isEmptyRow($row)) {
                    continue;
                }
                yield $this->mapRowToHeaders($row);
            }
        } finally {
            fclose($handle);
        }
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    private function normalizeHeaders(array $headers): array
    {
        // MAP: Input (Lowercase) => Output (Standard)
        $columnMap = [
            // Emails
            'email address' => 'email',
            'e-mail'        => 'email',
            'mail'          => 'email',
            
            // Names (Handle underscores and aliases)
            'first_name'    => 'first name',
            'last_name'     => 'last name',
            'fname'         => 'first name',
            'lname'         => 'last name',
            'given name'    => 'first name',
            'family name'   => 'last name',
            'surname'       => 'last name',
            
            // Company
            'organization'  => 'company',
            'business'      => 'company',
            
            // Phones
            'phone number'  => 'phone',
            'mobile'        => 'phone',
            'cell'          => 'phone',
        ];

        $normalized = [];

        foreach ($headers as $index => $header) {
            $header = trim($header);
            $header = $this->removeBom($header);
            $header = strtolower($header);

            // Apply Mapping
            if (isset($columnMap[$header])) {
                $header = $columnMap[$header];
            }

            if ($header === '') {
                $header = 'column_' . $index;
            }

            $normalized[] = $header;
        }

        return $normalized;
    }

    private function mapRowToHeaders(array $row): array
    {
        $mapped = [];
        foreach ($this->headers as $index => $header) {
            $mapped[$header] = $row[$index] ?? null;
        }
        return $mapped;
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string)$value) !== '') return false;
        }
        return true;
    }

    private function removeBom(string $text): string
    {
        return preg_replace('/^\xEF\xBB\xBF/', '', $text);
    }
}