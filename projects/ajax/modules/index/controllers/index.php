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
        // Get the requested URL, default to 'index' if not provided
        $module = $request->get('module', 'index')->filter('a-z');
        // Check if the selected template exists
        if (file_exists('modules/index/views/'.$module.'.html')) {
            // Load the $module.html template
            $template = file_get_contents('modules/index/views/'.$module.'.html');
        } else {
            // If the template does not exist, use the index.html template
            $template = file_get_contents('modules/index/views/index.html');
        }
        // Create a View object
        $view = new \Kotchasan\View();
        // Render and output the HTML template
        echo $view->renderHTML($template);
    }
}
