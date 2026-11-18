<?php
/**
 * @filesource login/modules/index/models/auth.php
 */

namespace Index\Auth;

use Kotchasan\Password;

/**
 * Who is logged in.
 *
 * The accounts are in settings/config.php ('users'). Only the hash of each
 * password is stored, made with Kotchasan\Password::hash(). The user who has
 * logged in is kept in the session.
 */
class Model extends \Kotchasan\KBase
{
    /**
     * Where the logged-in user is kept in $_SESSION.
     *
     * @var string
     */
    const SESSION_KEY = 'login_example_user';

    /**
     * A hash that no password matches. It is checked when the username does
     * not exist, so a wrong username takes as long as a wrong password and
     * the time of the answer does not tell which usernames exist.
     *
     * @var string
     */
    const NO_USER_HASH = '$2y$12$3w2byf1ZCg46Y4absYgXKufw3r0xV6M.uuhfe4Uhmnhr6rbg.gvHO';

    /**
     * Checks a username and password.
     *
     * @param string $username
     * @param string $password
     *
     * @return array|null The user (username, name, role) or null
     */
    public static function attempt($username, $password)
    {
        $users = (array) self::$cfg->get('users', []);
        $user = isset($users[$username]) ? $users[$username] : null;

        $hash = $user === null ? self::NO_USER_HASH : $user['password'];
        $valid = Password::verify($password, $hash, '', self::$cfg->password_key);

        if ($user === null || !$valid) {
            return null;
        }

        return [
            'username' => $username,
            'name' => $user['name'],
            'role' => $user['role']
        ];
    }

    /**
     * Logs a user in.
     *
     * @param array $user from attempt()
     */
    public static function login(array $user)
    {
        // A new session id after logging in, so an id known before is worth nothing (session fixation)
        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = $user + ['login_at' => time()];
    }

    /**
     * Logs the user out.
     */
    public static function logout()
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    /**
     * The user who is logged in, or null.
     *
     * @return array|null
     */
    public static function user()
    {
        return empty($_SESSION[self::SESSION_KEY]) ? null : $_SESSION[self::SESSION_KEY];
    }
}
