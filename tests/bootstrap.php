<?php
/**
 * tests/bootstrap.php.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 * @copyright 2015 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */
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
