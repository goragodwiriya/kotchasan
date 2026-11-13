<?php
/**
 * site/settings/config.php
 * Kotchasan\Config loads this file into self::$cfg, so every controller,
 * model and view reads the values as self::$cfg->web_title and so on.
 */
return [
    'web_title' => 'Chang Noi Coffee',
    'web_description' => 'A small coffee shop website built with the Kotchasan PHP framework',
    // Templates are read from themes/{skin}/
    'skin' => 'default',
    // Languages that have a file in language/
    'languages' => ['th', 'en'],
    'timezone' => 'Asia/Bangkok'
];
