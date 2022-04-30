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
        echo '<!DOCTYPE html><html><head>';
        echo '<title>ตัวอย่างการแปลง HTML เป็นไฟล์ PDF</title>';
        echo '<meta charset=utf-8>';
        echo '<link href="https://www.kotchasan.com/skin/gcss.css" rel="stylesheet" type="text/css">';
        echo '<link href="https://www.kotchasan.comskin/fonts.css" rel="stylesheet" type="text/css">';
        echo '<meta name=viewport content="width=device-width, initial-scale=1.0">';
        echo '<style>';
        echo '.warper{display:inline-block;text-align:center;height:50%;}';
        echo '.warper::before{content:"";display:inline-block;height:100%;vertical-align:middle;width:0px;}';
        echo '</style>';
        echo '</head><body style="height:100%;width:100%;margin:0;font-family:Tahoma, Loma;color:#666;">';
        echo '<form style="margin: 10px;" method=post action="index.php/index/controller/export" target="_blank">';
        $content = file_get_contents('./modules/index/controllers/pdf.html');
        echo '<label class="g-input icon-file"><textarea name=content rows=20>'.htmlentities($content).'</textarea></label>';
        echo '<p class="submit"><input type=submit class="button large print wide" value="Export To PDF"></p>';
        echo '<div class="center message">รองรับ HTML บางตัวเท่านั้น และไม่ได้รองรับ CSS ทุกตัว</div></form>';
        echo '</body></html>';
    }
}
