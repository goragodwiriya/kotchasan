<?php
/**
 * @filesource projects/site/index.php.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */
/**
 * 0 (default )บันทึกข้อผิดพลาดร้ายแรงลง error_log .php
 * 1 บันทึกข้อผิดพลาดและคำเตือนลง error_log .php
 * 2 แสดงผลข้อผิดพลาดและคำเตือนออกทางหน้าจอ (ใช้เฉพาะตอนออกแบบเท่านั้น).
 */
define('DEBUG', 0);

/*
 * false (default)
 * true บันทึกการ query ฐานข้อมูลลง log (ใช้เฉพาะตอนออกแบบเท่านั้น)
 */
define('DB_LOG', false);

// load Kotchasan
include '../../Kotchasan/load.php';
// Initial Kotchasan Framework
Kotchasan::createWebApplication()->run();
