<?php
/**
 * @filesource ajax/modules/index/controllers/ajax.php
 */

namespace Index\Ajax;

use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Province;

/**
 * The answers of the Ajax calls, all in JSON.
 *
 * Kotchasan\Router turns the URL into a class and a method:
 *
 *   index.php/index/controller/ajax/time       Index\Ajax\Controller::time()
 *   index.php/index/controller/ajax/provinces  Index\Ajax\Controller::provinces()
 *   index.php/index/controller/ajax/comments   Index\Ajax\Controller::comments()
 *
 * Only public methods can be called this way.
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Comments kept in the session (the newest first).
     *
     * @var int
     */
    const MAX_COMMENTS = 5;

    /**
     * GET: the time of the server.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function time(Request $request)
    {
        if (!$request->isGet()) {
            return self::json(['error' => 'Method not allowed'], 405);
        }

        return self::json([
            'time' => date('H:i:s'),
            // Kotchasan\Date writes Thai day and month names and the Buddhist year
            'date' => Date::format(time(), 'L d F Y'),
            'timezone' => date_default_timezone_get(),
            'timestamp' => time()
        ]);
    }

    /**
     * GET ?q=: the provinces whose Thai or English name contains q.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function provinces(Request $request)
    {
        if (!$request->isGet()) {
            return self::json(['error' => 'Method not allowed'], 405);
        }

        $q = $request->get('q')->topic(50);
        $items = [];
        // Kotchasan\Province has the 77 provinces of Thailand: ISO code => name
        foreach (Province::all() as $iso => $thai) {
            $english = Province::get($iso, 'en');
            if ($q === '' || mb_stripos($thai, $q) !== false || stripos($english, $q) !== false) {
                $items[] = ['iso' => $iso, 'th' => $thai, 'en' => $english];
            }
        }

        return self::json([
            'query' => $q,
            'count' => count($items),
            // Only the first 12 are sent; count tells how many matched
            'items' => array_slice($items, 0, 12)
        ]);
    }

    /**
     * GET: the comments. POST {name, message, token}: adds one.
     *
     * The page sends JSON, which Kotchasan\Http\Request reads like a form,
     * so $request->post('name') works for both.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function comments(Request $request)
    {
        Request::startSecureSession();
        $comments = isset($_SESSION['ajax_comments']) ? $_SESSION['ajax_comments'] : [];

        if ($request->isGet()) {
            return self::json(['items' => $comments]);
        }
        if (!$request->isPost()) {
            return self::json(['error' => 'Method not allowed'], 405);
        }

        // The token of the page proves that the call comes from this site
        if (!Request::validateCsrfToken($request->post('token')->toString())) {
            return self::json(['error' => 'หน้านี้หมดอายุ กรุณาโหลดหน้าใหม่'], 403);
        }

        $name = $request->post('name')->topic(50);
        $message = $request->post('message')->topic(200);
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'กรุณากรอกชื่อ';
        }
        if (mb_strlen($message) < 3) {
            $errors['message'] = 'กรุณาพิมพ์อย่างน้อย 3 ตัวอักษร';
        }
        if (!empty($errors)) {
            // 422: the data was read but did not pass the checks
            return self::json(['error' => 'กรุณาแก้ไขข้อมูล', 'errors' => $errors], 422);
        }

        array_unshift($comments, [
            'name' => $name,
            'message' => $message,
            'time' => Date::format(time(), 'd M Y H:i:s')
        ]);
        $_SESSION['ajax_comments'] = array_slice($comments, 0, self::MAX_COMMENTS);

        return self::json(['message' => 'บันทึกความคิดเห็นแล้ว', 'items' => $_SESSION['ajax_comments']], 201);
    }

    /**
     * A JSON answer. Response::json() sets the Content-Type header and the status.
     *
     * @param array $data
     * @param int   $status
     *
     * @return Response
     */
    private static function json(array $data, $status = 200)
    {
        return (new Response())->json($data, $status);
    }
}
