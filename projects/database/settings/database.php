<?php
/**
 * database/settings/database.php
 * Database connections. Kotchasan\Database reads this file the first time a
 * model needs the database.
 *
 * SQLite needs no server: the file datas/database.sqlite and its table are
 * created on the first visit (Index\Product\Model::install()).
 *
 * For MySQL or MariaDB, create an empty database and use this 'default' instead:
 *
 *   'default' => [
 *       'driver' => 'mysql',
 *       'host' => 'localhost',
 *       'username' => 'root',
 *       'password' => '',
 *       'database' => 'kotchasan_demo',
 *       'prefix' => 'demo'
 *   ],
 *
 * The models do not change: the query builder writes the SQL of each driver.
 */
return [
    'default' => [
        'driver' => 'sqlite',
        'database' => ROOT_PATH.DATA_FOLDER.'database.sqlite',
        // Every table name gets the prefix: product is the table demo_product
        'prefix' => 'demo'
    ]
];
