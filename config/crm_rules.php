<?php
/**
 * LeadProof - CRM Rules Configuration
 * Defines validation rules per CRM
 */

return [

    /*
    |--------------------------------------------------------------------------
    | HubSpot CRM Rules
    |--------------------------------------------------------------------------
    */
    'hubspot' => [

        // Fields required for a safe import
        'required_fields' => [
            'email',
        ],

        // Recommended (not mandatory) fields
        'recommended_fields' => [
            'first name',
            'last name',
            'company',
            'phone',
            'lifecycle stage',
        ],

        // Field-level validation rules
        'field_rules' => [
            'email' => [
                'type' => 'email',
                'required' => true,
            ],
            'phone' => [
                'type' => 'phone',
                'required' => false,
            ],
            'created date' => [
                'type' => 'date',
                'required' => false,
            ],
        ],

        // Weighting used for CRM readiness scoring
        'scoring_weights' => [
            'completeness' => 0.30,
            'duplicates'   => 0.40,
            'invalid_data' => 0.30,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Salesforce CRM Rules
    |--------------------------------------------------------------------------
    */
    'salesforce' => [

        'required_fields' => [
            'email',
            'last name',
        ],

        'recommended_fields' => [
            'first name',
            'company',
            'phone',
            'lead source',
        ],

        'field_rules' => [
            'email' => [
                'type' => 'email',
                'required' => true,
            ],
            'last name' => [
                'type' => 'string',
                'required' => true,
            ],
            'phone' => [
                'type' => 'phone',
                'required' => false,
            ],
        ],

        'scoring_weights' => [
            'completeness' => 0.35,
            'duplicates'   => 0.35,
            'invalid_data' => 0.30,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Generic / Default Rules
    |--------------------------------------------------------------------------
    */
    'generic' => [

        'required_fields' => [
            'email',
        ],

        'recommended_fields' => [
            'first name',
            'last name',
            'company',
            'phone',
        ],

        'field_rules' => [
            'email' => [
                'type' => 'email',
                'required' => true,
            ],
        ],

        'scoring_weights' => [
            'completeness' => 0.33,
            'duplicates'   => 0.34,
            'invalid_data' => 0.33,
        ],
    ],

];
