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

use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Template;

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
        // Initialize Template
        Template::init(self::$cfg->skin);

        // If no module selected, set it to 'home'
        $module = $request->get('module', 'home')->toString();

        // Create a new View
        $view = new \Kotchasan\View();

        // Set the template contents
        $view->setContents([
            // Menu
            '/{MENU}/' => createClass('Index\Menu\Controller')->render($module),
            // Web title
            '/{TITLE}/' => self::$cfg->web_title,
            // Load selected page (HTML)
            '/{CONTENT}/' => Template::load('', '', $module),
            // Display current time
            '/{TIME}/' => Date::format()
        ]);

        // Render HTML and output
        echo $view->renderHTML();
    }
}
