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
