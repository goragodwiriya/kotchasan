<?php
/**
 * @filesource login/modules/index/controllers/index.php
 */

namespace Index\Index;

use Index\Auth\Model as Auth;
use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Template;
use Kotchasan\Text;
use Kotchasan\View;

/**
 * Login, the members' page and logout.
 *
 * Visitors who are not logged in see the login form. Logged-in users see the
 * members' page. Both forms POST to index.php with a CSRF token, and every
 * successful POST ends with a redirect (Post/Redirect/Get).
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Names of the roles in settings/config.php.
     *
     * @var array
     */
    const ROLES = [
        'admin' => 'ผู้ดูแลระบบ',
        'member' => 'สมาชิก'
    ];

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // The logged-in user and the CSRF tokens are kept in the session
        Request::startSecureSession();
        Template::init(self::$cfg->skin);

        $username = '';
        $alert = self::takeFlash();

        if ($request->isPost()) {
            if (!Request::validateCsrfToken($request->post('token')->toString())) {
                return self::back('แบบฟอร์มหมดอายุ กรุณาลองอีกครั้ง', 'error');
            }

            if ($request->post('action')->filter('a-z') === 'logout') {
                Auth::logout();

                return self::back('ออกจากระบบแล้ว', 'success');
            }

            // username() keeps a-z, 0-9 and @ . - _
            $username = $request->post('username')->username();
            // The password is only compared with the hash, so it is read exactly as typed
            $user = Auth::attempt($username, $request->post('password')->toString());
            if ($user !== null) {
                Auth::login($user);

                return self::back('ยินดีต้อนรับ '.$user['name'], 'success');
            }
            // One message for both mistakes: it does not tell which usernames exist
            $alert = '<div class="alert alert-error" role="alert">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</div>';
        }

        $user = Auth::user();
        $token = $request->generateCsrfToken();
        if ($user === null) {
            $title = 'เข้าสู่ระบบ';
            $content = Template::create('', '', 'login')->add([
                '/{TOKEN}/' => $token,
                '/{USERNAME}/' => Text::htmlspecialchars($username),
                // After a failed login the username is kept, so the cursor goes to the password
                '/{FOCUS_USERNAME}/' => $username === '' ? 'autofocus' : '',
                '/{FOCUS_PASSWORD}/' => $username === '' ? '' : 'autofocus'
            ])->render();
        } else {
            $title = 'หน้าสมาชิก';
            $content = $this->member($user, $token);
        }

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
     * The members' page. Part of it is shown only to the role "admin".
     *
     * @param array  $user
     * @param string $token
     *
     * @return string
     */
    private function member(array $user, $token)
    {
        $isAdmin = $user['role'] === 'admin';

        return Template::create('', '', 'member')->add([
            '/{INITIAL}/' => Text::htmlspecialchars(mb_substr($user['name'], 0, 1)),
            '/{NAME}/' => Text::htmlspecialchars($user['name']),
            '/{USERNAME}/' => Text::htmlspecialchars($user['username']),
            '/{ROLE}/' => self::ROLES[$user['role']],
            '/{ROLE_CLASS}/' => $isAdmin ? 'badge-primary' : 'badge-success',
            '/{LOGIN_AT}/' => Date::format($user['login_at'], 'd F Y เวลา H:i:s น.'),
            '/{SESSION}/' => substr(session_id(), 0, 8).'…',
            '/{ADMIN}/' => $isAdmin
                ? '<section class="card"><h2>เฉพาะผู้ดูแลระบบ</h2><p>กล่องนี้แสดงเพราะ <code>role</code> ของคุณคือ <code>admin</code> ลองออกจากระบบแล้วเข้าด้วย <code>demo</code> กล่องนี้จะหายไป</p></section>'
                : '<section class="card"><h2>เนื้อหาสำหรับสมาชิก</h2><p>บัญชี <code>member</code> ไม่เห็นกล่องของผู้ดูแลระบบ ลองเข้าด้วย <code>admin</code> เพื่อเปรียบเทียบ</p></section>',
            '/{TOKEN}/' => $token
        ])->render();
    }

    /**
     * Keeps a message for the next page and redirects to index.php.
     *
     * @param string $message
     * @param string $type    success or error
     *
     * @return Response
     */
    private static function back($message, $type)
    {
        $_SESSION['login_flash'] = [$type, $message];

        return (new Response())->redirect(WEB_URL.'index.php', 303);
    }

    /**
     * The message kept by back(), shown once.
     *
     * @return string
     */
    private static function takeFlash()
    {
        if (empty($_SESSION['login_flash'])) {
            return '';
        }
        list($type, $message) = $_SESSION['login_flash'];
        unset($_SESSION['login_flash']);

        return '<div class="alert alert-'.$type.'" role="status">'.Text::htmlspecialchars($message).'</div>';
    }
}
