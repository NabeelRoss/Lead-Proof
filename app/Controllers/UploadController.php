<?php
/**
 * LeadProof - Upload Controller
 * Handles CSV upload and full processing pipeline
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Auth;
use App\Services\CsvParserService;
use App\Services\DataCleanerService;
use App\Services\StatsCalculatorService;
use App\Services\CrmRulesService;
use App\Services\ReportGeneratorService;

class UploadController
{
    public function handle(): void
    {
        Auth::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Invalid request method', [], 405);
        }

        if (empty($_FILES['csv_file'])) {
            Response::validation(['csv_file' => 'CSV file is required']);
        }

        $file = $_FILES['csv_file'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::error('File upload failed');
        }

        if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'csv') {
            Response::validation(['csv_file' => 'Only CSV files are allowed']);
        }

        // Storage paths
        $baseStorage = dirname(__DIR__, 2) . '/storage';
        $uploadPath  = $baseStorage . '/uploads';
        $cleanedPath = $baseStorage . '/cleaned';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        if (!is_dir($cleanedPath)) {
            mkdir($cleanedPath, 0775, true);
        }

        $uniqueName = uniqid('upload_', true) . '.csv';
        $storedFile = $uploadPath . '/' . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $storedFile)) {
            Response::error('Unable to store uploaded file');
        }

        // Selected CRM (optional)
        $crm = $_POST['crm'] ?? 'generic';

        try {
            // Initialize services
            $parser = new CsvParserService($storedFile);
            $cleaner = new DataCleanerService();

            $cleanedRows = [];

            foreach ($parser->parse() as $row) {
                $cleaned = $cleaner->cleanRow($row);
                if ($cleaned !== null) {
                    $cleanedRows[] = $cleaned;
                }
            }

            if (empty($cleanedRows)) {
                Response::error('No valid rows found after cleaning');
            }

            // Save cleaned CSV
            $cleanedFileName = uniqid('cleaned_', true) . '.csv';
            $cleanedFilePath = $cleanedPath . '/' . $cleanedFileName;

            $this->writeCsv($cleanedFilePath, $cleanedRows);

            // Calculate stats
            $statsService = new StatsCalculatorService();
            $finalStats = $statsService->calculate(
                $cleaner->getStats(),
                $parser->getHeaders()
            );

            // CRM validation
            $crmService = new CrmRulesService($crm);
            $crmValidation = $crmService->validate(
                $parser->getHeaders(),
                $finalStats
            );

            // Generate report
            $reportService = new ReportGeneratorService();
            $reportPath = $reportService->generate(
                $finalStats,
                $crmValidation,
                pathinfo($file['name'], PATHINFO_FILENAME)
            );

            // ---------------------------------------------------------
            // DATABASE SAVE: Record the upload for the Dashboard
            // ---------------------------------------------------------
            $pdo = require dirname(__DIR__, 2) . '/config/database.php';

            $stmt = $pdo->prepare("
                INSERT INTO uploads 
                (user_id, original_filename, cleaned_filename, report_filename, crm, crm_score, crm_level) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                Auth::id(),
                $file['name'],
                basename($cleanedFilePath),
                basename($reportPath),
                $crm,
                $finalStats['crm_readiness']['score'],
                $finalStats['crm_readiness']['level']
            ]);
            // ---------------------------------------------------------

            Response::success('File processed successfully', [
                'stats' => $finalStats,
                'crm_validation' => $crmValidation,
                'files' => [
                    'cleaned_csv' => basename($cleanedFilePath),
                    'report' => basename($reportPath),
                ],
            ]);

        } catch (\Throwable $e) {
            Response::error('Processing failed', [
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Write cleaned rows to CSV file
     */
    private function writeCsv(string $filePath, array $rows): void
    {
        $handle = fopen($filePath, 'w');

        if ($handle === false) {
            throw new \RuntimeException('Unable to create cleaned CSV');
        }

        // Write headers
        fputcsv($handle, array_keys($rows[0]));

        // Write rows
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }
}