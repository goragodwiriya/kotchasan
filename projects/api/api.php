<?php
/**
 * @filesource projects/api/api.php.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

/**
 * 0 (default) - Log severe errors to error_log.php.
 * 1 - Log errors and warnings to error_log.php.
 * 2 - Display errors and warnings on the screen (for development purposes only).
 */
// define('DEBUG', 0);

/**
 * false (default) - Do not log database queries.
 * true - Log database queries to the log (for development purposes only).
 */
// define('DB_LOG', false);

// Load Kotchasan
include '../../Kotchasan/load.php';

// Initialize Kotchasan Framework
$request = Kotchasan::createWebApplication();
$request->defaultController = 'Index\Api\Controller';
$request->run();
