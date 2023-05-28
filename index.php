<?php
require_once __DIR__."/vendor/autoload.php";

use app\src\controllers\TodoController;
use app\src\core\Application;


//$todo = new Todo();
//$todo->title = "test";
//$todo->description = "description";
//$todo->created = "2022-20-10";
//var_dump($todo->save());
//
//var_dump(Todo::create(['title' => 'ALADIN', 'description' => "step", 'created' => "2025-02-22"]));
//
//
//$todo = Todo::find(3);
//var_dump($todo);
//var_dump($todo->delete());


$app = new Application();

$app->router->get('/todos', [TodoController::class, "getTodos"]);

echo($app->router->resolve());





