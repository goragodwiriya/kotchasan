<?php
/**
 * api/api.php
 * Every call of the JSON API comes in here: api.php/v1/books, api.php/v1/books/3
 *
 * Kotchasan\Router sees "api" in the path and sends the call to
 * Kotchasan\ApiController, which calls V1\Books\Controller::index()
 * (modules/v1/controllers/books.php).
 */

// The project folder. Kotchasan reads settings/ and modules/ from here.
define('ROOT_PATH', str_replace('\\', '/', __DIR__).'/');

// Writable folder under ROOT_PATH for the SQLite file and the logs
define('DATA_FOLDER', 'datas/');

// 0 = log errors only, 2 = also put the error in the JSON answer while developing
define('DEBUG', 0);

// Load Kotchasan straight from the repository, no Composer needed
require __DIR__.'/../../Kotchasan/load.php';

Kotchasan::createWebApplication()->run();
