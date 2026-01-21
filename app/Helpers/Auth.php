<?php
/**
 * LeadProof - Authentication Helper
 */

declare(strict_types=1);

namespace App\Helpers;

class Auth
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(int $userId, array $userData = []): void
    {
        self::startSession();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user'] = $userData;
        $_SESSION['logged_in'] = true;
    }

    public static function check(): bool
    {
        self::startSession();
        return !empty($_SESSION['user_id']);
    }

    public static function id()
    {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    public static function user()
    {
        self::startSession();
        return $_SESSION['user'] ?? null;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            // FIX: Use relative path 'login.php' instead of hardcoded folder
            header('Location: login.php');
            exit;
        }
    }
}