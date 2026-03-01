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

function site_local_url(string $value = ''): string
{
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
  return site_local_url('/public/assets/' . ltrim($path, '/'));
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
