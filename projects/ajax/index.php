<?php
/**
 * ajax/index.php
 * The page (index.php) and the Ajax calls (index.php/index/controller/ajax/...)
 * both come in here.
 */

// The project folder. Kotchasan reads settings/, modules/ and themes/ from here.
define('ROOT_PATH', str_replace('\\', '/', __DIR__).'/');

// 0 = log errors only, 2 = show errors on the page while developing
define('DEBUG', 0);

// Load Kotchasan straight from the repository, no Composer needed
require __DIR__.'/../../Kotchasan/load.php';

// Kotchasan\Router picks the controller from the URL
Kotchasan::createWebApplication()->run();
