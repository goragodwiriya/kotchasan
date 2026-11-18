<?php
/**
 * @filesource form/modules/index/controllers/index.php
 */

namespace Index\Index;

use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Template;
use Kotchasan\Text;
use Kotchasan\Validator;
use Kotchasan\View;

/**
 * A contact form.
 *
 * GET shows the form. The form POSTs back to index.php:
 * - with errors, the same form comes back with what was typed and a message under each field
 * - without errors, the data is kept in the session and the browser is sent to
 *   index.php again (Post/Redirect/Get), which shows it once. Reloading that
 *   page never sends the form twice.
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Choices of the "topic" field: value => label.
     *
     * @var array
     */
    const TOPICS = [
        'question' => 'สอบถามทั่วไป',
        'quote' => 'ขอใบเสนอราคา',
        'support' => 'แจ้งปัญหาการใช้งาน',
        'other' => 'เรื่องอื่น ๆ'
    ];

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // The CSRF tokens and the data shown after the redirect live in the session
        Request::startSecureSession();

        // Templates are read from themes/default/
        Template::init(self::$cfg->skin);

        $data = ['name' => '', 'email' => '', 'phone' => '', 'topic' => '', 'message' => '', 'agree' => false];
        $errors = [];
        $alert = '';

        if ($request->isPost()) {
            // post() reads the form, and each method cleans the value its own way
            $data = [
                // topic(): one line of plain text, HTML tags removed
                'name' => $request->post('name')->topic(),
                'email' => $request->post('email')->topic(),
                // filter(): keep only these characters
                'phone' => $request->post('phone')->filter('0-9\-\+ '),
                'topic' => $request->post('topic')->filter('a-z'),
                'message' => trim(strip_tags($request->post('message')->toString())),
                'agree' => $request->post('agree')->bool()
            ];

            if (!Request::validateCsrfToken($request->post('token')->toString())) {
                // The token is missing, expired or comes from another site
                $alert = self::alert('error', 'แบบฟอร์มหมดอายุ กรุณากดส่งอีกครั้ง');
            } else {
                $errors = self::check($data);
                if (empty($errors)) {
                    // A real site would save the message or send it with Kotchasan\Email here
                    $_SESSION['contact_sent'] = $data + ['time' => time()];

                    return (new Response())->redirect(WEB_URL.'index.php', 303);
                }
                $alert = self::alert('error', 'กรุณาแก้ไขข้อมูล '.count($errors).' ช่องที่มีเครื่องหมายสีแดง');
            }
        } elseif (isset($_SESSION['contact_sent'])) {
            // The page after the redirect: show the data once, then forget it
            $sent = $_SESSION['contact_sent'];
            unset($_SESSION['contact_sent']);

            return $this->page('ส่งข้อความเรียบร้อย', $this->thanks($sent), self::alert('success', 'ได้รับข้อความของคุณแล้ว ขอบคุณที่ติดต่อเรา'));
        }

        // generateCsrfToken() stores a new token in the session; the form sends it back
        return $this->page('ฟอร์มติดต่อ', $this->form($data, $errors, $request->generateCsrfToken()), $alert);
    }

    /**
     * Checks the form. Every rule runs on the server, whatever the browser did.
     *
     * @param array $data
     *
     * @return array field => message
     */
    private static function check(array $data)
    {
        $errors = [];
        if (!Validator::required($data['name'])) {
            $errors['name'] = 'กรุณากรอกชื่อ';
        } elseif (mb_strlen($data['name']) > 100) {
            $errors['name'] = 'ชื่อยาวได้ไม่เกิน 100 ตัวอักษร';
        }

        if (!Validator::required($data['email'])) {
            $errors['email'] = 'กรุณากรอกอีเมล';
        } elseif (!Validator::email($data['email'])) {
            $errors['email'] = 'รูปแบบอีเมลไม่ถูกต้อง เช่น name@example.com';
        }

        // Optional: checked only when it was filled in
        $digits = preg_replace('/[^0-9]/', '', $data['phone']);
        if ($data['phone'] !== '' && (!Validator::phone($data['phone']) || strlen($digits) < 9 || strlen($digits) > 12)) {
            $errors['phone'] = 'เบอร์โทรศัพท์ควรมี 9-10 หลัก เช่น 081-234-5678';
        }

        if (!Validator::inList($data['topic'], array_keys(self::TOPICS))) {
            $errors['topic'] = 'กรุณาเลือกเรื่องที่ต้องการติดต่อ';
        }

        $length = mb_strlen($data['message']);
        if ($length < 10) {
            $errors['message'] = 'กรุณาพิมพ์ข้อความอย่างน้อย 10 ตัวอักษร (ตอนนี้ '.$length.')';
        } elseif ($length > 1000) {
            $errors['message'] = 'ข้อความยาวได้ไม่เกิน 1,000 ตัวอักษร';
        }

        if (!$data['agree']) {
            $errors['agree'] = 'กรุณายอมรับก่อนส่งข้อความ';
        }

        return $errors;
    }

    /**
     * The form, filled with $data, with a message under each field that has an error.
     *
     * @param array  $data
     * @param array  $errors
     * @param string $token
     *
     * @return string
     */
    private function form(array $data, array $errors, $token)
    {
        $topics = '<option value="">-- เลือก --</option>';
        foreach (self::TOPICS as $value => $label) {
            $selected = $value === $data['topic'] ? ' selected' : '';
            $topics .= '<option value="'.$value.'"'.$selected.'>'.$label.'</option>';
        }

        $replace = [
            '/{TOKEN}/' => $token,
            '/{TOPICS}/' => $topics,
            '/{AGREE}/' => $data['agree'] ? 'checked' : ''
        ];
        foreach (['name', 'email', 'phone', 'topic', 'message', 'agree'] as $field) {
            $key = strtoupper($field);
            $replace['/{'.$key.'}/'] = is_string($data[$field]) ? self::e($data[$field]) : '';
            $replace['/{'.$key.'_ERROR}/'] = isset($errors[$field]) ? '<p class="error-text" id="'.$field.'-error">'.$errors[$field].'</p>' : '';
            $replace['/{'.$key.'_INVALID}/'] = isset($errors[$field]) ? 'aria-invalid="true" aria-describedby="'.$field.'-error"' : '';
        }

        return Template::create('', '', 'form')->add($replace)->render();
    }

    /**
     * What was sent, shown after the redirect.
     *
     * @param array $sent
     *
     * @return string
     */
    private function thanks(array $sent)
    {
        return Template::create('', '', 'thanks')->add([
            '/{NAME}/' => self::e($sent['name']),
            '/{EMAIL}/' => self::e($sent['email']),
            '/{PHONE}/' => $sent['phone'] === '' ? '-' : self::e($sent['phone']),
            '/{TOPIC}/' => self::TOPICS[$sent['topic']],
            '/{MESSAGE}/' => nl2br(self::e($sent['message'])),
            '/{TIME}/' => Date::format($sent['time'], 'd F Y เวลา H:i:s น.')
        ])->render();
    }

    /**
     * Puts the content into the layout themes/default/index.html.
     *
     * @param string $title
     * @param string $content
     * @param string $alert
     *
     * @return Response
     */
    private function page($title, $content, $alert = '')
    {
        $view = View::create();
        $view->setContents([
            '/{TITLE}/' => $title,
            '/{ALERT}/' => $alert,
            '/{CONTENT}/' => $content,
            '/{VERSION}/' => VERSION
        ]);

        return (new Response())->html($view->renderHTML());
    }

    /**
     * Text made safe to put into a template.
     *
     * Text::htmlspecialchars() encodes the HTML characters and also $ and \,
     * which Template would read as part of its replacement. { is encoded
     * as well, so a visitor who types {WEBURL} sees exactly that.
     *
     * @param string $text
     *
     * @return string
     */
    private static function e($text)
    {
        return str_replace('{', '&#123;', Text::htmlspecialchars((string) $text));
    }

    /**
     * A message box.
     *
     * @param string $type    success or error
     * @param string $message
     *
     * @return string
     */
    private static function alert($type, $message)
    {
        return '<div class="alert alert-'.$type.'" role="status">'.$message.'</div>';
    }
}
