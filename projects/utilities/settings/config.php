<?php
/**
 * utilities/settings/config.php
 * Read in every controller, model and view as self::$cfg->web_title and so on.
 */
return [
    'web_title' => 'ยูทิลิตีภาษาไทย',
    // Templates are read from themes/{skin}/
    'skin' => 'default',
    'timezone' => 'Asia/Bangkok',
    // Secret of Password::hash() and Jwt::encode(). Change it for a real site.
    'password_key' => 'change-this-to-a-long-random-key'
];
