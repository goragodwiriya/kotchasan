<?php
/**
 * @filesource modules/index/views/login.php
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Login;

use Kotchasan\Html;
use Kotchasan\Language;
use Kotchasan\Login;

/**
 * Login Form.
 *
 * @see https://www.kotchasan.com/
 */
class View extends \Kotchasan\View
{
    /**
     * Render the form.
     */
    public function render()
    {
        // Create the form
        $form = Html::create('form', array(
            'id' => 'login_frm',
            'class' => 'login',
            'autocomplete' => 'off',
            'gform' => false
        ));

        // Add h1 heading
        $form->add('h1', array(
            'class' => 'icon-customer',
            'innerHTML' => Language::get('Administrator Area')
        ));

        // Add message
        if (isset(Login::$login_message)) {
            $form->add('p', array(
                'class' => empty(Login::$login_input) ? 'message' : 'error',
                'innerHTML' => Login::$login_message
            ));
        }

        // Add fieldset
        $fieldset = $form->add('fieldset', array(
            'title' => 'Please enter Username and Password (admin+admin)'
        ));

        // Add username input
        $fieldset->add('text', array(
            'id' => 'login_username',
            'labelClass' => 'g-input icon-user',
            'placeholder' => Language::get('Username'),
            'accesskey' => 'e',
            'maxlength' => 255,
            'value' => isset(Login::$login_params['username']) ? Login::$login_params['username'] : ''
        ));

        // Add password input
        $fieldset->add('password', array(
            'id' => 'login_password',
            'labelClass' => 'g-input icon-password',
            'autocomplete' => 'off',
            'placeholder' => Language::get('Password'),
            'value' => isset(Login::$login_params['password']) ? Login::$login_params['password'] : ''
        ));

        // Add input groups (div for grouping inputs)
        $group = $fieldset->add('groups');

        // Add a link
        $group->add('a', array(
            'href' => self::$request->getUri()->withParams(array('action' => 'forgot'), true),
            'class' => 'td',
            'title' => Language::get('Request new password'),
            'innerHTML' => ''.Language::get('Forgot').' ?'
        ));

        // Add submit button
        $fieldset->add('submit', array(
            'class' => 'button ok large wide',
            'value' => Language::get('Sign in')
        ));

        // Return the HTML
        return $form->render();
    }

    /**
     * Get the title bar.
     */
    public function title()
    {
        return Language::get('Administrator Area');
    }
}
