<?php
/**
 * @filesource projects/youtube/index.php.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

/**
 * Debug mode:
 * 0 (default): Log severe errors to error_log.php.
 * 1: Log errors and warnings to error_log.php.
 * 2: Display errors and warnings on the screen (only for development purposes).
 */
define('DEBUG', 0);

/**
 * Database query logging:
 * false (default): Do not log database queries.
 * true: Log database queries to log file (only for development purposes).
 */
define('DB_LOG', false);

// load Kotchasan
include '../../Kotchasan/load.php';

// Initial Kotchasan Framework
Kotchasan::createWebApplication()->run();
