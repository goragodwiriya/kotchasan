<?php
/**
 * View for the Index module.
 * This class handles the rendering of the default view for the Index module.
 * For more information, please visit: https://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Index;

/**
 * Render the default view.
 *
 * This method is responsible for rendering the default view of the Index module.
 * It retrieves video information from YouTube API and displays it on the page.
 */
class View extends \Kotchasan\View
{
    /**
     * Render the default view.
     */
    public function render()
    {
        echo '<!DOCTYPE html><html><head>';
        echo '<title>Sample HTML to PDF and DOC Conversion</title>';
        echo '<meta charset=utf-8>';
        echo '<link href="https://www.kotchasan.com/skin/gcss.css" rel="stylesheet" type="text/css">';
        echo '<link href="https://www.kotchasan.comskin/fonts.css" rel="stylesheet" type="text/css">';
        echo '<meta name=viewport content="width=device-width, initial-scale=1.0">';
        echo '<script>var doClick=function(name){document.getElementById("type").value=name;document.getElementById("submit").click()};</script>';
        echo '<style>';
        echo '.warper{display:inline-block;text-align:center;height:50%;}';
        echo '.warper::before{content:"";display:inline-block;height:100%;vertical-align:middle;width:0px;}';
        echo '</style>';
        echo '</head><body style="height:100%;width:100%;margin:0;font-family:Tahoma, Loma;color:#666;">';
        echo '<form style="margin: 10px;" method="post" action="index.php/index/controller/export" target="_blank">';
        $content = file_get_contents('./modules/index/controllers/pdf.html');
        echo '<label class="g-input icon-file"><textarea name="content" rows="20">'.htmlentities($content).'</textarea></label>';
        echo '<p class="submit"><input type="button" name="pdf" class="button large print wide" value="Export To PDF" onclick="doClick(this.name)"></p>';
        echo '<p class="submit"><input type="button" name="doc" class="button large print wide" value="Export To DOC" onclick="doClick(this.name)"></p>';
        echo '<input type="submit" id="submit" style="display:none"><input type="hidden" name="type" id="type">';
        echo '<div class="center message">PDF and DOC supports only specific HTML tags and does not support all CSS styles</div></form>';
        echo '</body></html>';
    }
}
