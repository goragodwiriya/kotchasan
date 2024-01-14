<?php
/**
 * @filesource modules/index/controllers/api.php
 *
 * API Controller.
 *
 * This file is part of the Kotchasan CMS package.
 * It is used to handle API requests.
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya
 */

namespace Index\Api;

use Kotchasan\Http\Request;
use Kotchasan\Http\Response;

class Controller extends \Kotchasan\Controller
{
    /**
     * Method for validating and processing the API.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // Get the requested method
        $method = $request->get('method')->toString();
        // Variable for storing the response data
        $ret = [];
        // Process the requested method
        if (method_exists('Index\Api\Model', $method)) {
            $ret['result'] = call_user_func(array('Index\Api\Model', $method), $request);
        } else {
            // Error: Method not found
            $ret['error'] = 'Method not found';
        }
        // Create a Response object for sending the response
        $response = new Response();
        // Set the header to JSON+UTF-8
        $response->withHeader('Content-Type', 'application/json; charset=utf-8')
        // Set the response content
            ->withContent(json_encode($ret))
        // Send the response
            ->send();
    }
}
