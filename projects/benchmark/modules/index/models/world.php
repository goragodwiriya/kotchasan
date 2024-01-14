<?php
/**
 * @filesource modules/index/models/world.php
 *
 * Model class for connecting to the GCMS database.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\World;

/**
 * Model class for connecting to the GCMS database.
 */
class Model extends \Kotchasan\Orm\Field
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'world';
}
