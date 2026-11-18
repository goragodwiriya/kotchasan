<?php
/**
 * @filesource database/modules/index/views/index.php
 */

namespace Index\Index;

use Kotchasan\Currency;
use Kotchasan\Date;
use Kotchasan\Number;
use Kotchasan\Template;
use Kotchasan\Text;

/**
 * HTML of the product page.
 *
 * The markup is in themes/default/*.html. Template::create() reads one file,
 * add() fills a copy of it and render() joins the copies. A view only turns
 * data into HTML: it does not read the request or the database.
 */
class View extends \Kotchasan\View
{
    /**
     * Names of the sort orders (Product\Model::SORTS).
     *
     * @var array
     */
    const SORT_NAMES = [
        'newest' => 'เพิ่มล่าสุด',
        'name' => 'ชื่อ ก-ฮ',
        'cheap' => 'ราคาน้อยไปมาก',
        'expensive' => 'ราคามากไปน้อย',
        'stock' => 'ใกล้หมดก่อน'
    ];

    /**
     * The list, the form and the SQL panel.
     *
     * @param array  $result Product\Model::search()
     * @param object $stats  Product\Model::stats()
     * @param array  $state  q, sort, page
     * @param array  $form   id, data, errors
     * @param string $token  CSRF token for the forms
     *
     * @return string
     */
    public function products(array $result, $stats, array $state, array $form, $token)
    {
        $action = self::url($state);

        $rows = Template::create('', '', 'row');
        foreach ($result['items'] as $item) {
            $stock = (int) $item->stock;
            if ($stock === 0) {
                $badge = '<span class="badge badge-danger">หมด</span>';
            } elseif ($stock < 5) {
                $badge = '<span class="badge badge-warning">'.$stock.'</span>';
            } else {
                $badge = '<span class="badge">'.Number::format($stock).'</span>';
            }
            $rows->add([
                '/{ID}/' => (int) $item->id,
                '/{NAME}/' => self::e($item->name),
                '/{PRICE}/' => Currency::format($item->price),
                '/{STOCK}/' => $badge,
                '/{CREATED}/' => Date::format($item->created_at, 'd M Y'),
                '/{EDIT_URL}/' => self::url($state, ['id' => (int) $item->id]),
                '/{ACTION}/' => $action,
                '/{TOKEN}/' => $token
            ]);
        }

        $sorts = '';
        foreach (self::SORT_NAMES as $value => $label) {
            $sorts .= '<option value="'.$value.'"'.($value === $state['sort'] ? ' selected' : '').'>'.$label.'</option>';
        }

        return Template::create('', '', 'products')->add([
            '/{TOTAL}/' => Number::format((int) $stats->total),
            '/{STOCK}/' => Number::format((int) $stats->stock),
            '/{VALUE}/' => Currency::format((float) $stats->value),
            '/{LOW}/' => Number::format((int) $stats->low),
            '/{SUMMARY}/' => $state['q'] === '' ? 'ทั้งหมด '.$result['total'].' รายการ' : 'พบ '.$result['total'].' รายการ',
            '/{Q}/' => self::e($state['q']),
            '/{SORTS}/' => $sorts,
            '/{ROWS}/' => $rows->hasItem() ? $rows->render() : '<tr><td colspan="4" class="empty">ไม่พบสินค้า</td></tr>',
            '/{PAGINATION}/' => self::pagination($result, $state),
            '/{FORM}/' => self::form($form, $action, $token),
            '/{SQL}/' => self::e($result['sql']),
            '/{BINDINGS}/' => empty($result['bindings']) ? '<span class="muted">ไม่มี (ลองค้นหาดู)</span>' : '<code>'.self::e(json_encode($result['bindings'], JSON_UNESCAPED_UNICODE)).'</code>'
        ])->render();
    }

    /**
     * Puts the content into the layout themes/default/index.html.
     *
     * @param string $content
     * @param string $alert
     *
     * @return string
     */
    public function page($content, $alert = '')
    {
        $this->setContents([
            '/{ALERT}/' => $alert,
            '/{CONTENT}/' => $content,
            '/{VERSION}/' => VERSION
        ]);

        return $this->renderHTML();
    }

    /**
     * Shown when the database cannot be opened.
     *
     * @param string $error
     *
     * @return string
     */
    public function setupError($error)
    {
        return '<section class="card"><h2>เปิดฐานข้อมูลไม่ได้</h2>'
            .'<p>ตัวอย่างนี้ใช้ SQLite ตรวจสอบสองอย่างนี้</p>'
            .'<ol class="steps"><li>PHP เปิดส่วนขยาย <code>pdo_sqlite</code> แล้ว</li>'
            .'<li>เว็บเซิร์ฟเวอร์เขียนไฟล์ในโฟลเดอร์ <code>database/datas/</code> ได้</li></ol>'
            .'<pre><code>'.self::e($error).'</code></pre></section>';
    }

    /**
     * URL of the list with its state, e.g. index.php?q=coffee&page=2.
     *
     * @param array $state
     * @param array $more   values to add, e.g. ['id' => 3]
     * @param bool  $encode true for HTML (&amp;), false for a Location header
     *
     * @return string
     */
    public static function url(array $state, array $more = [], $encode = true)
    {
        $query = array_filter($more + $state, function ($value, $key) {
            // Left out when they are the default
            return !($value === '' || ($key === 'sort' && $value === 'newest') || ($key === 'page' && $value <= 1));
        }, ARRAY_FILTER_USE_BOTH);

        return 'index.php'.(empty($query) ? '' : '?'.http_build_query($query, '', $encode ? '&amp;' : '&'));
    }

    /**
     * Text made safe to put into a template.
     *
     * Text::htmlspecialchars() encodes the HTML characters and also $ and \,
     * which Template would read as part of its replacement. { is encoded
     * as well, so typed text such as {WEBURL} stays as it is.
     *
     * @param mixed $text
     *
     * @return string
     */
    public static function e($text)
    {
        return str_replace('{', '&#123;', Text::htmlspecialchars((string) $text));
    }

    /**
     * The add/edit form, filled with $form['data'] and its errors.
     *
     * @param array  $form
     * @param string $action
     * @param string $token
     *
     * @return string
     */
    private static function form(array $form, $action, $token)
    {
        $editing = $form['id'] > 0;
        $replace = [
            '/{FORM_TITLE}/' => $editing ? 'แก้ไขสินค้า #'.$form['id'] : 'เพิ่มสินค้า',
            '/{ACTION}/' => $action,
            '/{TOKEN}/' => $token,
            '/{ID}/' => (int) $form['id'],
            '/{SUBMIT}/' => $editing ? 'บันทึกการแก้ไข' : 'เพิ่มสินค้า',
            '/{CANCEL}/' => $editing ? '<a class="btn btn-secondary" href="'.self::url([]).'">ยกเลิก</a>' : ''
        ];
        foreach (['name', 'price', 'stock'] as $field) {
            $key = strtoupper($field);
            $error = isset($form['errors'][$field]) ? $form['errors'][$field] : '';
            $replace['/{'.$key.'}/'] = self::e($form['data'][$field]);
            $replace['/{'.$key.'_ERROR}/'] = $error === '' ? '' : '<p class="error-text" id="'.$field.'-error">'.$error.'</p>';
            $replace['/{'.$key.'_INVALID}/'] = $error === '' ? '' : 'aria-invalid="true" aria-describedby="'.$field.'-error"';
        }

        return Template::create('', '', 'form')->add($replace)->render();
    }

    /**
     * Page links: 1 2 3.
     *
     * @param array $result
     * @param array $state
     *
     * @return string
     */
    private static function pagination(array $result, array $state)
    {
        if ($result['pages'] < 2) {
            return '';
        }
        $html = '<nav class="pagination" aria-label="หน้า">';
        for ($i = 1; $i <= $result['pages']; ++$i) {
            if ($i === $result['page']) {
                $html .= '<span aria-current="page">'.$i.'</span>';
            } else {
                $html .= '<a href="'.self::url(['page' => $i] + $state).'">'.$i.'</a>';
            }
        }

        return $html.'</nav>';
    }
}
