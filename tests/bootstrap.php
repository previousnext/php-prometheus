<?php

/**
 * @file
 * Bootstrap for PHPUnit.
 */

declare(strict_types=1);

$autoloader = __DIR__ . '/../vendor/autoload.php';
$loader = require $autoloader;

if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
  define('PHPUNIT_COMPOSER_INSTALL', $autoloader);
}

date_default_timezone_set('Australia/Sydney');
