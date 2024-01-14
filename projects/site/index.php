<?php
/**
 * @filesource projects/site/index.php.
 *
 * Main entry point for the Site project.
 * This file initializes the Kotchasan Framework and starts the web application.
 * For more information, please visit: https://www.kotchasan.com/
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

// Load Kotchasan Framework
include '../../Kotchasan/load.php';

// Initialize Kotchasan Framework and start the web application
Kotchasan::createWebApplication()->run();
