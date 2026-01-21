<?php
/**
 * LeadProof - Preview Controller
 * Provides a safe preview of cleaned CSV data
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Response;

class PreviewController
{
    private int $previewLimit = 20;

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

        $cleanedPath = dirname(__DIR__, 2) . '/storage/cleaned';
        $filePath = realpath($cleanedPath . '/' . basename($fileName));

        // Security check: file must exist inside cleaned directory
        if (!$filePath || !str_starts_with($filePath, realpath($cleanedPath))) {
            Response::error('Invalid file reference', [], 403);
        }

        if (!file_exists($filePath)) {
            Response::error('File not found', [], 404);
        }

        try {
            $handle = fopen($filePath, 'r');

            if ($handle === false) {
                Response::error('Unable to open file');
            }

            $headers = fgetcsv($handle);
            if (!$headers) {
                Response::error('Invalid CSV format');
            }

            $rows = [];
            $count = 0;

            while (($row = fgetcsv($handle)) !== false && $count < $this->previewLimit) {
                $rows[] = $row;
                $count++;
            }

            fclose($handle);

            // Render HTML View directly
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Preview – LeadProof</title>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="bg-gray-100 p-8">
                <div class="max-w-7xl mx-auto bg-white shadow rounded p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">File Preview: <?= htmlspecialchars(basename($fileName)) ?></h1>
                        <button onclick="window.close()" class="text-red-600 hover:underline">Close</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border collapse">
                            <thead class="bg-gray-50 text-gray-700 uppercase">
                                <tr>
                                    <?php foreach ($headers as $header): ?>
                                        <th class="border p-3"><?= htmlspecialchars($header) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <?php foreach ($row as $cell): ?>
                                            <td class="border p-3"><?= htmlspecialchars((string)$cell) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-4 text-sm text-gray-500">
                        Showing first <?= $this->previewLimit ?> rows only.
                    </p>
                </div>
            </body>
            </html>
            <?php
            exit;

        } catch (\Throwable $e) {
            Response::error('Failed to load preview', [
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}