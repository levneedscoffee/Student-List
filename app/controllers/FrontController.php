<?php
namespace StudentList\Controllers;

class FrontController
{
    private $routes;

    public function __construct()
    {
        $routesPath = '/var/www/project/app/config/routes.php';
        $this->routes = include($routesPath);
    }

    public function start()
    {
        if(!empty($_SERVER['REQUEST_URI'])){
            $uri = trim($_SERVER['REQUEST_URI'], '/');
        }

        foreach($this->routes as $controller => $action){
            $uriControllerName = explode("?",$uri)[0];

            if($uriControllerName === $controller){
                $array = explode("/", $action);

                $controllerName = ucfirst($array[0])."Controller";
                $actionName = "action".ucfirst($array[1]);

                $path = '/var/www/project/app/controllers/'.$controllerName.'.php';
                if(file_exists($path)){
                    include_once($path);
                }else{
                    FrontController::http404();
                }

                $controllerName= 'StudentList\\Controllers\\'.$controllerName;

                $obj = new $controllerName;
                $obj->$actionName();
                exit;
            }
        }

        FrontController::http404();

    }


    static function http404()
    {
        header('HTTP/1.0 404 Not Found', true, 404);
    }
}
