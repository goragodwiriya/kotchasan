<?php
/**
 * tests/bootstrap.php.
 * @copyright 2015 Goragod.com
 * @license https://www.kotchasan.com/license/
 *
 * @see https://www.kotchasan.com/
 */
define('DEBUG', 2);
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__FILE__)).'/';
// ตัวแปรที่จำเป็นสำหรับ Framework ใช้ระบุ root folder
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
// ตัวแปรที่จำเป็นสำหรับ Framework ใช้ระบุ root folder
define('BASE_PATH', '/');
// load Kotchasan
include ROOT_PATH.'Kotchasan/load.php';
// start application for testing
Kotchasan::createWebApplication();
