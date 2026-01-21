<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Auth;
use App\Controllers\UploadController;

Auth::requireAuth();
$user = Auth::user(); // Fetch user for the navbar

// ROUTING LOGIC: Handle the POST request from the JavaScript below
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UploadController();
    $controller->handle();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV – LeadProof</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #3b82f6;
            width: 24px;
            height: 24px;
            -webkit-animation: spin 1s linear infinite; /* Safari */
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-2">
                    <a href="dashboard.php" class="flex items-center gap-2 group">
                        <div class="bg-blue-600 text-white p-1.5 rounded-lg group-hover:bg-blue-700 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 tracking-tight">LeadProof</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                        &larr; Back to Dashboard
                    </a>
                    <div class="h-4 w-px bg-gray-300 mx-2 hidden sm:block"></div>
                    <div class="text-sm text-gray-500 hidden sm:block">
                        Signed in as <span class="font-semibold text-gray-900"><?= htmlspecialchars($user['name'] ?? 'User') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="max-w-3xl w-full space-y-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                    <h1 class="text-2xl font-bold text-gray-900">Upload Lead CSV</h1>
                    <p class="mt-1 text-sm text-gray-500">Upload your raw CSV file to clean, validate, and score it against CRM rules.</p>
                </div>

                <div class="p-8">
                    <form id="uploadForm" class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-colors cursor-pointer relative">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label for="csv_file" class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="csv_file" name="csv_file" type="file" accept=".csv" required class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">CSV up to 100MB</p>
                                    <p id="file-name-display" class="text-sm font-semibold text-gray-900 mt-2 h-5"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="crm" class="block text-sm font-medium text-gray-700 mb-2">Target CRM</label>
                            <div class="relative">
                                <select id="crm" name="crm" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg border bg-white shadow-sm">
                                    <option value="generic">Generic CRM (Standard Rules)</option>
                                    <option value="hubspot">HubSpot (Strict Validation)</option>
                                    <option value="salesforce">Salesforce (Strict Validation)</option>
                                </select>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">We will apply validation rules specific to the selected platform.</p>
                        </div>

                        <div>
                            <button type="submit" id="submitBtn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                Upload & Process File
                            </button>
                        </div>
                    </form>

                    <div id="loading" class="hidden mt-8 text-center py-4 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex flex-col items-center justify-center">
                            <div class="loader mb-3"></div>
                            <p class="text-blue-700 font-medium">Processing your file...</p>
                            <p class="text-blue-500 text-xs mt-1">This may take a moment depending on file size.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="result" class="hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-up">
                </div>

        </div>
    </main>

    <script>
    // Show filename when selected
    document.getElementById('csv_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        document.getElementById('file-name-display').textContent = fileName ? fileName : '';
    });

    document.getElementById('uploadForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitBtn');
        const loadingDiv = document.getElementById('loading');
        const resultDiv = document.getElementById('result');

        // Reset UI
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        loadingDiv.classList.remove('hidden');
        resultDiv.classList.add('hidden');
        resultDiv.innerHTML = '';

        try {
            const response = await fetch('upload.php', {
                method: 'POST',
                body: formData
            });

            // Handle non-JSON responses (like 404 or 500 HTML errors)
            if (!response.ok) {
                throw new Error('Server Error: ' + response.statusText);
            }

            const json = await response.json();

            if (json.status !== 'success') {
                throw new Error(json.message || 'Upload failed');
            }

            renderResult(json.data);

        } catch (error) {
            alert(error.message);
            console.error(error);
        } finally {
            loadingDiv.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        }
    });

    function renderResult(data) {
        const result = document.getElementById('result');
        const score = data.stats.crm_readiness.score;
        const level = data.stats.crm_readiness.level;

        // Color logic
        let colorClass = 'text-gray-600 bg-gray-100';
        let barColor = 'bg-gray-500';
        
        if (level === 'safe') {
            colorClass = 'text-green-700 bg-green-50 ring-green-600/20';
            barColor = 'bg-green-500';
        } else if (level === 'review') {
            colorClass = 'text-yellow-800 bg-yellow-50 ring-yellow-600/20';
            barColor = 'bg-yellow-500';
        } else if (level === 'risky') {
            colorClass = 'text-red-700 bg-red-50 ring-red-600/20';
            barColor = 'bg-red-500';
        }

        result.innerHTML = `
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Processing Results</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset ${colorClass} capitalize">
                    ${level}
                </span>
            </div>

            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500">CRM Readiness Score</p>
                        <h3 class="text-4xl font-extrabold text-gray-900 mt-1">${score}<span class="text-xl text-gray-400 font-normal">/100</span></h3>
                    </div>
                    <div class="w-1/2">
                         <div class="w-full bg-gray-100 rounded-full h-4">
                            <div class="${barColor} h-4 rounded-full transition-all duration-1000" style="width: ${score}%"></div>
                        </div>
                        <p class="text-right text-xs text-gray-400 mt-2">Based on field completeness & validity</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-center">
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Total Rows</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">${data.stats.rows.total}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-100 text-center">
                        <p class="text-xs text-green-600 uppercase tracking-wide font-semibold">Valid Leads</p>
                        <p class="text-2xl font-bold text-green-700 mt-1">${data.stats.rows.valid}</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 border border-red-100 text-center">
                        <p class="text-xs text-red-600 uppercase tracking-wide font-semibold">Rejected</p>
                        <p class="text-2xl font-bold text-red-700 mt-1">${data.stats.rows.excluded}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100">
                    <a href="preview.php?file=${data.files.cleaned_csv}" target="_blank"
                       class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                       <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                       Preview Data
                    </a>
                    
                    <a href="download.php?file=${data.files.cleaned_csv}"
                       class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                       <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0L8 8m4-4v12"></path></svg>
                       Download CSV
                    </a>

                    <a href="report.php?file=${data.files.report}"
                       class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                       <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                       Download Report
                    </a>
                </div>
            </div>
        `;

        result.classList.remove('hidden');
        
        // Smooth scroll to results
        result.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    </script>

</body>
</html>