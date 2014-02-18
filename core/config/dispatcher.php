<?php

require_once CORE_PATH . '/router/map.php';
require_once CORE_PATH . '/router/definition.php';
require_once CORE_PATH . '/router/definitionFactory.php';
require_once CORE_PATH . '/router/route.php';
require_once CORE_PATH . '/router/routeFactory.php';

class Dispatcher {

    private $router_map;
    private $suffix;
    static $route;

    public function __construct() {


        $this->suffix = 'Controller';
        $this->router_map = new Map(new DefinitionFactory, new RouteFactory);
        //router home
        $this->router_map->add('home', '/{:base}/', array(
            'values' => array(
                'controller' => 'index',
                'method' => 'index'
            )
        ));
        //router index
        $this->router_map->add('index', '/{:base}/{:controller}/', array(
            'values' => array(
                'method' => 'index'
            )
        ));
        $this->router_map->add('index', '/{:base}/{:controller}', array(
            'values' => array(
                'method' => 'index'
            )
        ));

        // Autoload Modules

        foreach (glob(APP_ROOT . 'modules' . DS . '*', GLOB_ONLYDIR) as $path) {

            $name = str_replace(APP_ROOT . 'modules' . DS, '', $path);
            $this->router_map->add($name, "/{:base}/$name/{:controller}/{:method}/{:args*}", array(
                'values' => array(
                    'module' => $name
                )
            ));
            $this->router_map->add($name, "/{:base}/$name/{:controller}/", array(
                'values' => array(
                    'controller' => 'index',
                    'method' => 'index',
                    'module' => $name
                )
            ));
        }
        //router default
        $this->router_map->add('default', '/{:base}/{:controller}/{:method}/{:args*}');
    }

    /**
     * Hace el llamado al correspondiente controlador.
     */
    public function dispatch() {

        try {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            $route = $this->router_map->match($path, $_SERVER);

            if (isset($route->values['controller'])) {
                $controller = $route->values['controller'] . $this->suffix;
            } else {
                $controller = DEFAULT_CONTROLLER . $this->suffix;
            }

            if (isset($route->values['method'])) {
                $method = $route->values['method'];
            } else {
                $method = 'index';
            }
            $args = $route->values['args'];
            if (isset($route->values['module'])) {
                $pathController = trim(APP_ROOT . 'modules/' . $route->values['module'] . '/controllers/' . $controller . '.php');
            } else {
                $pathController = trim(APP_ROOT . 'app/controllers/' . $controller . '.php');
            }

            if (is_readable($pathController)) {
                require_once $pathController;
                self::$route = $route;
                $class = new $controller;

                if (method_exists($class, $method)) {
                    if (is_callable(array($class, $method))) {
                        $method = str_replace('-', '_', $method);
                    }
                    if (isset($args) && is_array($args) && count($args) > 0) {
                        call_user_func_array(array($class, $method), $args);
                    } else {
                        call_user_func(array($class, $method));
                    }
                } else {
                    Utility::redirect('/oops/error/XIV');
                }
            } else {
                Utility::redirect('/oops/error/XV');
            }
        } catch (RouteNotFound $e) {
            Utility::redirect('/oops/error/XVI');
        }
    }

    /**
     * Obtiene el router
     * 
     * @return Router
     */
    public static function getRoute() {
        return self::$route;
    }

}