<?php

namespace app\src\controllers;

use app\src\core\Controller;
use app\src\core\View;
use app\src\models\Todo;

class TodoController extends Controller
{
    public static function getTodos()
    {
        return  View::render("todos", ["todos" => Todo::get()]);
}
}