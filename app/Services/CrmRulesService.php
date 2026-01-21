<?php
/**
 * LeadProof - CRM Rules Service
 * Validates cleaned data against CRM-specific rules
 */

declare(strict_types=1);

namespace App\Services;

class CrmRulesService
{
    private array $rules;
    private string $crm;

    public function __construct(string $crm = 'generic')
    {
        $this->rules = require __DIR__ . '/../../config/crm_rules.php';
        $this->crm   = array_key_exists($crm, $this->rules) ? $crm : 'generic';
    }

    /**
     * Validate cleaned rows against CRM rules
     *
     * @param array $headers Normalized CSV headers
     * @param array $stats   Stats from StatsCalculatorService
     */
    public function validate(array $headers, array $stats): array
    {
        $crmRules = $this->rules[$this->crm];

        $missingRequired = $this->missingFields(
            $crmRules['required_fields'],
            $headers
        );

        $missingRecommended = $this->missingFields(
            $crmRules['recommended_fields'],
            $headers
        );

        return [
            'crm' => $this->crm,

            'missing_required_fields' => $missingRequired,
            'missing_recommended_fields' => $missingRecommended,

            'import_recommendation' => $this->importRecommendation(
                $missingRequired,
                $stats['crm_readiness']['score'] ?? 0
            ),

            'warnings' => $this->generateWarnings(
                $missingRequired,
                $missingRecommended,
                $stats
            ),
        ];
    }

    /**
     * Find missing fields from headers
     */
    private function missingFields(array $required, array $headers): array
    {
        $missing = [];

        foreach ($required as $field) {
            if (!in_array($field, $headers, true)) {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    /**
     * Generate CRM import recommendation
     */
    private function importRecommendation(array $missingRequired, int $score): string
    {
        if (!empty($missingRequired)) {
            return 'do_not_import';
        }

        if ($score >= 85) {
            return 'safe_to_import';
        }

        if ($score >= 60) {
            return 'review_before_import';
        }

        return 'do_not_import';
    }

    /**
     * Generate human-readable warnings
     */
    private function generateWarnings(
        array $missingRequired,
        array $missingRecommended,
        array $stats
    ): array {
        $warnings = [];

        if (!empty($missingRequired)) {
            $warnings[] = sprintf(
                'Missing required CRM fields: %s',
                implode(', ', $missingRequired)
            );
        }

        if (!empty($missingRecommended)) {
            $warnings[] = sprintf(
                'Missing recommended fields: %s',
                implode(', ', $missingRecommended)
            );
        }

        if (($stats['rates']['duplicate_rate'] ?? 0) > 10) {
            $warnings[] = 'High duplicate rate detected';
        }

        if (($stats['rates']['invalid_email_rate'] ?? 0) > 5) {
            $warnings[] = 'High invalid email rate detected';
        }

        return $warnings;
    }

    /**
     * Get CRM scoring weights
     */
    public function getScoringWeights(): array
    {
        return $this->rules[$this->crm]['scoring_weights'] ?? [];
    }

    /**
     * Get active CRM
     */
    public function getCrm(): string
    {
        return $this->crm;
    }
}
