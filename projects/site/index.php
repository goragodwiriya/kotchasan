<?php
/**
 * site/index.php
 * Every page of this example comes in here: index.php, index.php?module=about
 */

// The project folder. Kotchasan reads settings/, modules/, language/ and themes/ from here.
define('ROOT_PATH', str_replace('\\', '/', __DIR__).'/');

// 0 = log errors only, 2 = show errors on the page while developing
define('DEBUG', 0);

// Language for visitors who have not picked one (th, en or auto)
define('INIT_LANGUAGE', 'th');

// Load Kotchasan straight from the repository, no Composer needed
require __DIR__.'/../../Kotchasan/load.php';

// Reads settings/config.php, then calls Index\Index\Controller::index()
Kotchasan::createWebApplication()->run();
