<?php
/**
 * @filesource modules/index/views/menu.php
 *
 * View file for the Menu module.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Menu;

/**
 * Default View for the Menu module.
 *
 * This class is responsible for rendering the menu.
 *
 * @see https://www.kotchasan.com/
 */
class View extends \Kotchasan\View
{
    /**
     * Render the menu.
     *
     * This method is responsible for rendering the menu based on the specified module.
     *
     * @param string $module The module name.
     *
     * @return string The rendered menu HTML.
     */
    public function render($module)
    {
        // Menu items
        $menus['home'] = array('Home', 'index.php');
        $menus['about'] = array('About Us', 'index.php?module=about');

        // Generate the menu HTML
        $menu = '';
        foreach ($menus as $key => $values) {
            $c = $module == $key ? ' class=select' : '';
            $menu .= '<li'.$c.'><a href="'.$values[1].'"><span>'.$values[0].'</span></a></li>';
        }

        return $menu;
    }
}
