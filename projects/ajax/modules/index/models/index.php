<?php
/**
 * @filesource modules/index/models/index.php
 *
 * Model for handling Ajax data.
 *
 * This file is part of the Kotchasan CMS package.
 * It is used to handle Ajax requests and perform related operations.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya
 */

namespace Index\Index;

use Kotchasan\Http\Request;

class Model
{
    /**
     * Load a website using Ajax.
     *
     * @param Request $request
     *
     * @return string
     */
    public function web(Request $request)
    {
        // Check if the request is made from within the website
        if ($request->isReferer()) {
            // View the sent data
            //print_r($_POST);
            // Get the URL parameter
            $url = $request->post('url')->url();
            if ($url != '' && preg_match('/^https?:\/\/.*/', $url)) {
                // Load the URL
                $content = file_get_contents($url);
                // Return the HTML content to Ajax
                echo $content;
            } else {
                // Not an HTTP URL
                echo $url;
            }
        }
    }

    /**
     * Save data using Ajax.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // Check if the request is made from within the website
        if ($request->isReferer()) {
            // View the sent data
            //print_r($_POST);
            // Create a Model object
            $model = new \Kotchasan\Model();
            // Loop through the $_POST data
            foreach ($_POST as $key => $value) {
                if ($key == 'test') {
                    // For 'test' key, cast the value to an integer
                    $save['test'] = $request->post($key)->toInt();
                } else {
                    // For 'name' key, sanitize the value as a single-line string
                    $save['name'] = $request->post($key)->topic();
                }
            }
            if (!empty($save)) {
                if (isset($save['name']) && $save['name'] == '') {
                    $json = array('error' => 'Please enter a message');
                } else {
                    // Prepare INSERT query
                    $query = $model->db()->createQuery()->insert('world', $save);
                    // Uncomment the following line to execute the SQL query in a real scenario
                    //$query->execute();
                    // JSON data to be returned for display
                    $json = array(
                        // Return the generated SQL query
                        'sql' => $query->text()
                    );
                }
                // Return JSON data
                echo json_encode($json);
            }
        }
    }

    /**
     * Get the current server time.
     *
     * @param Request $request
     */
    public function time(Request $request)
    {
        // Return the current server time
        echo date('H:i:s');
    }
}
