<?php
/**
 * @filesource modules/index/controllers/index.php
 *
 * Controller for the Index module.
 * This class handles the default actions for the Index module, including rendering the index page.
 * For more information, please visit: https://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Index;

use Kotchasan\Http\Request;
use Kotchasan\Login;

/**
 * Render the index page.
 *
 * This method is responsible for rendering the index page of the Index module.
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Render the index page.
     *
     * This method is responsible for rendering the index page of the Index module.
     *
     * @param Request $request The HTTP request object.
     */
    public function index(Request $request)
    {
        // Initialize session cookie
        $request->initSession();

        // Check login status
        Login::create($request);

        if (Login::isMember()) {
            echo '<a href="?action=logout">Logout</a><br>';
            var_dump($_SESSION);
        } else {
            // Forgot password or login
            if ($request->get('action')->toString() == 'forgot') {
                $main = new \Index\Forgot\View();
            } else {
                $main = new \Index\Login\View();
            }
            echo $main->render();
        }
    }
}
