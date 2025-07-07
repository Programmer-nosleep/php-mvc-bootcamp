<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Kernel\Bootstrap;

ob_start();
$app = new Bootstrap();
$app->run();
ob_end_flush();