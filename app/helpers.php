<?php

declare(strict_types=1);
namespace App\Kernel;

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