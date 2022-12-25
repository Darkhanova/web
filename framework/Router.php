<?php

class Route {
    public string $route_regexp;
    public $controller;
    public array $middlewareList = []; // добавил массив под middleware
     // метод с помощью которого будем добавлять обработчик
     public function middleware(BaseMiddleware $m) : Route {
        array_push($this->middlewareList, $m);
        return $this;
    }
    public function __construct($route_regexp, $controller)
    {
        $this->route_regexp = $route_regexp;
        $this->controller = $controller;
    }
}

class Router {
    /**
     * @var Route[]
     */
    protected $routes = [];
    protected $twig;
    protected $pdo;

    public function __construct($twig, $pdo)
    {
        $this->twig = $twig;
        $this->pdo = $pdo;
    }

    public function add($route_regexp, $controller) {

        // array_push($this->routes, new Route("#^$route_regexp$#", $controller));

        // создаем экземпляр маршрута
        $route = new Route("#^$route_regexp$#", $controller);
        array_push($this->routes, $route);

        // возвращаем как результат функции
        return $route;
    }

    // функция которая должна по url найти маршрут и вызывать его функцию get
    // если маршрут не найден, то будет использоваться контроллер по умолчанию
    public function get_or_default($default_controller) {
        $url = $_SERVER["REQUEST_URI"]; // получили url

        $path = parse_url($url, PHP_URL_PATH);

        $controller = $default_controller;
        $newRoute = null; // добавили переменную под маршрут
        $matches = [];
        foreach($this->routes as $route) {
            // проверяем подходит ли маршрут под шаблон
            if (preg_match($route->route_regexp, $path, $matches)) {
                // если подходит, то фиксируем привязанные к шаблону контроллер
                $controller = $route->controller;
                $newRoute = $route;
               // и выходим из цикла
                break;
            }
        }


        // создаем экземпляр контроллера
        $controllerInstance = new $controller();
        // передаем в него pdo
        $controllerInstance->setPDO($this->pdo);
        $controllerInstance->setParams($matches);

        if ($controllerInstance instanceof ObjectController) {
            $controllerInstance->chooseTemplate();
        }

        if ($controllerInstance instanceof TwigBaseController) {
            $controllerInstance->setTwig($this->twig);
        }
        if ($newRoute) {
            foreach ($newRoute->middlewareList as $m) {
                $m->apply($controllerInstance, []);
            }
        }

        return $controllerInstance->process_response();
    }
        // вызываем
        // return $controllerInstance->process_response();
    }
