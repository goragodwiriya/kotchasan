<?php
/**
 * @filesource modules/index/views/index.php
 *
 * View class for rendering HTML.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Index;

use Kotchasan\Http\Request;

/**
 * View class for rendering HTML.
 */
class View extends \Kotchasan\View
{
    /**
     * Render the HTML.
     *
     * @param Request $request The HTTP request object.
     */
    public function render()
    {
        // Get the current timestamp
        $mktime = time();

        // Initialize Curl
        $ch = new \Kotchasan\Curl();

        // Call the Online API
        $json = $ch->get('https://projects.kotchasan.com/api/api.php', array('method' => 'getTime', 'id' => $mktime));

        // Convert JSON to an array
        $array = json_decode($json, true);

        // Prepare data for inserting into the template
        $this->setContents(array(
            // Current timestamp to be inserted into the template
            '/{MKTIME}/' => $mktime,
            // Result obtained from calling the API
            '/{RESULT}/' => isset($array['result']) ? $array['result'] : ''
        ));

        // Load the index.html template
        $template = file_get_contents('modules/index/views/index.html');

        // Render and return the HTML template
        echo $this->renderHTML($template);
    }
}
