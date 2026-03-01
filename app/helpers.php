<?php

declare(strict_types=1);
namespace App;

function url_join(string $base, string $path = ''): string
{
  $base = rtrim($base, '/');
  $path = trim($path);

  if ($path === '') {
    return $base;
  }

  return $base . '/' . ltrim($path, '/');
}

function request_base_path(): string
{
  $scriptName = (string)($_SERVER['SCRIPT_NAME'] ?? '');
  if ($scriptName === '') {
    $basePath = parse_url($_ENV['LOCAL_URL'] ?? '', PHP_URL_PATH) ?: '';
    return rtrim($basePath, '/');
  }

  $dir = str_replace('\\', '/', dirname($scriptName));
  if ($dir === '/' || $dir === '.' || $dir === '\\') {
    return '';
  }

  return rtrim($dir, '/');
}

function site_local_url(string $value = ''): string
{
  $host = (string)($_SERVER['HTTP_HOST'] ?? '');
  if ($host !== '') {
    $https = (string)($_SERVER['HTTPS'] ?? '');
    $scheme = (!empty($https) && $https !== 'off') ? 'https' : 'http';
    $origin = $scheme . '://' . $host;

    $base = url_join($origin, request_base_path());
    return url_join($base, $value);
  }

  return url_join($_ENV['LOCAL_URL'] ?? '', $value);
}

function site_prod_url(string $value = ''): string
{
  return url_join($_ENV['PROD_URL'] ?? '', $value);
}

function site_dev_url(string $value = ''): string
{
  return url_join($_ENV['DEV_URL'] ?? '', $value);
}

function site_name(): string
{
  return $_ENV['SITE_NAME'] ?? 'GetMeALatte';
}

function asset_url(string $path = ''): string
{
  $path = ltrim($path, '/');

  $basePath = request_base_path();
  $documentRoot = (string)($_SERVER['DOCUMENT_ROOT'] ?? '');

  if ($documentRoot !== '') {
    $root = rtrim($documentRoot, DIRECTORY_SEPARATOR) . str_replace('/', DIRECTORY_SEPARATOR, $basePath);

    if (is_file($root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $path)) {
      return site_local_url('/assets/' . $path);
    }

    if (is_file($root . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $path)) {
      return site_local_url('/public/assets/' . $path);
    }
  }

  return site_local_url('/public/assets/' . $path);
}

function escape(?string $value): string
{
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
  return \App\Kernel\Security\Csrf::token();
}

function csrf_field(): string
{
  return sprintf('<input type="hidden" name="_token" value="%s">', escape(csrf_token()));
}

function midtrans_is_production(): bool
{
  return filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false', FILTER_VALIDATE_BOOL);
}

function midtrans_client_key(): string
{
  return (string)($_ENV['MIDTRANS_CLIENT_KEY'] ?? '');
}

function midtrans_is_enabled(): bool
{
  return midtrans_client_key() !== '' && !empty($_ENV['MIDTRANS_SERVER_KEY'] ?? '');
}

function midtrans_snap_js_url(): string
{
  return midtrans_is_production()
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js';
}

function redirect(string $value = '', bool $permanent = false) : void
{
  if ($permanent)
  {
    header('HTTP/1.1 301 Moved Permanently');
  }

  if ($value !== '' && str_contains($value, 'http')) {
    header('Location: ' . $value);
    exit;
  }

  header('Location: ' . site_local_url($value));
  exit;
}
