<?php
/**
 * api/index.php
 * The page that explains the API and lets you call it. The API itself is api.php.
 */

// The project folder. Kotchasan reads settings/, modules/ and themes/ from here.
define('ROOT_PATH', str_replace('\\', '/', __DIR__).'/');

// Writable folder under ROOT_PATH for the SQLite file and the logs
define('DATA_FOLDER', 'datas/');

// 0 = log errors only, 2 = show errors on the page while developing
define('DEBUG', 0);

// Load Kotchasan straight from the repository, no Composer needed
require __DIR__.'/../../Kotchasan/load.php';

// Calls Index\Index\Controller::index()
Kotchasan::createWebApplication()->run();
