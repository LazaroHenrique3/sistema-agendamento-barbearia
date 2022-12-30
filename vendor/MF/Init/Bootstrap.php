<?php
//Script de incialização da aplicação

namespace MF\Init;

//Classes Abstratas não podem ser Instanciadas, apenas Herdadas
abstract class Bootstrap
{

    private $routes;

    abstract protected function initRoutes();

    public function __construct()
    {
        //Inicialização do Array de Rotas
        $this->initRoutes();
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
        $this->run($this->getUrl());
    }

    protected function run($url)
    {
        foreach ($this->getRoutes() as $key => $route) {
            if ($url == $route['route']) {
                //Instanciar Dinamicamente o controller

                //Montando o nome da classe
                $class = "App\\Controllers\\" . ucfirst($route['controller']);

                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
            }
        }
    }

    //Pega para onde o usuário deseja navegar
    protected function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
