<?php

namespace app\src\controllers;

use app\src\core\View;

class HomeController
{
    public static function greet()
    {
        return  View::render("greetings");
}
}