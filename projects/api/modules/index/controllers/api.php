<?php
/**
 * @filesource modules/index/controllers/api.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Api;

use Kotchasan\Http\Request;
use Kotchasan\Http\Response;

/**
 * API Controller.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * method สำหรับตรวจสอบและประมวลผล API.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // method ที่ต้องการ
        $method = $request->get('method')->toString();
        // ตัวแปรสำหรับส่งค่ากลับ
        $ret = array();
        // ประมวลผล method ที่ต้องการ
        if (method_exists('Index\Api\Model', $method)) {
            $ret['result'] = call_user_func(array('Index\Api\Model', $method), $request);
        } else {
            // ข้อผิดดพลาด ไม่พบ method
            $ret['error'] = 'Method not found';
        }
        // create Response สำหรับส่งค่ากลับ
        $response = new Response();
        // กำหนด header เป็น JSON+UTF-8
        $response->withHeader('Content-Type', 'application/json; charset=utf-8')
        // ข้อมูลที่ส่งกลับ
            ->withContent(json_encode($ret))
        // ส่ง
            ->send();
    }
}
