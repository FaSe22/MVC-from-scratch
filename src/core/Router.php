<?php

namespace app\src\core;

class Router
{

    protected array $routes = [];
    public Request $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function get($path, $callback){
//        var_dump($path);
//        var_dump($callback);
        $path =ltrim($path, "/");
        $this->routes['get'][$path] = $callback;
    }

    public function resolve(){
        return ($this->routes[$this->request->getMethod()][ltrim($this->request->getPath(), "/")])();

    }
}