<?php
/**
 * api/settings/database.php
 * SQLite: the file datas/api.sqlite and its table are created on the first call.
 * See projects/database/settings/database.php for a MySQL connection.
 */
return [
    'default' => [
        'driver' => 'sqlite',
        'database' => ROOT_PATH.DATA_FOLDER.'api.sqlite',
        // Every table name gets the prefix: book is the table api_book
        'prefix' => 'api'
    ]
];
