<?php

namespace Controllers;

use \Core\View;
use \Models\User;
use \Utilities\Flash;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{
    
    /**
     * Show the signup page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('User/Signup/signup.html');
    }

    /**
     * Sign up a new user
     *
     * @return void
     */
    public function createAction()
    {
        if (User::register($this->request->data('username'), $this->request->data('email'), $this->request->data('password'))) {

            Flash::addMessage('Activation email was sent to your email address.');

            $this->redirect('/');

        }

        $this->redirect('/signup');
    }

    /**
     * Activate a new account
     *
     * @return void
     */
    public function activateAction()
    {
        User::activate($this->request->params('token'));

        $this->redirect('/signup/activated');
    }

    /**
     * Show the activation success page
     *
     * @return void
     */
    public function activatedAction()
    {
        View::renderTemplate('User/Signup/activated.html');
    }
}
