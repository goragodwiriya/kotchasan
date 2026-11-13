<?php
/**
 * login/settings/config.php
 * Read in every controller, model and view as self::$cfg->web_title and so on.
 */
return [
    'web_title' => 'ระบบสมาชิก',
    // Templates are read from themes/{skin}/
    'skin' => 'default',
    'timezone' => 'Asia/Bangkok',
    // Secret mixed into every password hash. Change it for a real site,
    // then make the hashes below again.
    'password_key' => 'change-this-to-a-long-random-key',
    // The accounts. A real site keeps them in a database table.
    // Make a hash with: Kotchasan\Password::hash('the password', password_key)
    'users' => [
        // password: admin1234
        'admin' => [
            'name' => 'ผู้ดูแลระบบ',
            'role' => 'admin',
            'password' => '$2y$12$gw/ZWFR40qLTVqgS0cEl9OxNTTcm8PShfiig.TgkwhaKAJf/Io2um'
        ],
        // password: demo1234
        'demo' => [
            'name' => 'สมาชิกทดลอง',
            'role' => 'member',
            'password' => '$2y$12$AAP6lbZwN47itm/dpq8UFO4Gtk.ups8Ab0XmpCrAvkyvmiHv.YOe.'
        ]
    ]
];
