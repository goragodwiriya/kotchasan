<?php
/**
 * @filesource modules/index/models/world.php
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\World;

/**
 * Class Model
 *
 * This class is used to connect to the GCMS database and interact with the "world" table.
 *
 * @see https://www.kotchasan.com/
 */
class Model extends \Kotchasan\Orm\Field
{
    /**
     * The name of the table.
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
