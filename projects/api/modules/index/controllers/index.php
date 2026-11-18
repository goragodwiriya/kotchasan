<?php
/**
 * @filesource api/modules/index/controllers/index.php
 */

namespace Index\Index;

use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Template;
use Kotchasan\Text;
use Kotchasan\View;

/**
 * The page index.php: what the API does, and a console that calls it with
 * fetch() (themes/default/script.js). The API itself is api.php.
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
        Template::init(self::$cfg->skin);

        // The demo token is printed so the console can use it.
        // A real site never shows a token on a public page.
        $tokens = (array) self::$cfg->get('api_tokens', []);

        $view = View::create();
        $view->setContents([
            '/{API_URL}/' => WEB_URL.'api.php/v1/books',
            '/{TOKEN}/' => Text::htmlspecialchars((string) reset($tokens)),
            '/{VERSION}/' => VERSION
        ]);

        return (new Response())->html($view->renderHTML());
    }
}
