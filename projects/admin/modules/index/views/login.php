<?php
/**
 * @filesource modules/index/views/login.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Login;

use Kotchasan\Html;
use Kotchasan\Language;
use Kotchasan\Login;

/**
 * Login Form.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Kotchasan\View
{
    /**
     * แสดงผล.
     */
    public function render()
    {
        // form login
        $form = Html::create('form', array(
            'id' => 'login_frm',
            'class' => 'login',
            'autocomplete' => 'off',
            'gform' => false,
        ));
        // h1
        $form->add('h1', array(
            'class' => 'icon-customer',
            'innerHTML' => Language::get('Administrator Area'),
        ));
        // message
        if (isset(Login::$login_message)) {
            $form->add('p', array(
                'class' => empty(Login::$login_input) ? 'message' : 'error',
                'innerHTML' => Login::$login_message,
            ));
        }
        // fieldset
        $fieldset = $form->add('fieldset', array(
            'title' => 'Please enter Username and Password (admin+admin)',
        ));
        // username
        $fieldset->add('text', array(
            'id' => 'login_username',
            'labelClass' => 'g-input icon-user',
            'placeholder' => Language::get('Username'),
            'accesskey' => 'e',
            'maxlength' => 255,
            'value' => isset(Login::$text_username) ? Login::$text_username : '',
        ));
        // password
        $fieldset->add('password', array(
            'id' => 'login_password',
            'labelClass' => 'g-input icon-password',
            'autocomplete' => 'off',
            'placeholder' => Language::get('Password'),
            'value' => isset(Login::$text_password) ? Login::$text_password : '',
        ));
        // input-groups (div สำหรับจัดกลุ่ม input)
        $group = $fieldset->add('groups');
        // a
        $group->add('a', array(
            'href' => self::$request->getUri()->withParams(array('action' => 'forgot'), true),
            'class' => 'td',
            'title' => Language::get('Request new password'),
            'innerHTML' => ''.Language::get('Forgot').' ?',
        ));
        // submit
        $fieldset->add('submit', array(
            'class' => 'button ok large wide',
            'value' => Language::get('Sign in'),
        ));
        // คืนค่า form

        return $form->render();
    }

    /**
     * title bar.
     */
    public function title()
    {
        return Language::get('Administrator Area');
    }
}
