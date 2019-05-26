<?php

declare(strict_types=1);

namespace BestItBugsnag;

use Shopware\Components\Plugin;

/**
 * The plugin base class.
 *
 * @package BestItBugsnag
 */
class BestItBugsnag extends Plugin
{
}

// check if it is not installed via composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
