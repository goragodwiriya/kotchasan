<?php
/**
 * @filesource modules/index/controllers/menu.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Menu;

/**
 * default Controller.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{
    /*
     * Initial Controller.
     *
     * @param array $modules
     *
     * @return string
     */

    /**
     * @param $module
     */
    public function render($module)
    {
        // สร้างเมนู
        return \Index\Menu\View::create()->render($module);
    }
}
