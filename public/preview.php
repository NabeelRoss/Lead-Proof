<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PreviewController;

$controller = new PreviewController();
$controller->handle();
