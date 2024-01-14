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

use Kotchasan\Curl;

/**
 * Render the default view.
 *
 * This method is responsible for rendering the default view of the Index module.
 * It retrieves video information from YouTube API and displays it on the page.
 */
class View extends \Kotchasan\View
{
    public function render()
    {
        /*
         * YouTube API Key.
         * If you don't have one, you can obtain it from: https://gcms.in.th/howto/การสร้างคีย์สำหรับใช้งาน_Youtube_API_V3.html
         */
        $youtube_api_key = '';

        // ideo ID (e.g., https://www.youtube.com/watch?v=YPeMyo6F5UQ)
        $youtube = 'YPeMyo6F5UQ';

        // Get video info from YouTube API
        $url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,statistics&id='.$youtube.'&key='.$youtube_api_key;

        // Use Curl for HTTP request
        $curi = new Curl();
        $feed = $curi->referer(WEB_URL)->get($url);

        // Convert response to JSON
        $datas = json_decode($feed);

        // Check for errors
        if (isset($datas->error)) {
            echo $datas->error->message;
        } else {
            // Display video information
            echo '<html style="height:100%;width:100%"><head>';
            echo '<meta charset=utf-8>';
            echo '<meta name=viewport content="width=device-width, initial-scale=1.0">';
            echo '</head><body style="height:100%;width:100%;margin:0;font-family:Tahoma, Loma;color:#666;">';
            echo '<h1>'.$datas->items[0]->snippet->title.'</h1>';
            echo '<p><a href="https://www.youtube.com/watch?v='.$youtube.'" target=_blank><img src="'.$datas->items[0]->snippet->thumbnails->high->url.'"></a></p>';
            echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$youtube.'" frameborder="0" allowfullscreen></iframe>';
            echo '</body></html>';
        }
    }
}
