<?php
/**
 * @filesource modules/index/models/api.php
 *
 * API Model.
 *
 * @see https://www.kotchasan.com/
 */

namespace Index\Api;

use Kotchasan\Http\Request;

class Model
{
    /**
     * Convert an ID to a time string.
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
