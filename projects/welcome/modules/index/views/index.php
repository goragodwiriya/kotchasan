<?php
/**
 * @filesource modules/index/views/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Index;

/*
 * default View
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */

class View extends \Kotchasan\View
{
    public function render()
    {
        echo '<html><head>';
        echo '<meta charset=utf-8>';
        echo '<link href="https://fonts.googleapis.com/css?family=Itim&subset=thai,latin" rel="stylesheet" type="text/css">';
        echo '<link href="modules/index/views/style.css" rel="stylesheet" type="text/css">';
        echo '<meta name=viewport content="width=device-width, initial-scale=1.0">';
        echo '</head><body style="height:100%;width:100%;margin:0;font-family:Itim, Tahoma, Loma;color:#666;">';
        echo '<div class=warper style="display:block"><div class="warper"><div>';
        echo '<div class="elephant"><div class="body"></div><div class="tail"></div><div class="head"><div class="trunk"></div><div class="ear"></div><div class="eye"></div></div></div>';
        echo '<h1 style="line-height:1.8;margin:0;text-shadow:3px 3px 0 rgba(0,0,0,0.1);font-weight:normal;">คชสาร (Kotchasan)</h1>';
        echo 'Siam PHP Framework';
        echo '</div></div></body></html>';
    }
}
