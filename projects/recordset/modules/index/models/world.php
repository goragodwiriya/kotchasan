<?php
/**
 * @filesource modules/index/models/world.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\World;

/**
 * คลาสสำหรับเชื่อมต่อกับฐานข้อมูลของ GCMS.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Orm\Field
{
    /**
     * ชื่อตาราง.
     *
     * @var string
     */
    protected $table = 'world';
    /*
 * CREATE TABLE IF NOT EXISTS `world` (
 * `id` int(11) NOT NULL AUTO_INCREMENT,
 * `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
 * `updated_at` datetime NOT NULL,
 * `created_at` datetime NOT NULL,
 * `user_id` int(11) NOT NULL,
 * `randomNumber` int(11) NOT NULL,
 * PRIMARY KEY (`id`)
 * ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
 */
}
