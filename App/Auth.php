<?php
declare(strict_types=1);

namespace App;


use App\Models\RememberedLogin;
use App\Models\User;
use ReCaptcha\ReCaptcha;

class Auth
{
	public static function login(User $user, bool $remember_me): void
	{
//	    ob_start();
		// protects from session fixation attacks
		session_regenerate_id(true);
		
		$_SESSION['user_id'] = $user->id;

		$user->loggingIn();
		
		if ($remember_me){
			if($user->rememberLogin()){
				
				setcookie('remember_me', $user->remember_token, $user->expirey_timestamp, '/');
			}
		}
	}
	
	public static function logout(): void
	{
		// Unset all of the session variables
		$_SESSION = [];
		
		// Delete the session cookie
		if(ini_get('session.user_id')){
			$params = session_get_cookie_params();
			
			setcookie(
				session_name(),
				'',
				time() . 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
			);
		}
		
		// Finally destroy the session
		session_destroy();
		
		static::forgetLogin();
	}
	
	/**
	 * Remember the originally requested page in the session
	 */
	public static function rememberRequestedPage(): void
	{
		$_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Get the originally requested page to return to after requiring login, or default to the homepage
	 */
	public static function getReturnToPage()
	{
		return $_SESSION['return_to'] ?? '/'.Config::APP_NAME;
	}
	
	/**
	 * Get the current logged in user, from the session or the remember me cookie
	 */
	public static function getUser()
	{
		if(isset($_SESSION['user_id'])){
			
			return User::findByID($_SESSION['user_id']);
			
		}else{
		
			return static::loginFromRememberCookie();
		
		}
	}
	
	/**
	 * Login the user from a remembered login cookie
	 *
	 * @return mixed The user model if login cookie found; null otherwise
	 * @throws \Exception
	 */
	public static function loginFromRememberCookie()
	{
		
		if(isset($_COOKIE['remember_me'])){
			
			$cookie = $_COOKIE['remember_me'];
			
			$remembered_login = RememberedLogin::findByToken($cookie);
			
			if($remembered_login && ! $remembered_login->hasExpired()){
				
				$user = $remembered_login->getUser();
				
				static::login($user, false);
				
				return $user;
			}
		}
		return null;
	}
	
	/**
	 * Forget the remembered login, if present
	 */
	protected static function forgetLogin()
	{
		if(isset($_COOKIE['remember_me'])) {
			
			$cookie = $_COOKIE['remember_me'];
			
			$remembered_login = RememberedLogin::findByToken($cookie);
			
			if($remembered_login){
				
				$remembered_login->delete();
				
			}
			
			setcookie('remember_me', '', time() - 3600);    // set to expire in the past
		}
	}

	public static function reCaptchaCheck(): bool
    {
        if(isset($_POST['g-recaptcha-response'])) {

            $recaptcha = new ReCaptcha(Config::RECAPTCHA_SECRET);
            $resp = $recaptcha->verify($_POST['g-recaptcha-response']);

            unset($_POST['g-recaptcha-response']);

            if ($resp->isSuccess()) {
                // Verified!
                return true;
            }
            // Incorrect
//            $errors = $resp->getErrorCodes();
            Flash::addMessage('reCAPTCHA incorrect', Flash::WARNING);
        }
        return false;
    }
}