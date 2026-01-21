<?php
/**
 * LeadProof - Response Helper
 * Standardized JSON responses for controllers
 */

declare(strict_types=1);

namespace App\Helpers;

class Response
{
    /**
     * Send a success JSON response
     */
    public static function success(
        string $message = 'Success',
        array $data = [],
        int $statusCode = 200
    ): void {
        self::send([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Send an error JSON response
     */
    public static function error(
        string $message = 'Something went wrong',
        array $errors = [],
        int $statusCode = 400
    ): void {
        self::send([
            'status'  => 'error',
            'message' => $message,
            'errors'  => $errors,
        ], $statusCode);
    }

    /**
     * Send validation error response (422)
     */
    public static function validation(array $errors): void
    {
        self::error(
            'Validation failed',
            $errors,
            422
        );
    }

    /**
     * Send unauthorized response (401)
     */
    public static function unauthorized(string $message = 'Unauthorized'): void
    {
        self::error($message, [], 401);
    }

    /**
     * Send forbidden response (403)
     */
    public static function forbidden(string $message = 'Forbidden'): void
    {
        self::error($message, [], 403);
    }

    /**
     * Send not found response (404)
     */
    public static function notFound(string $message = 'Not found'): void
    {
        self::error($message, [], 404);
    }

    /**
     * Core JSON sender
     */
    private static function send(array $payload, int $statusCode): void
    {
        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: application/json; charset=utf-8');
        }

        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
