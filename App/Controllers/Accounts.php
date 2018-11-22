<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Config;
use App\Flash;
use App\Models\User;
use Core\View;


class Accounts extends \Core\Controller
{
    // Login

    /**
     * Show the log in page
     */
    public function loginAction(): void
    {
        View::renderTemplate('Accounts/login.html.twig');
    }

    /**
     * Log in the user
     */
    public function loggedinAction(): void
    {
        if(!Auth::reCaptchaCheck())
            $this->redirect('/'.Config::APP_NAME.'/login');
    	
        $user = User::authenticate($_POST['email'], $_POST['password']);
        
        $remember_me = isset($_POST['remember_me']);

        if($user){
        	
	        Auth::login($user, $remember_me);
	        
	        Flash::addMessage('Login successful');
        	
            $this->redirect(Auth::getReturnToPage());
            
        }else
        	
        	Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);
        	
            View::renderTemplate('Accounts/login.html.twig', [
                'email' => $_POST['email'],
	            'remember_me' => $remember_me
            ]);
    }
	
	/**
	 * Log out a user
	 */
    public function logoutAction(): void
    {
    	Auth::logout();
    	
    	$this->redirect('/'.Config::APP_NAME.'/accounts/show-logout-message');
    }
	
	/**
	 * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
	 * as they use the session and at the end of the logout method (logoutAction) the session is destroyed
	 * so a new action needs to be called in order to use the session.
	 */
    public function showLogoutMessageAction(): void
    {
	    Flash::addMessage('Logout successful');
	
	    $this->redirect();
    }

    // Registration
    /**
     * Validate if email is available (AJAX) for a new signup
     */
    public function validateEmailAction(): void
    {
        $is_valid = ! User::emailExists($_GET['email'], $_GET['ignore_id'] ?? null);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    public function registerAction(): void
    {

//        $img = new Securimage();
//        $img->code_length = 6;
//        $img->num_lines   = 5;
//        $img->noise_level = 5;

//        $img = new Securimage();


        View::renderTemplate('Accounts/register.html.twig'); //, [
//            'image' => $img,
//            'html' => Securimage::getCaptchaHtml(),
//        ]);
    }

    public function createAction(): void
    {

        if(!Auth::reCaptchaCheck())
            $this->redirect('/'.Config::APP_NAME.'/accounts/register');

        $user = new User($_POST);

        if($user->save()) {

            $user->sendActivationEmail();

            $this->redirect('/'.Config::APP_NAME.'/accounts/success');

        }else {
            View::renderTemplate('Accounts/register.html.twig', [
                'user' => $user
            ]);
        }
    }

    /**
     * Show the registration success page
     */
    public function successAction(): void
    {
        View::renderTemplate('Accounts/registered.html.twig');
    }

    /**
     * Activate a new account
     */
    public function activateAction(): void
    {
        User::activate($this->route_params['token']);

        $this->redirect('/'.Config::APP_NAME.'/accounts/activated');
    }

    /**
     * Show the activation success page
     */
    public function activatedAction(): void
    {
        View::renderTemplate('Accounts/activated.html.twig');
    }
}