<?php
declare(strict_types=1);

namespace Core;

use App\Auth;
use App\Config;
use App\Flash;

abstract class Controller
{

    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Controller constructor.
     * @param array $route_params
     */
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * @param $name
     * @param $args
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if(method_exists($this, $method)){
            if($this->before() !== false){
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
//            echo "Method $method not found in controller " . get_class($this);
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return bool
     */
    protected function before(): bool
    {
        return true;
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after(): void
    {

    }

    /**
     * Redirect to a different page
     * @param string $url
     */
    public function redirect(string $url = '/'.Config::APP_NAME): void
    {
        header('Location: http' . (Config::SSL ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }
	
	/**
	 * Require the user to be logged in before giving access to the requested page.
	 * Remember the requested page for later, then redirect to the login page.
	 */
    public function requireLogin(): void
    {
	    if(!Auth::getUser()){
	    	
	    	Flash::addMessage('Please login to access that page', Flash::INFO);
	    	
		    Auth::rememberRequestedPage();
		
		    $this->redirect('/'.Config::APP_NAME.'/login');
	    }
    }
}