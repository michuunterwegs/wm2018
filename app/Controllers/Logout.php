<?php

namespace Controllers;

use \Core\View;
use \Models\User;
use \Utilities\Auth;
use \Utilities\Flash;

/**
 * Logout controller
 *
 * PHP version 7.0
 */
class Logout extends \Core\Controller
{
    /**
     * Log out a user
     *
     * @return void
     */
    public function destroyAction()
    {
        Auth::logout();
        $this->redirect('/logout/show-logout-message');
    }

    /**
     * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
     * as they use the session and at the end of the Logout method (destroyAction) the session is destroyed
     * so a new action needs to be called in order to use the session.
     *
     * @return void
     */
    public function showLogoutMessageAction()
    {
        Flash::addMessage('Logout successful, bye bye!', Flash::INFO);

        $this->redirect('/');
    }
}
