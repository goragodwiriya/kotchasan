<?php
/**
 * @filesource ajax/modules/index/controllers/index.php
 */

namespace Index\Index;

use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Template;
use Kotchasan\View;

/**
 * The page with the three demos. The JavaScript in themes/default/script.js
 * calls the methods of Index\Ajax\Controller (modules/index/controllers/ajax.php).
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // The comment form sends a CSRF token, which is kept in the session
        Request::startSecureSession();
        Template::init(self::$cfg->skin);

        $view = View::create();
        $view->setContents([
            // index.php/{module}/controller/{file}/{method} calls a method of a controller:
            // index.php/index/controller/ajax/time is Index\Ajax\Controller::time()
            '/{ENDPOINT}/' => WEB_URL.'index.php/index/controller/ajax/',
            '/{TOKEN}/' => $request->generateCsrfToken(),
            '/{VERSION}/' => VERSION
        ]);

        return (new Response())->html($view->renderHTML());
    }
}
