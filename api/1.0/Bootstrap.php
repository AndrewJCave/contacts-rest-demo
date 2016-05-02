<?php

/**
 * Bookings Restful API
 *
 * @category   API
 * @package    Core
 * @copyright  Copyright (c) 2015 Andrew Cave (http://wpdevhouse.com)
 * @license    http://wpdevhouse.com/api/license
 */

/**
 * Application path
 */
$appDir = dirname(__FILE__);
$appDir = str_replace('\\', '/', $appDir);
define('API_DIR', $appDir);

//define('SPC_SYSPATH', API_DIR . '/system');

/**
 * User config file
 */
require_once API_DIR . '/config.php';

/**
 * Include all core and model files
 */

foreach (glob("core/*.php") as $filename)
{
    include $filename;
}
foreach (glob("core/datalayer/*.php") as $filename)
{
    include $filename;
}
foreach (glob("model/*.php") as $filename)
{
    include $filename;
}
foreach (glob("lib/*.php") as $filename)
{
    include $filename;
}

//require_once API_DIR . '/model/User.php';
//require_once 'core/RequestController.php';

/**
 * Default timezone for whole application
 */
date_default_timezone_set(WPD_DEFAULT_TIMEZONE);