<?php

namespace app\src\core;

class Request
{

    public function getPath()
    {
        $pathHasQueryParams = strstr($_SERVER['REQUEST_URI'], "/?", true);
        if ($pathHasQueryParams) {
            return strtolower($pathHasQueryParams) ?? '/';
        }else{
            return trim(strtolower($_SERVER['REQUEST_URI']), "/");
        }
    }

    public function getParams()
    {
        return (ltrim(strstr($_SERVER['REQUEST_URI'], "?"), "?")) ?? null;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

}
