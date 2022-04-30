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

use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Template;

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
        // เริ่มต้นการใช้งาน Template
        Template::init(self::$cfg->skin);
        // ถ้าไม่มีโมดูลเลือกหน้า home
        $module = $request->get('module', 'home')->toString();
        // สร้าง View
        $view = new \Kotchasan\View();
        // template default
        $view->setContents(array(
            // menu
            '/{MENU}/' => createClass('Index\Menu\Controller')->render($module),
            // web title
            '/{TITLE}/' => self::$cfg->web_title,
            // โหลดหน้าที่เลือก (html)
            '/{CONTENT}/' => Template::load('', '', $module),
            // แสดงเวลาปัจจุบัน
            '/{TIME}/' => Date::format(),
        ));
        // ส่งออกเป็น HTML
        echo $view->renderHTML();
    }
}
