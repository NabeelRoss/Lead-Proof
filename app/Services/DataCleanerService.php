<?php
/**
 * LeadProof - Data Cleaner Service
 * Responsible for cleaning and validating CSV row data
 */

declare(strict_types=1);

namespace App\Services;

class DataCleanerService
{
    private array $seenEmails = [];

    private array $stats = [
        'total_rows' => 0,
        'valid_rows' => 0,
        'excluded_rows' => 0,
        'duplicates' => 0,
        'invalid_emails' => 0,
        'fixed_names' => 0,
        'fixed_phones' => 0,
        'fixed_dates' => 0,
    ];

    private array $excluded = [];

    /**
     * Clean a single CSV row
     */
    public function cleanRow(array $row): ?array
    {
        $this->stats['total_rows']++;

        // Normalize values
        $row = $this->trimValues($row);

        // Email is mandatory
        if (empty($row['email']) || !$this->isValidEmail($row['email'])) {
            $this->stats['invalid_emails']++;
            $this->excludeRow($row, 'Invalid or missing email');
            return null;
        }

        $email = strtolower($row['email']);
        $row['email'] = $email;

        // Deduplicate by email
        if (isset($this->seenEmails[$email])) {
            $this->stats['duplicates']++;
            $this->excludeRow($row, 'Duplicate email');
            return null;
        }

        $this->seenEmails[$email] = true;

        // Name normalization
        if (!empty($row['first name'])) {
            $row['first name'] = $this->normalizeName($row['first name']);
            $this->stats['fixed_names']++;
        }

        if (!empty($row['last name'])) {
            $row['last name'] = $this->normalizeName($row['last name']);
            $this->stats['fixed_names']++;
        }

        // Phone normalization
        if (!empty($row['phone'])) {
            $normalizedPhone = $this->normalizePhone($row['phone']);
            if ($normalizedPhone !== null) {
                $row['phone'] = $normalizedPhone;
                $this->stats['fixed_phones']++;
            }
        }

        // Date normalization (example: created date)
        if (!empty($row['created date'])) {
            $normalizedDate = $this->normalizeDate($row['created date']);
            if ($normalizedDate !== null) {
                $row['created date'] = $normalizedDate;
                $this->stats['fixed_dates']++;
            }
        }

        $this->stats['valid_rows']++;
        return $row;
    }

    /**
     * Trim all values in row
     */
    private function trimValues(array $row): array
    {
        foreach ($row as $key => $value) {
            $row[$key] = is_string($value) ? trim($value) : $value;
        }
        return $row;
    }

    /**
     * Validate email format
     */
    private function isValidEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Normalize name (Title Case)
     */
    private function normalizeName(string $name): string
    {
        return mb_convert_case(strtolower($name), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Normalize phone number (digits only, basic validation)
     * Returns null if cannot be normalized safely
     */
    private function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (strlen($digits) < 7) {
            return null;
        }

        return $digits;
    }

    /**
     * Normalize date to YYYY-MM-DD
     * Conservative: only normalize if unambiguous
     */
    private function normalizeDate(string $date): ?string
    {
        $timestamp = strtotime($date);

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }

    /**
     * Exclude row with reason
     */
    private function excludeRow(array $row, string $reason): void
    {
        $row['_excluded_reason'] = $reason;
        $this->excluded[] = $row;
        $this->stats['excluded_rows']++;
    }

    /**
     * Get excluded rows
     */
    public function getExcludedRows(): array
    {
        return $this->excluded;
    }

    /**
     * Get cleaning statistics
     */
    public function getStats(): array
    {
        return $this->stats;
    }
}
