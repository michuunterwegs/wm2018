<?php

namespace Controllers;

use \Core\View;
use \Models\User;
use \Utilities\Auth;
use \Utilities\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{
    /**
     * Before filter - called before any action method.
     *
     * @return void
     */
    protected function before() 
    {
        $this->requireNoLogin();
    }

    /**
     * Log in a user
     *
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($this->request->data('username'), $this->request->data('password'));
        
        $remember_me = isset($_POST['remember_me']);

        if ($user) {

            Auth::login($user, $remember_me);

        } else {

            Flash::addMessage('Wrong password or email!', Flash::ERROR);
        }

        $this->redirect('/');
    }
}
