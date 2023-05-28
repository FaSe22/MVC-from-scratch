<?php

use app\src\repositories\BaseRepository;
use app\src\services\DatabaseConnector;

beforeEach(function(){
    $this->base = new BaseRepository();
    $this->conn = new DatabaseConnector();
    $this->base->truncate($this->conn, 'todos');
});

it('should create an entry in todos table', function () {

    $this->base->create($this->conn, 'todos', [
        'title' => "wow",
        'description' => "so good",
        'created' => "2025-01-01"
    ]);

    $this->assertCount(1, $this->base->get($this->conn, 'todos'));
});

afterEach(function(){
        $this->base->truncate($this->conn, 'todos');
});