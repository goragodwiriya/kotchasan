<?php
/**
 * @filesource modules/index/views/menu.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Menu;

/*
 * default View
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */

class View extends \Kotchasan\View
{
    /**
     * ฟังก์ชั่นสร้างเมนู.
     *
     * @param array $module หน้าที่เรียก มาจาก Controller
     *
     * @return string
     */
    public function render($module)
    {
        // รายการเมนู
        $menus['home'] = array('หน้าหลัก', 'index.php');
        $menus['about'] = array('เกี่ยวกับเรา', 'index.php?module=about');
        // สร้างเมนู
        $menu = '';
        foreach ($menus as $key => $values) {
            $c = $module == $key ? ' class=select' : '';
            $menu .= '<li'.$c.'><a href="'.$values[1].'"><span>'.$values[0].'</span></a></li>';
        }

        return $menu;
    }
}
