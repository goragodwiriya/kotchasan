<?php
/**
 * @filesource modules/index/controllers/menu.php
 *
 * Controller file for the Menu module.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Menu;

/**
 * Default Controller for menu module.
 *
 * This class handles the creation and rendering of the menu.
 *
 * @see https://www.kotchasan.com/
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Render the menu.
     *
     * This method is responsible for rendering the menu for the specified module.
     *
     * @param string $module The module name.
     *
     * @return string The rendered menu HTML.
     */
    public function render($module)
    {
        // Create and render the menu view
        return \Index\Menu\View::create()->render($module);
    }
}
