<?php
/**
 * LeadProof - Report Controller
 * Securely serves PDF data quality reports
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;

class ReportController
{
    public function handle(): void
    {
        Auth::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Invalid request method', [], 405);
        }

        $fileName = $_GET['file'] ?? null;
        $action   = $_GET['action'] ?? 'download'; // view | download

        if (!$fileName) {
            Response::validation(['file' => 'File parameter is required']);
        }

        $reportsDir = dirname(__DIR__, 2) . '/storage/reports';
        $filePath = realpath($reportsDir . '/' . basename($fileName));

        // Security: ensure file exists inside reports directory
        if (
            !$filePath ||
            !str_starts_with($filePath, realpath($reportsDir))
        ) {
            Response::error('Invalid file reference', [], 403);
        }

        if (!file_exists($filePath)) {
            Response::error('Report not found', [], 404);
        }

        // Headers for PDF
        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        if ($action === 'view') {
            header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        }

        // Clean output buffer
        if (ob_get_length()) {
            ob_end_clean();
        }

        readfile($filePath);
        exit;
    }
}
