<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Helpers\Auth;

// Auto-redirect logged-in users to dashboard
if (Auth::check()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LeadProof – The Smartest Way to Clean Your CRM Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Subtle grid pattern for hero background */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, #f3f4f6 1px, transparent 1px),
                              linear-gradient(to bottom, #f3f4f6 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 tracking-tight">LeadProof</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">How it Works</a>
                    <a href="login.php" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">Sign in</a>
                    <a href="register.php" class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm hover:shadow-md">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 -z-10 h-full w-full bg-white bg-grid-pattern [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-sm font-medium text-blue-600 mb-8">
                <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2"></span>
                Now supporting Salesforce & HubSpot exports
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight mb-8 leading-tight">
                Data quality control <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">for modern sales teams.</span>
            </h1>
            
            <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Stop manually fixing CSVs. LeadProof analyzes your lead lists, standardizes formatting, and removes risky contacts before they enter your CRM.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16">
                <a href="register.php" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Start Cleaning Free
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
                <a href="#demo" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition">
                    View Demo Report
                </a>
            </div>

            <div class="relative max-w-5xl mx-auto rounded-2xl shadow-2xl bg-gray-900 border border-gray-800 p-2 transform rotate-1 hover:rotate-0 transition duration-500">
                <div class="bg-gray-50 rounded-xl overflow-hidden">
                    <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <div class="ml-4 bg-gray-100 rounded-md px-3 py-1 text-xs text-gray-400 flex-1 text-center font-mono">dashboard.leadproof.app</div>
                    </div>
                    <div class="p-8 grid grid-cols-12 gap-6">
                        <div class="col-span-2 space-y-4 hidden md:block">
                            <div class="h-8 bg-gray-200 rounded w-full"></div>
                            <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-100 rounded w-5/6"></div>
                            <div class="h-4 bg-gray-100 rounded w-2/3"></div>
                        </div>
                        <div class="col-span-12 md:col-span-10 space-y-6">
                            <div class="flex justify-between">
                                <div class="h-8 bg-gray-200 rounded w-1/3"></div>
                                <div class="h-8 bg-blue-600 rounded w-1/4"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="h-24 bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="w-8 h-8 bg-blue-100 rounded mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                </div>
                                <div class="h-24 bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="w-8 h-8 bg-green-100 rounded mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                </div>
                                <div class="h-24 bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="w-8 h-8 bg-red-100 rounded mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-12 bg-white border border-gray-200 rounded-lg w-full"></div>
                                <div class="h-12 bg-white border border-gray-200 rounded-lg w-full"></div>
                                <div class="h-12 bg-white border border-gray-200 rounded-lg w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="py-10 bg-gray-50 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest mb-6">Built for data-driven teams</p>
            <div class="flex justify-center items-center gap-12 grayscale opacity-50">
                <span class="text-xl font-bold font-mono">ACME Corp</span>
                <span class="text-xl font-bold font-serif">Globex</span>
                <span class="text-xl font-bold font-sans">Soylent</span>
                <span class="text-xl font-bold font-mono">Initech</span>
                <span class="text-xl font-bold font-serif">Umbrella</span>
            </div>
        </div>
    </div>

    <section id="how-it-works" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">From messy CSV to CRM-ready</h2>
                <p class="mt-4 text-lg text-gray-500">Three simple steps to perfect data hygiene.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 text-center relative">
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gray-100 -z-10 transform scale-x-75"></div>

                <div class="relative bg-white p-6">
                    <div class="w-24 h-24 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-lg">
                        <span class="text-4xl">📂</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">1. Upload</h3>
                    <p class="text-gray-500">Drag & drop your raw CSV export. We handle files up to 100MB.</p>
                </div>

                <div class="relative bg-white p-6">
                    <div class="w-24 h-24 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-lg">
                        <span class="text-4xl">⚙️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">2. Process</h3>
                    <p class="text-gray-500">Our engine normalizes names, formats phones, and detects duplicates.</p>
                </div>

                <div class="relative bg-white p-6">
                    <div class="w-24 h-24 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-lg">
                        <span class="text-4xl">🚀</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">3. Download</h3>
                    <p class="text-gray-500">Get your clean CSV and a PDF audit report to prove data quality.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Everything you need to trust your data</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Auto-Cleaning</h3>
                    <p class="text-gray-500">We automatically capitalization names (e.g. "JOHN" &rarr; "John") and standardizes inconsistent formatting.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">CRM Validation Rules</h3>
                    <p class="text-gray-500">Pre-configured rule sets for Salesforce and HubSpot ensure you never upload missing required fields.</p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Audit Trails</h3>
                    <p class="text-gray-500">Every upload generates a downloadable PDF report summarizing data health, duplicates, and errors.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-blue-600 py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl mb-6">Ready to fix your data?</h2>
            <p class="text-xl text-blue-100 mb-10">Join thousands of sales ops professionals who trust LeadProof.</p>
            <a href="register.php" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-blue-600 bg-white rounded-full hover:bg-gray-50 shadow-xl transition">
                Get Started for Free
            </a>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="col-span-2">
                <span class="text-xl font-bold tracking-tight">LeadProof</span>
                <p class="mt-4 text-gray-400 max-w-xs">The easiest way to validate, clean, and format your CSV data before import.</p>
            </div>
            <div>
                <h4 class="font-bold mb-4">Product</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white">Features</a></li>
                    <li><a href="#" class="hover:text-white">Pricing</a></li>
                    <li><a href="#" class="hover:text-white">API</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4">Legal</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-white">Privacy</a></li>
                    <li><a href="#" class="hover:text-white">Terms</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> LeadProof. All rights reserved. Built for Portfolio Purposes.
        </div>
    </footer>

</body>
</html>