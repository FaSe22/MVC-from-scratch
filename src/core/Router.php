<?php

namespace app\src\core;

class Router
{

    protected array $routes = [];
    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get($path, $callback)
    {
        $pathHasQueryParams = strstr($path, "/?", true);
        if ($pathHasQueryParams) {
            $path = strstr($path, "?", true) ?? null;
        }
        $this->routes['get'][trim($path, "/")] = $callback;
    }

    public function resolve()
    {
        $param = !is_numeric(strstr($this->request->getParams(), "=")) ?? (int)strstr($this->request->getParams(), "=");
        return ($this->routes[$this->request->getMethod()][ltrim($this->request->getPath(), "/")])($param);

    }
}
