<?php

namespace Core;

/**
 * Base controller
 */
abstract class Controller
{

    /**
     * @var array
     */
    protected $route_params = [];

    protected $redirectUnauthenticated = '/sign-in';
    protected $redirectAuthenticated = '/';


    /**
     * Controller constructor.
     * @param $route_params
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
        $this->before();
    }


    /**
     * @param $name
     * @param $args
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return bool
     */
    protected function before()
    {
        $requiresAuth = $this->route_params['auth'] ?? false;
        $loggedInUser = $_SESSION['user'] ?? false;
        $requiredBoth = $this->route_params['auth_always'] ?? false;
        if($requiredBoth) return true;

        if($requiresAuth && !$loggedInUser) {
            header('Location: ' . $this->redirectUnauthenticated);
            return false;
        } else if(!$requiresAuth && $loggedInUser) {
            header('Location: ' . $this->redirectAuthenticated);
            return false;
        }
    }
}
