<table>
    <caption>Todos</caption>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Created</th>
    </tr>
    <?php
    foreach ($todos as $todo)
        echo("<tr>
        <td>$todo->id</td>
        <td>$todo->title</td>
        <td>$todo->description</td>
        <td>$todo->created</td>
    </tr>"); ?>
</table>
