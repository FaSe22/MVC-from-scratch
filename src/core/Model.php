<?php

namespace app\src\core;

use app\src\core\orm\BaseRepository;
use app\src\core\orm\traits\Instantiable;

abstract class Model
{
    use Instantiable;

    public ?int $id;

    /**
     * @param $args
     * @return mixed
     */
    public static function create($args): Model
    {
        $instance = static::getInstance($args, "mapColumnsToProperties");
        //Wir nehmen die neue Id und schreiben sie in die Instanz
        $instance->id = BaseRepository::create($instance, $args);

        return $instance;
    }


    /**
     * @return $this
     */
    public function save(): Model
    {
        $args = [];
        $instance = static::getInstance($args, "mapPropertiesToColumns", $this);
        //Wir nehmen die neue Id und schreiben sie in die Instanz
        $instance->id = BaseRepository::create($this, $args);

        return $instance;
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        BaseRepository::delete($this);
        return true;

    }


    /**
     * @return array
     */
    public static function get(): array
    {
        $entries = BaseRepository::get(static::getTableName());
        static::format($entries);

        return array_map(fn($entry) =>  static::getInstance($entry, "mapColumnsToProperties"), $entries);
    }


    /**
     * @param int $id
     * @return Model|null
     */
    public static function find(int $id): ?Model
    {
        $entry = BaseRepository::find(static::getTableName(), $id);

        if (!empty($entry)) {
            return static::getInstance($entry, "mapColumnsToProperties");
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        $string = str_replace('\\', '/', get_called_class());
        return (strtolower(basename($string)) . "s");
    }

}
