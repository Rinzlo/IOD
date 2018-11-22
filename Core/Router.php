<?php
declare(strict_types=1);

namespace Core;

class Router
{
    /**
     * Routing Table
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters
     * @var array
     */
    protected $params = [];

    /**
     * Adds a route
     *
     * @param string $route
     * @param array $params
     *
     * @return void
     */
    public function add(string $route, array $params = []): void
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        
        // Convert variables with custom regular expressions e.g. {id:\d+}
	    $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Get all routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Match the route to the table
     *
     * @param string $url
     * @return bool
     */
    public function match(string $url): bool
    {
        // Match to the fixed URL format /controller/action
        //$reg_exp = "/^(?<controller>[a-z-]+)\/(?<action>[a-z-]+)$/";

        foreach ($this->routes as $route => $params){
            if(preg_match($route, $url, $matches)){
                // Get named capture group values
                //$params = [];

                foreach ($matches as $key => $match){
                    if(is_string($key)){
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Dispatch the url via its respective route and params
     *
     * @param string $url
     *
     * @return void
     * @throws \Exception
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);

    	if($this->match($url)){
    		$controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
//            $controller = "App\Controllers\\$controller";
    		$controller = $this->getNamespace() . $controller;

    		if(class_exists($controller)){
    			$controller_object = new $controller($this->params);
    			
    			$action = $this->params['action'];
    			$action = $this->convertToCamelCase($action);
    			
    			if(preg_match('/action$/i', $action) == 0){
    				$controller_object->$action();
			    }else{
    				throw new \Exception("Method $action (in controller $controller) not found");
			    }
		    }else{
                throw new \Exception("Controller class $controller not found");
		    }
	    }else{
            throw new \Exception("No route matched.", 404);
	    }
    }
	
	/**
	 * Convert the string with hyphens to Studly Caps,
	 * e.g. post-authors => PostAuthors
	 *
	 * @param string $string
	 *
	 * @return string
	 */
    protected function convertToStudlyCaps(string $string): string
    {
    	return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
	
	/**
	 * Convert the string with hyphens to camelCase,
	 * e.g. add-new => addNew
	 *
	 * @param string $string
	 *
	 * @return string
	 */
    protected function convertToCamelCase(string $string): string
    {
    	return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     * URL                          $_SERVER['QUERY_STRING']    Route
     * ---------------------------------------------------------------------|
     * localhost/?page=1            page=1                      ''          \
     * localhost/posts?page=1       posts&page=1                posts       |
     * localhost/posts/index        posts/index                 posts/index \
     * localhost/posts/index?page=1 posts/index&page=1          posts/index |
     * ---------------------------------------------------------------------\
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed though to the $_SERVER variable).
     *
     * @param string $url The full URL
     * @return string The URL with the query string variables removed
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if($url != '') {
            $parts = explode('&', $url, 2);

            if(strpos($parts[0], '=') === false){
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     *
     * @return string The request URL
     */
    protected function getNamespace(): string
    {
        $namespace = 'App\Controllers\\';

        if(array_key_exists('namespace', $this->params)){
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}