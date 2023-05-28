<?php
require_once __DIR__."/vendor/autoload.php";

use app\src\models\Todo;


$todo = new Todo();
$todo->title = "test";
$todo->description = "description";
$todo->created = "2022-20-10";
var_dump($todo->save());

var_dump(Todo::create(['title' => 'ALADIN', 'description' => "step", 'created' => "2025-02-22"]));
foreach (Todo::get() as $todo){
    echo "id";
    echo PHP_EOL;
    echo $todo->id;
    echo "|";

    echo "title";
    echo PHP_EOL;
    echo $todo->title;
    echo "|||";

    echo "description";
    echo PHP_EOL;
    echo $todo->description;
    echo "|||";
}

$todo = Todo::find(3);
var_dump($todo);
//var_dump($todo->delete());

//    @require('src/views/todos.html');
