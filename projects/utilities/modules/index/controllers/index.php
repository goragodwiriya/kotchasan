<?php
/**
 * @filesource utilities/modules/index/controllers/index.php
 */

namespace Index\Index;

use Kotchasan\Barcode;
use Kotchasan\Currency;
use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Jwt;
use Kotchasan\Number;
use Kotchasan\Password;
use Kotchasan\Promptpay;
use Kotchasan\Province;
use Kotchasan\Template;
use Kotchasan\Text;
use Kotchasan\Validator;
use Kotchasan\View;

/**
 * The utility classes of Kotchasan, run on the values of the form.
 * Every row shows a call and what it returned for this request.
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

        // A GET form: the values stay in the URL, so a result can be shared
        $amountText = $request->get('amount', '1234.50')->filter('0-9\.,');
        $amount = Text::toDouble($amountText);
        $birthday = $request->get('birthday')->date();
        if ($birthday === null) {
            $birthday = '1990-05-15';
        }
        $text = $request->get('text', 'Kotchasan PHP Framework')->topic(100);
        $email = $request->get('email', 'admin@example.com')->topic(100);
        $promptpay = $request->get('promptpay', '0812345678')->filter('0-9');
        $barcode = $request->get('barcode', 'KOTCHASAN-7')->filter('A-Z0-9\-\. ');

        $key = self::$cfg->password_key;
        $hash = Password::hash($text, $key);
        $jwt = Jwt::encode(['sub' => 7, 'name' => $text, 'iat' => time()], $key);
        $age = Date::compare($birthday, date('Y-m-d'));

        $sections = [
            'เงินและตัวเลข' => [
                'Currency::format($amount)' => Currency::format($amount),
                'Currency::bahtThai($amount)' => Currency::bahtThai($amount),
                'Currency::bahtEng($amount)' => Currency::bahtEng($amount),
                'Currency::calcVat($amount, 7)' => Currency::format(Currency::calcVat($amount, 7)).' (VAT 7% ของราคาที่ยังไม่รวม VAT)',
                'Currency::calcVat($amount, 7, false)' => Currency::format(Currency::calcVat($amount, 7, false)).' (VAT ที่รวมอยู่ในราคาแล้ว)',
                'Number::format(1234567)' => Number::format(1234567)
            ],
            'วันที่ภาษาไทย (พ.ศ.)' => [
                'Date::format()' => Date::format(),
                "Date::format(\$birthday, 'L d F Y')" => Date::format($birthday, 'L d F Y'),
                "Date::format(\$birthday, 'd M Y')" => Date::format($birthday, 'd M Y'),
                'Date::compare($birthday, date(\'Y-m-d\'))' => 'อายุ '.$age['year'].' ปี '.$age['month'].' เดือน '.$age['day'].' วัน (รวม '.Number::format($age['days']).' วัน)',
                'Date::monthName(12, false)' => Date::monthName(12, false)
            ],
            'ข้อความ' => [
                'Text::cut($text, 12)' => Text::cut($text, 12),
                'Text::topic("<b>Hello</b>\n  world")' => Text::topic("<b>Hello</b>\n  world"),
                "Text::htmlspecialchars('<b>\$5</b>')" => Text::htmlspecialchars('<b>$5</b>'),
                'Text::formatFileSize(1536000)' => Text::formatFileSize(1536000),
                "Text::toDouble('1,234.50')" => Text::toDouble('1,234.50'),
                "Text::generateRandomString(8, 'abcdef0123456789')" => Text::generateRandomString(8, 'abcdef0123456789')
            ],
            'ตรวจข้อมูล' => [
                'Validator::email($email)' => self::bool(Validator::email($email)),
                "Validator::date('2026-02-30')" => self::bool(Validator::date('2026-02-30')),
                "Validator::url('https://www.kotchasan.com')" => self::bool(Validator::url('https://www.kotchasan.com')),
                "Validator::integer('42')" => self::bool(Validator::integer('42'))
            ],
            'รหัสผ่านและ JWT' => [
                '$hash = Password::hash($text, $key)' => $hash,
                'Password::verify($text, $hash, \'\', $key)' => self::bool(Password::verify($text, $hash, '', $key)),
                'Password::uniqid()' => Password::uniqid(),
                '$jwt = Jwt::encode($claims, $key)' => $jwt,
                'Jwt::decode($jwt, $key)' => json_encode(Jwt::decode($jwt, $key), JSON_UNESCAPED_UNICODE),
                "Jwt::decode(\$jwt, 'wrong key')" => var_export(Jwt::decode($jwt, 'wrong key'), true)
            ],
            'จังหวัด' => [
                'Province::get(10)' => Province::get(10),
                "Province::get(50, 'en')" => Province::get(50, 'en'),
                'count(Province::all())' => count(Province::all())
            ],
            'พร้อมเพย์' => [
                'Promptpay::create($id, $amount)->payload()' => Promptpay::create($promptpay, $amount)->payload()
            ]
        ];

        $html = '';
        foreach ($sections as $title => $calls) {
            $rows = Template::create('', '', 'row');
            foreach ($calls as $call => $result) {
                $rows->add([
                    '/{CALL}/' => self::e($call),
                    '/{RESULT}/' => self::e($result)
                ]);
            }
            $html .= '<section class="card"><h2>'.$title.'</h2><div class="table-wrap"><table class="table calls"><tbody>'.$rows->render().'</tbody></table></div></section>';
        }

        // The barcode is drawn with GD
        if (!extension_loaded('gd')) {
            $html .= '<section class="card"><h2>บาร์โค้ด Code 128</h2><p class="muted">ต้องเปิดส่วนขยาย <code>gd</code> ของ PHP ก่อน</p></section>';
        } elseif ($barcode !== '') {
            // toPng() returns the PNG itself; a data: URL shows it without saving a file
            $png = Barcode::create($barcode, 60)->toPng();
            $html .= '<section class="card"><h2>บาร์โค้ด Code 128</h2>'
                .'<p><code>'.self::e("Barcode::create('$barcode', 60)->toPng()").'</code></p>'
                .'<p class="barcode"><img src="data:image/png;base64,'.base64_encode($png).'" alt="'.self::e($barcode).'"></p></section>';
        }

        $view = View::create();
        $view->setContents([
            '/{AMOUNT}/' => self::e($amountText),
            '/{BIRTHDAY}/' => self::e($birthday),
            '/{TEXT}/' => self::e($text),
            '/{EMAIL}/' => self::e($email),
            '/{PROMPTPAY}/' => self::e($promptpay),
            '/{BARCODE}/' => self::e($barcode),
            '/{RESULTS}/' => $html,
            '/{VERSION}/' => VERSION
        ]);

        return (new Response())->html($view->renderHTML());
    }

    /**
     * true / false as text.
     *
     * @param bool $value
     *
     * @return string
     */
    private static function bool($value)
    {
        return $value ? 'true' : 'false';
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
    private static function e($text)
    {
        return str_replace('{', '&#123;', Text::htmlspecialchars((string) $text));
    }
}
