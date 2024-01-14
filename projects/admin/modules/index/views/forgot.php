<?php
/**
 * @filesource modules/index/views/forgot.php
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Forgot;

use Kotchasan\Html;
use Kotchasan\Language;
use Kotchasan\Login;

/**
 * Forgot Form.
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
            'id' => 'forgot_frm',
            'class' => 'login',
            'autocomplete' => 'off',
            'gform' => false
        ));

        // Add h1 heading
        $form->add('h1', array(
            'class' => 'icon-password',
            'innerHTML' => Language::get('Request new password')
        ));

        // Add message
        if (!empty(Login::$login_message)) {
            $form->add('p', array(
                'class' => empty(Login::$login_input) ? 'message' : 'error',
                'innerHTML' => Login::$login_message
            ));
        }

        // Add fieldset
        $fieldset = $form->add('fieldset');

        // Add email input
        $fieldset->add('email', array(
            'id' => 'email',
            'labelClass' => 'g-input icon-email',
            'placeholder' => Language::get('Email'),
            'value' => isset(Login::$login_params['username']) ? Login::$login_params['username'] : '',
            'autofocus',
            'required',
            'accesskey' => 'e',
            'maxlength' => 255,
            'comment' => Language::get('New password will be sent to the email address registered. If you do not remember or do not receive emails. Please contact your system administrator (Please check in the Junk Box)')
        ));

        // Add input groups (div for grouping inputs)
        $group = $fieldset->add('groups');

        // Add a link
        $group->add('a', array(
            'href' => self::$request->getUri()->withParams(array('action' => 'login'), true),
            'class' => 'td',
            'title' => Language::get('Administrator area'),
            'innerHTML' => ''.Language::get('Sign in').' ?'
        ));

        // Add submit button
        $fieldset->add('submit', array(
            'class' => 'button ok large wide',
            'value' => Language::get('Get new password')
        ));

        $fieldset->add('hidden', array(
            'id' => 'action',
            'value' => 'forgot'
        ));

        // Return the HTML
        return $form->render();
    }

    /**
     * Get the title bar.
     */
    public function title()
    {
        return Language::get('Request new password');
    }
}
