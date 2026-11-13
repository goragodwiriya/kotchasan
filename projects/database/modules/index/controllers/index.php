<?php
/**
 * @filesource database/modules/index/controllers/index.php
 */

namespace Index\Index;

use Index\Product\Model as Product;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Template;
use Kotchasan\Validator;

/**
 * The product page: list, search, sort, add, edit and delete.
 *
 * The controller reads the request and decides what happens. The model
 * (modules/index/models/product.php) talks to the database, and the view
 * (modules/index/views/index.php) turns the data into HTML.
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
        // The CSRF tokens and the message shown after a redirect live in the session
        Request::startSecureSession();
        Template::init(self::$cfg->skin);
        $view = View::create();

        try {
            // The first visit creates datas/database.sqlite and the table
            Product::install();
        } catch (\Throwable $e) {
            return (new Response())->html($view->page($view->setupError($e->getMessage())), 500);
        }

        // The state of the list, kept in every link: ?q=word&sort=name&page=2
        $sort = $request->get('sort')->filter('a-z');
        $state = [
            'q' => $request->get('q')->topic(),
            'sort' => isset(Product::SORTS[$sort]) ? $sort : 'newest',
            'page' => max(1, $request->get('page', 1)->toInt())
        ];

        // An empty form adds a product; ?id=3 fills it with product 3 to edit it
        $form = ['id' => 0, 'data' => ['name' => '', 'price' => '', 'stock' => ''], 'errors' => []];
        $alert = self::takeFlash();

        if ($request->isPost()) {
            if (!Request::validateCsrfToken($request->post('token')->toString())) {
                return self::back('แบบฟอร์มหมดอายุ กรุณาลองอีกครั้ง', 'error', $state);
            }
            $id = $request->post('id')->toInt();

            if ($request->post('action')->filter('a-z') === 'delete') {
                $ok = Product::remove($id);

                return self::back($ok ? 'ลบสินค้าแล้ว' : 'ไม่พบสินค้าที่ต้องการลบ', $ok ? 'success' : 'error', $state);
            }

            $form = self::read($request, $id);
            if ($id > 0 && Product::get($id) === null) {
                return self::back('ไม่พบสินค้าที่ต้องการแก้ไข', 'error', $state);
            }
            if (empty($form['errors'])) {
                Product::save($id, $form['data']);

                return self::back($id > 0 ? 'บันทึกการแก้ไขแล้ว' : 'เพิ่มสินค้าแล้ว', 'success', $state);
            }
            // Not saved: the same page comes back with what was typed and the errors
            $alert = '<div class="alert alert-error" role="status">กรุณาแก้ไขข้อมูลที่มีเครื่องหมายสีแดง</div>';
        } elseif (($id = $request->get('id')->toInt()) > 0) {
            $product = Product::get($id);
            if ($product !== null) {
                $form = ['id' => (int) $product->id, 'data' => (array) $product, 'errors' => []];
            }
        }

        $content = $view->products(
            Product::search($state['q'], $state['sort'], $state['page']),
            Product::stats(),
            $state,
            $form,
            $request->generateCsrfToken()
        );

        return (new Response())->html($view->page($content, $alert), empty($form['errors']) ? 200 : 422);
    }

    /**
     * Reads and checks the product form.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return array id, data (ready to save), errors (field => message)
     */
    private static function read(Request $request, $id)
    {
        $name = $request->post('name')->topic();
        // filter() keeps digits, the point and the comma: "1,290.50"
        $price = $request->post('price')->filter('0-9\.,');
        $stock = $request->post('stock')->filter('0-9\-');

        $errors = [];
        if (!Validator::required($name)) {
            $errors['name'] = 'กรุณากรอกชื่อสินค้า';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'ชื่อสินค้ายาวได้ไม่เกิน 100 ตัวอักษร';
        }
        if (!Validator::float(str_replace(',', '', $price))) {
            $errors['price'] = 'กรุณากรอกราคาเป็นตัวเลข เช่น 1290 หรือ 1,290.50';
        }
        if (!Validator::integer($stock) || (int) $stock < 0) {
            $errors['stock'] = 'กรุณากรอกจำนวนเป็นจำนวนเต็มตั้งแต่ 0 ขึ้นไป';
        }

        return [
            'id' => $id,
            'data' => [
                'name' => $name,
                'price' => empty($errors['price']) ? (float) str_replace(',', '', $price) : $price,
                'stock' => empty($errors['stock']) ? (int) $stock : $stock
            ],
            'errors' => $errors
        ];
    }

    /**
     * Keeps a message for the next page and sends the browser back to the
     * list (Post/Redirect/Get), so reloading the page never sends a form twice.
     *
     * @param string $message
     * @param string $type    success or error
     * @param array  $state   q, sort and page of the list
     *
     * @return Response
     */
    private static function back($message, $type, array $state)
    {
        $_SESSION['product_flash'] = [$type, $message];

        return (new Response())->redirect(WEB_URL.View::url($state, [], false), 303);
    }

    /**
     * The message kept by back(), shown once.
     *
     * @return string
     */
    private static function takeFlash()
    {
        if (empty($_SESSION['product_flash'])) {
            return '';
        }
        list($type, $message) = $_SESSION['product_flash'];
        unset($_SESSION['product_flash']);

        return '<div class="alert alert-'.$type.'" role="status">'.$message.'</div>';
    }
}
