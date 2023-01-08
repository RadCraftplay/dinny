<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/ServerController.php';
require_once 'src/controllers/UserController.php';

class Router {
    public static $routes;

    public static function get($url, $view) {
        self::$routes[$url] = [
            "controller" => $view,
            "method" => "GET"
        ];
    }

    public static function post($url, $view) {
        self::$routes[$url] = [
            "controller" => $view,
            "method" => "POST"
        ];
    }

    public static function run($url) {
        $action = $url == null ? null : explode("/", $url)[0];
        $method = $_SERVER['REQUEST_METHOD'];
        if (!array_key_exists($action, self::$routes)) {
            die("Wrong url!");
        }

        $route = self::$routes[$action];
        if ($route["method"] != $method) {
            http_response_code(400);
            die("Bad request (" . $route["method"] . " method expected)");
        }

        $controller = $route["controller"];
        $object = new $controller;
        $action = $action ?: 'index';

        $object->$action();
    }
}