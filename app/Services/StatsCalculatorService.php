<?php
/**
 * LeadProof - Stats Calculator Service
 * Converts cleaning counters into meaningful data quality metrics
 */

declare(strict_types=1);

namespace App\Services;

class StatsCalculatorService
{
    /**
     * Calculate full statistics summary
     *
     * @param array $cleanerStats Stats from DataCleanerService::getStats()
     * @param array $headers CSV headers (normalized)
     */
    public function calculate(array $cleanerStats, array $headers = []): array
    {
        $totalRows   = $cleanerStats['total_rows'] ?? 0;
        $validRows   = $cleanerStats['valid_rows'] ?? 0;
        $excluded    = $cleanerStats['excluded_rows'] ?? 0;
        $duplicates  = $cleanerStats['duplicates'] ?? 0;
        $invalidMail = $cleanerStats['invalid_emails'] ?? 0;

        $completeness = $this->calculateCompleteness($headers);
        $duplicateRate = $this->rate($duplicates, $totalRows);
        $invalidEmailRate = $this->rate($invalidMail, $totalRows);

        $readinessScore = $this->calculateReadinessScore(
            $completeness,
            $duplicateRate,
            $invalidEmailRate
        );

        return [
            'rows' => [
                'total'     => $totalRows,
                'valid'     => $validRows,
                'excluded'  => $excluded,
            ],

            'issues' => [
                'duplicates' => $duplicates,
                'invalid_emails' => $invalidMail,
            ],

            'rates' => [
                'duplicate_rate'      => $duplicateRate,
                'invalid_email_rate'  => $invalidEmailRate,
            ],

            'completeness' => $completeness,

            'crm_readiness' => [
                'score' => $readinessScore,
                'level' => $this->readinessLevel($readinessScore),
            ],
        ];
    }

    /**
     * Calculate field completeness percentage
     * Based on presence of common CRM fields
     */
    private function calculateCompleteness(array $headers): array
    {
        if (empty($headers)) {
            return [
                'score' => 0,
                'details' => [],
            ];
        }

        $requiredFields = [
            'email',
            'first name',
            'last name',
            'company',
            'phone',
        ];

        $present = 0;
        $details = [];

        foreach ($requiredFields as $field) {
            $exists = in_array($field, $headers, true);
            $details[$field] = $exists ? 100 : 0;
            if ($exists) {
                $present++;
            }
        }

        $score = (int) round(($present / count($requiredFields)) * 100);

        return [
            'score' => $score,
            'details' => $details,
        ];
    }

    /**
     * Calculate CRM readiness score (0–100)
     */
    private function calculateReadinessScore(
        array $completeness,
        float $duplicateRate,
        float $invalidEmailRate
    ): int {
        $score = 100;

        // Completeness penalty
        $score -= (100 - ($completeness['score'] ?? 0)) * 0.3;

        // Duplicate penalty
        $score -= $duplicateRate * 0.4;

        // Invalid email penalty
        $score -= $invalidEmailRate * 0.3;

        return max(0, min(100, (int) round($score)));
    }

    /**
     * Convert score to human-readable level
     */
    private function readinessLevel(int $score): string
    {
        if ($score >= 85) {
            return 'safe';
        }

        if ($score >= 60) {
            return 'review';
        }

        return 'risky';
    }

    /**
     * Calculate percentage rate
     */
    private function rate(int $count, int $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round(($count / $total) * 100, 2);
    }
}
