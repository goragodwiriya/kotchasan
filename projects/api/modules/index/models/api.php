<?php
/**
 * @filesource modules/index/models/api.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Api;

use Kotchasan\Http\Request;

/**
 * API Model.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{
    /**
     * ฟังก์ชั่นแปลง id เป็นเวลา.
     *
     * @param Request $request
     *
     * @return string
     */
    public static function getTime(Request $request)
    {
        return \Kotchasan\Date::format($request->get('id')->toInt());
    }
}
