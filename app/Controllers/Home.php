<?php

namespace Controllers;

use \Core\View;
use \Models\User;
use \Utilities\Auth;
use \Utilities\Flash;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{
    
    /**
     * Show the signup page
     *
     * @return void
     */
    public function startAction()
    {
        if (Auth::getUser()) {

            View::renderTemplate('home.html');

        } else {

            View::renderTemplate('landingpage.html');
        }
    }
}
