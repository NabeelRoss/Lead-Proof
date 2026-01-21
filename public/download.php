<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\DownloadController;

$controller = new DownloadController();
$controller->handle();
