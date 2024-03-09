<?php

namespace Source\Middlewares;

class RouterMiddleware{

    private $routes;
    private $path;
    private $method;

    public function __construct()
    {
        require __dir__.'/../routes/routes.php';

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $this->routes = $webRoutes;

        if(strpos($this->path, '/api') === 0){
            $this->routes = $apiRoutes;
        }
    }

    public function execute() {

        foreach ($this->routes[$this->method] as $routePattern => $handler) {
            if (preg_match('#^' . $routePattern . '$#', $this->path, $matches)) {
                list($controllerName, $action) = explode('@', $handler);
                $controllerName = 'Source\\Controllers\\' . $controllerName;
                $controllerInstance = new $controllerName();

                $id = isset($matches[1]) ? $matches[1] : null;

                if ($id !== null) {
                    return $controllerInstance->$action($id);
                } else {
                    return $controllerInstance->$action();
                }
            }
        }

        echo json_encode([
            'error' => 'Resource not found',
            'code' => 404,
        ]);
        
    }

}