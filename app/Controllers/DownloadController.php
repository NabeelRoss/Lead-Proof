<?php
/**
 * LeadProof - Download Controller
 * Securely serves cleaned CSV files
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;

class DownloadController
{
    public function handle(): void
    {
        Auth::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Invalid request method', [], 405);
        }

        $fileName = $_GET['file'] ?? null;

        if (!$fileName) {
            Response::validation(['file' => 'File parameter is required']);
        }

        $cleanedDir = dirname(__DIR__, 2) . '/storage/cleaned';
        $filePath = realpath($cleanedDir . '/' . basename($fileName));

        // Security check: file must exist inside cleaned directory
        if (
            !$filePath ||
            !str_starts_with($filePath, realpath($cleanedDir))
        ) {
            Response::error('Invalid file reference', [], 403);
        }

        if (!file_exists($filePath)) {
            Response::error('File not found', [], 404);
        }

        // Force CSV download
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        // Clean output buffer
        if (ob_get_length()) {
            ob_end_clean();
        }

        readfile($filePath);
        exit;
    }
}
