<?php

namespace app\src\core;

class Request {

    public function getPath(){

        return strtolower($_SERVER['REQUEST_URI']) ?? '/';
    }

    public function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

}
