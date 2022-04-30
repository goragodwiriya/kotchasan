<?php
/**
 * @filesource modules/index/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Index;

use Kotchasan\Http\Request;

/**
 * default Controller.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * แสดงผล.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // รับค่า URL ที่ต้องการ ถ้าไม่มีใช้ index
        $module = $request->get('module', 'index')->filter('a-z');
        // ตรวจสอบ template ที่เลือก
        if (file_exists('modules/index/views/'.$module.'.html')) {
            // โหลด $module.html
            $template = file_get_contents('modules/index/views/'.$module.'.html');
        } else {
            // ถ้าไม่มีใช้ index.html
            $template = file_get_contents('modules/index/views/index.html');
        }
        // create View
        $view = new \Kotchasan\View();
        // คืนค่า HTML template
        echo $view->renderHTML($template);
    }
}
