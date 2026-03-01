<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$documentRoot = (string)($_SERVER['DOCUMENT_ROOT'] ?? __DIR__);
$file = rtrim($documentRoot, DIRECTORY_SEPARATOR)
    . DIRECTORY_SEPARATOR
    . ltrim(str_replace('/', DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);

if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
