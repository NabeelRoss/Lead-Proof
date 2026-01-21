<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Auth;

// 1. Clear the user session
Auth::logout();

// 2. Redirect to the Landing Page (index.php)
header('Location: index.php');
exit;