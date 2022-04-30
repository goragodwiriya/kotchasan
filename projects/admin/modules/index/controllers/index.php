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
use Kotchasan\Login;

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
        // session cookie
        $request->initSession();
        // ตรวจสอบการ login
        Login::create();
        if (Login::isMember()) {
            echo '<a href="?action=logout">Logout</a><br>';
            var_dump($_SESSION);
        } else {
            // forgot or login
            if ($request->get('action')->toString() == 'forgot') {
                $main = new \Index\Forgot\View();
            } else {
                $main = new \Index\Login\View();
            }
            echo $main->render();
        }
    }
}
