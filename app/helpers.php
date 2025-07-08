<?php

declare(strict_types=1);
namespace App;

function site_local_url(string $value = '')
{
  if (!empty($value))
  {
    return $_ENV['LOCAL_URL'] . $value;
  }

  return $_ENV['LOCAL_URL'];
}

function site_prod_url(string $value = '')
{
  if (!empty($value))
  {
    return $_ENV['PROD_URL'] . $value;
  }

  return $_ENV['PROD_URL'];
}

function site_dev_url(string $value = '')
{
  if (!empty($value))
  {
    return $_ENV['DEV_URL'] . $value;
  }

  return $_ENV['DEV_URL'];
}

function redirect(string $value, $permanent = true) : void
{
  if ($permanent)
  {
    header('HTTP/1.1 301 Moved Permanently');
  }
  if (!empty($value))
  {
    $url = str_contains($value, 'http') ? $value : $_ENV['LOCAL_URL'] . $value;
  } else {
    $url = $_ENV['LOCAL_URL'];
  }
  header('Location: '. $url);
  exit;
}